<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Http\Request;

interface FilterContract
{
    public function getName(): ?string;

    public function getWidth(): string;

    public function getPlaceholder(): ?string;

    public function getTemplate(): string;

    public function search(Request $request, DataTypeContract $dataType, FormFieldContract $column, $query): void;
}
