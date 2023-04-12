<div class="mb-3">
    <x-adminpanel::forms.textarea
        class="{{ $field->get('class') }}"
        rows="{{ $field->get('rows',5) }}"
        name="{{ $field->get('name') }}"
        label="{{ $field->get('label') }}"
        :required="$field->get('required')"
        :disabled="$field->get('disabled')"
        :readonly="$field->get('readonly')"
        placeholder="{{ $field->get('placeholder') }}"
        :instruction="$field->get('instruction')"
    >
        <x-slot:multilangual>
            @include('adminpanel::multilingual.input-hidden-form')
        </x-slot:multilangual>
        {{ old($field->get('name'),$field->getValue($model)) }}
    </x-adminpanel::forms.textarea>
</div>

