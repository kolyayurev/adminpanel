<?php

namespace KY\AdminPanel\Blocks;

class Tabs extends BaseBlock
{
    protected string $id;

    public function getId(): string
    {
        return $this->id ?? 'tabs';
    }

    /**
     * @return Accordion
     */
    public function id(string $id): Tabs
    {
        $this->id = $id;

        return $this;
    }
}
