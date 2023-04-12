<input
    type="search"
    class="form-control"
    id="{{ $filter->getName() }}"
    data-orderable="{{ $filter->get('orderable') }}"
    placeholder="{{ $filter->getPlaceholder() }}"
    data-action="dataTableFilterInput"
    {!! $filter->convertAttributesToHtml() !!}
    value="{{  session('datatable.'.$dataType->getSlug().'.'.$field->get('name')) }}"
>
