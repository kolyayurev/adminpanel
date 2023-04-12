<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

interface ActionContract
{
    /**
     * @return string
     */
    public function getTag():string;

    /**
     * @return string
     */
    public function getIcon():string;

    /**
     * @return string
     */
    public function getTitle():string;

    /**
     * @return string
     */
    public function getColor():string;

    /**
     * @return string
     */
    public function getRoute() : string;

    /**
     * @return string
     */
    public function getPolicyName() : string;

    /**
     * @return string
     */
    public function getTemplate() : string;

    /**
     * @return array
     */
    public function getAttributes() : array;

    /**
     * @return string
     */
    public function convertAttributesToHtml() :string;

}
