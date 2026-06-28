@props(['dataType'])

@php($mount = 'dataTableApp_'.$dataType->getSlug())
@php($columns = $dataType->getColumns())
@php($columnsMeta = $columns->map(fn ($c) => $c->toArray())->values())
@php($order = $dataType->getColumnsOrder())

@php($filterDefaults = [])
@foreach($columns as $c)
    @if($c->hasField() && $c->getField()->hasFilter())
        @php($f = $c->getField()->getFilter())
        @php($filterDefaults[$f->getName()] = (method_exists($f, 'isMultiple') && $f->isMultiple()) ? [] : '')
    @endif
@endforeach

<div id="{{ $mount }}" v-cloak>
    <el-table v-loading="loading" :data="rows" border stripe style="width: 100%" @sort-change="onSortChange">
        @foreach($columns as $column)
            <el-table-column
                prop="{{ $column->get('data') }}"
                :sortable="{{ $column->get('orderable') ? "'custom'" : 'false' }}"
            >
                <template #header>
                    <div class="datatable-th__title">{{ $column->get('title') }}</div>
                    @if($column->hasField() && $column->getField()->hasFilter())
                        @php($field = $column->getField())
                        <div class="datatable-th__filter mt-1" @click.stop>
                            @include($field->getFilter()->getTemplate(), [
                                'dataType' => $dataType,
                                'field' => $field,
                                'filter' => $field->getFilter(),
                            ])
                        </div>
                    @endif
                </template>
                <template #default="{ row }">
                    <span v-html="row['{{ $column->get('data') }}']"></span>
                </template>
            </el-table-column>
        @endforeach
    </el-table>

    <div class="d-flex justify-content-end mt-3">
        <el-pagination
            v-model:current-page="page"
            v-model:page-size="perPage"
            :total="total"
            :page-sizes="[10, 25, 50, 100]"
            layout="total, sizes, prev, pager, next"
            @current-change="fetch"
            @size-change="onSizeChange"
        />
    </div>
</div>

@push('vue')
    <script>
        // Логика серверной таблицы (Yajra DataTables-формат). Шаблон — в blade выше,
        // ячейки/экшены/фильтры — отдельными blade-файлами (редактируемы).
        createVueApp({
            data() {
                return {
                    url: @json(route('adminpanel.'.$dataType->getSlug().'.table')),
                    cols: @json($columnsMeta),
                    rows: [],
                    total: 0,
                    page: 1,
                    perPage: 25,
                    sortIndex: @json(count($order) ? $order[0][0] : null),
                    sortDir: @json(count($order) ? $order[0][1] : 'asc'),
                    loading: false,
                    draw: 0,
                    filters: @json((object) $filterDefaults),
                    relationOptions: {},
                    relationLoading: {},
                    filterTimer: null,
                }
            },
            mounted() {
                window.adminTableReload = () => this.fetch()
                this.fetch()
            },
            methods: {
                buildParams() {
                    const p = {
                        draw: ++this.draw,
                        start: (this.page - 1) * this.perPage,
                        length: this.perPage,
                        'search[value]': '',
                    }
                    this.cols.forEach((c, i) => {
                        p[`columns[${i}][data]`] = c.data
                        p[`columns[${i}][name]`] = c.name
                        p[`columns[${i}][searchable]`] = !!c.searchable
                        p[`columns[${i}][orderable]`] = !!c.orderable
                        p[`columns[${i}][search][value]`] = ''
                    })
                    if (this.sortIndex !== null) {
                        p['order[0][column]'] = this.sortIndex
                        p['order[0][dir]'] = this.sortDir
                    }
                    Object.entries(this.filters).forEach(([k, v]) => {
                        if (v !== '' && v != null && !(Array.isArray(v) && v.length === 0)) p[k] = v
                    })
                    return p
                },
                fetch() {
                    this.loading = true
                    axios.get(this.url, { params: this.buildParams() })
                        .then((r) => {
                            this.rows = r.data.data || []
                            this.total = r.data.recordsFiltered ?? 0
                        })
                        .catch(() => { toastr.error(lang.get('common.whoopsie') || 'Ошибка') })
                        .finally(() => {
                            this.loading = false
                            this.$nextTick(() => {
                                const m = $('#contentBody').data('multilingual')
                                if (m) m.init()
                            })
                        })
                },
                onFilter() {
                    clearTimeout(this.filterTimer)
                    this.filterTimer = setTimeout(() => { this.page = 1; this.fetch() }, 350)
                },
                onSizeChange() {
                    this.page = 1
                    this.fetch()
                },
                onSortChange({ prop, order }) {
                    if (!order) {
                        this.sortIndex = null
                    } else {
                        this.sortIndex = this.cols.findIndex((c) => c.data === prop)
                        this.sortDir = order === 'ascending' ? 'asc' : 'desc'
                    }
                    this.page = 1
                    this.fetch()
                },
                loadRelation(name, field, url, search) {
                    this.relationLoading[name] = true
                    axios.get(url, { params: { search, field, method: 'list', page: 1 } })
                        .then((r) => {
                            this.relationOptions[name] = (r.data.results || []).map((o) => ({ value: String(o.id), label: o.text }))
                        })
                        .finally(() => { this.relationLoading[name] = false })
                },
            },
        }).mount('#{{ $mount }}');
    </script>
@endpush
