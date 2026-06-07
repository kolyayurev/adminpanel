<?php

namespace KY\AdminPanel\Blocks;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use KY\AdminPanel\Contracts\BlockContract;
use KY\AdminPanel\Traits\HasBlocks;
use KY\AdminPanel\Traits\HasDynamicCall;

class BaseBlock implements Arrayable, BlockContract
{
    use HasBlocks,HasDynamicCall;

    protected string $type;

    protected string $class = '';

    protected string $beforeTemplate;

    protected string $template;

    protected string $afterTemplate;

    protected $instruction;

    protected bool $visibleOnlyWhenHasFields = false;

    public function getType(): string
    {
        if (empty($this->type)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'Block')) {
                $name = substr($name, 0, -strlen('Block'));
            }

            $this->type = Str::snake($name);
        }

        return $this->type;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function class(string $class): BaseBlock
    {
        $this->class = $class;

        return $this;
    }

    public function getBeforeTemplate(): ?string
    {
        return $this->beforeTemplate ?? 'adminpanel::blocks.layout.before';
    }

    public function beforeTemplate(?string $beforeTemplate): BaseBlock
    {
        $this->beforeTemplate = $beforeTemplate;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template ?? 'adminpanel::blocks.'.$this->getType().'.index';
    }

    public function template(string $template): BaseBlock
    {
        $this->template = $template;

        return $this;
    }

    public function getAfterTemplate(): string
    {
        return $this->afterTemplate ?? 'adminpanel::blocks.layout.after';
    }

    public function afterTemplate(string $afterTemplate): BaseBlock
    {
        $this->afterTemplate = $afterTemplate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstruction()
    {
        return $this->instruction;
    }

    public function instruction($instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function isVisibleOnlyWhenHasFields(): bool
    {
        return $this->visibleOnlyWhenHasFields;
    }

    public function visibleOnlyWhenHasFields(bool $visibleOnlyWhenHasFields = true): BaseBlock
    {
        $this->visibleOnlyWhenHasFields = $visibleOnlyWhenHasFields;

        return $this;
    }

    public function toArray()
    {
        return array_merge(get_object_vars($this), [
            'type' => $this->getType(),
            'blocks' => $this->getBlocks()->toArray(),
        ]);
    }
}
