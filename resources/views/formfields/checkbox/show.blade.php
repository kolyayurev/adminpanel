<div class="form-group mb-2 border-bottom">
    <h4>{{ $field->get('label') }}</h4>
@if($field->getValue($model))
    <span class="label label-info">{{ trans($field->get('textOn')) }}</span>
@else
    <span class="label label-primary">{{  trans($field->get('textOff')) }}</span>
@endif
</div>
