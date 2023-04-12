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
        data-alias-source="{{ $field->get('source') }}"
        :data-alias-forceupdate="$field->get('forceUpdate')"
        :data-alias-change-on-typing="$field->get('changeOnTyping')"
        :inputGroup="true"
    >
        <x-slot:after >
            <button class="btn btn-outline-primary" type="button"><x-adminpanel::icon name="arrow-clockwise"></x-adminpanel::icon></button>
        </x-slot:after>
    </x-adminpanel::forms.input>
</div>


