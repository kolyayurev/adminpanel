@php($filterName = $filter->getName())
@php($mountId = $filterName.'_filter')
@php($isMultiple = $filter->isMultiple())
@php($sessionValue = session('datatable.'.$dataType->getSlug().'.'.$field->get('name')))
@php($options = collect($filter->getOptions())->map(fn ($label, $key) => ['value' => (string) $key, 'label' => $label])->values())
@php($value = $isMultiple ? array_map('strval', (array) ($sessionValue ?: [])) : (is_null($sessionValue) ? '' : (string) $sessionValue))

<div id="{{ $mountId }}" v-cloak>
    <el-select v-model="value"
               :multiple="{{ $isMultiple ? 'true' : 'false' }}"
               filterable clearable
               class="w-100" style="width: 100%"
               placeholder="{{ $field->get('label') }}"
               @change="onChange">
        <el-option v-for="opt in options" :key="opt.value" :label="opt.label" :value="opt.value"/>
    </el-select>
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
                window.dataTableFilterValues = window.dataTableFilterValues || {}
                window.dataTableFilterValues['{{ $filterName }}'] = () => this.value
            },
            methods: {
                onChange() {
                    if (window.$table) window.$table.DataTable().ajax.reload()
                },
            },
        }).mount('#{{ $mountId }}');
    </script>
@endpush
