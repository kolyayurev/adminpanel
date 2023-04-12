<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Support\ArrayBuilderRule;
use KY\AdminPanel\Traits\FormFields\HasArrayBuilderFields;

class ArrayBuilder extends BaseFormField
{
    use HasArrayBuilderFields;

    protected  $viewCell = "adminpanel::formfields.array_builder.cell";
    protected  $viewForm = "adminpanel::formfields.array_builder.form";
    protected  $viewShow = "adminpanel::formfields.array_builder.show";

    protected $attributes = [
        'value' => null,
        'name' => null,
        'label' => null,
        'min' => 0, // min elements
        'max' => 100, // max elements
        'defaultFieldName' => 'title',
        "displayValue" => "return item.title;" // this is body of function. function has one parameter "item"
    ];

    public function min(int $min) : self
    {
        return $this->set('min',$min);
    }

    public function max(int $max) : self
    {
        return $this->set('max',$max);
    }


    public function displayValue(string $displayValue):self{
        return $this->set('displayValue',$displayValue);
    }

    public function __construct()
    {
        $this->fields(
            ArrayBuilderElement::make('title')
                ->label('Заголовок')
                ->component("el-input") // ['el-input','el-input-number','el-time-select','el-rate',...]
                ->default("") // "default text"
                ->rules(ArrayBuilderRule::make()->required())// validation rules
                ->props([]) // component props, example [ "rows"=>3 , "type"=>"textarea", "placeholder"=>"text" ] for textarea
                ->col(24) // column,
        );
    }


}
