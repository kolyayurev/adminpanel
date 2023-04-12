<?php
declare(strict_types=1);

namespace KY\AdminPanel\Traits\FormFields;

use Illuminate\Support\Collection;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Support\Trumbnail;

trait HasArrayBuilderFields
{
    protected array $fields = [];

    public function fields():self
    {
        $this->fields = [];

        $args = func_get_args();
        foreach ($args as $index => $arg) {
            $this->addField($arg);
        }
        return $this;
    }

    public function addField($handler):self{
        if ($handler instanceof ArrayBuilderElement) {
            $this->fields[] = $handler;
        }
        return $this;
    }

    public function getFields():Collection
    {
        return collect($this->fields);
    }
}
