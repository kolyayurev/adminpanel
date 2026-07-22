<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Filters;

use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Filters\SelectFilter;
use KY\AdminPanel\Models\Redirect;
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
        $filter = new SelectFilter;

        $this->assertTrue($filter->hasHandler());
        $this->assertSame('adminpanel::datatables.filters.select', $filter->getTemplate());
    }

    /**
     * @covers ::getDefaultText
     * @covers ::defaultText
     */
    public function test_default_text_sets_default_text(): void
    {
        $filter = new SelectFilter;

        $this->assertSame($filter, $filter->defaultText('Choose'));
        $this->assertSame('Choose', $filter->getDefaultText());
    }

    /**
     * @covers ::hasDefaultText
     */
    public function test_has_default_text_returns_true_when_text_exists(): void
    {
        $this->assertTrue((new SelectFilter)->hasDefaultText());
    }

    /**
     * @covers ::defaultValue
     * @covers ::getDefaultValue
     */
    public function test_default_value_sets_default_value(): void
    {
        $filter = new SelectFilter;

        $this->assertSame($filter, $filter->defaultValue(''));
        $this->assertSame('', $filter->getDefaultValue());
    }

    /**
     * @covers ::options
     * @covers ::getOptions
     */
    public function test_options_returns_options_with_default_text(): void
    {
        $filter = (new SelectFilter)->defaultValue('')->defaultText('Choose')->options([
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
        $filter = new SelectFilter;

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
        $filter = new SelectFilter;

        $this->assertFalse($filter->isAjax());
        $this->assertSame($filter, $filter->aAjax());
        $this->assertTrue($filter->isAjax());
    }

    /**
     * @covers ::__construct
     * @covers ::usesExactMatch
     */
    public function test_construct_handler_matches_any_selected_item_by_substring(): void
    {
        Redirect::factory()->create(['from' => '/draft-post']);
        Redirect::factory()->create(['from' => '/published-post']);
        Redirect::factory()->create(['from' => '/archived-post']);

        $filter = new SelectFilter;
        $query = Redirect::query();

        $filter->search(
            new Request(['from' => 'draft,published']),
            $this->createDataTypeTestDouble(model: new Redirect),
            $this->createFormFieldTestDouble('from'),
            $query
        );

        $this->assertEqualsCanonicalizing(['/draft-post', '/published-post'], $query->pluck('from')->all());
    }

    /**
     * @covers ::__construct
     * @covers ::usesExactMatch
     */
    public function test_construct_handler_matches_exact_value_not_substring_on_numeric_column(): void
    {
        $one = Redirect::factory()->create(['from' => '/one', 'status' => 1]);
        Redirect::factory()->create(['from' => '/twenty-one', 'status' => 21]);

        $filter = new SelectFilter;
        $query = Redirect::query();

        $filter->search(
            new Request(['status' => '1']),
            $this->createDataTypeTestDouble(model: new Redirect),
            $this->createFormFieldTestDouble('status'),
            $query
        );

        $this->assertSame([$one->id], $query->pluck('id')->all());
    }

    /**
     * @covers ::__construct
     */
    public function test_construct_handler_does_not_widen_query_beyond_other_active_filters(): void
    {
        // orWhere без группировки склеивался с уже наложенными условиями через ИЛИ и
        // «протекал» мимо остальных фильтров колонок.
        Redirect::factory()->create(['from' => '/draft-post', 'to' => '/a']);
        Redirect::factory()->create(['from' => '/draft-post', 'to' => '/b']);
        Redirect::factory()->create(['from' => '/other-post', 'to' => '/a']);

        $filter = new SelectFilter;
        $query = Redirect::where('to', '/a');

        $filter->search(
            new Request(['from' => 'draft']),
            $this->createDataTypeTestDouble(model: new Redirect),
            $this->createFormFieldTestDouble('from'),
            $query
        );

        $this->assertSame(['/draft-post'], $query->pluck('from')->all());
    }
}
