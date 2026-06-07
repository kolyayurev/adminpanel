<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\MediaPicker;
use KY\AdminPanel\Models\User;
use KY\AdminPanel\Support\Trumbnail;
use KY\AdminPanel\Support\Watermark;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\MediaPicker
 */
class MediaPickerTest extends TestCase
{
    /**
     * @covers ::buildAfterLabel
     */
    public function test_build_after_label_returns_accept_extensions_and_thumbnail_size(): void
    {
        $field = MediaPicker::make('image')
            ->accept('.png,.jpg')
            ->thumbnails(Trumbnail::make('preview')->resize(320, 180));

        $this->assertSame('(png/jpg 320x180px)', $field->buildAfterLabel());
    }

    /**
     * @covers ::basePath
     * @covers ::hasBasePath
     * @covers ::getBasePath
     */
    public function test_base_path_sets_and_reads_base_path(): void
    {
        $field = new MediaPicker();

        $this->assertTrue($field->hasBasePath());
        $this->assertSame($field, $field->basePath('uploads/{key}'));
        $this->assertSame('uploads/{key}', $field->getBasePath());
    }

    /**
     * @covers ::single
     * @covers ::isMultiSelect
     */
    public function test_single_limits_picker_to_one_file(): void
    {
        $field = (new MediaPicker())->max(3);

        $this->assertTrue($field->isMultiSelect());
        $this->assertSame($field, $field->single());
        $this->assertFalse($field->isMultiSelect());
    }

    /**
     * @covers ::hideThumbnails
     */
    public function test_hide_thumbnails_sets_hide_thumbnails_attribute(): void
    {
        $field = new MediaPicker();

        $this->assertSame($field, $field->hideThumbnails());
        $this->assertTrue($field->get('hideThumbnails'));
    }

    /**
     * @covers ::allowedTypes
     */
    public function test_allowed_types_sets_allowed_types_attribute(): void
    {
        $field = new MediaPicker();

        $this->assertSame($field, $field->allowedTypes(['video']));
        $this->assertSame(['video'], $field->get('allowedTypes'));
    }

    /**
     * @covers ::getOptions
     */
    public function test_get_options_returns_picker_options_without_value(): void
    {
        $field = MediaPicker::make('image')
            ->basePath('uploads')
            ->max(2)
            ->thumbnails(Trumbnail::make('preview')->resize(320, 180));

        $options = $field->getOptions();

        $this->assertArrayNotHasKey('value', $options);
        $this->assertSame('uploads', $options['basePath']);
        $this->assertTrue($options['allowMultiSelect']);
        $this->assertSame('input[name="image"]', $options['element']);
        $this->assertSame('preview', $options['thumbnails'][0]['name']);
    }

    /**
     * @covers ::uuidSessionName
     */
    public function test_uuid_session_name_uses_model_class_and_field_id(): void
    {
        $field = MediaPicker::make('image');

        $this->assertSame('User_media_picker_image_uuid', $field->uuidSessionName(new User()));
    }

    /**
     * @covers ::prepareBasePath
     */
    public function test_prepare_base_path_replaces_model_key_for_existing_model(): void
    {
        $this->actingAs($this->createUser());

        $field = MediaPicker::make('image')->basePath('uploads/{key}');
        $model = new User();
        $model->id = 15;
        $model->exists = true;

        $field->prepareBasePath($model);

        $this->assertSame('uploads/15', $field->getBasePath());
    }

    /**
     * @covers ::prepareData
     */
    public function test_prepare_data_returns_raw_value_for_single_select(): void
    {
        $field = MediaPicker::make('image');
        $model = (object) ['image' => 'uploads/image.jpg'];

        $this->assertSame('uploads/image.jpg', $field->prepareData(null, $model));
    }

    /**
     * @covers ::prepareData
     */
    public function test_prepare_data_decodes_json_for_multi_select(): void
    {
        $field = MediaPicker::make('images')->max(2);
        $model = (object) ['images' => '["a.jpg","b.jpg"]'];

        $this->assertSame(['a.jpg', 'b.jpg'], $field->prepareData(null, $model));
    }

    /**
     * @covers ::hasTempFiles
     */
    public function test_has_temp_files_requires_path_and_uuid_sessions(): void
    {
        $field = MediaPicker::make('image');

        $this->assertFalse($field->hasTempFiles());

        session()->put($field->getId().'_path', 'uploads/tmp');
        session()->put($field->getId().'_uuid', 'uuid');

        $this->assertTrue($field->hasTempFiles());
    }

    /**
     * @coversNothing
     */
    public function test_watermark_trait_is_available_on_media_picker(): void
    {
        $field = new MediaPicker();
        $watermark = Watermark::make()->source('watermark.png');

        $field->watermark($watermark);

        $this->assertSame($watermark, $field->getWatermark());
    }
}
