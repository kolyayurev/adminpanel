<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\MediaPicker;
use KY\AdminPanel\FormFields\VideoGallery;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\VideoGallery
 */
class VideoGalleryTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_construct_sets_default_media_pickers_and_title_field(): void
    {
        $field = new VideoGallery;

        $this->assertInstanceOf(MediaPicker::class, $field->get('videoMediaPicker'));
        $this->assertInstanceOf(MediaPicker::class, $field->get('previewMediaPicker'));
        $this->assertCount(1, $field->getFields());
        $this->assertSame('title', $field->getFields()->first()->get('name'));
    }

    /**
     * @covers ::getId
     */
    public function test_get_id_returns_normalized_video_gallery_id(): void
    {
        $this->assertSame('videogallery_videos', VideoGallery::make('videos')->getId());
    }

    /**
     * @covers ::videoMediaPicker
     */
    public function test_video_media_picker_sets_video_media_picker(): void
    {
        $field = new VideoGallery;
        $mediaPicker = MediaPicker::make('video');

        $this->assertSame($field, $field->videoMediaPicker($mediaPicker));
        $this->assertSame($mediaPicker, $field->get('videoMediaPicker'));
    }

    /**
     * @covers ::previewMediaPicker
     */
    public function test_preview_media_picker_sets_preview_media_picker(): void
    {
        $field = new VideoGallery;
        $mediaPicker = MediaPicker::make('preview');

        $this->assertSame($field, $field->previewMediaPicker($mediaPicker));
        $this->assertSame($mediaPicker, $field->get('previewMediaPicker'));
    }

    /**
     * @covers ::min
     */
    public function test_min_sets_min_attribute(): void
    {
        $field = new VideoGallery;

        $this->assertSame($field, $field->min(1));
        $this->assertSame(1, $field->get('min'));
    }

    /**
     * @covers ::max
     */
    public function test_max_sets_max_attribute(): void
    {
        $field = new VideoGallery;

        $this->assertSame($field, $field->max(3));
        $this->assertSame(3, $field->get('max'));
    }

    /**
     * @covers ::getVideoMediaPickerOptions
     */
    public function test_get_video_media_picker_options_sets_video_selector_and_type(): void
    {
        $options = VideoGallery::make('videos')->getVideoMediaPickerOptions();

        $this->assertSame('#videogallery_videos input[name="video"]', $options['element']);
        $this->assertSame(['video'], $options['allowedTypes']);
    }

    /**
     * @covers ::getPreviewMediaPickerOptions
     */
    public function test_get_preview_media_picker_options_sets_preview_selector_and_type(): void
    {
        $options = VideoGallery::make('videos')->getPreviewMediaPickerOptions();

        $this->assertSame('#videogallery_videos input[name="preview"]', $options['element']);
        $this->assertSame(['image'], $options['allowedTypes']);
    }
}
