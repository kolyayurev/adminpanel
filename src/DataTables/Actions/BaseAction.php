<?php

namespace KY\AdminPanel\DataTables\Actions;

use KY\AdminPanel\Contracts\ActionContract;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\Makeable;

abstract class BaseAction implements ActionContract
{
    use HasDynamicCall,Makeable;

    protected DataTypeContract $dataType;

    protected $model;

    protected string $tag = 'a';

    protected string $icon = '';

    protected string $color = '';

    protected string $title = '';

    protected string $route = '';

    protected string $policyName = '';

    protected string $template;

    protected array $attributes = [];

    public function setup(DataTypeContract $dataType, $model)
    {
        $this->dataType = $dataType;
        $this->model = $model;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function tag(string $tag): BaseAction
    {
        $this->tag = $tag;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function icon(string $icon): BaseAction
    {
        $this->icon = $icon;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function color(string $color): BaseAction
    {
        $this->color = $color;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function title(string $title): BaseAction
    {
        $this->title = $title;

        return $this;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function route(string $route): BaseAction
    {
        $this->route = $route;

        return $this;
    }

    public function getPolicyName(): string
    {
        return $this->policyName;
    }

    public function policyName(string $policyName): BaseAction
    {
        $this->policyName = $policyName;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template ?? 'adminpanel::datatables.actions.button';
    }

    public function template(string $template): BaseAction
    {
        $this->template = $template;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return $this
     */
    public function attributes(array $attributes): BaseAction
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function convertAttributesToHtml(): string
    {
        $result = '';

        foreach ($this->getAttributes() as $key => $attribute) {
            $result .= $key.'="'.$attribute.'"';
        }

        return $result;
    }
}
