<?php

if (!function_exists('is_field_translatable')) {
    /**
     * Check if a Field is translatable.
     *
     * @param Illuminate\Database\Eloquent\Model      $model
     * @param $field
     */
    function is_field_translatable($model, $field)
    {
        if (!is_translatable($model)) {
            return;
        }

        return $model->translatable()
            && method_exists($model, 'isTranslatableAttribute')
            && $model->isTranslatableAttribute($field->get('name'));
    }
}

if (!function_exists('get_field_translations')) {
    /**
     * Return all field translations.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $field
     * @param string                             $rowType
     * @param bool                               $stripHtmlTags
     */
    function get_field_translations($model, $field, $stripHtmlTags = false)
    {
        $_out = $model->getTranslationsOf($field->get('name'));

        if ($stripHtmlTags && $field->getSlug() == 'rich_text_box') {
            foreach ($_out as $language => $value) {
                $_out[$language] = strip_tags($_out[$language]);
            }
        }

        return json_encode($_out);
    }
}

if (!function_exists('is_translatable')) {
    /**
     * Check if model is translatable.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     */
    function is_translatable($model)
    {
        return config('adminpanel.multilingual.enabled')
            && isset($model)
            && method_exists($model, 'translatable')
            && $model->translatable();
    }
}

