@if ($field->hasFormat() && !is_null($field->getValue($model)) )
    {{ \Carbon\Carbon::parse($field->getValue($model))->formatLocalized($field->get('format')) }}
@else
    {{ $field->getValue($model) }}
@endif
