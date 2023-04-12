<?php
namespace KY\AdminPanel\Blocks;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use KY\AdminPanel\Contracts\BlockContract;
use KY\AdminPanel\Traits\HasBlocks;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\HasLayout;

class BaseBlock implements BlockContract, Arrayable
{
    use HasDynamicCall,HasBlocks;

    protected string $type;
    protected string $class = '';
    protected string $beforeTemplate;
    protected string $template;
    protected string $afterTemplate;

    protected $instruction;

    protected bool $visibleOnlyWhenHasFields = false;


    /**
     * @return string
     */
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
    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return BaseBlock
     */
    public function class(string $class): BaseBlock
    {
        $this->class = $class;
        return $this;
    }

    public function getBeforeTemplate() : ?string
    {
        return $this->beforeTemplate ?? 'adminpanel::blocks.layout.before';
    }

    /**
     * @param ?string $beforeTemplate
     * @return BaseBlock
     */
    public function beforeTemplate(?string $beforeTemplate): BaseBlock
    {
        $this->beforeTemplate = $beforeTemplate;
        return $this;
    }

    public function getTemplate() : string
    {
        return $this->template ??  'adminpanel::blocks.'.$this->getType().'.index';
    }

    /**
     * @param string $template
     * @return BaseBlock
     */
    public function template(string $template): BaseBlock
    {
        $this->template = $template;
        return $this;
    }
    public function getAfterTemplate() : string
    {
        return $this->afterTemplate ?? 'adminpanel::blocks.layout.after';
    }

    /**
     * @param string $afterTemplate
     * @return BaseBlock
     */
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

    /**
     * @return bool
     */
    public function isVisibleOnlyWhenHasFields(): bool
    {
        return $this->visibleOnlyWhenHasFields;
    }

    /**
     * @param bool $visibleOnlyWhenHasFields
     * @return BaseBlock
     */
    public function visibleOnlyWhenHasFields(bool $visibleOnlyWhenHasFields = true): BaseBlock
    {
        $this->visibleOnlyWhenHasFields = $visibleOnlyWhenHasFields;
        return $this;
    }


    public function toArray()
    {
        return array_merge(get_object_vars($this),[
            'type' => $this->getType(),
            'blocks' => $this->getBlocks()->toArray(),
        ]);
    }
}
