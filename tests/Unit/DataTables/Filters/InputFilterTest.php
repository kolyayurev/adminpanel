<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Filters;

use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Filters\InputFilter;
use KY\AdminPanel\Models\Redirect;
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
        $filter = new InputFilter;

        $this->assertTrue($filter->hasHandler());
        $this->assertSame('adminpanel::datatables.filters.input', $filter->getTemplate());
    }

    /**
     * @covers ::__construct
     * @covers ::usesExactMatch
     */
    public function test_construct_handler_adds_like_where_when_column_type_is_unknown(): void
    {
        // Без реальной модели (DataTypeContract-двойник в остальных тестах пакета) тип
        // колонки не определить — сохраняем прежнее поведение (LIKE) как безопасный дефолт.
        $filter = new InputFilter;
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

    /**
     * @covers ::__construct
     * @covers ::usesExactMatch
     */
    public function test_construct_handler_keeps_like_match_on_string_column(): void
    {
        Redirect::factory()->create(['from' => '/hello-world']);
        Redirect::factory()->create(['from' => '/other-page']);

        $filter = new InputFilter;
        $query = Redirect::query();

        $filter->search(
            new Request(['from' => 'hello']),
            $this->createDataTypeTestDouble(model: new Redirect),
            $this->createFormFieldTestDouble('from'),
            $query
        );

        $this->assertSame(['/hello-world'], $query->pluck('from')->all());
    }

    /**
     * @covers ::__construct
     * @covers ::usesExactMatch
     */
    public function test_construct_handler_matches_exact_value_not_substring_on_numeric_column(): void
    {
        // LIKE по числовой колонке на PostgreSQL падает с 500, а на других СУБД ловит
        // лишние совпадения (напр. "1" совпадает с "21").
        $one = Redirect::factory()->create(['from' => '/one', 'status' => 1]);
        Redirect::factory()->create(['from' => '/twenty-one', 'status' => 21]);

        $filter = new InputFilter;
        $query = Redirect::query();

        $filter->search(
            new Request(['status' => '1']),
            $this->createDataTypeTestDouble(model: new Redirect),
            $this->createFormFieldTestDouble('status'),
            $query
        );

        $this->assertSame([$one->id], $query->pluck('id')->all());
    }
}
