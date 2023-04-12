@props(['dataType'])

<div class="table-responsive">
    <table class="table datatable table-striped table-condensed w-100"
           data-component="dataTable"
           data-filter="{{ json_encode($dataType->getColumnNames()) }}"
        >
        <thead>
        <tr>
            @foreach($dataType->getColumns() as $column)
                <th >{{ $column->get('title') }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($dataType->getColumns() as $column)
                <th>
                    @if($column->hasField() && $column->getField()->hasFilter())
                        @php($field = $column->getField())
                        @include($field->getFilter()->getTemplate(), [
                            'dataType' => $dataType,
                            'field' => $field,
                            'filter' => $field->getFilter()
                          ])
                    @else
                        {{ $column->get('title') }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
