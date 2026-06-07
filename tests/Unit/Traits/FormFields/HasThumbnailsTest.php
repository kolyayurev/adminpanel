<?php

namespace KY\AdminPanel\Tests\Unit\Traits\FormFields;

use Illuminate\Support\Collection;
use KY\AdminPanel\Support\Trumbnail;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\Attributable;
use KY\AdminPanel\Traits\FormFields\HasThumbnails;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\FormFields\HasThumbnails
 */
class HasThumbnailsTest extends TestCase
{
    /**
     * @covers ::hasThumbnails
     * @covers ::getThumbnails
     */
    public function test_has_thumbnails_returns_false_for_empty_collection(): void
    {
        $element = new HasThumbnailsTestElement;

        $this->assertFalse($element->hasThumbnails());
        $this->assertInstanceOf(Collection::class, $element->getThumbnails());
        $this->assertTrue($element->getThumbnails()->isEmpty());
    }

    /**
     * @covers ::addThumbnail
     * @covers ::getFirstThumbnails
     */
    public function test_add_thumbnail_appends_thumbnail(): void
    {
        $element = new HasThumbnailsTestElement;
        $thumbnail = Trumbnail::make('preview');

        $this->assertSame($element, $element->addThumbnail($thumbnail));
        $this->assertSame($thumbnail, $element->getFirstThumbnails());
    }

    /**
     * @covers ::thumbnails
     * @covers ::getThumbnailsName
     */
    public function test_thumbnails_adds_only_thumbnail_instances(): void
    {
        $element = new HasThumbnailsTestElement;

        $element->thumbnails(Trumbnail::make('preview'), 'ignored', Trumbnail::make('small'));

        $this->assertSame(['preview', 'small'], $element->getThumbnailsName());
    }
}

class HasThumbnailsTestElement
{
    use Attributable;
    use HasThumbnails;

    protected array $attributes = [
        'thumbnails' => [],
    ];
}
