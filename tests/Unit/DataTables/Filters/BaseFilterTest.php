<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Filters;

use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Filters\BaseFilter;
use KY\AdminPanel\Tests\TestCase;
use TypeError;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTables\Filters\BaseFilter
 */
class BaseFilterTest extends TestCase
{
    /**
     * @covers ::name
     * @covers ::getName
     */
    public function test_name_sets_name(): void
    {
        $filter = new BaseFilterTestElement;

        $this->assertSame($filter, $filter->name('title'));
        $this->assertSame('title', $filter->getName());
    }

    /**
     * @covers ::width
     * @covers ::getWidth
     */
    public function test_width_sets_width(): void
    {
        $filter = new BaseFilterTestElement;

        $this->assertSame($filter, $filter->width('200px'));
        $this->assertSame('200px', $filter->getWidth());
    }

    /**
     * @covers ::placeholder
     * @covers ::getPlaceholder
     */
    public function test_placeholder_sets_placeholder(): void
    {
        $filter = new BaseFilterTestElement;

        $this->assertSame($filter, $filter->placeholder('Search'));
        $this->assertSame('Search', $filter->getPlaceholder());
    }

    /**
     * @covers ::template
     * @covers ::getTemplate
     */
    public function test_template_sets_template(): void
    {
        $filter = new BaseFilterTestElement;

        $this->assertSame($filter, $filter->template('custom.filter'));
        $this->assertSame('custom.filter', $filter->getTemplate());
    }

    /**
     * @covers ::attributes
     */
    public function test_attributes_throws_type_error_because_return_type_is_base_action(): void
    {
        $this->expectException(TypeError::class);

        (new BaseFilterTestElement)->attributes(['class' => 'form-control']);
    }

    /**
     * @covers ::getAttributes
     */
    public function test_get_attributes_returns_attributes_after_private_property_set(): void
    {
        $filter = new BaseFilterTestElement;
        $this->setNonPublicProperty($filter, 'attributes', ['class' => 'form-control']);

        $this->assertSame(['class' => 'form-control'], $filter->getAttributes());
    }

    /**
     * @covers ::convertAttributesToHtml
     */
    public function test_convert_attributes_to_html_concatenates_attributes(): void
    {
        $filter = new BaseFilterTestElement;
        $this->setNonPublicProperty($filter, 'attributes', [
            'class' => 'form-control',
            'data-id' => 7,
        ]);

        $this->assertSame('class="form-control"data-id="7"', $filter->convertAttributesToHtml());
    }

    /**
     * @covers ::setHandler
     * @covers ::hasHandler
     */
    public function test_set_handler_sets_handler(): void
    {
        $filter = new BaseFilterTestElement;

        $this->assertFalse($filter->hasHandler());
        $this->assertSame($filter, $filter->setHandler(static function (): void {}));
        $this->assertTrue($filter->hasHandler());
    }

    /**
     * @covers ::search
     */
    public function test_search_calls_handler_when_handler_exists(): void
    {
        $filter = new BaseFilterTestElement;
        $query = $this->createQueryTestDouble();
        $filter->setHandler(static function (Request $request, $dataType, $field, $query): void {
            $query->where('handled', '=', $request->get('value'));
        });

        $filter->search(
            new Request(['value' => 'yes']),
            $this->createDataTypeTestDouble(),
            $this->createFormFieldTestDouble(),
            $query
        );

        $this->assertSame([[
            'column' => 'handled',
            'operator' => '=',
            'value' => 'yes',
        ]], $query->whereCalls);
    }
}

class BaseFilterTestElement extends BaseFilter {}
