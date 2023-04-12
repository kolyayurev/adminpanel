<?php

namespace KY\AdminPanel\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\RepositoryContract;
use KY\AdminPanel\Contracts\DataTypeContract;

abstract class BaseRepository implements RepositoryContract
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->model();
    }

    public function model()
    {
        return app($this->modelClass());
    }

    public function modelClass():string
    {
        return Model::class;
    }

    public function create(array $data)
    {
        $class = $this->modelClass();
        $model = new $class($data);
        $model->save();

        return $model;
    }
    public function getDataTableFilter(Request $request,DataTypeContract $dataType){

        $query = $this->model->query();
        //TODO: eager load translations

        foreach ($dataType->getColumns() as $column)
        {
            session()->put('datatable.'.$dataType->getSlug().'.'.$column->get('name'),$request->get($column->get('name')));

            if($column->hasField()){
                $field = $column->getField();
                if($field->hasFilter())
                    $field->getFilter()->search($request,$dataType,$field,$query);
            }

        }

        return $query;
    }
}
