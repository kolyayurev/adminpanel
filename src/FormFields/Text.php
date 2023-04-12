<?php

namespace KY\AdminPanel\FormFields;

class Text extends BaseFormField
{
    function default(string $default = null): self
    {
        return $this->set('default', $default);
    }

    function type(string $type = 'text'): self
    {
        return $this->set('type',$type);
    }

    function required(bool $required = true): self
    {
        return $this->set('required',$required);
    }

}
