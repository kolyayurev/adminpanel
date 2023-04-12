<?php
declare(strict_types=1);

namespace KY\AdminPanel\Traits\FormFields;

use Illuminate\Support\Collection;
use KY\AdminPanel\Support\Trumbnail;

trait HasThumbnails
{
//    protected $attributes = [
//        'thumbnails'=>[],
//    ];
    public function addThumbnail(Trumbnail $thumbnail):self
    {
        $thumbnails = $this->get('thumbnails');

        $thumbnails[] = $thumbnail;
        $this->set('thumbnails',$thumbnails);

        return $this;
    }

    public function getThumbnails():Collection{
        return  collect($this->get('thumbnails'));
    }

    public function getFirstThumbnails():Trumbnail{
        return  $this->getThumbnails()->first();
    }

    public function hasThumbnails():bool
    {
        return  !!collect($this->get('thumbnails'))->count();
    }

    public function getThumbnailsName() : array
    {
        return $this->getThumbnails()->map(function($thumbnail){
            return $thumbnail->get('name');
        })->toArray();

    }
    public function thumbnails():self
    {
        $args = func_get_args();
        foreach ($args as $index => $arg) {
            if ($arg instanceof Trumbnail) {
                $this->addThumbnail($arg);
            }
        }
        return $this;
    }
}
