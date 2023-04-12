<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Support\Collection;

interface PageTypeContract
{
    /**
     * @return string
     */
    public function getTitle():string;

    /**
     * @return string
     */
    public function getSlug():string;


}
