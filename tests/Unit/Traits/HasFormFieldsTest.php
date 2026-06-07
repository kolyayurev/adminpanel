<?php

namespace KY\AdminPanel\Tests\Unit\Traits;

use Illuminate\Support\Collection;
use KY\AdminPanel\FormFields\Field;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\HasFormFields;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\HasFormFields
 */
class HasFormFieldsTest extends TestCase
{
    /**
     * @covers ::fields
     */
    public function test_fields_returns_empty_collection_by_default(): void
    {
        $fields = (new HasFormFieldsTestElement)->fields();

        $this->assertInstanceOf(Collection::class, $fields);
        $this->assertTrue($fields->isEmpty());
    }

    /**
     * @covers ::getFields
     */
    public function test_get_fields_returns_fields_keyed_by_name(): void
    {
        $title = Field::make('title');
        $body = Field::make('body');
        $element = new HasFormFieldsTestElement(collect([$title, $body]));

        $fields = $element->getFields();

        $this->assertSame($title, $fields->get('title'));
        $this->assertSame($body, $fields->get('body'));
    }

    /**
     * @covers ::getField
     */
    public function test_get_field_returns_field_by_name(): void
    {
        $title = Field::make('title');
        $element = new HasFormFieldsTestElement(collect([$title, Field::make('body')]));

        $this->assertSame($title, $element->getField('title'));
        $this->assertNull($element->getField('missing'));
    }

    /**
     * @covers ::getFieldsName
     */
    public function test_get_fields_name_returns_field_names(): void
    {
        $element = new HasFormFieldsTestElement(collect([
            Field::make('title'),
            Field::make('body'),
        ]));

        $this->assertSame(['title', 'body'], $element->getFieldsName());
    }
}

class HasFormFieldsTestElement
{
    use HasFormFields;

    public function __construct(private readonly ?Collection $fields = null) {}

    public function fields(): Collection
    {
        return $this->fields ?? collect();
    }
}
