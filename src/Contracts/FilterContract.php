<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface FilterContract
{

    /**
     * @return ?string
     */
    public function getName() : ?string;

    /**
     * @return string
     */
    public function getWidth() : string;

    /**
     * @return string
     */
    public function getPlaceholder():?string;

    /**
     * @return string
     */
    public function getTemplate() : string;

    /**
     * @param Request $request
     * @param DataTypeContract $dataType
     * @param FormFieldContract $column
     * @param $query
     * @return void
     */
    public function search(Request $request, DataTypeContract $dataType, FormFieldContract $column, $query) : void;


}
