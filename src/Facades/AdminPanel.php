<?php

namespace KY\AdminPanel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KY\AdminPanel\AdminPanel
 */
class AdminPanel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \KY\AdminPanel\AdminPanel::class;
    }
}
