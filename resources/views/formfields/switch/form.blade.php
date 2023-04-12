@if($field->getValue($model) || old($field->get('name')))
    <?php $checked = old($field->get('name'), $field->getValue($model)); ?>
@else
    <?php $checked = $field->get('default'); ?>
@endif
<div class="mb-3">
    <label for="{{ $field->getId() }}">{{ $field->get('label') }}</label>
    <x-adminpanel::forms.switch
        id="{{ $field->getId() }}"
        class="{{ $field->get('class') }}"
        name="{{ $field->get('name') }}"
        :checked="(bool)$checked"
        :required="$field->get('required')"
        :disabled="$field->get('disabled')"
        instruction="{{ $field->get('instruction') }}"
    >
    </x-adminpanel::forms.switch>
</div>

