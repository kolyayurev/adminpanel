<select
        @class([
            'form-control',
            'select2'.($filter->isAjax()?'-ajax':'')
        ])
        id="{{ $filter->getName() }}"
        data-width="100%"
        data-action="dataTableFilterSelect"
        data-allow-clear="true"
        data-placeholder="{{ $field->get('label') }}"
        data-minimum-results-for-search="10"
        @if($filter->isMultiple()) multiple @endif
        {!! $filter->convertAttributesToHtml() !!}
    >
    @foreach ($filter->getOptions() as $key => $option)
    <option value="{{ $key }}"
        @selected(session('datatable.'.$dataType->getSlug().'.'.$field->get('name')) === $key)
    >{{ $option }}</option>
    @endforeach
</select>
