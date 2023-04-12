@php($options = [])

@php($sessionData = session('datatable.'.$dataType->getSlug().'.'.$field->get('name')))

@if(!empty($sessionData))
    @if($field->isBelongsTo())
        @php($options = app($field->get('relatedModel'))->where($field->get('key'), $sessionData)->get())
    @elseif($field->isHasOne())
        @php($options = app($field->get('relatedModel'))->where($field->get('column'), $sessionData)->get())
    @elseif($field->isHasMany())
        @php($options = app($field->get('relatedModel'))->whereIn($field->get('column'), (array) $sessionData)->get())
    @endif
@endif

<select class="form-control select2-ajax"
        id="{{ $filter->getName() }}"
        data-action="dataTableFilterSelect"
        @if($field->isHasMany() || $field->isBelongsToMany()) multiple @endif
        data-component="relation"
        data-datatype="{{ $dataType->getSlug() }}"
        data-field="{{ $field->get('name') }}"
        data-method="list"
        data-allow-clear="true"
        data-minimum-results-for-search="10"
        data-placeholder="{{ $field->get('label') }}"
        {!! $filter->convertAttributesToHtml() !!}
    >
        @foreach($options as $option)
            <option value="{{ $option->{$field->get('key')} }}"
                    selected>{{ $option->{$field->get('displayedField')} }}</option>
        @endforeach
</select>
