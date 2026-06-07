<?php

namespace KY\AdminPanel\Support;

use Illuminate\Contracts\Support\Arrayable;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\Makeable;

class Watermark implements Arrayable
{
    use HasDynamicCall, Makeable;

    protected string $source;

    protected int $size = 15;

    protected int $x = 0;

    protected int $y = 0;

    protected string $position = 'top-left';

    public function getSource(): string
    {
        return $this->source;
    }

    public function source(string $source): Watermark
    {
        $this->source = $source;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): Watermark
    {
        $this->size = $size;

        return $this;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function x(int $x): Watermark
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function y(int $y): Watermark
    {
        $this->y = $y;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function position(string $position): Watermark
    {
        $this->position = $position;

        return $this;
    }

    public function hasSource(): bool
    {
        return ! empty($this->get('source'));
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
