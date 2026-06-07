<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Http\Request;

interface RepositoryContract
{
    /**
     * @return mixed
     */
    public function model();

    public function modelClass(): string;

    public function create(array $data);

    public function getDataTableFilter(Request $request, DataTypeContract $dataType);
}
