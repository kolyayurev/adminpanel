<?php

namespace KY\AdminPanel\FormFields;

class TextEditor extends BaseFormField
{
    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'height' => 200,
//        'content_css' => null,
        'toolbar2' => ''
    ];

    public function height(int $height): self
    {
        return $this->set('height',$height);
    }

    public function contentCss(string $contentCss): self
    {
        return $this->set('content_css',$contentCss);
    }

    public function content_css(string $content_css): self
    {
        return $this->set('content_css',$content_css);
    }

    public function toolbar2(string $toolbar2): self
    {
        return $this->set('toolbar2',$toolbar2);
    }

    public function getOptions(){
        $options = parent::toArray();
        unset($options['class']);
        unset($options['value']);
        unset($options['name']);
        unset($options['label']);
        unset($options['hiddenOn']);

        return $options;
    }

}
