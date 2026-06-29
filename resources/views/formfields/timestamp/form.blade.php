@php($vue_instance_name = vue_instance_name($field, $model))
@php($raw = old($field->get('name'), $field->getValue($model)))
@php($value = $raw ? \Carbon\Carbon::parse($raw)->format('d.m.Y H:i') : '')

<div class="mb-3">
    <div class="form-group" id="{{ $field->getId() }}" v-cloak>
        @if (!empty($field->get('label')))
            <label>{{ $field->get('label') }}</label>
        @endif
        @include('adminpanel::multilingual.input-hidden-form')
        {{-- Скрытый input отдаёт значение в прежнем формате d.m.Y H:i (сервер не меняем). --}}
        <input type="hidden" class="is-vue" name="{{ $field->get('name') }}" :value="value"
               data-vue-instance="{{ $vue_instance_name }}"/>
        <el-date-picker v-model="value"
                        type="datetime"
                        format="DD.MM.YYYY HH:mm"
                        value-format="DD.MM.YYYY HH:mm"
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
            mounted() {
                vueFieldInstances['{{ $vue_instance_name }}'] = this
            },
            methods: {
                // Вызывается мостом мультиязычности при переключении локали.
                updateLocaleData(value) {
                    this.value = value ? value : ''
                },
            },
        }).mount('#{{ $field->getId() }}');
    </script>
@endpush
