<?php

namespace KY\AdminPanel\FormFields;

use KY\AdminPanel\Support\ArrayBuilderElement;

class ListField extends ArrayBuilder
{
    protected $viewCell = "adminpanel::formfields.array_builder.cell";
    protected $viewShow = "adminpanel::formfields.array_builder.show";

    protected $attributes = [
        'value' => null,
        'name' => null,
        'label' => null,
        'defaultFieldName' => '',
        "displayValue" => "return item.text;"
    ];

    public function __construct()
    {
        $this->fields(
            ArrayBuilderElement::make('text')
                ->label('Текст')
                ->rules([ "required"=> true, "message"=> "Обязательное поле", "trigger"=> "blur" ])
        );
    }

}
