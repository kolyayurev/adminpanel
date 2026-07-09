<?php

namespace KY\AdminPanel\Tests\Unit\Widgets;

use Illuminate\Http\Request;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Widgets\BaseWidget;

/**
 * @coversDefaultClass \KY\AdminPanel\Widgets\BaseWidget
 */
class BaseWidgetTest extends TestCase
{
    /**
     * @covers ::getSlug
     */
    public function test_get_slug_returns_snake_case_class_name_without_widget_suffix(): void
    {
        $widget = new DemoWidget;

        $this->assertSame('demo', $widget->getSlug());
    }

    /**
     * @covers ::getType
     */
    public function test_get_type_returns_snake_case_class_name_without_widget_suffix(): void
    {
        $widget = new DemoWidget;

        $this->assertSame('demo', $widget->getType());
    }

    /**
     * @covers ::name
     * @covers ::getSlug
     */
    public function test_name_overrides_slug_when_given(): void
    {
        $widget = new DemoWidget;

        $this->assertSame($widget, $widget->name('custom_slug'));
        $this->assertSame('custom_slug', $widget->getSlug());
    }

    /**
     * @covers ::name
     * @covers ::getSlug
     */
    public function test_name_keeps_default_slug_when_null(): void
    {
        $widget = new DemoWidget;

        $widget->name(null);

        $this->assertSame('demo', $widget->getSlug());
    }

    /**
     * @covers ::getTitle
     */
    public function test_get_title_defaults_to_slug_when_not_set(): void
    {
        $widget = new DemoWidget;

        $this->assertSame('demo', $widget->getTitle());
    }

    /**
     * @covers ::title
     * @covers ::getTitle
     */
    public function test_title_sets_title(): void
    {
        $widget = new DemoWidget;

        $this->assertSame($widget, $widget->title('Продажи'));
        $this->assertSame('Продажи', $widget->getTitle());
    }

    /**
     * @covers ::make
     */
    public function test_make_returns_new_instance_with_default_slug(): void
    {
        $widget = DemoWidget::make();

        $this->assertInstanceOf(DemoWidget::class, $widget);
        $this->assertSame('demo', $widget->getSlug());
    }

    /**
     * @covers ::make
     * @covers ::name
     */
    public function test_make_with_name_overrides_slug(): void
    {
        $widget = DemoWidget::make('custom_slug');

        $this->assertSame('custom_slug', $widget->getSlug());
    }

    /**
     * @covers ::data
     */
    public function test_data_returns_payload_from_concrete_widget(): void
    {
        $widget = new DemoWidget;

        $this->assertSame(['ok' => true], $widget->data(new Request));
    }
}

class DemoWidget extends BaseWidget
{
    public function data(Request $request): array
    {
        return ['ok' => true];
    }
}
