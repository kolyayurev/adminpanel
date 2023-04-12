<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\FilterContract;
use KY\AdminPanel\DataTables\Filters\SelectFilter;

class Checkbox extends BaseFormField
{
    protected $attributes = [
        'value' => true,
        'name' => null,
        'label' => null,
        'textOn' => 'adminpanel::form-fields.checkbox.on',
        'textOff' => 'adminpanel::form-fields.checkbox.off',
        'default' => false
    ];
    public function __construct()
    {
        $this->filter = SelectFilter::make();
    }

    public function getFilter() : FilterContract
    {
        $filter = parent::getFilter();
        $filter->options([
            0 => trans($filter->get('textOff',$this->get('textOff'))),
            1 => trans($filter->get('textOn',$this->get('textOn'))),
        ]);
        return $filter;
    }

    public function default(bool $value = false):self
    {
        $this->set('default', $value);
        return $this;
    }

    public function textOn(string $textOn):self
    {
        $this->set('textOn', $textOn);
        return $this;
    }

    public function textOff(string $textOff):self
    {
        $this->set('textOff', $textOff);
        return $this;
    }

    public function prepareValue($value,Request $request,$model){
        return (int) ($value === 'on');
    }
}
