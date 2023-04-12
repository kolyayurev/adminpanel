<?php

namespace KY\AdminPanel\FormFields;

class Date extends BaseFormField
{

    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'multilingual' => false,
        'instruction' => null,
        'format' => '%Y-%m-%d'
    ];

    /**
     * @return bool
     */
    public function hasFormat(): bool
    {
        return !empty($this->get('format'));
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
