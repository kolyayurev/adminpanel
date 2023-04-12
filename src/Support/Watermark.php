<?php

namespace KY\AdminPanel\Support;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\Makeable;

class Watermark implements Arrayable
{
    use Makeable, HasDynamicCall;

    protected string $source;
    protected int $size = 15;
    protected int $x = 0;
    protected int $y = 0;
    protected string $position = 'top-left';

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return Watermark
     */
    public function source(string $source): Watermark
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return Watermark
     */
    public function setSize(int $size): Watermark
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $x
     * @return Watermark
     */
    public function x(int $x): Watermark
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $y
     * @return Watermark
     */
    public function y(int $y): Watermark
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @param string $position
     * @return Watermark
     */
    public function position(string $position): Watermark
    {
        $this->position = $position;
        return $this;
    }

    function hasSource(): bool
    {
        return !empty($this->get('source'));
    }

    public function toArray()
    {
        return [
            'source' => $this->source,
            'size' => $this->size,
            'x' => $this->x,
            'y' => $this->y,
            'position' => $this->position,
        ];
    }
}
