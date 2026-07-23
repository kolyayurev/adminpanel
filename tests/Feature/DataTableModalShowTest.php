<?php

namespace KY\AdminPanel\Tests\Feature;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\DataTables\Actions\BaseAction;
use KY\AdminPanel\DataTypes\BaseDataType;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Policies\BasePolicy;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Http\Controllers\BaseDataController
 */
class DataTableModalShowTest extends TestCase
{
    // DataType регистрируем в register() — политики связываются с моделями в boot()
    // провайдера пакета, подробности см. в DataTableModalFormTest.
    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            // Полноэкранный show рисует хлебные крошки — в хост-приложении провайдер
            // подключается автообнаружением, Testbench его надо назвать явно.
            \Diglactic\Breadcrumbs\ServiceProvider::class,
            ModalShowFixtureServiceProvider::class,
        ]);
    }

    protected function getPackageAliases($app): array
    {
        return ['Breadcrumbs' => Breadcrumbs::class];
    }

    protected function defineRoutes($router): void
    {
        $router->middleware('web')->group(function () use ($router) {
            $router->group(['prefix' => 'admin'], function () {
                AdminPanel::routes();
            });
        });

        // В хост-приложении это делает routes/breadcrumbs.php (см. InstallCommand).
        AdminPanel::breadcrumbsRoutes();
    }

    /**
     * @covers ::modalShow
     */
    public function test_modal_show_returns_json_fragment_without_layout(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create(['from' => '/old-path']);

        $response = $this->getJson(route('adminpanel.modal_show_redirects.modal-show', $redirect->id));

        $response->assertOk();
        $response->assertJson(['status' => true]);

        $template = $response->json('template');
        $this->assertStringNotContainsString('<!doctype', strtolower($template));
        $this->assertStringNotContainsString('<html', strtolower($template));
        $this->assertStringContainsString('/old-path', $template);
    }

    /**
     * В модалке нет ни формы, ни кнопки сабмита — только тело просмотра.
     *
     * @covers ::modalShow
     */
    public function test_modal_show_template_has_no_form(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create();

        $response = $this->getJson(route('adminpanel.modal_show_redirects.modal-show', $redirect->id));

        $this->assertStringNotContainsString('<form', $response->json('template'));
    }

    /**
     * @covers ::modalShow
     */
    public function test_modal_show_requires_show_policy(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create(); // та же таблица redirects

        $response = $this->getJson(route('adminpanel.modal_show_denied.modal-show', $redirect->id));

        $response->assertForbidden();
    }

    /**
     * @covers ::modalShow
     */
    public function test_modal_show_returns_404_for_missing_model(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('adminpanel.modal_show_redirects.modal-show', 999));

        $response->assertNotFound();
    }

    /**
     * Приложение подменяет тело просмотра своим шаблоном — парный хук к getShowView().
     *
     * @covers ::modalShow
     */
    public function test_modal_show_uses_data_type_show_body_view(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create(); // та же таблица redirects

        $response = $this->getJson(route('adminpanel.modal_show_custom_body.modal-show', $redirect->id));

        $response->assertOk();
        $this->assertStringContainsString('custom show body', $response->json('template'));
        // Vue-инстансы кастомного тела должны уехать в ответ вместе с ним.
        $this->assertStringContainsString('customShowBodyMounted', $response->json('template'));
    }

    /**
     * Во встроенной таблице «глаз» ведёт в модалку тем же путём, что и «карандаш».
     */
    public function test_show_action_in_modal_table_points_to_modal_show(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create();

        $html = $this->renderActions(new ModalShowRedirectDataType, $redirect, true);

        $this->assertStringContainsString('modal_show_redirects/modal-show/'.$redirect->id, $html);
        $this->assertStringContainsString('modal_show_redirects/modal-form/'.$redirect->id, $html);
        $this->assertSame(2, substr_count($html, 'data-modal-open="1"'));
    }

    /**
     * Свой экшен с политикой `show`, но собственным маршрутом, модалкой не подменяется.
     */
    public function test_custom_action_with_show_policy_keeps_its_own_route(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create();

        $html = $this->renderActions(new ModalShowCustomActionDataType, $redirect, true);

        $this->assertStringContainsString('https://example.test/', $html);
        $this->assertStringNotContainsString('modal-show', $html);
        $this->assertStringNotContainsString('data-modal-open', $html);
    }

    /**
     * Обычный режим таблицы не меняется — полноэкранные ссылки без разметки модалки.
     */
    public function test_show_action_without_modal_keeps_full_page_route(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create();

        $html = $this->renderActions(new ModalShowRedirectDataType, $redirect, false);

        $this->assertStringContainsString('modal_show_redirects/'.$redirect->id, $html);
        $this->assertStringNotContainsString('modal-show', $html);
        $this->assertStringNotContainsString('data-modal-open', $html);
    }

    private function renderActions($dataType, $model, bool $modal): string
    {
        return view('adminpanel::datatables.actions.index', [
            'dataType' => $dataType,
            'model' => $model,
            'modal' => $modal,
        ])->render();
    }

    /**
     * Полноэкранный просмотр рисует то же тело — partial один на оба режима.
     *
     * @covers ::show
     */
    public function test_full_page_show_renders_the_same_body_view(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create(); // та же таблица redirects

        $response = $this->get(route('adminpanel.modal_show_custom_body.show', $redirect->id));

        $response->assertOk();
        $response->assertSee('custom show body');
    }
}

class ModalShowFixtureServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        AdminPanel::addDataType(new ModalShowRedirectDataType);
        AdminPanel::addDataType(new ModalShowDeniedDataType);
        AdminPanel::addDataType(new ModalShowCustomBodyDataType);
    }

    public function boot(): void
    {
        $this->app['view']->addNamespace('modal-show-fixture', __DIR__.'/../Fixtures/views');
    }
}

class AllowShowPolicy extends BasePolicy
{
    public function show($user, $model): bool
    {
        return true;
    }
}

class ModalShowRedirectDataType extends BaseDataType
{
    protected string $name = 'redirect';

    protected string $title = 'Redirects';

    protected string $slug = 'modal_show_redirects';

    protected string $orderDisplayColumn = 'from';

    protected ?string $modelClass = Redirect::class;

    protected string $policy = AllowShowPolicy::class;

    public function layout(): Collection
    {
        return collect([Row::blocks('from', 'to')]);
    }

    public function fields(): Collection
    {
        return collect([
            Text::make('from')->label('From'),
            Text::make('to')->label('To'),
        ]);
    }
}

/**
 * Отдельные классы моделей на ту же таблицу: политики маппятся по FQCN модели,
 * общий Redirect не дал бы развести разрешающий и запрещающий случаи.
 */
class ShowDeniedRedirect extends Redirect {}

class CustomBodyRedirect extends Redirect {}

class ModalShowDeniedDataType extends BaseDataType
{
    protected string $name = 'redirect';

    protected string $title = 'Redirects (denied)';

    protected string $slug = 'modal_show_denied';

    protected string $orderDisplayColumn = 'from';

    protected ?string $modelClass = ShowDeniedRedirect::class;

    public function layout(): Collection
    {
        return collect([Row::blocks('from')]);
    }

    public function fields(): Collection
    {
        return collect([Text::make('from')->label('From')]);
    }
}

/**
 * Экшен приложения со своим маршрутом — политика та же, что у ShowAction.
 */
class ExternalLinkAction extends BaseAction
{
    protected string $icon = 'eye';

    protected string $policyName = 'show';

    protected string $route = 'https://example.test/';
}

class ModalShowCustomActionDataType extends ModalShowRedirectDataType
{
    public function actions(): Collection
    {
        return collect([ExternalLinkAction::make()]);
    }
}

class ModalShowCustomBodyDataType extends BaseDataType
{
    protected string $name = 'redirect';

    protected string $title = 'Redirects (custom body)';

    protected string $slug = 'modal_show_custom_body';

    protected string $orderDisplayColumn = 'from';

    protected ?string $modelClass = CustomBodyRedirect::class;

    protected string $policy = AllowShowPolicy::class;

    public function getShowBodyView(): string
    {
        return 'modal-show-fixture::show-body';
    }

    public function layout(): Collection
    {
        return collect([Row::blocks('from')]);
    }

    public function fields(): Collection
    {
        return collect([Text::make('from')->label('From')]);
    }
}
