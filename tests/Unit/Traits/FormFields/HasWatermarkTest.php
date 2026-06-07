<?php

namespace KY\AdminPanel\Tests\Unit\Traits\FormFields;

use KY\AdminPanel\Support\Watermark;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\Attributable;
use KY\AdminPanel\Traits\FormFields\HasWatermark;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\FormFields\HasWatermark
 */
class HasWatermarkTest extends TestCase
{
    /**
     * @covers ::hasWatermark
     * @covers ::getWatermark
     */
    public function test_has_watermark_returns_false_without_watermark(): void
    {
        $element = new HasWatermarkTestElement();

        $this->assertFalse($element->hasWatermark());
        $this->assertNull($element->getWatermark());
    }

    /**
     * @covers ::watermark
     * @covers ::hasWatermark
     * @covers ::getWatermark
     */
    public function test_watermark_sets_watermark(): void
    {
        $element = new HasWatermarkTestElement();
        $watermark = Watermark::make()->source('watermark.png');

        $this->assertSame($element, $element->watermark($watermark));
        $this->assertSame($watermark, $element->getWatermark());
        $this->assertTrue($element->hasWatermark());
    }
}

class HasWatermarkTestElement
{
    use Attributable;
    use HasWatermark;

    protected array $attributes = [
        'watermark' => null,
    ];
}
