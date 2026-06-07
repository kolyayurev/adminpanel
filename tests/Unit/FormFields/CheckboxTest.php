<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Filters\SelectFilter;
use KY\AdminPanel\FormFields\Checkbox;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Checkbox
 */
class CheckboxTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_construct_sets_select_filter(): void
    {
        $this->assertInstanceOf(SelectFilter::class, (new Checkbox)->getFilter());
    }

    /**
     * @covers ::getFilter
     */
    public function test_get_filter_sets_boolean_options_from_field_texts(): void
    {
        $field = Checkbox::make('active')->textOff('No')->textOn('Yes');

        $this->assertSame(['' => 'Выбрать', 0 => 'No', 1 => 'Yes'], $field->getFilter()->getOptions());
    }

    /**
     * @covers ::default
     */
    public function test_default_sets_default_attribute(): void
    {
        $field = new Checkbox;

        $this->assertSame($field, $field->default(true));
        $this->assertTrue($field->get('default'));
    }

    /**
     * @covers ::textOn
     */
    public function test_text_on_sets_text_on_attribute(): void
    {
        $field = new Checkbox;

        $this->assertSame($field, $field->textOn('Enabled'));
        $this->assertSame('Enabled', $field->get('textOn'));
    }

    /**
     * @covers ::textOff
     */
    public function test_text_off_sets_text_off_attribute(): void
    {
        $field = new Checkbox;

        $this->assertSame($field, $field->textOff('Disabled'));
        $this->assertSame('Disabled', $field->get('textOff'));
    }

    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_converts_on_marker_to_integer(): void
    {
        $field = new Checkbox;

        $this->assertSame(1, $field->prepareValue('on', new Request, null));
        $this->assertSame(0, $field->prepareValue(null, new Request, null));
    }
}
