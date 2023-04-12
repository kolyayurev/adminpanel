<?php

namespace KY\AdminPanel\FormFields;

use AdminPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Traits\FormFields\HasArrayBuilderFields;

class Gallery extends BaseFormField
{
    use HasArrayBuilderFields;

    protected $attributes = [
        'value' => null,
        'name' => null,
        'label' => null,
        'mediaPicker' => null,
        'min' => 0, // min elements in gallery
        'max' => 100, // max elements in gallery
        "displayValue" => "return item.title;" // this is body of function. function has one parameter "item"
    ];

    public function __construct()
    {
        $this->mediaPicker(
            MediaPicker::make('url')->expanded()->allowedTypes(["image"])->max(1)->basePath('gallery')
        );
        $this->fields(
            ArrayBuilderElement::make('title')
                ->label('Заголовок')
                ->rules([ "required"=> true, "message"=> "Обязательное поле", "trigger"=> "blur" ])
        );
    }

    public function displayValue(string $displayValue):self{
        return $this->set('displayValue',$displayValue);
    }

    public function getId()
    {
        return name2id(strtolower(class_basename($this)).'_'.$this->get('name'));;
    }

    public function mediaPicker(MediaPicker $mediaPicker) : self
    {
        return $this->set('mediaPicker',$mediaPicker);
    }

    public function getMediaPicker() : MediaPicker
    {
        return $this->get('mediaPicker');
    }

    public function min(int $min) : self
    {
        return $this->set('min',$min);
    }

    public function max(int $max) : self
    {
        return $this->set('max',$max);
    }

    public function getMediaPickerOptions():array
    {
        $mediaPicker =  $this->getMediaPicker();
        $options =  $mediaPicker->getOptions();
        if(empty($options["allowedTypes"]))
        $options["allowedTypes"] = ["image"];
        $options["element"] = '#'.$this->getId().' input[name="' . $mediaPicker->get('name') . '"]';

        return $options;

    }

    public function beforeCreateContent($dataType, $model): void
    {
        $mediaPicker = $this->getMediaPicker();
        $mediaPicker->prepareBasePath($model);
        $this->mediaPicker($mediaPicker);
    }

    public function afterSave(Request $request, $model)
    {
        $mediaPicker = $this->getMediaPicker();

        if ($mediaPicker->hasTempFiles()) {
            $items = json_decode($this->getValue($model),true);

            foreach ($items ?? [] as $key => $item)
            {
                $items[$key][$mediaPicker->get('name')] = str_replace(session($mediaPicker->uuidSessionName($model)), $model->getKey(), $item[$mediaPicker->get('name')]);
            }

            $settingClass = AdminPanel::modelClass('Setting');

            if($model instanceof $settingClass)
                $model->value = json_encode($items);
            else
                $model->{$this->get('name')} = json_encode($items);

            $model->save();

            $mediaPicker->moveTempFiles($model);
        }
    }

}
