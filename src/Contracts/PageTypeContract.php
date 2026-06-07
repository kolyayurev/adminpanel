<?php

namespace KY\AdminPanel\Contracts;

interface PageTypeContract
{
    public function getTitle(): string;

    public function getSlug(): string;
}
