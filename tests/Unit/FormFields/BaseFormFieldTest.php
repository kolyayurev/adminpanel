<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Filters\InputFilter;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\BaseFormField
 */
class BaseFormFieldTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::hasFilter
     */
    public function test_construct_sets_default_input_filter(): void
    {
        $field = new Text;

        $this->assertTrue($field->hasFilter());
        $this->assertInstanceOf(InputFilter::class, $field->getFilter());
    }

    /**
     * @covers ::getValue
     */
    public function test_get_value_reads_named_model_attribute(): void
    {
        $field = Text::make('title');
        $model = (object) ['title' => 'Hello'];

        $this->assertSame('Hello', $field->getValue($model));
    }

    /**
     * @covers ::label
     * @covers ::getLabel
     */
    public function test_label_sets_label(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->label('Title'));
        $this->assertSame('Title', $field->getLabel());
    }

    /**
     * @covers ::afterLabel
     * @covers ::getAfterLabel
     * @covers ::buildAfterLabel
     */
    public function test_after_label_returns_explicit_or_default_after_label(): void
    {
        $field = new Text;

        $this->assertNull($field->getAfterLabel());
        $this->assertSame($field, $field->afterLabel('After'));
        $this->assertSame('After', $field->getAfterLabel());
    }

    /**
     * @covers ::getSlug
     */
    public function test_get_slug_returns_snake_case_class_name(): void
    {
        $this->assertSame('text', (new Text)->getSlug());
    }

    /**
     * @covers ::getFilter
     * @covers ::filter
     */
    public function test_get_filter_fills_filter_name_and_placeholder_from_field(): void
    {
        $field = Text::make('title')->label('Title');
        $filter = InputFilter::make();

        $field->filter($filter);

        $this->assertSame($filter, $field->getFilter());
        $this->assertSame('title', $filter->getName());
        $this->assertSame('Title', $filter->getPlaceholder());
    }

    /**
     * @covers ::getId
     */
    public function test_get_id_returns_normalized_field_id(): void
    {
        $this->assertSame('text_title', Text::make('title')->getId());
    }

    /**
     * @covers ::multilingual
     * @covers ::isMultilingual
     */
    public function test_multilingual_sets_multilingual_flag(): void
    {
        $field = new Text;

        $this->assertTrue($field->isMultilingual());
        $this->assertSame($field, $field->multilingual(false));
        $this->assertFalse($field->isMultilingual());
    }

    /**
     * @covers ::instruction
     */
    public function test_instruction_sets_instruction_attribute(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->instruction('Use short text'));
        $this->assertSame('Use short text', $field->get('instruction'));
    }

    /**
     * @covers ::hiddenOn
     */
    public function test_hidden_on_sets_hidden_on_attribute(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->hiddenOn(['index']));
        $this->assertSame(['index'], $field->get('hiddenOn'));
    }

    /**
     * @covers ::columnDefaultOrder
     * @covers ::getColumnDefaultOrder
     */
    public function test_column_default_order_sets_column_default_order(): void
    {
        $field = new Text;

        $this->assertNull($field->getColumnDefaultOrder());
        $this->assertSame($field, $field->columnDefaultOrder('desc'));
        $this->assertSame('desc', $field->getColumnDefaultOrder());
    }

    /**
     * @covers ::columnOrderable
     * @covers ::getColumnOrderable
     */
    public function test_column_orderable_sets_column_orderable_flag(): void
    {
        $field = new Text;

        $this->assertTrue($field->getColumnOrderable());
        $this->assertSame($field, $field->columnOrderable(false));
        $this->assertFalse($field->getColumnOrderable());
    }

    /**
     * @covers ::columnSearchable
     * @covers ::getColumnSearchable
     */
    public function test_column_searchable_sets_column_searchable_flag(): void
    {
        $field = new Text;

        $this->assertTrue($field->getColumnSearchable());
        $this->assertSame($field, $field->columnSearchable(false));
        $this->assertFalse($field->getColumnSearchable());
    }

    /**
     * @covers ::columnWidth
     * @covers ::getColumnWidth
     */
    public function test_column_width_sets_column_width(): void
    {
        $field = new Text;

        $this->assertSame('1', $field->getColumnWidth());
        $this->assertSame($field, $field->columnWidth('120px'));
        $this->assertSame('120px', $field->getColumnWidth());
    }

    /**
     * @covers ::columnEditable
     * @covers ::columnIsEditable
     */
    public function test_column_editable_sets_column_editable_flag(): void
    {
        $field = new Text;

        $this->assertFalse($field->columnIsEditable());
        $this->assertSame($field, $field->columnEditable());
        $this->assertTrue($field->columnIsEditable());
    }

    /**
     * @covers ::viewCell
     * @covers ::viewForm
     * @covers ::viewShow
     * @covers ::getViewByType
     */
    public function test_get_view_by_type_returns_custom_existing_views_or_default_views(): void
    {
        $field = new Text;

        $this->assertSame('adminpanel::formfields.text.cell', $field->getViewByType('cell'));
        $this->assertSame('adminpanel::formfields.text.form', $field->getViewByType('form'));
        $this->assertSame('adminpanel::formfields.text.show', $field->getViewByType('show'));

        $field->viewCell('adminpanel::formfields.text.cell')
            ->viewForm('adminpanel::formfields.text.form')
            ->viewShow('adminpanel::formfields.text.show');

        $this->assertSame('adminpanel::formfields.text.cell', $field->getViewByType('cell'));
        $this->assertSame('adminpanel::formfields.text.form', $field->getViewByType('form'));
        $this->assertSame('adminpanel::formfields.text.show', $field->getViewByType('show'));
    }

    /**
     * @covers ::checkView
     */
    public function test_check_view_returns_view_existence(): void
    {
        $field = new Text;

        $this->assertTrue($this->callNonPublicMethod($field, 'checkView', ['adminpanel::formfields.text.form']));
        $this->assertFalse($this->callNonPublicMethod($field, 'checkView', ['adminpanel::missing']));
    }

    /**
     * @covers ::beforeCreateContent
     */
    public function test_before_create_content_is_noop_hook(): void
    {
        $this->assertNull((new Text)->beforeCreateContent(null, null));
    }

    /**
     * @covers ::needSave
     */
    public function test_need_save_returns_true(): void
    {
        $this->assertTrue((new Text)->needSave());
    }

    /**
     * @covers ::beforePrepare
     */
    public function test_before_prepare_returns_value(): void
    {
        $this->assertSame('value', (new Text)->beforePrepare('value', new Request, null));
    }

    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_returns_value_or_default_for_empty_value(): void
    {
        $field = (new Text)->default('Default');

        $this->assertSame('Actual', $field->prepareValue('Actual', new Request, null));
        $this->assertSame('Default', $field->prepareValue('', new Request, null));
    }

    /**
     * @covers ::afterPrepare
     */
    public function test_after_prepare_returns_value(): void
    {
        $this->assertSame('value', (new Text)->afterPrepare('value', new Request, null));
    }

    /**
     * @covers ::beforeSave
     */
    public function test_before_save_is_noop_hook(): void
    {
        $this->assertNull((new Text)->beforeSave(new Request, null));
    }

    /**
     * @covers ::prepareValueToSave
     */
    public function test_prepare_value_to_save_reads_named_request_value(): void
    {
        $field = Text::make('title')->default('Default');

        $this->assertSame('Hello', $field->prepareValueToSave(new Request(['title' => 'Hello']), null));
        $this->assertSame('Default', $field->prepareValueToSave(new Request(['title' => '']), null));
    }

    /**
     * @covers ::afterSave
     */
    public function test_after_save_is_noop_hook(): void
    {
        $this->assertNull((new Text)->afterSave(new Request, null));
    }

    /**
     * @covers ::toArray
     */
    public function test_to_array_returns_field_attributes(): void
    {
        $field = Text::make('title')->label('Title')->multilingual(false);

        $this->assertSame('title', $field->toArray()['name']);
        $this->assertSame('Title', $field->toArray()['label']);
        $this->assertFalse($field->toArray()['multilingual']);
    }

    /**
     * @covers ::toColumn
     */
    public function test_to_column_returns_column_configured_from_field(): void
    {
        $field = Text::make('title')
            ->label('Title')
            ->columnDefaultOrder('asc')
            ->columnSearchable(false)
            ->columnOrderable(false)
            ->columnWidth('120px')
            ->columnEditable(true);

        $column = $field->toColumn();

        $this->assertSame('title', $column->get('name'));
        $this->assertSame('Title', $column->get('title'));
        $this->assertSame('asc', $column->get('defaultOrder'));
        $this->assertFalse($column->get('searchable'));
        $this->assertFalse($column->get('orderable'));
        $this->assertSame('120px', $column->get('width'));
        $this->assertTrue($column->isEditable());
        $this->assertSame($field, $column->getField());
    }
}
