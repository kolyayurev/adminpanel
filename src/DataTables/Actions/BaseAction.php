<?php

namespace KY\AdminPanel\DataTables\Actions;

use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Contracts\ActionContract;
use KY\AdminPanel\Traits\Makeable;

abstract class BaseAction implements ActionContract
{
    use Makeable,HasDynamicCall;

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

    public function setup(DataTypeContract $dataType, $model){
        $this->dataType = $dataType;
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return BaseAction
     */
    public function tag(string $tag): BaseAction
    {
        $this->tag = $tag;
        return $this;
    }


    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return BaseAction
     */
    public function icon(string $icon): BaseAction
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return BaseAction
     */
    public function color(string $color): BaseAction
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return BaseAction
     */
    public function title(string $title): BaseAction
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute() : string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return BaseAction
     */
    public function route(string $route): BaseAction
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string
     */
    public function getPolicyName(): string
    {
        return $this->policyName;
    }

    /**
     * @param string $policyName
     * @return BaseAction
     */
    public function policyName(string $policyName): BaseAction
    {
        $this->policyName = $policyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template ?? 'adminpanel::datatables.actions.button';
    }

    /**
     * @param string $template
     * @return BaseAction
     */
    public function template(string $template): BaseAction
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function attributes(array $attributes): BaseAction
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return string
     */
    public function convertAttributesToHtml() :string
    {
        $result = '';

        foreach ($this->getAttributes() as $key => $attribute) {
            $result .= $key.'="'.$attribute.'"';
        }

        return $result;
    }

}
