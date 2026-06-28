{{-- Select-фильтр колонки. Привязан к данным таблицы (datatable.blade.php). --}}
<el-select
    v-model="filters['{{ $filter->getName() }}']"
    size="small"
    clearable
    filterable
    @if($filter->isMultiple()) multiple @endif
    placeholder="{{ $field->get('label') }}"
    style="width: 100%"
    @change="onFilter"
>
    @foreach($filter->getOptions() as $key => $label)
        <el-option value="{{ $key }}" label="{{ $label }}"></el-option>
    @endforeach
</el-select>
