<?php

namespace KY\AdminPanel\Tests\Unit\Traits;

use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\Makeable;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\Makeable
 */
class MakeableTest extends TestCase
{
    /**
     * @covers ::make
     */
    public function test_make_creates_instance_and_passes_name(): void
    {
        $instance = MakeableTestElement::make('title');

        $this->assertInstanceOf(MakeableTestElement::class, $instance);
        $this->assertSame('title', $instance->name);
    }
}

class MakeableTestElement
{
    use Makeable;

    public ?string $name = null;

    public function name(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
