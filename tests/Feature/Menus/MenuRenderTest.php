<?php

namespace KY\AdminPanel\Tests\Feature\Menus;

use Illuminate\Support\Collection;
use KY\AdminPanel\CustomPages\BaseCustomPage;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\Tests\TestCase;

/**
 * Регрессия вывода сайдбар-меню (resources/views/menus/admin.blade.php).
 *
 * @coversDefaultClass \KY\AdminPanel\Menus\BaseMenu
 */
class MenuRenderTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->group(['prefix' => 'admin'], function () {
            AdminPanel::routes();
        });
    }

    /**
     * @covers ::items
     */
    public function test_default_menu_shows_seo_and_data_type_items(): void
    {
        AdminPanel::addCustomPage(MenuRenderTestCustomPage::class);
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('adminpanel.pages.index', ['page' => 'menu_render_test']));

        $response->assertOk();
        // SEO-группа (встроенные Seo/Redirect/Sef DataType).
        $response->assertSee(route('adminpanel.seo.index'), false);
        $response->assertSee(route('adminpanel.redirects.index'), false);
        $response->assertSee(route('adminpanel.sef.index'), false);
        // Прикладные DataType (Users/Roles регистрируются провайдером по умолчанию).
        $response->assertSee(route('adminpanel.users.index'), false);
        $response->assertSee(route('adminpanel.roles.index'), false);
    }

    /**
     * @covers ::items
     */
    public function test_default_menu_hides_content_group_without_registered_page_types(): void
    {
        AdminPanel::addCustomPage(MenuRenderTestCustomPage::class);
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('adminpanel.pages.index', ['page' => 'menu_render_test']));

        $response->assertDontSee('Контент', false);
    }

    /**
     * @covers ::items
     */
    public function test_default_menu_hides_tools_when_gate_disabled(): void
    {
        // Гейт view_tools по умолчанию выключен (config/config.php) — Tools не должен рендериться.
        AdminPanel::addCustomPage(MenuRenderTestCustomPage::class);
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('adminpanel.pages.index', ['page' => 'menu_render_test']));

        $response->assertDontSee(route('adminpanel.tools.index'), false);
    }

    /**
     * @covers ::items
     */
    public function test_registered_custom_page_appears_in_menu(): void
    {
        AdminPanel::addCustomPage(MenuRenderTestCustomPage::class);
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('adminpanel.pages.index', ['page' => 'menu_render_test']));

        $response->assertSee(route('adminpanel.pages.index', 'menu_render_test'), false);
        $response->assertSee('Пункт меню теста', false);
    }

    /**
     * @covers ::items
     */
    public function test_custom_page_hidden_from_menu_when_show_in_menu_is_false(): void
    {
        AdminPanel::addCustomPage(HiddenMenuRenderTestCustomPage::class);
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('adminpanel.pages.index', ['page' => 'hidden_menu_render_test']));

        $response->assertDontSee(route('adminpanel.pages.index', 'hidden_menu_render_test'), false);
    }
}

class MenuRenderTestCustomPage extends BaseCustomPage
{
    protected string $title = 'Пункт меню теста';

    protected string $slug = 'menu_render_test';

    public function layout(): Collection
    {
        return collect([]);
    }
}

class HiddenMenuRenderTestCustomPage extends BaseCustomPage
{
    protected string $title = 'Скрытый пункт меню';

    protected string $slug = 'hidden_menu_render_test';

    public function layout(): Collection
    {
        return collect([]);
    }

    public function showInMenu(): bool
    {
        return false;
    }
}
