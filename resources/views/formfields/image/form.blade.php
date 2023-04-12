<x-adminpanel::forms.image-cropper
    class="{{ $field->get('class') }}"
    col="{{ $field->get('col',12) }}"
    name="{{ $field->get('name') }}"
    image="{{ old($field->get('name'),$field->getValue($model)) }}"
    label="{{ $field->get('label') }}"
    :crop="$field->get('crop') ?? [1920, 1080]"
    canRemove="{{ $element->settings['canRemove'] ?? 0 }}"
    :required="$field->get('required')"
    :disabled="$field->get('disabled')"
    instruction="{{ $field->get('instruction') }}"
>
</x-adminpanel::forms.image-cropper>

