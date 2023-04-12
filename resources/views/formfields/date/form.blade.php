<div class="mb-3">
    <x-adminpanel::forms.input
        class="datepicker {{ $field->get('class') }}"
        type="date"
        name="{{ $field->get('name') }}"
        value="{{ \Carbon\Carbon::parse(old($field->get('name'), $field->getValue($model)))->format('Y-m-d') }}"
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
    </x-adminpanel::forms.input>
</div>
