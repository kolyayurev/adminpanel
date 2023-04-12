<?php
declare(strict_types=1);

namespace KY\AdminPanel\Traits;

trait HasDynamicCall
{

    public function __call(string $name, array $arguments)
    {
        $arguments = collect($arguments)->map(static fn ($argument) => $argument instanceof Closure ? $argument() : $argument);

        if (method_exists($this, $name)) {
            $this->$name($arguments);
        }

        return $this->set($name, $arguments->first() ?? true);
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function set(string $key, $value = true): self
    {
        $this->{$key} = $value;

        return $this;
    }

    /**
     * @param mixed|null $value
     *
     * @return static|mixed|null
     */

    public function get(string $key, $default = null)
    {
        return $this->{$key} ?? $default;
    }


}
