<?php

namespace KY\AdminPanel\Tests\Utils\Traits;

use ReflectionClass;
use ReflectionException;

trait ReflationTestTrait
{
    /**
     * Call a non-public method on the given object or class.
     *
     * @param  object|class-string  $target
     * @param  array<int, mixed>  $arguments
     *
     * @throws ReflectionException
     */
    protected function callNonPublicMethod(object|string $target, string $method, array $arguments = []): mixed
    {
        $reflection = new ReflectionClass($target);
        $reflectionMethod = $reflection->getMethod($method);

        return $reflectionMethod->invokeArgs(is_object($target) ? $target : null, $arguments);
    }

    /**
     * Read a non-public property from the given object or class.
     *
     * @param  object|class-string  $target
     *
     * @throws ReflectionException
     */
    protected function getNonPublicProperty(object|string $target, string $property): mixed
    {
        $reflection = new ReflectionClass($target);
        $reflectionProperty = $reflection->getProperty($property);

        return $reflectionProperty->getValue(is_object($target) ? $target : null);
    }

    /**
     * Write a non-public property on the given object or class.
     *
     * @param  object|class-string  $target
     *
     * @throws ReflectionException
     */
    protected function setNonPublicProperty(object|string $target, string $property, mixed $value): void
    {
        $reflection = new ReflectionClass($target);
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setValue(is_object($target) ? $target : null, $value);
    }
}
