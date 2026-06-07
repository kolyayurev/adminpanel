<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\Collapse;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Blocks\Collapse
 */
class CollapseTest extends TestCase
{
    /**
     * @covers ::getId
     */
    public function test_get_id_builds_id_from_header(): void
    {
        $collapse = (new Collapse)->header('Main Settings');

        $this->assertSame('main-settings', $collapse->getId());
    }

    /**
     * @covers ::getId
     */
    public function test_get_id_generates_stable_id_without_header(): void
    {
        $collapse = new Collapse;

        $id = $collapse->getId();

        $this->assertNotSame('', $id);
        $this->assertSame($id, $collapse->getId());
    }
}
