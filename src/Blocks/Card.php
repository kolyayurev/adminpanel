<?php
namespace KY\AdminPanel\Blocks;

class Card extends BaseBlock
{
    protected string $header;

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->get('header');
    }

    /**
     * @param string $header
     * @return self
     */
    public function header(string $header): self
    {

        return $this->set('header',$header);
    }

    /**
     * @return bool
     */
    public function hasHeader(): bool
    {
        return !empty($this->get('header'));
    }
}
