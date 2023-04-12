<div class="mb-3">
    <x-adminpanel::forms.textarea
        id="{{ $field->getId() }}"
        class="text-editor {{ $field->get('class') }}"
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

@push('end-body-scripts')
    <script>
        $(document).ready(function() {
            var additionalConfig = {
                selector: 'textarea.text-editor[name="{{ $field->get('name') }}"]',
            }

            $.extend(additionalConfig, {!! printObject($field->getOptions()) !!})

            tinymce.init(window.apTinyMCE.getConfig(additionalConfig));
        });
    </script>
@endpush
