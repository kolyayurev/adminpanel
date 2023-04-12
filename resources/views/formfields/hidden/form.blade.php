<x-adminpanel::forms.input
    type="hidden"
    name="{{ $field->get('name') }}"
    value="{{ old($field->get('name'),$field->getValue($model)) }}"
/>
