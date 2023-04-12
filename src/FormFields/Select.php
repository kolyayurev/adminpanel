<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\FilterContract;
use KY\AdminPanel\DataTables\Filters\SelectFilter;

class Select extends BaseFormField
{
    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'afterLabel'=> null,
        'multilingual' => false,
        'instruction' => null,
        'hiddenOn' => [],
        'options' => [],
        'multiple' => false,
        'selected' => null,
        // TODO: migrate to column
        'columnDefaultOrder' => null, // ['acs','desc']
        'columnOrderable' => true,
        'columnSearchable' => true,
        'columnWidth' =>  null,
        'columnEditable' => false,
    ];

    public function __construct()
    {
        $this->filter = SelectFilter::make();
    }

    public function getFilter() : FilterContract
    {
        $filter = parent::getFilter();
        $filter->multiple(!empty($filter->get('multiple'))?$filter->get('multiple'):$this->isMultiple());
        $filter->options(!empty($filter->get('options'))?$filter->get('options'):$this->getOptions());

        return $filter;
    }

    public function getValue($model)
    {
        return $this->isMultiple()?json_decode($model->{$this->get('name')}):$model->{$this->get('name')};
    }

    public function getOption($key):array
    {
        return $this->get('options',[])[$key] ?? '';
    }

    public function getOptions():array
    {
        return $this->get('options',[]);
    }

    public function options(array $options):self
    {
        return $this->set('options',$options);
    }

    public function hasOptions():bool
    {
        return !empty($this->get('options',[]));
    }

    public function isMultiple():bool
    {
        return $this->get('multiple',false);
    }

    public function multiple(bool $multiple = true):self
    {
        return $this->set('multiple',$multiple);
    }

    public function prepareValue($value, Request $request, $model)
    {
        return empty($value) ? ($this->get('default')??json_encode($value)) : json_encode($value);
    }
}
