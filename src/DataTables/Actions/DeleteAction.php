<?php

namespace KY\AdminPanel\DataTables\Actions;

use KY\AdminPanel\Contracts\DataTypeContract;
use Illuminate\Database\Eloquent\Model;

class DeleteAction extends BaseAction
{
    protected string $tag = 'button';
    protected string $policyName = 'delete';
    protected string $icon = 'trash3';


    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return !empty($this->attributes) ?$this->attributes: [
            'class'=>'btn btn-danger btn-sm ml-1',
            'data-action' => 'deleteModel',
            'data-slug' => $this->dataType->getSlug(),
            'data-id' => $this->model->getKey(),
        ];
    }

}
