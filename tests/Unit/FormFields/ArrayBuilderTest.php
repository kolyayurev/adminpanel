<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\ArrayBuilder;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\ArrayBuilder
 */
class ArrayBuilderTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_construct_adds_default_title_field(): void
    {
        $field = new ArrayBuilder;

        $this->assertCount(1, $field->getFields());
        $this->assertSame('title', $field->getFields()->first()->get('name'));
    }

    /**
     * @covers ::min
     */
    public function test_min_sets_min_attribute(): void
    {
        $field = new ArrayBuilder;

        $this->assertSame($field, $field->min(2));
        $this->assertSame(2, $field->get('min'));
    }

    /**
     * @covers ::max
     */
    public function test_max_sets_max_attribute(): void
    {
        $field = new ArrayBuilder;

        $this->assertSame($field, $field->max(5));
        $this->assertSame(5, $field->get('max'));
    }

    /**
     * @covers ::displayValue
     */
    public function test_display_value_sets_display_value_attribute(): void
    {
        $field = new ArrayBuilder;

        $this->assertSame($field, $field->displayValue('return item.name;'));
        $this->assertSame('return item.name;', $field->get('displayValue'));
    }

    /**
     * @coversNothing
     */
    public function test_fields_replaces_default_fields_with_given_elements(): void
    {
        $field = new ArrayBuilder;
        $element = ArrayBuilderElement::make('name');

        $field->fields($element);

        $this->assertCount(1, $field->getFields());
        $this->assertSame($element, $field->getFields()->first());
    }
}
