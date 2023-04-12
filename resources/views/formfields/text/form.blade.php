<div class="mb-3">
    <x-adminpanel::forms.input
        class="{{ $field->get('class') }}"
        type="{{ $field->get('type','text') }}"
        name="{{ $field->get('name') }}"
        value="{{ old($field->get('name'),$field->getValue($model)) }}"
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
        <x-slot:after-label>
            {{ $field->getAfterLabel() }}
        </x-slot:after-label>
    </x-adminpanel::forms.input>
</div>


