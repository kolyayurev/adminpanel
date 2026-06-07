<?php

namespace KY\AdminPanel\Tests\Unit\Traits;

use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\Attributable;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\Attributable
 */
class AttributableTest extends TestCase
{
    /**
     * @covers ::__call
     */
    public function test_call_sets_attribute_from_first_argument(): void
    {
        $element = new AttributableTestElement();

        $result = $element->placeholder('Enter title');

        $this->assertSame($element, $result);
        $this->assertSame('Enter title', $element->get('placeholder'));
    }

    /**
     * @covers ::__call
     */
    public function test_call_sets_true_when_argument_missing(): void
    {
        $element = new AttributableTestElement();

        $element->required();

        $this->assertTrue($element->get('required'));
    }

    /**
     * @covers ::__call
     */
    public function test_call_keeps_closure_argument(): void
    {
        $element = new AttributableTestElement();
        $closure = static fn (): string => 'resolved';

        $element->value($closure);

        $this->assertInstanceOf(\Closure::class, $element->get('value'));
    }

    /**
     * @covers ::set
     */
    public function test_set_stores_attribute(): void
    {
        $element = new AttributableTestElement();

        $result = $element->set('name', 'title');

        $this->assertSame($element, $result);
        $this->assertSame('title', $element->get('name'));
    }

    /**
     * @covers ::get
     */
    public function test_get_returns_attribute_or_default(): void
    {
        $element = new AttributableTestElement();
        $element->set('name', 'title');

        $this->assertSame('title', $element->get('name'));
        $this->assertSame('fallback', $element->get('missing', 'fallback'));
    }

    /**
     * @covers ::getAttributes
     */
    public function test_get_attributes_returns_all_attributes(): void
    {
        $element = new AttributableTestElement();
        $element->set('name', 'title');
        $element->set('required');

        $this->assertSame([
            'name' => 'title',
            'required' => true,
        ], $element->getAttributes());
    }
}

class AttributableTestElement
{
    use Attributable;

    protected array $attributes = [];
}
