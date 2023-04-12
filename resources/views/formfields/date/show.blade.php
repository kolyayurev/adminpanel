<div class="form-group mb-2 border-bottom">
    <h4>{{ $field->get('label') }}</h4>
    @if ($field->hasFormat() && !is_null($field->getValue($model)) )
        {{ \Carbon\Carbon::parse($field->getValue($model))->formatLocalized($field->get('format')) }}
    @else
        {{ $field->getValue($model) }}
    @endif
</div>
