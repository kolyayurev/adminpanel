<?php

namespace KY\AdminPanel\Support;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use KY\AdminPanel\Contracts\BlockContract;
use KY\AdminPanel\FormFields\BaseFormField;
use KY\AdminPanel\Traits\Attributable;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\Makeable;

class ArrayBuilderRule implements Arrayable
{
    use Makeable, HasDynamicCall;

    protected bool $required = false;
    protected string $message = "";
    protected string $trigger = "blur"; // ['blur','change']

    /**
     * @param bool $required
     * @return ArrayBuilderRule
     */
    public function required(bool $required = true): ArrayBuilderRule
    {
        $this->required = $required;
        $this->message = "Обязательное поле";
        return $this;
    }

    /**
     * @param string $trigger
     * @return ArrayBuilderRule
     */
    public function trigger(string $trigger): ArrayBuilderRule
    {
        $this->trigger = $trigger;
        return $this;
    }

    public function toArray()
    {
        return array_merge(get_object_vars($this),[
            'required' => $this->required,
            'message' => $this->message,
            'trigger' => $this->trigger,
        ]);
    }
}
