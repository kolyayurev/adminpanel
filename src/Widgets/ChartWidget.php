<?php

namespace KY\AdminPanel\Widgets;

abstract class ChartWidget extends BaseWidget
{
    protected string $type = 'chart';

    protected string $chartType = 'line';

    protected array $options = [];

    public function getChartType(): string
    {
        return $this->chartType;
    }

    public function chartType(string $chartType): self
    {
        $this->chartType = $chartType;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Произвольные опции Chart.js (scales, plugins, legend, animation и т.д.) — передаются
     * во фронтенд как есть, без интерпретации пакетом. Полный список — в документации
     * Chart.js: https://www.chartjs.org/docs/latest/. Мёржится поверх уже заданных опций.
     */
    public function options(array $options): self
    {
        $this->options = array_replace_recursive($this->options, $options);

        return $this;
    }

    /**
     * Собирает итоговый payload для Chart.js из данных графика и опций. Конкретный виджет
     * вызывает это из своего data(), опционально передавая доп. опции на конкретный запрос
     * (они мёржатся поверх заданных через ::options()).
     */
    protected function chartConfig(array $labels, array $datasets, array $options = []): array
    {
        return [
            'type' => $this->getChartType(),
            'labels' => $labels,
            'datasets' => $datasets,
            'options' => array_replace_recursive($this->getOptions(), $options),
        ];
    }
}
