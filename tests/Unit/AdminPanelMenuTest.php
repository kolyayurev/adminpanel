<?php

namespace KY\AdminPanel\Tests\Unit;

use Illuminate\Support\Collection;
use KY\AdminPanel\Contracts\MenuContract;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\Menus\MenuItem;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\AdminPanel
 */
class AdminPanelMenuTest extends TestCase
{
    /**
     * @covers ::addMenu
     * @covers ::getMenu
     */
    public function test_admin_menu_is_registered_by_default_on_boot(): void
    {
        $this->assertInstanceOf(Collection::class, AdminPanel::getMenu('admin'));
    }

    /**
     * @covers ::addMenu
     * @covers ::getMenu
     */
    public function test_add_menu_replaces_builder_for_slug(): void
    {
        AdminPanel::addMenu(AdminPanelMenuTestCustomMenu::class);

        $items = AdminPanel::getMenu('admin');

        $this->assertCount(1, $items);
        $this->assertSame('Единственный пункт', $items->first()->getTitle());
    }

    /**
     * @covers ::addMenuItem
     * @covers ::getMenu
     */
    public function test_add_menu_item_appends_without_replacing_builder(): void
    {
        $before = AdminPanel::getMenu('admin')->count();

        AdminPanel::addMenuItem(new MenuItem('Внешний пункт', '/admin/external'));

        $items = AdminPanel::getMenu('admin');

        $this->assertSame($before + 1, $items->count());
        $this->assertSame('Внешний пункт', $items->last()->getTitle());
    }
}

class AdminPanelMenuTestCustomMenu implements MenuContract
{
    public function items(): Collection
    {
        return collect([new MenuItem('Единственный пункт', '/admin/only')]);
    }

    public function getSlug(): string
    {
        return 'admin';
    }

    public function getName(): string
    {
        return 'Admin';
    }
}
