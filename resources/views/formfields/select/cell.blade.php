@if ($field->hasOptions())
{{--@include('adminpanel::multilingual.input-hidden-cell')--}}
{!! $field->isMultiple()?implode(',',$field->getValue($model)):$field->getOption($field->getValue($model)) !!}
@endif
