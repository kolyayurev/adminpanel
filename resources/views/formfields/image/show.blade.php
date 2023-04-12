<img src="@if( !filter_var($field->getValue($model), FILTER_VALIDATE_URL)){{ APMedia::getUrl($field->getValue($model)) }}@else{{ $field->getValue($model) }}@endif" style="width:100px">
