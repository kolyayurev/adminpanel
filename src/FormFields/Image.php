<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Support\Facades\Auth;
use APMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as InterventionImage;
use KY\AdminPanel\Support\Trumbnail;
use KY\AdminPanel\Traits\FormFields\HasTrumbnails;

class Image extends BaseFormField
{
    use HasTrumbnails;

    protected $attributes = [
        'value' => null,
        'name' => null,
        'label' => null,
        'type' => null,
        'resize' => ['width'=>null,'height'=>null],
        'upsize' => true,
        'quality' => 100,
        'thumbnails' => [],
        'folder' => 'images'
    ];

    public function quality(int $quality):self
    {
        $this->set('quality',(0 <= $quality) && ($quality <= 100)? $quality : 100);

        return $this;
    }

    public function resize(int $width,int $height = null):self
    {
        $this->set('resize',['width'=>$width,'height'=>$height]);
        return $this;
    }


    public function getFolder($key):string
    {
        return $this->get('folder');
    }

    public function prepareValue($value,Request $request,$model){

        dd($request);
        if ($request->hasFile($this->get('name'))) {
           $filePath =  APMedia::save(
               $request->file($this->get('name')),
               APMedia::preparePath($this->getFolder(),$model->getKey()??date('FY')),
               [
                   'upsize'=>$this->get('upsize'),
                   'quality'=>$this->get('quality'),
                   'resize'=>$this->get('resize'),
               ]
           );
           foreach ($this->getThumbnails() as $thumbnail){
               APMedia::thumbnail($filePath,$thumbnail);
           }
        }

        return null;
    }




}
