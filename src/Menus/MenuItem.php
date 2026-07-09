<?php

namespace KY\AdminPanel\Menus;

class MenuItem
{
    public function __construct(
        protected string $title,
        protected string $url,
        protected string $icon = '',
        protected bool $active = false,
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
