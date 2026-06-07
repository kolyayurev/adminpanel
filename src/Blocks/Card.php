<?php

namespace KY\AdminPanel\Blocks;

class Card extends BaseBlock
{
    protected string $header;

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
