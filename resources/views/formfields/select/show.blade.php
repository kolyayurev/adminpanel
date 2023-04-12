<div class="form-group mb-2 border-bottom">
    <h4>{{ $field->get('label') }}</h4>
{{--    @include('adminpanel::multilingual.input-hidden-show')--}}
    @if ($field->hasOptions())
        <p>{!! $field->isMultiple()?implode(',',$field->getValue($model)):$field->getOption($field->getValue($model)) !!}</p>
    @endif
</div>
