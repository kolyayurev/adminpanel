@php($filterName = $filter->getName())
@php($mountId = $filterName.'_filter')
@php($isMultiple = $field->isHasMany() || $field->isBelongsToMany())
@php($sessionData = session('datatable.'.$dataType->getSlug().'.'.$field->get('name')))

@php($selected = collect())
@if(!empty($sessionData))
    @if($field->isBelongsTo())
        @php($selected = app($field->get('relatedModel'))->where($field->get('key'), $sessionData)->get())
    @elseif($field->isHasOne())
        @php($selected = app($field->get('relatedModel'))->where($field->get('column'), $sessionData)->get())
    @elseif($field->isHasMany())
        @php($selected = app($field->get('relatedModel'))->whereIn($field->get('column'), (array) $sessionData)->get())
    @endif
@endif

@php($initOptions = $selected->map(fn ($o) => ['value' => (string) $o->{$field->get('key')}, 'label' => $o->{$field->get('displayedField')}])->values())
@php($value = $isMultiple ? $initOptions->pluck('value')->all() : ($initOptions->first()['value'] ?? ''))
@php($relationUrl = route('adminpanel.'.$dataType->getSlug().'.relation'))

<div id="{{ $mountId }}" v-cloak>
    <el-select v-model="value"
               :multiple="{{ $isMultiple ? 'true' : 'false' }}"
               filterable remote :remote-method="remoteMethod" :loading="loading" reserve-keyword clearable
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
                    options: @json($initOptions),
                    loading: false,
                }
            },
            mounted() {
                window.dataTableFilterValues = window.dataTableFilterValues || {}
                window.dataTableFilterValues['{{ $filterName }}'] = () => this.value
                this.load('')
            },
            methods: {
                load(search) {
                    this.loading = true
                    axios.get(@json($relationUrl), {
                        params: { search: search, field: @json($field->get('name')), method: 'list', page: 1 },
                    }).then(r => {
                        let loaded = (r.data.results || []).map(o => ({ value: String(o.id), label: o.text }))
                        let selected = Array.isArray(this.value) ? this.value : [this.value]
                        let merged = this.options.filter(o => selected.includes(o.value))
                        loaded.forEach(o => { if (!merged.some(m => m.value === o.value)) merged.push(o) })
                        this.options = merged
                    }).finally(() => { this.loading = false })
                },
                remoteMethod(search) {
                    this.load(search)
                },
                onChange() {
                    if (window.$table) window.$table.DataTable().ajax.reload()
                },
            },
        }).mount('#{{ $mountId }}');
    </script>
@endpush
