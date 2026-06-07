<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Text
 */
class TextTest extends TestCase
{
    /**
     * @covers ::default
     */
    public function test_default_sets_default_attribute(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->default('Untitled'));
        $this->assertSame('Untitled', $field->get('default'));
    }

    /**
     * @covers ::type
     */
    public function test_type_sets_type_attribute(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->type('email'));
        $this->assertSame('email', $field->get('type'));
    }

    /**
     * @covers ::required
     */
    public function test_required_sets_required_attribute(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->required());
        $this->assertTrue($field->get('required'));
    }
}
