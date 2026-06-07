<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KY\AdminPanel\Database\Factories\SettingFactory;
use KY\AdminPanel\Traits\Translatable;

/**
 * @property int $id
 * @property string $key
 * @property string|null $display_name
 * @property mixed $value
 * @property mixed|null $details
 *
 * @method static SettingFactory factory($count = null, $state = [])
 */
class Setting extends Model
{
    use HasFactory;
    use Translatable;

    protected $translatable = ['value'];

    //    protected $translatableMode = ['settings'];

    protected $table = 'settings';

    protected $guarded = [];

    public $timestamps = false;

    public function getTranslatableAttributes(): array
    {
        return array_merge($this->translatable, $this->pluck('key')->toArray());
    }

    /**
     * Prepare translations and set default locale field value.
     *
     * @param  object  $request
     * @return false|array translations
     */
    public function prepareSettingTranslation($request, string $setting): false|array
    {
        if (! $request->input($setting.'_i18n')) {
            return false;
        }

        $trans = json_decode($request->input($setting.'_i18n'), true);

        // Set the default local value
        $request->merge([$setting => $trans[config('adminpanel.multilingual.default', 'ru')]]);

        unset($request[$setting.'_i18n']);

        return $this->setAttributeTranslations(
            $setting,
            $trans
        );
    }

    public function setAttributeTranslations($attribute, array $translations, $save = false): array
    {
        $response = [];

        if (! $this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $default = config('adminpanel.multilingual.default', 'ru');
        $locales = config('adminpanel.multilingual.locales', [$default]);

        foreach ($locales as $locale) {
            if (empty($translations[$locale])) {
                continue;
            }

            if ($locale == $default) {
                $this->value = $translations[$locale];

                continue;
            }

            $translator = $this->translate($locale, false);
            $translator->value = $translations[$locale];

            if ($save) {
                $translator->save();
            }

            $response[] = $translator;
        }

        return $response;
    }
}
