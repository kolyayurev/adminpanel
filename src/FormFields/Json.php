<?php

namespace KY\AdminPanel\FormFields;

class Json extends BaseFormField
{
    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'afterLabel' => null,
        'multilingual' => false,
        'instruction' => null,
        'hiddenOn' => ['list', 'create', 'edit'],
        'columnDefaultOrder' => null,
        'columnOrderable' => false,
        'columnSearchable' => false,
        'columnWidth' => null,
        'columnEditable' => false,
    ];

    public function getValue($model)
    {
        $value = parent::getValue($model);

        if ($value === null) {
            return '';
        }

        return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
