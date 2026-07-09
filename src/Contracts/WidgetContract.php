<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Http\Request;

interface WidgetContract
{
    public function getSlug(): string;

    public function getTitle(): string;

    public function getType(): string;

    public function data(Request $request): array;
}
