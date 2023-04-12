<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Http\Request;

class Password extends Text
{
    public function prepareValue($value,Request $request,$model){
        return empty($value) ?
            ($this->get('default')?\Hash::make($this->get('default')):$model->{$this->get('name')}) :
            \Hash::make($value);
    }

}
