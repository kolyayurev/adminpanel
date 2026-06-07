<?php

namespace KY\AdminPanel\Tests\Unit\Support;

use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Support\ArrayBuilderRule;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Support\ArrayBuilderElement
 */
class ArrayBuilderElementTest extends TestCase
{
    /**
     * @covers ::name
     * @covers ::getName
     */
    public function test_name_sets_name(): void
    {
        $element = new ArrayBuilderElement;

        $this->assertSame($element, $element->name('title'));
        $this->assertSame('title', $element->getName());
    }

    /**
     * @covers ::label
     * @covers ::getLabel
     */
    public function test_label_sets_label(): void
    {
        $element = new ArrayBuilderElement;

        $this->assertSame($element, $element->label('Title'));
        $this->assertSame('Title', $element->getLabel());
    }

    /**
     * @covers ::component
     * @covers ::getComponent
     */
    public function test_component_sets_component(): void
    {
        $element = new ArrayBuilderElement;

        $this->assertSame('el-input', $element->getComponent());
        $this->assertSame($element, $element->component('el-select'));
        $this->assertSame('el-select', $element->getComponent());
    }

    /**
     * @covers ::default
     * @covers ::getDefault
     */
    public function test_default_sets_default_value(): void
    {
        $element = new ArrayBuilderElement;

        $this->assertSame($element, $element->default('Untitled'));
        $this->assertSame('Untitled', $element->getDefault());
    }

    /**
     * @covers ::rules
     * @covers ::addRule
     * @covers ::getRules
     */
    public function test_rules_adds_only_array_builder_rules(): void
    {
        $rule = (new ArrayBuilderRule)->required();
        $element = new ArrayBuilderElement;

        $this->assertSame($element, $element->rules($rule, 'ignored'));

        $this->assertSame([$rule], $element->getRules());
    }

    /**
     * @covers ::props
     * @covers ::getProps
     */
    public function test_props_sets_props(): void
    {
        $element = new ArrayBuilderElement;

        $this->assertSame($element, $element->props(['clearable' => true]));
        $this->assertSame(['clearable' => true], $element->getProps());
    }

    /**
     * @covers ::col
     * @covers ::getCol
     */
    public function test_col_sets_column_span(): void
    {
        $element = new ArrayBuilderElement;

        $this->assertSame(24, $element->getCol());
        $this->assertSame($element, $element->col(12));
        $this->assertSame(12, $element->getCol());
    }

    /**
     * @covers ::toArray
     */
    public function test_to_array_returns_element_state(): void
    {
        $element = ArrayBuilderElement::make('title')
            ->label('Title')
            ->component('el-input')
            ->default('Untitled')
            ->rules((new ArrayBuilderRule)->required())
            ->props(['clearable' => true])
            ->col(12);

        $this->assertSame([
            'name' => 'title',
            'label' => 'Title',
            'component' => 'el-input',
            'default' => 'Untitled',
            'rules' => [[
                'required' => true,
                'message' => 'Обязательное поле',
                'trigger' => 'blur',
            ]],
            'props' => ['clearable' => true],
            'col' => 12,
        ], $element->toArray());
    }
}
