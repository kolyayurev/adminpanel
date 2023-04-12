<?php
declare(strict_types=1);

namespace KY\AdminPanel\Traits;

trait Makeable
{
    /**
     * Create a new element.
     *
     * @return static
     */
    public static function make(?string $name = null): self
    {
        return (new static)->name($name);
    }
}
