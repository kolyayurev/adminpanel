<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\FilterContract;
use KY\AdminPanel\DataTables\Filters\SelectFilter;

class Status extends Checkbox
{
    protected $attributes = [
        'value' => null,
        'name' => null,
        'label' => 'Статус',
        'textOn' => 'adminpanel::form-fields.status.on',
        'textOff' => 'adminpanel::form-fields.status.off',
        'default' => true,
        'editable' => true,
    ];

    public function prepareValue($value,Request $request,$model){
        return (int) ($value == 1);
    }
}
