<?php

namespace KY\AdminPanel\FormFields;

class TextArea extends BaseFormField
{
    public function rows(int $rows): self
    {
        return $this->set('rows', $rows);
    }
}
