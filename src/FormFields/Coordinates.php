<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Http\Request;

class Coordinates extends BaseFormField
{
    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'placeholder' => null,
        'instruction' => null,
        'hiddenOn' => [],

        'columnDefaultOrder' => null, // ['acs','desc']
        'columnOrderable' => true,
        'columnSearchable' => true,
        'columnWidth' =>  null,
        'columnEditable' => false,

        'storageType' => 'object', // ['object','point']
    ];

    function getPlaceholder() : string
    {
        return $this->get('placeholder',ap_trans('form-fields.coordinates.placeholder'));
    }

    function placeholder(string $placeholder) : self
    {
        return $this->set('placeholder',$placeholder);
    }

    function holdAsObject() : self
    {
        return $this->set('storageType','object');
    }

    function holdAsPoint() : self
    {
        return $this->set('storageType','point');
    }

    public function prepareValue($value,$request,$model){

        $value = json_decode($value,true);

        $value = match ($this->get('storageType')){
            'object' => $value,
            // TODO:
            'point' => $value['coords'],
        };

        return empty($value) ? ($this->get('default')??$value) : $value;
    }

}
