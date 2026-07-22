<?php

namespace KY\AdminPanel\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\DataTypes\BaseDataType;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Policies\BasePolicy;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Http\Controllers\BaseDataController
 */
class DataTableModalFormTest extends TestCase
{
    // DataType должен быть зарегистрирован в фазе register() — так же, как в реальном
    // хост-приложении (см. App\Providers\AppServiceProvider::register() в kolyayurev.ru).
    // AdminPanelServiceProvider::boot() собирает политики в loadAuth() уже ПОСЛЕ фазы
    // register() всех провайдеров, но ДО defineRoutes() (Testbench вызывает его в
    // $app->booted(...)) — если добавить DataType там, политика не свяжется с моделью.
    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            ModalFormFixtureServiceProvider::class,
        ]);
    }

    protected function defineRoutes($router): void
    {
        // 'web' нужен явно: в реальном приложении routes/web.php (где хост зовёт
        // AdminPanel::routes()) уже грузится Laravel'ем в группе 'web' — оттуда сессия
        // и общая $errors для вьюх. Testbench это делает только для defineWebRoutes().
        $router->middleware('web')->group(function () use ($router) {
            $router->group(['prefix' => 'admin'], function () {
                AdminPanel::routes();
            });
        });
    }

    /**
     * @covers ::modalForm
     */
    public function test_modal_form_returns_json_fragment_without_layout(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('adminpanel.modal_form_redirects.modal-form'));

        $response->assertOk();
        $response->assertJson(['status' => true]);

        $template = strtolower($response->json('template'));
        $this->assertStringNotContainsString('<!doctype', $template);
        $this->assertStringNotContainsString('<html', $template);
        $this->assertStringContainsString('<form', $template);
    }

    /**
     * @covers ::modalForm
     */
    public function test_modal_form_edit_returns_prefilled_form_for_existing_model(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create(['from' => '/old-path']);

        $response = $this->getJson(route('adminpanel.modal_form_redirects.modal-form', $redirect->id));

        $response->assertOk();
        $this->assertStringContainsString('/old-path', $response->json('template'));
    }

    /**
     * Залоченные фильтры встроенной таблицы приходят query-строкой — новая запись должна
     * по умолчанию принадлежать той записи, в чей блок её добавляют.
     *
     * @covers ::modalForm
     */
    public function test_modal_form_create_prefills_model_from_query_filters(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(
            route('adminpanel.modal_form_redirects.modal-form', ['from' => '/locked-path'])
        );

        $response->assertOk();
        $this->assertStringContainsString('value="/locked-path"', $response->json('template'));
    }

    /**
     * @covers ::modalForm
     */
    public function test_modal_form_create_ignores_query_keys_that_are_not_columns(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(
            route('adminpanel.modal_form_redirects.modal-form', ['not_a_column' => 'boom'])
        );

        $response->assertOk();
        $this->assertStringNotContainsString('boom', $response->json('template'));
    }

    /**
     * @covers ::modalForm
     */
    public function test_modal_form_requires_create_or_update_policy(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('adminpanel.modal_form_denied.modal-form'));

        $response->assertForbidden();
    }

    /**
     * @covers ::storeReturn
     */
    public function test_store_with_modal_flag_returns_json_instead_of_redirect(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->postJson(route('adminpanel.modal_form_redirects.store'), [
            'from' => '/new-path',
            'to' => '/target',
            'modal' => 1,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => true]);
        $this->assertDatabaseHas('redirects', ['from' => '/new-path', 'to' => '/target']);
    }

    /**
     * @covers ::storeReturn
     */
    public function test_store_with_modal_flag_returns_validation_errors_as_json(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->postJson(route('adminpanel.modal_form_redirects.store'), [
            'from' => '',
            'to' => '/target',
            'modal' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('from');
    }

    /**
     * Браузер шлёт форму модалки обычным multipart-POST'ом (FormData), а не JSON.
     * Ответ всё равно должен быть JSON: редирект «назад с ошибками» axios повторит
     * тем же POST на GET-маршрут формы и получит 405 вместо списка ошибок.
     *
     * @covers ::validateModalAware
     */
    public function test_store_with_modal_flag_returns_json_errors_for_form_encoded_request(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->post(route('adminpanel.modal_form_redirects.store'), [
            'from' => '',
            'to' => '/target',
            'modal' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('status', false);
        $response->assertJsonValidationErrors('from');
    }

    /**
     * @covers ::validateModalAware
     */
    public function test_store_without_modal_flag_still_redirects_back_with_errors(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->post(route('adminpanel.modal_form_redirects.store'), [
            'from' => '',
            'to' => '/target',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('from');
    }

    /**
     * @covers ::updateReturn
     */
    public function test_update_with_modal_flag_returns_json_instead_of_redirect(): void
    {
        $this->actingAs($this->createAdminUser());
        $redirect = Redirect::factory()->create(['from' => '/old-path', 'to' => '/old-target']);

        $response = $this->putJson(route('adminpanel.modal_form_redirects.update', $redirect->id), [
            'from' => '/updated-path',
            'to' => '/old-target',
            'modal' => 1,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => true]);
        $this->assertDatabaseHas('redirects', ['id' => $redirect->id, 'from' => '/updated-path']);
    }
}

class ModalFormFixtureServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        AdminPanel::addDataType(new ModalFormRedirectDataType);
        AdminPanel::addDataType(new ModalFormDeniedDataType);
    }
}

class ModalFormRedirectDataType extends BaseDataType
{
    protected string $name = 'redirect';

    protected string $title = 'Redirects';

    protected string $slug = 'modal_form_redirects';

    protected string $orderDisplayColumn = 'from';

    protected ?string $modelClass = Redirect::class;

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

    public function rules(Request $request): array
    {
        return [
            'from' => ['required', 'min:3'],
            'to' => ['required'],
        ];
    }
}

/**
 * Отдельный класс модели на ту же таблицу `redirects` — политики в loadAuth() маппятся
 * по FQCN модели, поэтому для проверки запрета нужен свой класс, а не тот же Redirect
 * (иначе вторая политика перезаписала бы первую для общего modelClass).
 */
class DeniedRedirect extends Redirect {}

class DenyAllPolicy extends BasePolicy
{
    public function create($user): bool
    {
        return false;
    }

    public function update($user, $model): bool
    {
        return false;
    }
}

class ModalFormDeniedDataType extends BaseDataType
{
    protected string $name = 'redirect';

    protected string $title = 'Redirects (denied)';

    protected string $slug = 'modal_form_denied';

    protected string $orderDisplayColumn = 'from';

    protected ?string $modelClass = DeniedRedirect::class;

    protected string $policy = DenyAllPolicy::class;

    public function layout(): Collection
    {
        return collect([Row::blocks('from')]);
    }

    public function fields(): Collection
    {
        return collect([
            Text::make('from')->label('From'),
        ]);
    }
}
