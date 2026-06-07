<?php

namespace KY\AdminPanel\FormFields;

use Carbon\Carbon;

class Date extends BaseFormField
{

    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'multilingual' => false,
        'instruction' => null,
        'format' => 'Y-m-d'
    ];

    /**
     * @return bool
     */
    public function hasFormat(): bool
    {
        return !empty($this->get('format'));
    }

    public function getFormattedValue($model): mixed
    {
        $value = $this->getValue($model);

        if (!$this->hasFormat() || is_null($value)) {
            return $value;
        }

        return Carbon::parse($value)->translatedFormat($this->get('format'));
    }

    /**
     * @param string $format
     * @return Timestamp
     */
    public function format(string $format): self
    {
        $this->set('format',$format);
        return $this;
    }

}
