<?php

namespace KY\AdminPanel\Tests\Unit\Traits\FormFields;

use Illuminate\Support\Collection;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\FormFields\HasArrayBuilderFields;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\FormFields\HasArrayBuilderFields
 */
class HasArrayBuilderFieldsTest extends TestCase
{
    /**
     * @covers ::getFields
     */
    public function test_get_fields_returns_collection(): void
    {
        $element = new HasArrayBuilderFieldsTestElement;

        $this->assertInstanceOf(Collection::class, $element->getFields());
        $this->assertTrue($element->getFields()->isEmpty());
    }

    /**
     * @covers ::addField
     */
    public function test_add_field_adds_only_array_builder_elements(): void
    {
        $element = new HasArrayBuilderFieldsTestElement;
        $field = ArrayBuilderElement::make('title');

        $this->assertSame($element, $element->addField('ignored'));
        $this->assertSame($element, $element->addField($field));
        $this->assertCount(1, $element->getFields());
        $this->assertSame($field, $element->getFields()->first());
    }

    /**
     * @covers ::fields
     */
    public function test_fields_replaces_fields_with_given_elements(): void
    {
        $element = new HasArrayBuilderFieldsTestElement;
        $title = ArrayBuilderElement::make('title');
        $body = ArrayBuilderElement::make('body');

        $this->assertSame($element, $element->fields($title, $body));
        $this->assertSame([$title, $body], $element->getFields()->all());
    }
}

class HasArrayBuilderFieldsTestElement
{
    use HasArrayBuilderFields;
}
