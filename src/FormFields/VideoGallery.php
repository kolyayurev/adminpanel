<?php

namespace KY\AdminPanel\FormFields;

use AdminPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Traits\FormFields\HasArrayBuilderFields;

class VideoGallery extends BaseFormField
{
    use HasArrayBuilderFields;

    protected $attributes = [
        'value' => null,
        'name' => null,
        'label' => null,
        'videoMediaPicker' => null,
        'previewMediaPicker' => null,
        'min' => 0, // min elements in gallery
        'max' => 100, // max elements in gallery
        "displayValue" => "return item.title;" // this is body of function. function has one parameter "item"
    ];

    public function __construct()
    {
        $this->videoMediaPicker(
            MediaPicker::make('video')
                ->label('Видео')
                ->allowedTypes(["video"])
                ->accept('.mp4,.webm,.avi')
                ->single()
                ->basePath('video-gallery/video')
        );
        $this->previewMediaPicker(
            MediaPicker::make('preview')
                ->label('Превью')
                ->allowedTypes(["image"])
                ->single()
                ->basePath('video-gallery/preview')
        );

        $this->fields(
            ArrayBuilderElement::make('title')
                ->label('Заголовок')
                ->rules([ "required"=> true, "message"=> "Обязательное поле", "trigger"=> "blur" ])
        );
    }

    public function getId()
    {
        return name2id(strtolower(class_basename($this)).'_'.$this->get('name'));;
    }

    public function videoMediaPicker(MediaPicker $mediaPicker) : self
    {
        $this->set('videoMediaPicker',$mediaPicker);
        return $this;
    }

    public function previewMediaPicker(MediaPicker $mediaPicker) : self
    {
        $this->set('previewMediaPicker',$mediaPicker);
        return $this;
    }

    public function min(int $min) : self
    {
        return $this->set('min',$min);
    }

    public function max(int $max) : self
    {
        return $this->set('max',$max);
    }

    public function getVideoMediaPickerOptions():array
    {
        $mediaPicker =  $this->get('videoMediaPicker');
        $options =  $mediaPicker->getOptions();

        $options["element"] = '#'.$this->getId().' input[name="' . $mediaPicker->get('name') . '"]';

        $options["allowedTypes"] = ["video"];

        return $options;
    }

    public function getPreviewMediaPickerOptions():array
    {
        $mediaPicker =  $this->get('previewMediaPicker');
        $options =  $mediaPicker->getOptions();

        $options["element"] = '#'.$this->getId().' input[name="' . $mediaPicker->get('name') . '"]';

        $options["allowedTypes"] = ["image"];

        return $options;
    }

    public function beforeCreateContent($dataType, $model): void
    {
        foreach (['videoMediaPicker','previewMediaPicker'] as $mediaPickerName)
        {
            $mediaPicker = $this->get($mediaPickerName);
            $mediaPicker->prepareBasePath($model);
            $this->$mediaPickerName($mediaPicker);
        }
    }

    public function afterSave(Request $request, $model)
    {
        foreach (['videoMediaPicker','previewMediaPicker'] as $mediaPickerName) {
            $mediaPicker = $this->get($mediaPickerName);

            if ($mediaPicker->hasTempFiles()) {
                $items = json_decode($this->getValue($model), true);

                foreach ($items ?? [] as $key => $item) {
                    $items[$key][$mediaPicker->get('name')] = str_replace(session($mediaPicker->uuidSessionName($model)), $model->getKey(), $item[$mediaPicker->get('name')]);
                }

                $settingClass = AdminPanel::modelClass('Setting');

                if ($model instanceof $settingClass)
                    $model->value = json_encode($items);
                else
                    $model->{$this->get('name')} = json_encode($items);

                $model->save();

                $mediaPicker->moveTempFiles($model);
            }
        }
    }
}
