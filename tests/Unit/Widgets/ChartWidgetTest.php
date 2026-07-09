<?php

namespace KY\AdminPanel\Tests\Unit\Widgets;

use Illuminate\Http\Request;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Widgets\ChartWidget;

/**
 * @coversDefaultClass \KY\AdminPanel\Widgets\ChartWidget
 */
class ChartWidgetTest extends TestCase
{
    /**
     * @covers ::getType
     */
    public function test_get_type_is_fixed_to_chart_regardless_of_class_name(): void
    {
        $widget = new RevenueChartWidget;

        $this->assertSame('chart', $widget->getType());
    }

    /**
     * @covers ::getSlug
     */
    public function test_get_slug_still_derives_from_class_name(): void
    {
        $widget = new RevenueChartWidget;

        $this->assertSame('revenue_chart', $widget->getSlug());
    }

    /**
     * @covers ::getChartType
     */
    public function test_get_chart_type_defaults_to_line(): void
    {
        $widget = new RevenueChartWidget;

        $this->assertSame('line', $widget->getChartType());
    }

    /**
     * @covers ::chartType
     * @covers ::getChartType
     */
    public function test_chart_type_sets_chart_type(): void
    {
        $widget = new RevenueChartWidget;

        $this->assertSame($widget, $widget->chartType('bar'));
        $this->assertSame('bar', $widget->getChartType());
    }

    /**
     * @covers ::getOptions
     */
    public function test_get_options_returns_empty_array_by_default(): void
    {
        $this->assertSame([], (new RevenueChartWidget)->getOptions());
    }

    /**
     * @covers ::options
     * @covers ::getOptions
     */
    public function test_options_merges_recursively_over_previous_options(): void
    {
        $widget = new RevenueChartWidget;

        $this->assertSame($widget, $widget->options(['plugins' => ['legend' => ['display' => false]]]));
        $widget->options(['plugins' => ['tooltip' => ['enabled' => true]], 'responsive' => true]);

        $this->assertSame([
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => ['enabled' => true],
            ],
            'responsive' => true,
        ], $widget->getOptions());
    }

    /**
     * @covers ::chartConfig
     */
    public function test_chart_config_builds_full_chartjs_payload(): void
    {
        $widget = (new RevenueChartWidget)
            ->chartType('bar')
            ->options(['responsive' => true]);

        $config = $this->callNonPublicMethod($widget, 'chartConfig', [
            ['Jan', 'Feb'],
            [['label' => 'Выручка', 'data' => [1, 2]]],
            ['scales' => ['y' => ['beginAtZero' => true]]],
        ]);

        $this->assertSame([
            'type' => 'bar',
            'labels' => ['Jan', 'Feb'],
            'datasets' => [['label' => 'Выручка', 'data' => [1, 2]]],
            'options' => [
                'responsive' => true,
                'scales' => ['y' => ['beginAtZero' => true]],
            ],
        ], $config);
    }
}

class RevenueChartWidget extends ChartWidget
{
    public function data(Request $request): array
    {
        return [];
    }
}
