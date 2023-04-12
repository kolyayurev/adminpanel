<?php

namespace KY\AdminPanel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KY\AdminPanel\Support\APMedia
 */
class APMedia extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \KY\AdminPanel\Support\APMedia::class;
    }
}
