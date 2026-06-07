<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\ListField;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\ListField
 */
class ListFieldTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_construct_adds_default_text_field(): void
    {
        $field = new ListField();

        $this->assertCount(1, $field->getFields());
        $this->assertSame('text', $field->getFields()->first()->get('name'));
    }
}
