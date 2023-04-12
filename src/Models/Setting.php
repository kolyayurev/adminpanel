<?php

namespace KY\AdminPanel\Models;

use Str;

use KY\AdminPanel\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Translatable;

    protected $translatable = ['value'];

//    protected $translatableMode = ['settings'];

    protected $table = 'settings';

    protected $guarded = [];

    public $timestamps = false;


    public function getTranslatableAttributes()
    {
        return array_merge($this->translatable,$this->pluck('key')->toArray());
    }

    /**
     * Prepare translations and set default locale field value.
     *
     * @param object $request
     *
     * @return array translations
     */
    public function prepareSettingTranslation($request,string $setting)
    {
        if (!$request->input($setting.'_i18n')) {
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

    public function setAttributeTranslations($attribute, array $translations, $save = false)
    {
        $response = [];

        if (!$this->relationLoaded('translations')) {
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

            $tranlator = $this->translate($locale, false);
            $tranlator->value = $translations[$locale];

            if ($save) {
                $tranlator->save();
            }

            $response[] = $tranlator;
        }

        return $response;
    }
}
