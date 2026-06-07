<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Filters;

use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Filters\SelectFilter;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTables\Filters\SelectFilter
 */
class SelectFilterTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getTemplate
     */
    public function test_construct_sets_default_handler_and_template(): void
    {
        $filter = new SelectFilter();

        $this->assertTrue($filter->hasHandler());
        $this->assertSame('adminpanel::datatables.filters.select', $filter->getTemplate());
    }

    /**
     * @covers ::getDefaultText
     * @covers ::defaultText
     */
    public function test_default_text_sets_default_text(): void
    {
        $filter = new SelectFilter();

        $this->assertSame($filter, $filter->defaultText('Choose'));
        $this->assertSame('Choose', $filter->getDefaultText());
    }

    /**
     * @covers ::hasDefaultText
     */
    public function test_has_default_text_returns_true_when_text_exists(): void
    {
        $this->assertTrue((new SelectFilter())->hasDefaultText());
    }

    /**
     * @covers ::defaultValue
     * @covers ::getDefaultValue
     */
    public function test_default_value_sets_default_value(): void
    {
        $filter = new SelectFilter();

        $this->assertSame($filter, $filter->defaultValue(''));
        $this->assertSame('', $filter->getDefaultValue());
    }

    /**
     * @covers ::options
     * @covers ::getOptions
     */
    public function test_options_returns_options_with_default_text(): void
    {
        $filter = (new SelectFilter())->defaultValue('')->defaultText('Choose')->options([
            'published' => 'Published',
        ]);

        $this->assertSame([
            '' => 'Choose',
            'published' => 'Published',
        ], $filter->getOptions());
    }

    /**
     * @covers ::multiple
     * @covers ::isMultiple
     */
    public function test_multiple_sets_multiple_flag(): void
    {
        $filter = new SelectFilter();

        $this->assertFalse($filter->isMultiple());
        $this->assertSame($filter, $filter->multiple(true));
        $this->assertTrue($filter->isMultiple());
    }

    /**
     * @covers ::aAjax
     * @covers ::isAjax
     */
    public function test_a_ajax_sets_ajax_flag(): void
    {
        $filter = new SelectFilter();

        $this->assertFalse($filter->isAjax());
        $this->assertSame($filter, $filter->aAjax());
        $this->assertTrue($filter->isAjax());
    }

    /**
     * @covers ::__construct
     */
    public function test_construct_handler_adds_or_where_for_each_selected_item(): void
    {
        $filter = new SelectFilter();
        $query = $this->createQueryTestDouble();

        $filter->search(
            new Request(['status' => 'draft,published']),
            $this->createDataTypeTestDouble(),
            $this->createFormFieldTestDouble('status'),
            $query
        );

        $this->assertSame([
            [
                'column' => 'status',
                'operator' => 'like',
                'value' => '%draft%',
            ],
            [
                'column' => 'status',
                'operator' => 'like',
                'value' => '%published%',
            ],
        ], $query->orWhereCalls);
    }
}
