<?php

namespace KY\AdminPanel\Tests\Unit\Menus;

use KY\AdminPanel\Menus\MenuItem;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Menus\MenuItem
 */
class MenuItemTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getTitle
     * @covers ::getUrl
     * @covers ::getIcon
     * @covers ::isActive
     */
    public function test_getters_return_constructor_values(): void
    {
        $item = new MenuItem('Пользователи', '/admin/users', 'collection', true);

        $this->assertSame('Пользователи', $item->getTitle());
        $this->assertSame('/admin/users', $item->getUrl());
        $this->assertSame('collection', $item->getIcon());
        $this->assertTrue($item->isActive());
    }

    /**
     * @covers ::__construct
     * @covers ::getIcon
     * @covers ::isActive
     */
    public function test_icon_and_active_default_to_empty_and_false(): void
    {
        $item = new MenuItem('Tools', '/admin/tools');

        $this->assertSame('', $item->getIcon());
        $this->assertFalse($item->isActive());
    }
}
