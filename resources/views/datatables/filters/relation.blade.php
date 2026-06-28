{{-- Relation-фильтр колонки (remote). Привязан к данным таблицы (datatable.blade.php). --}}
@php($relationUrl = route('adminpanel.'.$dataType->getSlug().'.relation'))
@php($isMultiple = $field->isHasMany() || $field->isBelongsToMany())
@php($filterName = $filter->getName())

<el-select
    v-model="filters['{{ $filterName }}']"
    size="small"
    clearable
    filterable
    remote
    @if($isMultiple) multiple @endif
    :remote-method="(q) => loadRelation('{{ $filterName }}', '{{ $field->get('name') }}', '{{ $relationUrl }}', q)"
    :loading="!!relationLoading['{{ $filterName }}']"
    placeholder="{{ $field->get('label') }}"
    style="width: 100%"
    @visible-change="(v) => v && loadRelation('{{ $filterName }}', '{{ $field->get('name') }}', '{{ $relationUrl }}', '')"
    @change="onFilter"
>
    <el-option
        v-for="opt in (relationOptions['{{ $filterName }}'] || [])"
        :key="opt.value"
        :label="opt.label"
        :value="opt.value"
    ></el-option>
</el-select>
