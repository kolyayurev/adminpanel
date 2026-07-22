<?php

namespace KY\AdminPanel\Tests\Unit\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\DataTypes\BaseDataType;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Policies\BasePolicy;
use KY\AdminPanel\Tests\TestCase;

/**
 * Тесты на компонент <x-adminpanel::datatable> (resources/views/components/datatable.blade.php).
 */
class DataTableComponentTest extends TestCase
{
    // DataType регистрируем в фазе register() — политики связываются с моделями в
    // AdminPanelServiceProvider::boot(), то есть уже после register() всех провайдеров.
    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            DataTableComponentFixtureServiceProvider::class,
        ]);
    }

    protected function defineRoutes($router): void
    {
        $router->group(['prefix' => 'admin'], function () {
            AdminPanel::routes();
        });
    }

    // Рендерим компонент и @stack('vue') в одном проходе — @push внутри компонента
    // складывается в общий Factory-стек и виден только пока стек не сдренирован.
    private function renderDataTable(array $props = []): string
    {
        return Blade::render(
            '<x-adminpanel::datatable :dataType="$dataType" :filters="$filters" :except="$except" :modal="$modal"/>@stack(\'vue\')',
            array_merge([
                'dataType' => new DataTableComponentTestElement,
                'filters' => [],
                'except' => [],
                'modal' => false,
            ], $props)
        );
    }

    public function test_default_call_renders_all_columns_without_locked_filters(): void
    {
        $html = $this->renderDataTable();

        $this->assertStringContainsString('prop="name"', $html);
        $this->assertStringContainsString('prop="user_id"', $html);
        $this->assertStringContainsString('lockedFilters: {}', $html);
    }

    public function test_except_prop_hides_column_from_rendering(): void
    {
        $html = $this->renderDataTable(['except' => ['user_id']]);

        $this->assertStringContainsString('prop="name"', $html);
        $this->assertStringNotContainsString('prop="user_id"', $html);
    }

    public function test_filters_prop_is_passed_as_locked_filters_to_the_vue_app(): void
    {
        $html = $this->renderDataTable(['filters' => ['user_id' => 5]]);

        $this->assertStringContainsString('lockedFilters: {"user_id":5}', $html);
    }

    public function test_two_renders_of_the_same_data_type_produce_different_mount_ids(): void
    {
        $first = $this->renderDataTable();
        $second = $this->renderDataTable();

        preg_match('/id="(dataTableApp_[^"]+)"/', $first, $firstMatch);
        preg_match('/id="(dataTableApp_[^"]+)"/', $second, $secondMatch);

        $this->assertNotEmpty($firstMatch[1]);
        $this->assertNotEmpty($secondMatch[1]);
        $this->assertNotSame($firstMatch[1], $secondMatch[1]);
    }

    public function test_reload_is_registered_in_a_per_instance_registry_not_a_global(): void
    {
        $html = $this->renderDataTable();

        $this->assertStringContainsString('window.adminTableReloads = window.adminTableReloads || {}', $html);
        $this->assertStringContainsString("window.adminTableReloads['", $html);
        $this->assertStringNotContainsString('window.adminTableReload =', $html);
    }

    public function test_modal_prop_renders_dialog_add_button_and_create_url(): void
    {
        $this->actingAs($this->createAdminUser());

        $html = $this->renderDataTable(['modal' => true]);

        $this->assertStringContainsString('<el-dialog', $html);
        $this->assertStringContainsString('openModal(createUrl)', $html);
        $this->assertStringContainsString('modalEnabled: true', $html);
        $this->assertStringContainsString('component_test_things\/modal-form', $html);
    }

    public function test_without_modal_prop_no_dialog_or_add_button_rendered(): void
    {
        $html = $this->renderDataTable();

        $this->assertStringNotContainsString('<el-dialog', $html);
        $this->assertStringContainsString('modalEnabled: false', $html);
    }

    public function test_create_button_is_hidden_when_policy_forbids_creation(): void
    {
        $this->actingAs($this->createAdminUser());

        $html = $this->renderDataTable([
            'dataType' => new DataTableComponentTestDenied,
            'modal' => true,
        ]);

        $this->assertStringNotContainsString('openModal(createUrl)', $html);
        // Сама таблица и модалка для правки строк остаются на месте.
        $this->assertStringContainsString('<el-dialog', $html);
    }

    public function test_locked_filters_are_passed_to_create_url(): void
    {
        $this->actingAs($this->createAdminUser());

        $html = $this->renderDataTable(['modal' => true, 'filters' => ['user_id' => 5]]);

        $this->assertStringContainsString('modal-form?user_id=5', str_replace('\/', '/', $html));
    }

    public function test_modal_validation_errors_are_rendered_next_to_fields(): void
    {
        $this->actingAs($this->createAdminUser());

        $html = $this->renderDataTable(['modal' => true]);

        $this->assertStringContainsString('showFormErrors', $html);
        $this->assertStringContainsString('invalid-feedback', $html);
    }
}

class DataTableComponentFixtureServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        AdminPanel::addDataType(new DataTableComponentTestElement);
        AdminPanel::addDataType(new DataTableComponentTestDenied);
    }
}

class DataTableComponentTestElement extends BaseDataType
{
    protected string $name = 'thing';

    protected string $title = 'Things';

    protected string $slug = 'component_test_things';

    protected string $orderDisplayColumn = 'name';

    protected ?string $modelClass = Redirect::class;

    public function layout(): Collection
    {
        return collect([Row::blocks('name')]);
    }

    public function fields(): Collection
    {
        return collect([
            Text::make('name')->label('Name'),
            Text::make('user_id')->label('User ID'),
        ]);
    }
}

/**
 * Отдельный класс модели на ту же таблицу: политики маппятся по FQCN модели, поэтому
 * для проверки запрета нужен свой класс, а не тот же Redirect.
 */
class DataTableComponentDeniedRedirect extends Redirect {}

class DataTableComponentDenyCreatePolicy extends BasePolicy
{
    public function create($user): bool
    {
        return false;
    }
}

class DataTableComponentTestDenied extends DataTableComponentTestElement
{
    protected string $slug = 'component_test_denied';

    protected ?string $modelClass = DataTableComponentDeniedRedirect::class;

    protected string $policy = DataTableComponentDenyCreatePolicy::class;
}
