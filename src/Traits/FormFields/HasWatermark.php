<?php
declare(strict_types=1);

namespace KY\AdminPanel\Traits\FormFields;

use Illuminate\Support\Collection;
use KY\AdminPanel\Support\Trumbnail;
use KY\AdminPanel\Support\Watermark;

trait HasWatermark
{
//    protected $attributes = [
//        'watermark'
//    ];

    function hasWatermark(): bool
    {
        $watermark = $this->getWatermark();
        return  $watermark instanceof Watermark?? $watermark->hasSource();
    }


    public function getWatermark(){
        return  $this->get('watermark');
    }

    public function watermark(Watermark $watermark):self
    {
        $this->set('watermark',$watermark);
        return $this;
    }
}
