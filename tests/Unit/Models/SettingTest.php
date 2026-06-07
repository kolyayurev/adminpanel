<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use Illuminate\Http\Request;
use KY\AdminPanel\Models\Setting;
use KY\AdminPanel\Models\Translation;
use KY\AdminPanel\Support\Translator;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\Setting
 */
class SettingTest extends TestCase
{
    /**
     * @covers ::getTranslatableAttributes
     */
    public function test_get_translatable_attributes_returns_value_and_setting_keys(): void
    {
        Setting::factory()->create(['key' => 'site_title']);
        Setting::factory()->create(['key' => 'footer_text']);

        $attributes = (new Setting())->getTranslatableAttributes();

        $this->assertEqualsCanonicalizing(['value', 'site_title', 'footer_text'], $attributes);
    }

    /**
     * @covers ::prepareSettingTranslation
     */
    public function test_prepare_setting_translation_sets_default_value_and_returns_translators(): void
    {
        config()->set('adminpanel.multilingual.default', 'ru');
        config()->set('adminpanel.multilingual.locales', ['ru', 'en']);

        $setting = Setting::factory()->create(['key' => 'site_title', 'value' => 'Old title']);
        $request = new Request([
            'site_title_i18n' => json_encode([
                'ru' => 'Русский заголовок',
                'en' => 'English title',
            ]),
        ]);

        $translations = $setting->prepareSettingTranslation($request, 'site_title');

        $this->assertSame('Русский заголовок', $request->input('site_title'));
        $this->assertCount(1, $translations);
        $this->assertInstanceOf(Translator::class, $translations[0]);
        $this->assertSame('English title', $translations[0]->value);
    }

    /**
     * @covers ::prepareSettingTranslation
     */
    public function test_prepare_setting_translation_returns_false_without_i18n_payload(): void
    {
        $setting = Setting::factory()->create(['key' => 'site_title']);

        $this->assertFalse($setting->prepareSettingTranslation(new Request(), 'site_title'));
    }

    /**
     * @covers ::setAttributeTranslations
     */
    public function test_set_attribute_translations_sets_default_value_and_returns_locale_translators(): void
    {
        config()->set('adminpanel.multilingual.default', 'ru');
        config()->set('adminpanel.multilingual.locales', ['ru', 'en']);

        $setting = Setting::factory()->create(['value' => 'Old value']);

        $translations = $setting->setAttributeTranslations('value', [
            'ru' => 'Русское значение',
            'en' => 'English value',
        ]);

        $this->assertSame('Русское значение', $setting->value);
        $this->assertCount(1, $translations);
        $this->assertSame('English value', $translations[0]->value);
    }

    /**
     * @covers ::setAttributeTranslations
     */
    public function test_set_attribute_translations_saves_non_default_locale_when_requested(): void
    {
        config()->set('adminpanel.multilingual.default', 'ru');
        config()->set('adminpanel.multilingual.locales', ['ru', 'en']);

        $setting = Setting::factory()->create(['value' => 'Old value']);

        $setting->setAttributeTranslations('value', [
            'ru' => 'Русское значение',
            'en' => 'English value',
        ], true);

        $this->assertDatabaseHas('translations', [
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'English value',
        ]);
    }

    /**
     * @covers ::setAttributeTranslations
     */
    public function test_set_attribute_translations_updates_loaded_existing_translation(): void
    {
        config()->set('adminpanel.multilingual.default', 'ru');
        config()->set('adminpanel.multilingual.locales', ['ru', 'en']);

        $setting = Setting::factory()->create(['value' => 'Old value']);
        Translation::factory()->create([
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'Old English value',
        ]);

        $setting->setAttributeTranslations('value', [
            'en' => 'New English value',
        ], true);

        $this->assertDatabaseHas('translations', [
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $setting->id,
            'locale' => 'en',
            'value' => 'New English value',
        ]);
    }
}
