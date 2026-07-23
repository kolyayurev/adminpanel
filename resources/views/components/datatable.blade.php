@props(['dataType', 'filters' => [], 'except' => [], 'modal' => false])

@php($mount = 'dataTableApp_'.$dataType->getSlug().'_'.substr(md5(uniqid('', true)), 0, 8))
@php($columns = $dataType->getColumns()->reject(fn ($c) => in_array($c->get('data'), $except, true))->values())
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
    @if($modal)
        @can('create', $dataType->getModel())
            <div class="d-flex justify-content-end mb-2">
                <el-button type="primary" size="small" @click="openModal(createUrl)">
                    {{ ap_trans('common.buttons.create') }}
                </el-button>
            </div>
        @endcan
    @endif
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

    @if($modal)
        <el-dialog v-model="modalVisible" :close-on-click-modal="false" @closed="onModalClosed">
            <div v-loading="modalLoading" ref="modalBody" v-html="modalHtml"></div>
        </el-dialog>
    @endif
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
                    lockedFilters: @json((object) $filters),
                    relationOptions: {},
                    relationLoading: {},
                    filterTimer: null,
                    modalEnabled: @json((bool) $modal),
                    {{-- Залоченные фильтры уходят в форму создания query-строкой: запись
                         должна попасть в тот же блок, из которого её создают. --}}
                    createUrl: @json($modal ? route('adminpanel.'.$dataType->getSlug().'.modal-form', $filters) : null),
                    modalVisible: false,
                    modalLoading: false,
                    modalHtml: '',
                }
            },
            mounted() {
                window.adminTableReloads = window.adminTableReloads || {}
                window.adminTableReloads['{{ $mount }}'] = () => this.fetch()
                this.fetch()

                // Кнопки строк (Edit/Show) рисуются сервером отдельно от этого компонента
                // (см. BaseDataType::getDataTable()) и размечены data-modal-open только
                // когда таблица встроена как modal — перехватываем клик нативно, Vue-директивы
                // в v-html не компилируются. Берём контейнер по id, а не this.$el: у компонента
                // несколько корневых узлов (кнопка/таблица/диалог), поэтому this.$el —
                // произвольный текстовый узел фрагмента, а не сам div.
                if (this.modalEnabled) {
                    document.getElementById('{{ $mount }}').addEventListener('click', (e) => {
                        const link = e.target.closest('[data-modal-open]')
                        if (!link) return
                        e.preventDefault()
                        this.openModal(link.href)
                    })
                }
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
                    Object.entries(this.lockedFilters).forEach(([k, v]) => {
                        if (v !== '' && v != null && !(Array.isArray(v) && v.length === 0)) p[k] = v
                    })
                    if (this.modalEnabled) p.modal = 1
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
                openModal(url) {
                    this.modalVisible = true
                    this.modalLoading = true
                    this.modalHtml = ''
                    axios.get(url)
                        .then((r) => {
                            this.modalHtml = r.data.template
                            this.$nextTick(() => this.mountModalContent())
                        })
                        .catch(() => {
                            toastr.error(lang.get('common.whoopsie') || 'Ошибка')
                            this.modalVisible = false
                        })
                        .finally(() => { this.modalLoading = false })
                },
                // <script> из v-html не выполняются браузером — пересоздаём их, чтобы поля
                // формы (select/relation/date/...) замонтировали свои Vue-инстансы.
                mountModalContent() {
                    const container = this.$refs.modalBody
                    if (!container) return
                    container.querySelectorAll('script').forEach((old) => {
                        const s = document.createElement('script')
                        s.textContent = old.textContent
                        old.replaceWith(s)
                    })
                    const m = $('#contentBody').data('multilingual')
                    if (m) m.init()
                    const form = container.querySelector('form')
                    if (form) form.addEventListener('submit', (e) => {
                        e.preventDefault()
                        this.submitModalForm(form)
                    })
                },
                submitModalForm(form) {
                    const data = new FormData(form)
                    data.append('modal', '1')
                    this.clearFormErrors(form)
                    axios.post(form.getAttribute('action'), data)
                        .then((r) => {
                            this.modalVisible = false
                            toastr.success(r.data.message || '')
                            const reload = window.adminTableReloads && window.adminTableReloads['{{ $mount }}']
                            if (reload) reload()
                        })
                        .catch((err) => {
                            const res = err.response
                            // 422 — ошибки по полям, модалку не закрываем: пользователь правит
                            // и отправляет снова. Прочие коды (403 от политики и т.п.) — тост
                            // с серверным сообщением, общий текст только если его нет.
                            if (res && res.status === 422) {
                                this.showFormErrors(form, res.data.errors || {})
                            } else {
                                toastr.error((res && res.data && res.data.message) || lang.get('common.whoopsie') || 'Ошибка')
                            }
                        })
                },
                // Раскладываем ошибки под поля той же разметкой, что рисует blade в
                // полноэкранной форме (.is-invalid на контроле + .invalid-feedback рядом).
                showFormErrors(form, errors) {
                    Object.entries(errors).forEach(([name, messages]) => {
                        const message = messages[0]
                        const input = form.querySelector(`[name="${name}"], [name="${name}[]"]`)
                        if (!input) {
                            // Поля нет в разметке (например, вложенный ключ перевода) —
                            // иначе ошибка потерялась бы совсем.
                            toastr.error(message)
                            return
                        }
                        input.classList.add('is-invalid')
                        const hint = document.createElement('span')
                        hint.className = 'invalid-feedback d-block'
                        hint.dataset.modalError = '1'
                        const strong = document.createElement('strong')
                        strong.textContent = message
                        hint.appendChild(strong)
                        input.insertAdjacentElement('afterend', hint)
                    })
                },
                clearFormErrors(form) {
                    form.querySelectorAll('[data-modal-error]').forEach((el) => el.remove())
                    form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'))
                },
                // Размонтируем Vue-инстансы полей, оставшиеся внутри модалки (реестр —
                // window.__vueApps, см. createVueApp в resources/js/app.js), иначе при
                // повторном открытии накапливаются мёртвые инстансы на старых DOM-узлах.
                onModalClosed() {
                    const container = this.$refs.modalBody
                    if (container && window.__vueApps) {
                        Object.keys(window.__vueApps).forEach((selector) => {
                            const el = document.querySelector(selector)
                            if (el && container.contains(el)) {
                                window.__vueApps[selector].unmount()
                                delete window.__vueApps[selector]
                            }
                        })
                    }
                    this.modalHtml = ''
                },
            },
        }).mount('#{{ $mount }}');
    </script>
@endpush
