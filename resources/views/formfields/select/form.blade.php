@php($vue_instance_name = vue_instance_name($field, $model))
@php($isMultiple = $field->isMultiple())
@php($selectedRaw = old($field->get('name'), $field->getValue($model)))
@php($options = collect($field->getOptions())->map(fn ($label, $key) => ['value' => (string) $key, 'label' => $label])->values())
@php($value = $isMultiple ? array_map('strval', (array) ($selectedRaw ?: [])) : (is_null($selectedRaw) ? '' : (string) $selectedRaw))

<div class="mb-3">
    <div class="form-group" id="{{ $field->getId() }}" v-cloak>
        @if (!empty($field->get('label')))
            <label>{{ $field->get('label') }}</label>
        @endif
        {{ $field->getAfterLabel() }}

        {{-- Скрытые input'ы зеркалят значение el-select для нативного сабмита формы --}}
        @if ($isMultiple)
            <input v-for="v in value" :key="v" type="hidden" name="{{ $field->get('name') }}[]" :value="v"
                   data-vue-instance="{{ $vue_instance_name }}"/>
        @else
            <input type="hidden" name="{{ $field->get('name') }}" :value="value"
                   data-vue-instance="{{ $vue_instance_name }}"/>
        @endif

        <el-select v-model="value"
                   :multiple="{{ $isMultiple ? 'true' : 'false' }}"
                   filterable
                   @if (!$field->get('required')) clearable @endif
                   @if ($field->get('disabled')) disabled @endif
                   class="w-100"
                   style="width: 100%"
                   placeholder="{{ $field->get('placeholder') }}">
            <el-option v-for="opt in options" :key="opt.value" :label="opt.label" :value="opt.value"/>
        </el-select>

        @error($field->get('name'))
        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
        @if (!empty($field->get('instruction')))
            <x-adminpanel::instruction :text="$field->get('instruction')"></x-adminpanel::instruction>
        @endif
    </div>
</div>

@push('vue')
    <script>
        createVueApp({
            data() {
                return {
                    value: @json($value),
                    options: @json($options),
                }
            },
            mounted() {
                vueFieldInstances['{{ $vue_instance_name }}'] = this
            },
        }).mount('#{{ $field->getId() }}');
    </script>
@endpush
