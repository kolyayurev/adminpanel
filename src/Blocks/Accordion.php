<?php

namespace KY\AdminPanel\Blocks;

class Accordion extends BaseBlock
{
    protected string $id;

    public function getId(): string
    {
        return $this->id ?? 'accordion';
    }

    public function id(string $id): Accordion
    {
        $this->id = $id;

        return $this;
    }
}
