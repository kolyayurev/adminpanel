<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Support\Collection;

interface CustomPageContract
{
    public function getTitle(): string;

    public function getSlug(): string;

    public function getIcon(): string;

    public function showInMenu(): bool;

    public function widgets(): Collection;
}
