<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\DataTypeContract;

interface RepositoryContract
{
    /**
     * @return mixed
     */
    public function model();

    /**
     * @return string
     */
    public function modelClass():string;

    /**
     * @param $data
     */
    public function create(array $data);

    public function getDataTableFilter(Request $request,DataTypeContract $dataType);

}
