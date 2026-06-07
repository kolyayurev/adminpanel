<?php

namespace KY\AdminPanel\Tests\Unit\Traits;

use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\HasDynamicCall;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\HasDynamicCall
 */
class HasDynamicCallTest extends TestCase
{
    /**
     * @covers ::__call
     */
    public function test_call_sets_dynamic_property_from_first_argument(): void
    {
        $element = new HasDynamicCallTestElement;

        $result = $element->placeholder('Enter title');

        $this->assertSame($element, $result);
        $this->assertSame('Enter title', $element->placeholder);
    }

    /**
     * @covers ::__call
     */
    public function test_call_sets_true_when_argument_missing(): void
    {
        $element = new HasDynamicCallTestElement;

        $element->required();

        $this->assertTrue($element->required);
    }

    /**
     * @covers ::__call
     */
    public function test_call_resolves_closure_argument(): void
    {
        $element = new HasDynamicCallTestElement;
        $closure = static fn (): string => 'resolved';

        $element->value($closure);

        $this->assertSame('resolved', $element->value);
    }

    /**
     * @covers ::set
     */
    public function test_set_assigns_dynamic_property(): void
    {
        $element = new HasDynamicCallTestElement;

        $result = $element->set('name', 'title');

        $this->assertSame($element, $result);
        $this->assertSame('title', $element->name);
    }

    /**
     * @covers ::get
     */
    public function test_get_returns_property_or_default(): void
    {
        $element = new HasDynamicCallTestElement;
        $element->set('name', 'title');

        $this->assertSame('title', $element->get('name'));
        $this->assertSame('fallback', $element->get('missing', 'fallback'));
    }
}

class HasDynamicCallTestElement
{
    use HasDynamicCall;
}
