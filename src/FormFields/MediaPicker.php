<?php

namespace KY\AdminPanel\FormFields;

use APMedia,AdminPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use KY\AdminPanel\Support\Watermark;
use KY\AdminPanel\Traits\FormFields\HasThumbnails;
use KY\AdminPanel\Traits\FormFields\HasWatermark;

class MediaPicker extends BaseFormField
{
    use HasThumbnails,HasWatermark;

    protected $attributes = [
        'value' => null,
        'name' => null,
        'label' => null,
        'afterLabel'=> null,
        'mode' => 'simple', // ['full','simple']
        'basePath' => '/',
        'filename' => null, //'{random:10}'
        'allowMultiSelect' => false,
        'allowUpload' => true,
        'allowMove' => false,
        'allowDelete' => true,
        'allowCreateFolder' => false,
        'allowRename' => true,
        'allowCrop' => true,
        'showFolders' => false,
        'showToolbar' => true,
        'max' => 0,
        'min' => 0,
        'allowedTypes' => ['image'], // ['image','folder','video']
        'accept' => '.png,.jpg,.jpeg',
        'preSelect' => false,
        'expanded' => true,
        'detailsExpanded' => false,
        'showExpandButton' => true,
        'element' => '',
        'hideThumbnails' => false,
        'showAsImage' => false,
        'thumbnails' => [],
        'watermark' => null,
        'quality' => 90,
    ];

    function buildAfterLabel(): ?string
    {
        $afterLabel = '';
        $accept = $this->get('accept');
        $accept = $this->get('accept');
        $afterLabel .=str_replace(['.',','],['','/'],$accept);
        if($this->hasThumbnails()){
            $thumbnail = $this->getFirstThumbnails();
            //TODO: remake
            if($thumbnail->hasWidth() && $thumbnail->hasHeight())
                $afterLabel .= ' '.$thumbnail->getWidth().'x'.$thumbnail->getHeight().'px';

        }

        return !empty($afterLabel)?'('.$afterLabel.')':$afterLabel;
    }

    function basePath(string $basePath): self
    {
        return $this->set('basePath',$basePath);
    }

    function hasBasePath(): bool
    {
        return !empty($this->get('basePath'));
    }

    function single(): self
    {
        return $this->set('max',1);
    }
    function hideThumbnails($value = true): self
    {
        return $this->set('hideThumbnails',$value);
    }

    function allowedTypes(array $allowedTypes): self
    {
        return $this->set('allowedTypes',$allowedTypes);
    }

    function isMultiSelect(): bool
    {
        return $this->get('max') > 1;
    }

    function getBasePath(): string
    {
        return !empty($this->get('basePath')) ? $this->get('basePath') : '/images/';
    }

    /**
     * @return array
     */
    function getOptions(): array
    {
        $options = $this->toArray();
        $options['basePath'] = $this->getBasePath();
        $options['allowMultiSelect'] = $this->isMultiSelect();
        $options['element'] = 'input[name="' . $this->get('name') . '"]';
        $thumbnails = [];
        foreach ($this->getThumbnails() as $thumbnail){
            $thumbnails[] = $thumbnail->toArray();
        }
        $options['thumbnails'] = $thumbnails;
        unset($options['value']);

        return $options;
    }


    public function uuidSessionName($model): string
    {
        return class_basename($model).'_'.$this->getId() . '_uuid';
    }
    public function prepareBasePath($model): void
    {
        if ($this->hasBasePath()) {

            if (!$model->getKey()) {
                $uuid = session()->exists($this->uuidSessionName($model)) ? session($this->uuidSessionName($model)) : (string)Str::uuid();
                $basePath = APMedia::preparePath($this->get('basePath'),$uuid);
                session()->put($this->getId() . '_path', $basePath);
                session()->put($this->uuidSessionName($model), $uuid);
            } else {
                $basePath = APMedia::preparePath($this->get('basePath'),$model->getKey());
            }
            $this->basePath($basePath);
        }
    }

    public function prepareData($dataType, $model)
    {
        $content = '';

        if ($this->isMultiSelect()) {
            if (is_array($model->{$this->get('name')})) {
                $model->{$this->get('name')} = json_encode($model->{$this->get('name')});
            }
            json_decode($model->{$this->get('name')});
            if (json_last_error() == JSON_ERROR_NONE) {
                $content = json_decode($model->{$this->get('name')});
            } else {
                $content = [];
            }
        } else {
            $content = $model->{$this->get('name')};
        }

        return $content;
    }

    public function beforeCreateContent($dataType, $model): void
    {
        $this->prepareBasePath($model);
    }

    public function createContent($dataType, $model, $viewType)
    {
        $this->beforeCreateContent($dataType, $model);

        $field = $this;
        $data = $this->prepareData($dataType, $model);

        return view($this->getViewByType($viewType), compact(['field', 'dataType', 'data', 'model']));
    }

    public function afterSave(Request $request, $model)
    {
        if ($this->hasTempFiles()) {
            $value = str_replace(session($this->uuidSessionName($model)), $model->getKey(), $model->{$this->get('name')});

            $settingClass = AdminPanel::modelClass('Setting');

            if($model instanceof $settingClass)
                $model->value = $value;
            else
                $model->{$this->get('name')} = $value;

            $model->save();

            $this->moveTempFiles($model);
        }
    }
    public function hasTempFiles(): bool
    {
        return session()->has($this->getId().'_path') && session()->has($this->getId().'_uuid');
    }
    public function moveTempFiles($model): void
    {
        if ($this->hasTempFiles()) {
            $storage = Storage::disk(config('adminpanel.storage.disk'));
            $uuid = session($this->uuidSessionName($model));
            $oldPath = session($this->getId() . '_path');
            $newPath = str_replace($uuid, $model->getKey(), $oldPath);

            if ($oldPath != $newPath && !$storage->exists($newPath)) {
                session()->forget([$this->getId() . '_path', $this->getId() . '_uuid']);

                if ($storage->exists($oldPath)) {
                    $files = $storage->files($oldPath);
                    foreach ($files as $file) {
                        $storage->move($file, str_replace('//', '/', $newPath . '/' . basename($file)));
                    }
                    $folderPath = substr($oldPath, 0, strpos($oldPath, $uuid)) . $uuid;
                    $storage->deleteDirectory($folderPath);
                }
            }
        }
    }

}
