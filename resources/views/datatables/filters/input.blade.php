{{-- Текстовый фильтр колонки. Привязан к данным таблицы (datatable.blade.php). --}}
<el-input
    v-model="filters['{{ $filter->getName() }}']"
    size="small"
    clearable
    placeholder="{{ $field->get('label') }}"
    @input="onFilter"
    @clear="onFilter"
/>
