<?php

namespace KY\AdminPanel\Tests\Unit\Support;

use KY\AdminPanel\Support\Trumbnail;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Support\Trumbnail
 */
class TrumbnailTest extends TestCase
{
    /**
     * @covers ::name
     * @covers ::getName
     */
    public function test_name_sets_name_or_default_name(): void
    {
        $thumbnail = new Trumbnail();

        $this->assertSame($thumbnail, $thumbnail->name('preview'));
        $this->assertSame('preview', $thumbnail->getName());

        $thumbnail->name(null);

        $this->assertSame('thumb', $thumbnail->getName());
    }

    /**
     * @covers ::type
     * @covers ::getType
     */
    public function test_type_sets_type(): void
    {
        $thumbnail = new Trumbnail();

        $this->assertSame('fit', $thumbnail->getType());
        $this->assertSame($thumbnail, $thumbnail->type('crop'));
        $this->assertSame('crop', $thumbnail->getType());
    }

    /**
     * @covers ::width
     * @covers ::getWidth
     * @covers ::hasWidth
     */
    public function test_width_sets_width(): void
    {
        $thumbnail = new Trumbnail();

        $this->assertFalse($thumbnail->hasWidth());
        $this->assertSame($thumbnail, $thumbnail->width(320));
        $this->assertSame(320, $thumbnail->getWidth());
        $this->assertTrue($thumbnail->hasWidth());
    }

    /**
     * @covers ::height
     * @covers ::getHeight
     * @covers ::hasHeight
     */
    public function test_height_sets_height(): void
    {
        $thumbnail = new Trumbnail();

        $this->assertFalse($thumbnail->hasHeight());
        $this->assertSame($thumbnail, $thumbnail->height(180));
        $this->assertSame(180, $thumbnail->getHeight());
        $this->assertTrue($thumbnail->hasHeight());
    }

    /**
     * @covers ::x
     * @covers ::getX
     * @covers ::y
     * @covers ::getY
     */
    public function test_coordinates_set_x_and_y(): void
    {
        $thumbnail = new Trumbnail();

        $this->assertSame($thumbnail, $thumbnail->x(10));
        $this->assertSame($thumbnail, $thumbnail->y(20));
        $this->assertSame(10, $thumbnail->getX());
        $this->assertSame(20, $thumbnail->getY());
    }

    /**
     * @covers ::position
     * @covers ::getPosition
     */
    public function test_position_sets_position(): void
    {
        $thumbnail = new Trumbnail();

        $this->assertSame('center', $thumbnail->getPosition());
        $this->assertSame($thumbnail, $thumbnail->position('top-left'));
        $this->assertSame('top-left', $thumbnail->getPosition());
    }

    /**
     * @covers ::quality
     * @covers ::getQuality
     */
    public function test_quality_sets_quality(): void
    {
        $thumbnail = new Trumbnail();

        $this->assertSame(90, $thumbnail->getQuality());
        $this->assertSame($thumbnail, $thumbnail->quality(75));
        $this->assertSame(75, $thumbnail->getQuality());
    }

    /**
     * @covers ::upsize
     * @covers ::isUpsize
     */
    public function test_upsize_sets_upsize_flag(): void
    {
        $thumbnail = new Trumbnail();

        $this->assertFalse($thumbnail->isUpsize());
        $this->assertSame($thumbnail, $thumbnail->upsize(true));
        $this->assertTrue($thumbnail->isUpsize());
    }

    /**
     * @covers ::crop
     * @covers ::isCrop
     */
    public function test_crop_sets_crop_type_and_dimensions(): void
    {
        $thumbnail = (new Trumbnail())->crop(320, 180, 5, 10);

        $this->assertTrue($thumbnail->isCrop());
        $this->assertSame(320, $thumbnail->getWidth());
        $this->assertSame(180, $thumbnail->getHeight());
        $this->assertSame(5, $thumbnail->getX());
        $this->assertSame(10, $thumbnail->getY());
    }

    /**
     * @covers ::scale
     * @covers ::getScale
     * @covers ::isScale
     */
    public function test_scale_sets_scale_type_and_clamps_negative_scale(): void
    {
        $thumbnail = (new Trumbnail())->scale(-10);

        $this->assertTrue($thumbnail->isScale());
        $this->assertSame(0, $thumbnail->getScale());
    }

    /**
     * @covers ::resize
     * @covers ::isResize
     */
    public function test_resize_sets_resize_type_and_dimensions(): void
    {
        $thumbnail = (new Trumbnail())->resize(640, 480);

        $this->assertTrue($thumbnail->isResize());
        $this->assertSame(640, $thumbnail->getWidth());
        $this->assertSame(480, $thumbnail->getHeight());
    }

    /**
     * @covers ::fit
     * @covers ::isFit
     * @covers ::isResize
     */
    public function test_fit_currently_sets_resize_type(): void
    {
        $thumbnail = (new Trumbnail())->fit(640, 480);

        $this->assertFalse($thumbnail->isFit());
        $this->assertTrue($thumbnail->isResize());
    }

    /**
     * @covers ::toArray
     */
    public function test_to_array_returns_thumbnail_state(): void
    {
        $thumbnail = Trumbnail::make('preview')->crop(320, 180, 5, 10)->quality(80);

        $this->assertSame([
            'name' => 'preview',
            'type' => 'crop',
            'width' => 320,
            'height' => 180,
            'resultWidth' => 320,
            'resultHeight' => 180,
            'x' => 5,
            'y' => 10,
            'position' => 'center',
            'scale' => 100,
            'upsize' => false,
            'quality' => 80,
        ], $thumbnail->toArray());
    }
}
