<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\Tabs;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Blocks\Tabs
 */
class TabsTest extends TestCase
{
    /**
     * @covers ::getId
     */
    public function test_get_id_returns_default_id(): void
    {
        $this->assertSame('tabs', (new Tabs())->getId());
    }

    /**
     * @covers ::id
     * @covers ::getId
     */
    public function test_id_sets_custom_id(): void
    {
        $tabs = new Tabs();

        $this->assertSame($tabs, $tabs->id('settings'));
        $this->assertSame('settings', $tabs->getId());
    }
}
