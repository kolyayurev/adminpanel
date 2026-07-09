<?php

namespace KY\AdminPanel\Tests\Unit\Menus;

use Illuminate\Support\Facades\Gate;
use KY\AdminPanel\CustomPages\BaseCustomPage;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\Menus\AdminMenu;
use KY\AdminPanel\Menus\MenuGroup;
use KY\AdminPanel\Menus\MenuItem;
use KY\AdminPanel\Models\User;
use KY\AdminPanel\PageTypes\BasePageType;
use KY\AdminPanel\Policies\BasePolicy;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Menus\BaseMenu
 */
class BaseMenuTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->group(['prefix' => 'admin'], function () {
            AdminPanel::routes();
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Gate-проверки в BaseMenu (Policy::list()) требуют аутентифицированного пользователя.
        $this->actingAs($this->createAdminUser());
    }

    /**
     * @covers ::items
     * @covers ::contentGroup
     */
    public function test_content_group_absent_without_registered_page_types(): void
    {
        $items = (new AdminMenu)->items();

        $this->assertFalse($items->contains(fn ($item) => $item instanceof MenuGroup && $item->getTitle() === 'Контент'));
    }

    /**
     * @covers ::items
     * @covers ::contentGroup
     */
    public function test_content_group_lists_registered_page_types(): void
    {
        AdminPanel::addPageType(BaseMenuTestPageType::class);

        $group = (new AdminMenu)->items()->first(fn ($item) => $item instanceof MenuGroup && $item->getTitle() === 'Контент');

        $this->assertNotNull($group);
        $this->assertSame('Раздел теста', $group->getItems()->first()->getTitle());
        $this->assertSame(route('adminpanel.settings', 'base_menu_test'), $group->getItems()->first()->getUrl());
    }

    /**
     * @covers ::items
     * @covers ::seoGroup
     */
    public function test_seo_group_lists_built_in_seo_data_types(): void
    {
        $group = (new AdminMenu)->items()->first(fn ($item) => $item instanceof MenuGroup && $item->getTitle() === 'SEO');

        $this->assertNotNull($group);
        $this->assertCount(3, $group->getItems());
    }

    /**
     * @covers ::items
     * @covers ::dataTypeItems
     */
    public function test_data_type_items_exclude_seo_data_types_and_use_plural_title(): void
    {
        $items = (new AdminMenu)->items();

        $this->assertTrue($items->contains(fn ($item) => $item instanceof MenuItem && $item->getUrl() === route('adminpanel.users.index')));
        $this->assertFalse($items->contains(fn ($item) => $item instanceof MenuItem && $item->getUrl() === route('adminpanel.seo.index')));
    }

    /**
     * @covers ::items
     * @covers ::dataTypeItems
     */
    public function test_data_type_item_hidden_when_list_ability_denied(): void
    {
        Gate::policy(User::class, BaseMenuTestDenyListPolicy::class);

        $items = (new AdminMenu)->items();

        $this->assertFalse($items->contains(fn ($item) => $item instanceof MenuItem && $item->getUrl() === route('adminpanel.users.index')));
    }

    /**
     * @covers ::items
     * @covers ::customPageItems
     */
    public function test_custom_page_items_respect_show_in_menu(): void
    {
        AdminPanel::addCustomPage(BaseMenuTestCustomPage::class);
        AdminPanel::addCustomPage(BaseMenuTestHiddenCustomPage::class);

        $items = (new AdminMenu)->items();

        $this->assertTrue($items->contains(fn ($item) => $item instanceof MenuItem && $item->getUrl() === route('adminpanel.pages.index', 'base_menu_test')));
        $this->assertFalse($items->contains(fn ($item) => $item instanceof MenuItem && $item->getUrl() === route('adminpanel.pages.index', 'base_menu_test_hidden')));
    }

    /**
     * @covers ::items
     * @covers ::toolsItem
     */
    public function test_tools_item_respects_gate(): void
    {
        // Gate 'view_tools' регистрируется закрытием один раз при boot из конфига
        // (см. AdminPanelServiceProvider::loadAuth) — постфактум конфиг не перечитывается,
        // поэтому «включённое» состояние подменяем через Gate::define напрямую.
        $this->assertFalse((new AdminMenu)->items()->contains(fn ($item) => $item instanceof MenuItem && $item->getTitle() === 'Tools'));

        Gate::define('view_tools', fn () => true);
        $this->assertTrue((new AdminMenu)->items()->contains(fn ($item) => $item instanceof MenuItem && $item->getTitle() === 'Tools'));
    }

    /**
     * @covers ::items
     */
    public function test_subclass_can_override_single_section_without_touching_others(): void
    {
        // Гейт включён намеренно — доказываем, что Tools скрыт именно переопределением
        // секции в наследнике, а не тем, что гейт и так закрыт.
        Gate::define('view_tools', fn () => true);

        $items = (new BaseMenuTestMenuWithoutTools)->items();

        $this->assertFalse($items->contains(fn ($item) => $item instanceof MenuItem && $item->getTitle() === 'Tools'));
        $this->assertTrue($items->contains(fn ($item) => $item instanceof MenuItem && $item->getUrl() === route('adminpanel.users.index')));
    }
}

class BaseMenuTestPageType extends BasePageType
{
    protected $title = 'Раздел теста';

    protected $slug = 'base_menu_test';
}

class BaseMenuTestCustomPage extends BaseCustomPage
{
    protected string $title = 'Кастомная страница';

    protected string $slug = 'base_menu_test';
}

class BaseMenuTestHiddenCustomPage extends BaseCustomPage
{
    protected string $title = 'Скрытая кастомная страница';

    protected string $slug = 'base_menu_test_hidden';

    public function showInMenu(): bool
    {
        return false;
    }
}

class BaseMenuTestDenyListPolicy extends BasePolicy
{
    public function list($user)
    {
        return false;
    }
}

class BaseMenuTestMenuWithoutTools extends AdminMenu
{
    protected function toolsItem(): ?MenuItem
    {
        return null;
    }
}
