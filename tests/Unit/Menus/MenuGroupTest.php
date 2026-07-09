<?php

namespace KY\AdminPanel\Tests\Unit\Menus;

use KY\AdminPanel\Menus\MenuGroup;
use KY\AdminPanel\Menus\MenuItem;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Menus\MenuGroup
 */
class MenuGroupTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getTitle
     * @covers ::getIcon
     * @covers ::addItem
     * @covers ::getItems
     */
    public function test_add_item_accumulates_items_in_order(): void
    {
        $group = new MenuGroup('SEO', 'search');
        $group->addItem($first = new MenuItem('Мета-информация', '/admin/seo'));
        $group->addItem($second = new MenuItem('Редиректы', '/admin/redirects'));

        $this->assertSame('SEO', $group->getTitle());
        $this->assertSame('search', $group->getIcon());
        $this->assertSame([$first, $second], $group->getItems()->all());
    }

    /**
     * @covers ::isActive
     */
    public function test_is_active_when_any_item_is_active(): void
    {
        $group = new MenuGroup('Контент');
        $group->addItem(new MenuItem('А', '/a', active: false));
        $group->addItem(new MenuItem('Б', '/b', active: true));

        $this->assertTrue($group->isActive());
    }

    /**
     * @covers ::isActive
     */
    public function test_is_not_active_without_active_items(): void
    {
        $group = new MenuGroup('Контент');
        $group->addItem(new MenuItem('А', '/a'));

        $this->assertFalse($group->isActive());
    }
}
