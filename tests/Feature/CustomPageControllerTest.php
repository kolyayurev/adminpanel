<?php

namespace KY\AdminPanel\Tests\Feature;

use Illuminate\Support\Collection;
use KY\AdminPanel\CustomPages\BaseCustomPage;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Http\Controllers\CustomPageController
 */
class CustomPageControllerTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->group(['prefix' => 'admin'], function () {
            AdminPanel::routes();
        });
    }

    /**
     * @covers ::index
     */
    public function test_index_renders_registered_custom_page(): void
    {
        AdminPanel::addCustomPage(DemoCustomPage::class);
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('adminpanel.pages.index', ['page' => 'demo']));

        $response->assertOk();
        $response->assertSee('Тестовая страница');
    }

    /**
     * @covers ::index
     */
    public function test_index_returns_404_for_unknown_page(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('adminpanel.pages.index', ['page' => 'missing']));

        $response->assertNotFound();
    }
}

class DemoCustomPage extends BaseCustomPage
{
    protected string $title = 'Тестовая страница';

    public function layout(): Collection
    {
        // Виджет-шаблоны для рендера в общий Blocks-layout появятся в T22 (Chart.js);
        // здесь проверяем только маршрутизацию/контроллер, поэтому layout пуст.
        return collect([]);
    }
}
