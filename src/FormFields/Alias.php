<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Database\Eloquent\Model;

class Alias extends BaseFormField
{
    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'changeOnTyping'=> false,
        'forceUpdate' => false,
        'source' => 'name', // field name, can be multiple "name,desc"
        'route' => null
    ];

    public function changeOnTyping(bool $changeOnTyping):self
    {
        return  $this->set('changeOnTyping',$changeOnTyping);
    }

    public function forceUpdate(bool $forceUpdate):self
    {
        return  $this->set('forceUpdate',$forceUpdate);
    }

    public function source(string $source):self
    {
        return  $this->set('source',$source);
    }

    public function route(string $route):self
    {
        return  $this->set('route',$route);
    }

    public function getRoute():string
    {
        return  $this->get('route');
    }

    public function hasRoute():bool
    {
        return  !empty($this->get('route'));
    }

    public function buildRoute(Model $model):string
    {
        return route($this->getRoute(),$model->{$this->get('name')});
    }
}
