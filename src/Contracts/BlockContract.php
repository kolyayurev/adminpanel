<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Support\Collection;

interface BlockContract
{
    public function getType(): string;

    public function getClass(): string;

    public function getTemplate(): string;

    public function getBlocks(): Collection;

    public function isVisibleOnlyWhenHasFields(): bool;
}
