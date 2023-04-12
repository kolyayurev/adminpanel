<div class="mb-3">
    <x-adminpanel::forms.input
        type="password"
        name="{{ $field->get('name') }}"
        class="rounded {{ $field->get('class') }}"
        value=""
        label="{{ $field->get('label') }}"
        :required="$field->get('required')"
        :disabled="$field->get('disabled')"
        :readonly="$field->get('readonly')"
        placeholder="{{ $field->get('placeholder') }}"
        :instruction="$field->get('instruction')"
        :inputGroup="true"
        spellcheck="false"
        autocorrect="off"
        autocapitalize="off"
    >
        <x-slot:after >
            <button type="button" class="d-none password-toggle"></button>
        </x-slot:after>
    </x-adminpanel::forms.input>
</div>
