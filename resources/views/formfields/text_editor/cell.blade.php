@include('adminpanel::multilingual.input-hidden-cell')
<div>{!! Str::limit( $field->getValue($model),50) !!}</div>
