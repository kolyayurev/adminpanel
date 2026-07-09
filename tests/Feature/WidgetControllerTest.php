<?php

namespace KY\AdminPanel\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\CustomPages\BaseCustomPage;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Widgets\BaseWidget;

/**
 * @coversDefaultClass \KY\AdminPanel\Http\Controllers\WidgetController
 */
class WidgetControllerTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->group(['prefix' => 'admin'], function () {
            AdminPanel::routes();
        });
    }

    /**
     * @covers ::data
     */
    public function test_data_returns_widget_payload_with_request_query_params(): void
    {
        AdminPanel::addWidget(EchoWidget::make('echo'));
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('adminpanel.widgets.data', [
            'widget' => 'echo',
            'from' => '2026-01-01',
        ]));

        $response->assertOk();
        $response->assertJson(['from' => '2026-01-01']);
    }

    /**
     * @covers ::data
     */
    public function test_data_returns_404_for_unknown_widget(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('adminpanel.widgets.data', ['widget' => 'missing']));

        $response->assertNotFound();
    }

    /**
     * Виджет доступен по собственному URL сразу после регистрации CustomPage — без
     * дополнительного отдельного вызова AdminPanel::addWidget().
     *
     * @covers ::data
     */
    public function test_widget_registered_via_custom_page_is_reachable_by_its_own_url(): void
    {
        AdminPanel::addCustomPage(WidgetControllerTestPage::class);
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('adminpanel.widgets.data', ['widget' => 'echo']));

        $response->assertOk();
    }
}

class EchoWidget extends BaseWidget
{
    public function data(Request $request): array
    {
        return $request->query();
    }
}

class WidgetControllerTestPage extends BaseCustomPage
{
    protected string $title = 'Test';

    public function widgets(): Collection
    {
        return collect([EchoWidget::make('echo')]);
    }
}
