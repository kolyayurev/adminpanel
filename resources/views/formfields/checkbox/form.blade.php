@if($field->getValue($model) || old($field->get('name')))
    <?php $checked = old($field->get('name'), $field->getValue($model)); ?>
@else
    <?php $checked = $field->get('default'); ?>
@endif
<div class="mb-3">
    <x-adminpanel::forms.checkbox
        type="checkbox"
        class="{{ $field->get('class') }}"
        name="{{ $field->get('name') }}"
        label="{{ $field->get('label') }}"
        :checked="(bool)$checked"
        :required="$field->get('required')"
        :disabled="$field->get('disabled')"
        placeholder="{{ $field->get('placeholder') }}"
        :instruction="$field->get('instruction')"
    />
</div>

