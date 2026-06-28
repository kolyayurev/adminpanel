@if($field->get('relatedModel') && $field->get('type'))
    <div class="mb-3">
        @if(class_exists($field->get('relatedModel')))

            @if($field->isBelongsTo())

                @php
                    $relationModel = app($field->get('relatedModel'));
                    $query = $relationModel::where($field->get('key'),$model->{$field->get('column')})->first();
                @endphp
                @if($field->get('disabled'))
                    @php
                        $value =  $query?($query->getKey()??null):null;
                        $label =  $query->{$field->get('displayedField')}??null;
                    @endphp
                    @if($value)
                        <a href="{{ route('adminpanel.'.$field->get('table').'.edit',$query->getKey()) }}"
                           target="_blank">@lang('adminpanel::common.buttons.show')</a>
                    @endif

                    <x-adminpanel::forms.input type="hidden" name="{{ $field->get('column') }}" value="{{ $value }}"/>
                    <x-adminpanel::forms.input disabled value="{{ $label }}"/>
                @else
                    @php
                        $vue_instance_name = vue_instance_name($field, $model);
                        $relationUrl = $field->get('pageTypeSlug')
                            ? route('adminpanel.settings.relation', ['name' => $field->get('pageTypeSlug')])
                            : route('adminpanel.'.$dataType->getSlug().'.relation');
                        $relationMethod = ! is_null($model->getKey()) ? 'update' : 'create';
                        $currentVal = old($field->get('column'), $model->{$field->get('column')});
                        $currentRow = (! is_null($currentVal) && $currentVal !== '')
                            ? $relationModel::where($field->get('key'), $currentVal)->first()
                            : null;
                        $selectedOption = $currentRow
                            ? ['value' => (string) $currentRow->{$field->get('key')}, 'label' => $currentRow->{$field->get('displayedField')}]
                            : null;
                        $initOptions = [];
                        if (! $field->get('required')) {
                            $initOptions[] = ['value' => '', 'label' => __('adminpanel::common.none')];
                        }
                        if ($selectedOption) {
                            $initOptions[] = $selectedOption;
                        }
                    @endphp
                    <div class="form-group" id="{{ $field->getId() }}" v-cloak>
                        <label>{{ $field->get('label') }}</label>
                        <input type="hidden" name="{{ $field->get('column') }}" :value="value"
                               data-vue-instance="{{ $vue_instance_name }}"/>
                        <el-select v-model="value"
                                   filterable remote :remote-method="remoteMethod" :loading="loading"
                                   reserve-keyword
                                   @if (!$field->get('required')) clearable @endif
                                   @if ($field->get('disabled')) disabled @endif
                                   class="w-100" style="width: 100%"
                                   placeholder="{{ $field->get('placeholder') }}">
                            <el-option v-for="opt in options" :key="opt.value" :label="opt.label" :value="opt.value"/>
                        </el-select>
                        @error($field->get('column'))
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        @if (!empty($field->get('instruction')))
                            <x-adminpanel::instruction :text="$field->get('instruction')"></x-adminpanel::instruction>
                        @endif
                    </div>
                    @push('vue')
                        <script>
                            createVueApp({
                                data() {
                                    return {
                                        value: @json($selectedOption ? $selectedOption['value'] : ''),
                                        options: @json($initOptions),
                                        selectedOption: @json($selectedOption),
                                        loading: false,
                                    }
                                },
                                mounted() {
                                    vueFieldInstances['{{ $vue_instance_name }}'] = this
                                    this.load('')
                                },
                                methods: {
                                    // Подгрузка вариантов с сервера (тот же роут/контракт, что был у select2-ajax).
                                    load(search) {
                                        this.loading = true
                                        axios.get(@json($relationUrl), {
                                            params: {
                                                search: search,
                                                field: @json($field->get('name')),
                                                method: @json($relationMethod),
                                                id: @json(! is_null($model->getKey()) ? $model->getKey() : ''),
                                                page: 1,
                                            },
                                        }).then(r => {
                                            let opts = (r.data.results || []).map(o => ({ value: String(o.id), label: o.text }))
                                            // не терять выбранный вариант, если его нет в текущей странице
                                            if (this.selectedOption && !opts.some(o => o.value === this.selectedOption.value)) {
                                                opts.unshift(this.selectedOption)
                                            }
                                            this.options = opts
                                        }).finally(() => { this.loading = false })
                                    },
                                    remoteMethod(search) {
                                        this.load(search)
                                    },
                                },
                            }).mount('#{{ $field->getId() }}');
                        </script>
                    @endpush
                @endif

            @elseif($field->isHasOne())

                @php
                    $relationshipData = (isset($data)) ? $data : $model;

                    $relationModel = app($field->get('relatedModel'));
                    $query = $relationModel::where($field->get('column'), '=', $relationshipData->{$field->get('key')})->first();

                @endphp
                <div class="form-group">
                    <label>{{ $field->get('label') }}</label>
                    @if(isset($query))
                        <p><a class="" href="{{ route('adminpanel.'.$dataType->getSlug().'.edit',$query->getKey()) }}" target="_blank">{{ $query->{$field->get('displayedField')} }}</a></p>
                    @else
                        <p>{{ __('adminpanel::common.no_results') }}</p>
                    @endif
                </div>

            @elseif($field->isHasMany())

                @php
                    $relationModel = app($field->get('relatedModel'));
                    $query = $relationModel::where($field->get('column'), '=', $model->{$field->get('key')})->get();
                @endphp
                <div class="form-group">
                    <label>{{ $field->get('label') }}</label>
                    @if(isset($query) && $query->count())
                        <ul>
                            @foreach($query as $query_res)
                                <li>
                                    <a href="{{ route('adminpanel.'.$field->get('table').'.show',$query_res->{$field->get('key')}) }}"
                                       target="_blank"> {{ $query_res->{$field->get('displayedField')} }}</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ __('adminpanel::common.no_results') }}</p>
                    @endif
                </div>

            @elseif($field->isBelongsToMany())

                @php
                    $selected_values = isset($model) ? $model->belongsToMany($field->get('relatedModel'), $field->get('pivotTable'), $field->get('foreignPivotKey'), $field->get('relatedPivotKey'), $field->get('parentKey'), $field->get('key'))->get()->map(function ($item, $key) use ($field) {
                        return $item->{$field->get('key')};
                    })->all() : array();
                    $relationshipOptions = app($field->get('relatedModel'))->all();
                    $selected_values = old($field->get('name'), $selected_values);
                    $relationshipOptions = $relationshipOptions->filter(function ($option, $key) use ($field,$selected_values) {
                        return in_array($option->{$field->get('key')}, $selected_values);
                    });
                @endphp
                @if($field->get('readonly'))
                    <div class="form-group">
                        <label>{{ $field->get('label') }}</label>
                        @if ($relationshipOptions->count())
                            <ul style="padding-left: 0">
                                @foreach ($relationshipOptions as $relationshipOption)
                                    <li class="form-control" style="margin-bottom: 5px">
                                        <input type="hidden" name="{{ $field->get('name') }}[]"
                                               value="{!! printInt($relationshipOption->{$field->get('key')}) !!}">
                                        <a href="{{ route('adminpanel.'.$field->get('table').'.show',$relationshipOption->{$field->get('key')}) }}"
                                           target="_blank"> {{ $relationshipOption->{$field->get('displayedField')} }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="form-control">{{ __('adminpanel::common.no_results') }}</p>
                        @endif
                    </div>
                @else
                    @php
                        $vue_instance_name = vue_instance_name($field, $model);
                        $relationUrl = route('adminpanel.'.$dataType->getSlug().'.relation');
                        $relationMethod = ! is_null($model->getKey()) ? 'update' : 'create';
                        $initSelected = $relationshipOptions->map(fn ($o) => ['value' => (string) $o->{$field->get('key')}, 'label' => $o->{$field->get('displayedField')}])->values();
                        $taggable = (bool) $field->get('taggable');
                        $tagRoute = $taggable ? route('adminpanel.'.$field->get('table').'.store') : '';
                        $tagLabel = $taggable ? $field->get('displayedField') : '';
                        $tagError = $taggable ? ap_trans('content-type.error_tagging') : '';
                    @endphp
                    <div class="form-group" id="{{ $field->getId() }}" v-cloak>
                        <label>{{ $field->get('label') }}</label>
                        <input v-for="v in value" :key="v" type="hidden" name="{{ $field->get('name') }}[]" :value="v"
                               data-vue-instance="{{ $vue_instance_name }}"/>
                        <el-select v-model="value"
                                   multiple filterable remote :remote-method="remoteMethod" :loading="loading"
                                   reserve-keyword
                                   @if ($taggable) allow-create default-first-option @endif
                                   @if (!$field->get('required')) clearable @endif
                                   @if ($field->get('disabled')) disabled @endif
                                   class="w-100" style="width: 100%"
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
                    @push('vue')
                        <script>
                            createVueApp({
                                data() {
                                    return {
                                        value: @json($initSelected->pluck('value')->all()),
                                        options: @json($initSelected),
                                        loading: false,
                                        taggable: @json($taggable),
                                        tagRoute: @json($tagRoute),
                                        tagLabel: @json($tagLabel),
                                        tagError: @json($tagError),
                                    }
                                },
                                watch: {
                                    // taggable: новый ввод (allow-create) приходит как значение === тексту;
                                    // создаём запись на сервере и подменяем на реальный id.
                                    value(newVal, oldVal) {
                                        if (!this.taggable) return
                                        newVal.filter(v => !oldVal.includes(v) && !this.options.some(o => o.value === v))
                                            .forEach(text => this.createTag(text))
                                    },
                                },
                                mounted() {
                                    vueFieldInstances['{{ $vue_instance_name }}'] = this
                                    this.load('')
                                },
                                methods: {
                                    load(search) {
                                        this.loading = true
                                        axios.get(@json($relationUrl), {
                                            params: {
                                                search: search,
                                                field: @json($field->get('name')),
                                                method: @json($relationMethod),
                                                id: @json(! is_null($model->getKey()) ? $model->getKey() : ''),
                                                page: 1,
                                            },
                                        }).then(r => {
                                            let loaded = (r.data.results || []).map(o => ({ value: String(o.id), label: o.text }))
                                            // сохранить уже выбранные варианты, чтобы чипы не теряли подписи
                                            let merged = this.options.filter(o => this.value.includes(o.value))
                                            loaded.forEach(o => { if (!merged.some(m => m.value === o.value)) merged.push(o) })
                                            this.options = merged
                                        }).finally(() => { this.loading = false })
                                    },
                                    remoteMethod(search) {
                                        this.load(search)
                                    },
                                    createTag(text) {
                                        axios.post(this.tagRoute, { [this.tagLabel]: text, _tagging: true })
                                            .then(r => {
                                                let id = String(r.data.data.id)
                                                this.options.push({ value: id, label: text })
                                                this.value = this.value.map(x => x === text ? id : x)
                                            })
                                            .catch(() => {
                                                toastr.error(this.tagError)
                                                this.value = this.value.filter(x => x !== text)
                                            })
                                    },
                                },
                            }).mount('#{{ $field->getId() }}');
                        </script>
                    @endpush
                @endif
            @endif
        @else
            cannot make relationship because {{ $field->get('relatedModel') }} does not exist.
        @endif
    </div>
@endif
