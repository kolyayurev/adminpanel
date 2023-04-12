@if($field->hasRoute())
    <a href="{{ $field->buildRoute($model) }}" class="text-decoration-none" target="_blank"><x-adminpanel::icon name="link"/> {{ $field->getValue($model) }}</a>
@else
    {{ $field->getValue($model) }}
@endif
