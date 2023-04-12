@if (is_field_translatable($model, $field) && $field->isMultilingual())
    <input type="hidden"
           data-i18n="true"
           name="{{ $field->get('name') }}_i18n"
           id="{{ $field->get('name') }}_i18n"
           value="{{ get_field_translations($model, $field, true) }}">
@endif
