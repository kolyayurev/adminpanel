<?php

namespace KY\AdminPanel\CustomPages;

use Illuminate\Support\Str;
use KY\AdminPanel\Contracts\CustomPageContract;
use KY\AdminPanel\Traits\HasLayout;
use KY\AdminPanel\Traits\HasWidgets;

class BaseCustomPage implements CustomPageContract
{
    use HasLayout, HasWidgets;

    protected string $title;

    protected string $slug;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        if (empty($this->slug)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'CustomPage')) {
                $name = substr($name, 0, -strlen('CustomPage'));
            }

            $this->slug = Str::snake($name);
        }

        return $this->slug;
    }
}
