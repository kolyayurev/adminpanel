<?php

namespace KY\AdminPanel\Tests\Unit\Support;

use Exception;
use KY\AdminPanel\Models\Setting;
use KY\AdminPanel\Models\Translation;
use KY\AdminPanel\Support\Translator;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Support\Translator
 */
class TranslatorTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_construct_initializes_raw_attributes_from_model(): void
    {
        $setting = Setting::factory()->create(['key' => 'site_title', 'value' => 'Заголовок']);

        $translator = new Translator($setting);

        $this->assertSame('Заголовок', $translator->getRawAttributes()['value']['value']);
        $this->assertSame('ru', $translator->getRawAttributes()['value']['locale']);
        $this->assertTrue($translator->getRawAttributes()['value']['exists']);
        $this->assertFalse($translator->getRawAttributes()['value']['modified']);
    }

    /**
     * @covers ::translate
     */
    public function test_translate_sets_locale_and_translates_model_attributes(): void
    {
        config()->set('adminpanel.multilingual.enabled', true);
        config()->set('adminpanel.multilingual.default', 'ru');
        config()->set('adminpanel.multilingual.locales', ['ru', 'en']);

        $setting = Setting::factory()->create(['value' => 'Русское значение']);
        Translation::factory()->create([
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'English value',
        ]);

        $translator = (new Translator($setting))->translate('en', false);

        $this->assertSame('en', $translator->getLocale());
        $this->assertSame('English value', $translator->value);
    }

    /**
     * @covers ::save
     */
    public function test_save_persists_modified_attributes_and_returns_current_false_value(): void
    {
        config()->set('adminpanel.multilingual.default', 'ru');
        config()->set('adminpanel.multilingual.locales', ['ru', 'en']);

        $setting = Setting::factory()->create(['value' => 'Русское значение']);
        $translator = (new Translator($setting))->translate('en', false);
        $translator->value = 'English value';

        $result = $translator->save();

        $this->assertFalse($result);
        $this->assertDatabaseHas('translations', [
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'English value',
        ]);
        $this->assertFalse($translator->translationAttributeModified('value'));
    }

    /**
     * @covers ::getModel
     */
    public function test_get_model_returns_wrapped_model(): void
    {
        $setting = Setting::factory()->create();

        $this->assertTrue($setting->is((new Translator($setting))->getModel()));
    }

    /**
     * @covers ::getRawAttributes
     */
    public function test_get_raw_attributes_returns_translator_state(): void
    {
        $translator = new Translator(Setting::factory()->create(['value' => 'Value']));

        $this->assertSame('Value', $translator->getRawAttributes()['value']['value']);
    }

    /**
     * @covers ::getOriginalAttributes
     */
    public function test_get_original_attributes_returns_model_attributes(): void
    {
        $setting = Setting::factory()->create(['key' => 'site_title']);

        $this->assertSame($setting->getAttributes(), (new Translator($setting))->getOriginalAttributes());
    }

    /**
     * @covers ::getOriginalAttribute
     */
    public function test_get_original_attribute_returns_model_attribute(): void
    {
        $translator = new Translator(Setting::factory()->create(['key' => 'site_title']));

        $this->assertSame('site_title', $translator->getOriginalAttribute('key'));
    }

    /**
     * @covers ::getTranslationModel
     */
    public function test_get_translation_model_returns_loaded_translation_for_locale(): void
    {
        $setting = Setting::factory()->create();
        $translation = Translation::factory()->create([
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
        ]);

        $translator = (new Translator($setting))->translate('en', false);

        $this->assertTrue($translation->is($translator->getTranslationModel('value', 'en')));
    }

    /**
     * @covers ::getModifiedAttributes
     */
    public function test_get_modified_attributes_returns_only_changed_translatable_attributes(): void
    {
        $translator = (new Translator(Setting::factory()->create()))->translate('en', false);
        $translator->value = 'Changed value';

        $modified = $translator->getModifiedAttributes();

        $this->assertArrayHasKey('value', $modified);
        $this->assertSame('Changed value', $modified['value']['value']);
    }

    /**
     * @covers ::translateAttribute
     */
    public function test_translate_attribute_updates_single_attribute_state(): void
    {
        config()->set('adminpanel.multilingual.enabled', true);

        $setting = Setting::factory()->create(['value' => 'Русское значение']);
        Translation::factory()->create([
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'English value',
        ]);
        $translator = new Translator($setting);

        $this->callNonPublicMethod($translator, 'translateAttribute', ['value', 'en', false]);

        $this->assertSame('English value', $translator->value);
        $this->assertSame('en', $translator->getRawAttributes()['value']['locale']);
    }

    /**
     * @covers ::translateAttributeToOriginal
     */
    public function test_translate_attribute_to_original_throws_error_exception_for_model_attributes_access(): void
    {
        $this->expectException(\ErrorException::class);

        $translator = (new Translator(Setting::factory()->create(['value' => 'Original value'])))->translate('en', false);
        $translator->value = 'Changed value';

        $this->callNonPublicMethod($translator, 'translateAttributeToOriginal', ['value']);
    }

    /**
     * @covers ::__get
     */
    public function test_get_returns_translator_or_model_attribute_values(): void
    {
        $translator = new Translator(Setting::factory()->create(['key' => 'site_title', 'value' => 'Value']));

        $this->assertSame('Value', $translator->value);
        $this->assertSame('site_title', $translator->key);
        $this->assertNull($translator->missing_attribute);
    }

    /**
     * @covers ::__set
     */
    public function test_set_marks_translatable_attributes_as_modified(): void
    {
        $translator = (new Translator(Setting::factory()->create()))->translate('en', false);

        $translator->value = 'Changed value';

        $this->assertTrue($translator->translationAttributeModified('value'));
        $this->assertSame('Changed value', $translator->value);
    }

    /**
     * @covers ::offsetGet
     * @covers ::offsetSet
     * @covers ::offsetExists
     * @covers ::offsetUnset
     */
    public function test_array_access_methods_read_write_and_unset_attributes(): void
    {
        $translator = (new Translator(Setting::factory()->create(['value' => 'Value'])))->translate('en', false);

        $this->assertTrue(isset($translator['value']));
        $this->assertNull($translator['value']);

        $translator['value'] = 'Changed value';

        $this->assertSame('Changed value', $translator['value']);
        $this->assertTrue($translator->translationAttributeModified('value'));

        unset($translator['value']);

        $this->assertFalse(isset($translator['value']));
    }

    /**
     * @covers ::getLocale
     */
    public function test_get_locale_returns_current_locale(): void
    {
        $translator = (new Translator(Setting::factory()->create()))->translate('en', false);

        $this->assertSame('en', $translator->getLocale());
    }

    /**
     * @covers ::translationAttributeExists
     */
    public function test_translation_attribute_exists_checks_attribute_state(): void
    {
        $translator = (new Translator(Setting::factory()->create()))->translate('en', false);

        $this->assertFalse($translator->translationAttributeExists('value'));
        $this->assertFalse($translator->translationAttributeExists('missing'));
    }

    /**
     * @covers ::translationAttributeModified
     */
    public function test_translation_attribute_modified_checks_attribute_state(): void
    {
        $translator = (new Translator(Setting::factory()->create()))->translate('en', false);
        $translator->value = 'Changed value';

        $this->assertTrue($translator->translationAttributeModified('value'));
        $this->assertFalse($translator->translationAttributeModified('missing'));
    }

    /**
     * @covers ::createTranslation
     */
    public function test_create_translation_persists_translation_and_returns_current_null_value(): void
    {
        $setting = Setting::factory()->create(['value' => 'Русское значение']);
        $translator = (new Translator($setting))->translate('en', false);

        $result = $translator->createTranslation('value', 'English value');

        $this->assertNull($result);
        $this->assertDatabaseHas('translations', [
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'English value',
        ]);
        $this->assertTrue($translator->translationAttributeExists('value'));
    }

    /**
     * @covers ::createTranslations
     */
    public function test_create_translations_persists_multiple_translations(): void
    {
        $setting = Setting::factory()->create(['value' => 'Русское значение']);
        $translator = (new Translator($setting))->translate('en', false);

        $translator->createTranslations(['value' => 'English value']);

        $this->assertDatabaseHas('translations', [
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'English value',
        ]);
    }

    /**
     * @covers ::deleteTranslation
     */
    public function test_delete_translation_removes_existing_translation(): void
    {
        $setting = Setting::factory()->create();
        Translation::factory()->create([
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'English value',
        ]);
        $translator = (new Translator($setting))->translate('en', false);

        $this->assertTrue($translator->deleteTranslation('value'));
        $this->assertDatabaseMissing('translations', [
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
        ]);
        $this->assertFalse($translator->translationAttributeExists('value'));
    }

    /**
     * @covers ::deleteTranslation
     */
    public function test_delete_translation_returns_false_for_missing_attribute(): void
    {
        $translator = new Translator(Setting::factory()->create());

        $this->assertFalse($translator->deleteTranslation('missing'));
    }

    /**
     * @covers ::deleteTranslations
     */
    public function test_delete_translations_removes_multiple_translations(): void
    {
        $setting = Setting::factory()->create();
        Translation::factory()->create([
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
        ]);
        $translator = (new Translator($setting))->translate('en', false);

        $translator->deleteTranslations(['value']);

        $this->assertDatabaseMissing('translations', [
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
        ]);
    }

    /**
     * @covers ::__call
     * @covers ::runTranslatorMethod
     */
    public function test_call_runs_registered_translator_method(): void
    {
        $translator = new Translator(TranslatorMethodSetting::query()->create([
            'key' => 'site_title',
            'value' => 'value',
        ]));

        $this->assertSame('VALUE!', $translator->upper_value('!'));
    }

    /**
     * @covers ::__call
     */
    public function test_call_throws_for_missing_translator_method(): void
    {
        $this->expectException(Exception::class);

        (new Translator(Setting::factory()->create()))->missing_method();
    }

    /**
     * @covers ::jsonSerialize
     */
    public function test_json_serialize_returns_plain_attribute_values(): void
    {
        $translator = new Translator(Setting::factory()->create(['key' => 'site_title', 'value' => 'Value']));

        $this->assertSame('Value', $translator->jsonSerialize()['value']);
        $this->assertSame('site_title', $translator->jsonSerialize()['key']);
    }
}

class TranslatorMethodSetting extends Setting
{
    protected $translatorMethods = [
        'upper_value' => 'translateUpperValue',
    ];

    public function translateUpperValue(Translator $translator, string $suffix): string
    {
        return strtoupper($translator->value).$suffix;
    }
}
