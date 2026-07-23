@php
    $fields = $dataType->getFormFields('show');
@endphp
@foreach($fields as $field)
    {!! $field->render($dataType,$model,'show') !!}
@endforeach
