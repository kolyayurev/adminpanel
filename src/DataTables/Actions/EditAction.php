<?php

namespace KY\AdminPanel\DataTables\Actions;

class EditAction extends BaseAction
{
    protected string $icon = 'pencil-square';

    protected string $policyName = 'update';

    protected array $attributes = ['class' => 'btn btn-info btn-xs'];

    public function getRoute(): string
    {
        return route('adminpanel.'.$this->dataType->getSlug().'.edit', $this->model->getKey());
    }
}
