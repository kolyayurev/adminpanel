<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\Accordion;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Blocks\Accordion
 */
class AccordionTest extends TestCase
{
    /**
     * @covers ::getId
     */
    public function test_get_id_returns_default_id(): void
    {
        $this->assertSame('accordion', (new Accordion)->getId());
    }

    /**
     * @covers ::id
     * @covers ::getId
     */
    public function test_id_sets_custom_id(): void
    {
        $accordion = new Accordion;

        $this->assertSame($accordion, $accordion->id('settings'));
        $this->assertSame('settings', $accordion->getId());
    }
}
