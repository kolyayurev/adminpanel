<?php
namespace KY\AdminPanel\Blocks;

class Instruction extends BaseBlock
{
    protected string $text = '';

    function getText():string
    {
        return $this->get('text');
    }

    function text(string $text):self
    {
        return $this->set('text',$text);
    }
}
