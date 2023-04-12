<?php
namespace KY\AdminPanel\Blocks;

class Tabs extends BaseBlock
{
    protected string $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id ?? 'tabs';
    }

    /**
     * @param string $id
     * @return Accordion
     */
    public function id(string $id): Tabs
    {
        $this->id = $id;
        return $this;
    }

}
