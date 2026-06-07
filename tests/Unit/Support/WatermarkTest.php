<?php

namespace KY\AdminPanel\Tests\Unit\Support;

use KY\AdminPanel\Support\Watermark;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Support\Watermark
 */
class WatermarkTest extends TestCase
{
    /**
     * @covers ::source
     * @covers ::getSource
     * @covers ::hasSource
     */
    public function test_source_sets_source(): void
    {
        $watermark = new Watermark;

        $this->assertFalse($watermark->hasSource());
        $this->assertSame($watermark, $watermark->source('watermark.png'));
        $this->assertSame('watermark.png', $watermark->getSource());
        $this->assertTrue($watermark->hasSource());
    }

    /**
     * @covers ::setSize
     * @covers ::getSize
     */
    public function test_set_size_sets_size(): void
    {
        $watermark = new Watermark;

        $this->assertSame(15, $watermark->getSize());
        $this->assertSame($watermark, $watermark->setSize(20));
        $this->assertSame(20, $watermark->getSize());
    }

    /**
     * @covers ::x
     * @covers ::getX
     * @covers ::y
     * @covers ::getY
     */
    public function test_coordinates_set_x_and_y(): void
    {
        $watermark = new Watermark;

        $this->assertSame($watermark, $watermark->x(10));
        $this->assertSame($watermark, $watermark->y(20));
        $this->assertSame(10, $watermark->getX());
        $this->assertSame(20, $watermark->getY());
    }

    /**
     * @covers ::position
     * @covers ::getPosition
     */
    public function test_position_sets_position(): void
    {
        $watermark = new Watermark;

        $this->assertSame('top-left', $watermark->getPosition());
        $this->assertSame($watermark, $watermark->position('bottom-right'));
        $this->assertSame('bottom-right', $watermark->getPosition());
    }

    /**
     * @covers ::toArray
     */
    public function test_to_array_returns_watermark_state(): void
    {
        $watermark = Watermark::make()
            ->source('watermark.png')
            ->setSize(20)
            ->x(10)
            ->y(30)
            ->position('center');

        $this->assertSame([
            'source' => 'watermark.png',
            'size' => 20,
            'x' => 10,
            'y' => 30,
            'position' => 'center',
        ], $watermark->toArray());
    }
}
