<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Filters;

use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Filters\InputFilter;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTables\Filters\InputFilter
 */
class InputFilterTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getTemplate
     */
    public function test_construct_sets_default_handler_and_template(): void
    {
        $filter = new InputFilter();

        $this->assertTrue($filter->hasHandler());
        $this->assertSame('adminpanel::datatables.filters.input', $filter->getTemplate());
    }

    /**
     * @covers ::__construct
     */
    public function test_construct_handler_adds_like_where_when_request_filled(): void
    {
        $filter = new InputFilter();
        $query = $this->createQueryTestDouble();

        $filter->search(
            new Request(['title' => 'hello']),
            $this->createDataTypeTestDouble(),
            $this->createFormFieldTestDouble('title'),
            $query
        );

        $this->assertSame([[
            'column' => 'title',
            'operator' => 'like',
            'value' => '%hello%',
        ]], $query->whereCalls);
    }
}
