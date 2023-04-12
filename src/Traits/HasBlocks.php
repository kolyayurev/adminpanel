<?php
declare(strict_types=1);

namespace KY\AdminPanel\Traits;

use Illuminate\Support\Collection;
use KY\AdminPanel\Contracts\BlockContract;
use KY\AdminPanel\FormFields\BaseFormField;

trait HasBlocks
{
    protected array $blocks = [];

    public static function blocks(): self
    {
        $instance = (new static);
        $args = func_get_args();
        foreach ($args as $index => $arg) {
            $instance->addBlock($arg);
        }
        return $instance;
    }

    public function addBlock($block): self
    {
        $this->blocks[] = $block;
        return $this;
    }

    public function getBlocks(): Collection
    {
        return collect($this->blocks);
    }

    public function getFieldsName(): Collection
    {
        $names = collect([]);
        foreach ($this->getBlocks() as $block)
        {
            $names->push($block instanceof BlockContract?$block->getFieldsName():$block);
        }

        return $names->flatten();
    }

}
