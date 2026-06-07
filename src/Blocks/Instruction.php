<?php

namespace KY\AdminPanel\Blocks;

class Instruction extends BaseBlock
{
    protected string $text = '';

    public function getText(): string
    {
        return $this->get('text');
    }

    public function text(string $text): self
    {
        return $this->set('text', $text);
    }
}
