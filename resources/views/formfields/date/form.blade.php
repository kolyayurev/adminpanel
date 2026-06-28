@php($vue_instance_name = vue_instance_name($field, $model))
@php($raw = old($field->get('name'), $field->getValue($model)))
@php($value = $raw ? \Carbon\Carbon::parse($raw)->format('Y-m-d') : '')

<div class="mb-3">
    <div class="form-group" id="{{ $field->getId() }}" v-cloak>
        @if (!empty($field->get('label')))
            <label>{{ $field->get('label') }}</label>
        @endif
        @include('adminpanel::multilingual.input-hidden-form')
        {{-- Скрытый input отдаёт значение в прежнем формате Y-m-d (сервер не меняем). --}}
        <input type="hidden" name="{{ $field->get('name') }}" :value="value"
               data-vue-instance="{{ $vue_instance_name }}"/>
        <el-date-picker v-model="value"
                        type="date"
                        value-format="YYYY-MM-DD"
                        @if (!$field->get('required')) clearable @else :clearable="false" @endif
                        @if ($field->get('disabled')) disabled @endif
                        @if ($field->get('readonly')) readonly @endif
                        class="w-100" style="width: 100%"
                        placeholder="{{ $field->get('placeholder') }}"/>
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
                }
            },
            created() {
                @if (is_field_translatable($model, $field))
                    this.updateLocaleData(this.value)
                @endif
            },
            mounted() {
                vueFieldInstances['{{ $vue_instance_name }}'] = this
            },
            methods: {
                @if (is_field_translatable($model, $field))
                updateLocaleData(value) {
                    this.value = value ? value : ''
                }
                @endif
            },
        }).mount('#{{ $field->getId() }}');
    </script>
@endpush
