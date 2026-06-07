@if ($field->hasFormat() && !is_null($field->getValue($model)) )
    {{ $field->getFormattedValue($model) }}
@else
    {{ $field->getValue($model) }}
@endif
