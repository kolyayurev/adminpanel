<?php

namespace KY\AdminPanel\Blocks;

use Str;

class Tab extends BaseBlock
{
    protected string $id;

    protected string $header;

    public function getId(): string
    {
        if (empty($this->id)) {
            $this->id = Str::lower($this->hasHeader() ? Str::slug($this->getHeader()) : Str::random(10));
        }

        return $this->id;
    }

    /**
     * @return Accordion
     */
    public function id(string $id): Tab
    {
        $this->id = $id;

        return $this;
    }

    public function getHeader(): string
    {
        return $this->get('header');
    }

    public function header(string $header): self
    {

        return $this->set('header', $header);
    }

    public function hasHeader(): bool
    {
        return ! empty($this->get('header'));
    }
}
