<x-adminpanel::forms.select
    class="{{ $field->get('class') }}"
    label="{{ $field->get('label') }}"
    name="{{ $field->get('name') }}{{ $field->isMultiple()?'[]':'' }}"
    :selected="old($field->get('name'),$field->getValue($model))"
    :options="$field->getOptions()"
    :multiple="$field->isMultiple()"
    :instruction="$field->get('instruction')"
    :required="$field->get('required')"
    :disabled="$field->get('disabled')"
    data-minimum-results-for-search="20"
>
    <x-slot:after-label>
        {{ $field->getAfterLabel() }}
    </x-slot:after-label>
    @if(!$field->get('required') && !$field->isMultiple())
        <option value="">{{__('adminpanel::common.none')}}</option>
    @endif
</x-adminpanel::forms.select>
