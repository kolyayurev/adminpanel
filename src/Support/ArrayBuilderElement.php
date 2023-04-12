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

class ArrayBuilderElement implements Arrayable
{
    use Makeable, HasDynamicCall;

    protected string $name;
    protected string $label = '';
    protected string $component = 'el-input';  // ['el-input','el-input-number','el-time-select','el-rate',...]
    protected $default = null;
    protected array $rules = [];
    protected array $props = [];
    protected int $col = 24;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ArrayBuilderElement
     */
    public function name(string $name): ArrayBuilderElement
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return ArrayBuilderElement
     */
    public function label(string $label): ArrayBuilderElement
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getComponent(): string
    {
        return $this->component;
    }

    /**
     * @param string $component
     * @return ArrayBuilderElement
     */
    public function component(string $component): ArrayBuilderElement
    {
        $this->component = $component;
        return $this;
    }

    /**
     * @return null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param null $default
     * @return ArrayBuilderElement
     */
    public function default($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return ArrayBuilderElement
     */
    public function rules(): ArrayBuilderElement
    {
        $args = func_get_args();
        foreach ($args as $index => $arg) {
            $this->addRule($arg);
        }

        return $this;
    }
    public function addRule($rule): self
    {
        if($rule instanceof ArrayBuilderRule)
            $this->rules[] = $rule;

        return $this;
    }

    /**
     * @return array
     */
    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * @param array $props
     * @return ArrayBuilderElement
     */
    public function props(array $props): ArrayBuilderElement
    {
        $this->props = $props;
        return $this;
    }

    /**
     * @return int
     */
    public function getCol(): int
    {
        return $this->col;
    }

    /**
     * @param int $col
     * @return ArrayBuilderElement
     */
    public function col(int $col): ArrayBuilderElement
    {
        $this->col = $col;
        return $this;
    }


    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'component' => $this->getComponent(),
            'default' => $this->getDefault(),
            'rules' => array_map(function($rule){return  $rule->toArray();},$this->getRules()),
            'props' => $this->getProps(),
            'col' => $this->getCol(),
        ];
    }
}
