<?php

namespace KY\AdminPanel\Menus;

use Illuminate\Support\Collection;

class MenuGroup
{
    protected array $items = [];

    public function __construct(
        protected string $title,
        protected string $icon = '',
    ) {}

    public function addItem(MenuItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getItems(): Collection
    {
        return collect($this->items);
    }

    public function isActive(): bool
    {
        return $this->getItems()->contains(fn (MenuItem $item) => $item->isActive());
    }
}
