<?php

namespace KY\AdminPanel\Support;

use Illuminate\Contracts\Support\Arrayable;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\Makeable;

class ArrayBuilderRule implements Arrayable
{
    use HasDynamicCall, Makeable;

    protected bool $required = false;

    protected string $message = '';

    protected string $trigger = 'blur'; // ['blur','change']

    public function required(bool $required = true): ArrayBuilderRule
    {
        $this->required = $required;
        $this->message = 'Обязательное поле';

        return $this;
    }

    public function trigger(string $trigger): ArrayBuilderRule
    {
        $this->trigger = $trigger;

        return $this;
    }

    public function toArray()
    {
        return array_merge(get_object_vars($this), [
            'required' => $this->required,
            'message' => $this->message,
            'trigger' => $this->trigger,
        ]);
    }
}
