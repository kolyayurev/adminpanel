<?php
namespace KY\AdminPanel\Blocks;

class Accordion extends BaseBlock
{
    protected string $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id ?? 'accordion';
    }

    /**
     * @param string $id
     * @return Accordion
     */
    public function id(string $id): Accordion
    {
        $this->id = $id;
        return $this;
    }

}
