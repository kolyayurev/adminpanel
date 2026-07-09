<?php

declare(strict_types=1);

namespace KY\AdminPanel\Traits;

use Illuminate\Support\Collection;
use KY\AdminPanel\Contracts\WidgetContract;

trait HasWidgets
{
    public function widgets(): Collection
    {
        return collect([]);
    }

    public function getWidgets(): Collection
    {
        return $this->widgets()->keyBy(fn (WidgetContract $widget) => $widget->getSlug());
    }

    public function getWidget(string $slug): ?WidgetContract
    {
        return $this->widgets()->first(fn (WidgetContract $widget) => $widget->getSlug() === $slug);
    }

    public function getWidgetsName(): array
    {
        $names = [];
        foreach ($this->widgets() as $widget) {
            $names[] = $widget->getSlug();
        }

        return $names;
    }
}
