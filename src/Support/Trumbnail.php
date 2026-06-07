<?php

namespace KY\AdminPanel\Support;

use Illuminate\Contracts\Support\Arrayable;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\Makeable;

class Trumbnail implements Arrayable
{
    use HasDynamicCall, Makeable;

    protected ?string $name;

    // ['crop','scale','resize','fit']
    protected string $type = 'fit';

    protected int $width;

    protected int $height;

    protected ?int $x;

    protected ?int $y;

    protected string $position = 'center';

    protected int $scale = 100; // %

    protected int $quality = 90;

    protected bool $upsize = false;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function name(?string $name): Trumbnail
    {
        $this->name = $name ?? config('adminpanel.media.default_thumb_name', 'thumb');

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function type(string $type): Trumbnail
    {
        $this->type = $type;

        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function width(int $width): Trumbnail
    {
        $this->width = $width;

        return $this;
    }

    public function hasWidth(): bool
    {
        return ! empty($this->width);
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function height(int $height): Trumbnail
    {
        $this->height = $height;

        return $this;
    }

    public function hasHeight(): bool
    {
        return ! empty($this->height);
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function x(?int $x): Trumbnail
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function y(?int $y): Trumbnail
    {
        $this->y = $y;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function position(string $position): Trumbnail
    {
        $this->position = $position;

        return $this;
    }

    public function getScale(): int
    {
        return $this->scale;
    }

    public function getQuality(): int
    {
        return $this->quality;
    }

    public function quality(int $quality): Trumbnail
    {
        $this->quality = $quality;

        return $this;
    }

    public function isUpsize(): bool
    {
        return $this->upsize;
    }

    public function upsize(bool $upsize): Trumbnail
    {
        $this->upsize = $upsize;

        return $this;
    }

    public function crop(int $width, ?int $height = null, ?int $x = null, ?int $y = null): self
    {
        $this->type = 'crop';
        $this->width = $width;
        $this->height = $height;
        $this->x = $x;
        $this->y = $y;

        return $this;
    }

    public function scale(int $scale): self
    {
        $this->type = 'scale';
        $this->scale = ($scale >= 0) ? $scale : 0;

        return $this;
    }

    public function resize(int $width, ?int $height = null): self
    {
        $this->type = 'resize';
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function fit(int $width, ?int $height = null): self
    {
        $this->type = 'resize';
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function isScale(): bool
    {
        return $this->type === 'scale';
    }

    public function isCrop(): bool
    {
        return $this->type === 'crop';
    }

    public function isResize(): bool
    {
        return $this->type === 'resize';
    }

    public function isFit(): bool
    {
        return $this->type === 'fit';
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'width' => $this->width ?? null,
            'height' => $this->height ?? null,
            'resultWidth' => $this->width ?? null,
            'resultHeight' => $this->height ?? null,
            'x' => $this->x ?? null,
            'y' => $this->y ?? null,
            'position' => $this->position,
            'scale' => $this->scale,
            'upsize' => $this->upsize,
            'quality' => $this->quality,
        ];
    }
}
