<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\Tab;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Blocks\Tab
 */
class TabTest extends TestCase
{
    /**
     * @covers ::getId
     */
    public function test_get_id_builds_id_from_header(): void
    {
        $tab = (new Tab)->header('Main Settings');

        $this->assertSame('main-settings', $tab->getId());
    }

    /**
     * @covers ::id
     * @covers ::getId
     */
    public function test_id_sets_custom_id(): void
    {
        $tab = new Tab;

        $this->assertSame($tab, $tab->id('custom'));
        $this->assertSame('custom', $tab->getId());
    }

    /**
     * @covers ::header
     * @covers ::getHeader
     */
    public function test_header_sets_header(): void
    {
        $tab = new Tab;

        $this->assertSame($tab, $tab->header('Main'));
        $this->assertSame('Main', $tab->getHeader());
    }

    /**
     * @covers ::hasHeader
     */
    public function test_has_header_returns_true_only_when_header_exists(): void
    {
        $tab = new Tab;

        $this->assertFalse($tab->hasHeader());
        $tab->header('Main');
        $this->assertTrue($tab->hasHeader());
    }
}
