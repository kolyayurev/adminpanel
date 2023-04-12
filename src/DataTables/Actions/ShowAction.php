<?php

namespace KY\AdminPanel\DataTables\Actions;

use KY\AdminPanel\Contracts\DataTypeContract;
use Illuminate\Database\Eloquent\Model;

class ShowAction extends BaseAction
{
    protected string $icon = 'eye';
    protected string $policyName = 'show';
    protected array $attributes = ['class'=>'btn btn-warning btn-xs'];

    public function getRoute() : string
    {
        return route('adminpanel.'.$this->dataType->getSlug().'.show', $this->model->getKey());
    }

}
