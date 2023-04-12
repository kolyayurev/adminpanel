@if($field->getValue($model))
    <span class="badge text-bg-primary">{{ trans($field->get('textOn')) }}</span>
@else
    <span class="badge text-bg-secondary">{{  trans($field->get('textOff')) }}</span>
@endif
