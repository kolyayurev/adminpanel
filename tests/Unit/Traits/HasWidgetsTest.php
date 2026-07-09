<?php

namespace KY\AdminPanel\Tests\Unit\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\HasWidgets;
use KY\AdminPanel\Widgets\BaseWidget;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\HasWidgets
 */
class HasWidgetsTest extends TestCase
{
    /**
     * @covers ::widgets
     */
    public function test_widgets_returns_empty_collection_by_default(): void
    {
        $widgets = (new HasWidgetsTestElement)->widgets();

        $this->assertInstanceOf(Collection::class, $widgets);
        $this->assertTrue($widgets->isEmpty());
    }

    /**
     * @covers ::getWidgets
     */
    public function test_get_widgets_returns_widgets_keyed_by_slug(): void
    {
        $sales = HasWidgetsTestWidget::make('sales');
        $visits = HasWidgetsTestWidget::make('visits');
        $element = new HasWidgetsTestElement(collect([$sales, $visits]));

        $widgets = $element->getWidgets();

        $this->assertSame($sales, $widgets->get('sales'));
        $this->assertSame($visits, $widgets->get('visits'));
    }

    /**
     * @covers ::getWidget
     */
    public function test_get_widget_returns_widget_by_slug(): void
    {
        $sales = HasWidgetsTestWidget::make('sales');
        $element = new HasWidgetsTestElement(collect([$sales, HasWidgetsTestWidget::make('visits')]));

        $this->assertSame($sales, $element->getWidget('sales'));
        $this->assertNull($element->getWidget('missing'));
    }

    /**
     * @covers ::getWidgetsName
     */
    public function test_get_widgets_name_returns_widget_slugs(): void
    {
        $element = new HasWidgetsTestElement(collect([
            HasWidgetsTestWidget::make('sales'),
            HasWidgetsTestWidget::make('visits'),
        ]));

        $this->assertSame(['sales', 'visits'], $element->getWidgetsName());
    }
}

class HasWidgetsTestElement
{
    use HasWidgets;

    public function __construct(private readonly ?Collection $items = null) {}

    public function widgets(): Collection
    {
        return $this->items ?? collect();
    }
}

class HasWidgetsTestWidget extends BaseWidget
{
    public function data(Request $request): array
    {
        return [];
    }
}
