<?php

namespace KY\AdminPanel\Widgets;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use KY\AdminPanel\Contracts\WidgetContract;
use KY\AdminPanel\Traits\Makeable;

abstract class BaseWidget implements WidgetContract
{
    use Makeable;

    protected string $slug;

    protected string $title;

    protected string $type;

    /**
     * Позволяет переопределить слаг через ::make('custom_slug'), как у FormFields/Actions.
     */
    public function name(?string $name): self
    {
        if (! empty($name)) {
            $this->slug = $name;
        }

        return $this;
    }

    public function getSlug(): string
    {
        if (empty($this->slug)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'Widget')) {
                $name = substr($name, 0, -strlen('Widget'));
            }

            $this->slug = Str::snake($name);
        }

        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title ?? $this->getSlug();
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): string
    {
        if (empty($this->type)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'Widget')) {
                $name = substr($name, 0, -strlen('Widget'));
            }

            $this->type = Str::snake($name);
        }

        return $this->type;
    }

    abstract public function data(Request $request): array;
}
