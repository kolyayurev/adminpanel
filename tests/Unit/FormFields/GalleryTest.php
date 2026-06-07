<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\Gallery;
use KY\AdminPanel\FormFields\MediaPicker;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Gallery
 */
class GalleryTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_construct_sets_default_media_picker_and_title_field(): void
    {
        $field = new Gallery();

        $this->assertInstanceOf(MediaPicker::class, $field->getMediaPicker());
        $this->assertSame('url', $field->getMediaPicker()->get('name'));
        $this->assertCount(1, $field->getFields());
        $this->assertSame('title', $field->getFields()->first()->get('name'));
    }

    /**
     * @covers ::displayValue
     */
    public function test_display_value_sets_display_value_attribute(): void
    {
        $field = new Gallery();

        $this->assertSame($field, $field->displayValue('return item.name;'));
        $this->assertSame('return item.name;', $field->get('displayValue'));
    }

    /**
     * @covers ::getId
     */
    public function test_get_id_returns_normalized_gallery_id(): void
    {
        $this->assertSame('gallery_images', Gallery::make('images')->getId());
    }

    /**
     * @covers ::mediaPicker
     * @covers ::getMediaPicker
     */
    public function test_media_picker_sets_media_picker(): void
    {
        $field = new Gallery();
        $mediaPicker = MediaPicker::make('image');

        $this->assertSame($field, $field->mediaPicker($mediaPicker));
        $this->assertSame($mediaPicker, $field->getMediaPicker());
    }

    /**
     * @covers ::min
     */
    public function test_min_sets_min_attribute(): void
    {
        $field = new Gallery();

        $this->assertSame($field, $field->min(2));
        $this->assertSame(2, $field->get('min'));
    }

    /**
     * @covers ::max
     */
    public function test_max_sets_max_attribute(): void
    {
        $field = new Gallery();

        $this->assertSame($field, $field->max(4));
        $this->assertSame(4, $field->get('max'));
    }

    /**
     * @covers ::getMediaPickerOptions
     */
    public function test_get_media_picker_options_sets_nested_element_selector(): void
    {
        $options = Gallery::make('images')->getMediaPickerOptions();

        $this->assertSame('#gallery_images input[name="url"]', $options['element']);
        $this->assertSame(['image'], $options['allowedTypes']);
    }
}
