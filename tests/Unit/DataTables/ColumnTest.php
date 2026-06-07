<?php

namespace KY\AdminPanel\Tests\Unit\DataTables;

use KY\AdminPanel\DataTables\Column;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTables\Column
 */
class ColumnTest extends TestCase
{
    /**
     * @covers ::name
     */
    public function test_name_sets_name_and_data(): void
    {
        $column = new Column;

        $this->assertSame($column, $column->name('title'));
        $this->assertSame('title', $column->get('name'));
        $this->assertSame('title', $column->get('data'));
    }

    /**
     * @covers ::data
     */
    public function test_data_sets_data(): void
    {
        $column = new Column;

        $this->assertSame($column, $column->data('author.name'));
        $this->assertSame('author.name', $column->get('data'));
    }

    /**
     * @covers ::title
     */
    public function test_title_sets_title(): void
    {
        $column = new Column;

        $this->assertSame($column, $column->title('Title'));
        $this->assertSame('Title', $column->get('title'));
    }

    /**
     * @covers ::searchable
     */
    public function test_searchable_sets_searchable_flag(): void
    {
        $column = new Column;

        $this->assertSame($column, $column->searchable(false));
        $this->assertFalse($column->get('searchable'));
    }

    /**
     * @covers ::orderable
     */
    public function test_orderable_sets_orderable_flag(): void
    {
        $column = new Column;

        $this->assertSame($column, $column->orderable(false));
        $this->assertFalse($column->get('orderable'));
    }

    /**
     * @covers ::width
     */
    public function test_width_sets_width(): void
    {
        $column = new Column;

        $this->assertSame($column, $column->width('120px'));
        $this->assertSame('120px', $column->get('width'));
    }

    /**
     * @covers ::defaultOrder
     * @covers ::hasDefaultOrder
     */
    public function test_default_order_sets_default_order(): void
    {
        $column = new Column;

        $this->assertFalse($column->hasDefaultOrder());
        $this->assertSame($column, $column->defaultOrder('desc'));
        $this->assertTrue($column->hasDefaultOrder());
        $this->assertSame('desc', $column->get('defaultOrder'));
    }

    /**
     * @covers ::editable
     * @covers ::isEditable
     */
    public function test_editable_sets_editable_flag(): void
    {
        $column = new Column;

        $this->assertFalse($column->isEditable());
        $this->assertSame($column, $column->editable(true));
        $this->assertTrue($column->isEditable());
    }

    /**
     * @covers ::field
     * @covers ::getField
     * @covers ::hasField
     */
    public function test_field_sets_form_field(): void
    {
        $column = new Column;
        $field = $this->createFormFieldTestDouble('title');

        $this->assertFalse($column->hasField());
        $this->assertSame($column, $column->field($field));
        $this->assertSame($field, $column->getField());
        $this->assertTrue($column->hasField());
    }

    /**
     * @covers ::toArray
     */
    public function test_to_array_returns_attributes(): void
    {
        $column = Column::make('title')->title('Title')->searchable(false);

        $this->assertSame([
            'name' => 'title',
            'data' => 'title',
            'title' => 'Title',
            'searchable' => false,
            'orderable' => true,
            'width' => 'auto',
            'defaultOrder' => null,
            'editable' => false,
        ], $column->toArray());
    }
}
