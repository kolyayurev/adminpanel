<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

interface BlockContract
{
    /**
     * @return string
     */
    public function getType():string;

    /**
     * @return string
     */
    public function getClass():string;


    /**
     * @return string
     */
    public function getTemplate() : string;

    /**
     * @return Collection
     */
    public function getBlocks():Collection;

    public function isVisibleOnlyWhenHasFields() : bool;
}
