@if (is_field_translatable($model, $field) && $field->isMultilingual())
    (<span class="language-label js-language-label"></span>)
    <input type="hidden"
           data-i18n="true"
           name="{{ $field->get('name') }}_i18n"
           id="{{ $field->get('name') }}_i18n"
           @if(!empty(session()->getOldInput($field->get('name').'_i18n') && is_null($model->getKey())))
             value="{{ session()->getOldInput($field->get('name').'_i18n') }}"
           @else
             value="{{ get_field_translations($model, $field) }}"
           @endif>
@endif
