<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Filters\SelectFilter;
use KY\AdminPanel\FormFields\Select;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Select
 */
class SelectTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_construct_sets_select_filter(): void
    {
        $this->assertInstanceOf(SelectFilter::class, (new Select)->getFilter());
    }

    /**
     * @covers ::options
     * @covers ::getOptions
     * @covers ::getOption
     * @covers ::hasOptions
     */
    public function test_options_sets_and_reads_options(): void
    {
        $field = (new Select)->options(['draft' => ['Draft']]);

        $this->assertTrue($field->hasOptions());
        $this->assertSame(['draft' => ['Draft']], $field->getOptions());
        $this->assertSame(['Draft'], $field->getOption('draft'));
    }

    /**
     * @covers ::multiple
     * @covers ::isMultiple
     */
    public function test_multiple_sets_multiple_flag(): void
    {
        $field = new Select;

        $this->assertFalse($field->isMultiple());
        $this->assertSame($field, $field->multiple());
        $this->assertTrue($field->isMultiple());
    }

    /**
     * @covers ::getFilter
     */
    public function test_get_filter_copies_options_and_multiple_from_field(): void
    {
        $field = Select::make('status')->options(['draft' => 'Draft'])->multiple();
        $filter = $field->getFilter();

        $this->assertSame(['' => 'Выбрать', 'draft' => 'Draft'], $filter->getOptions());
        $this->assertTrue($filter->isMultiple());
    }

    /**
     * @covers ::getValue
     */
    public function test_get_value_decodes_json_when_field_is_multiple(): void
    {
        $field = Select::make('tags')->multiple();
        $model = (object) ['tags' => '["news","top"]'];

        $this->assertSame(['news', 'top'], $field->getValue($model));
    }

    /**
     * @covers ::getValue
     */
    public function test_get_value_returns_raw_value_when_field_is_not_multiple(): void
    {
        $field = Select::make('status');
        $model = (object) ['status' => 'draft'];

        $this->assertSame('draft', $field->getValue($model));
    }

    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_json_encodes_multiple_and_keeps_single_as_is(): void
    {
        $this->assertSame('["a","b"]', (new Select)->multiple()->prepareValue(['a', 'b'], new Request, null));
        $this->assertSame('b', (new Select)->prepareValue('b', new Request, null));
    }

    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_returns_default_for_empty_value_when_default_exists(): void
    {
        $field = (new Select)->set('default', 'draft');

        $this->assertSame('draft', $field->prepareValue([], new Request, null));
    }
}
