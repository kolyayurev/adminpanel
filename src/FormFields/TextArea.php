<?php

namespace KY\AdminPanel\FormFields;

class TextArea extends BaseFormField
{

    function rows(int $rows): self
    {
        return $this->set('rows',$rows);
    }

}
