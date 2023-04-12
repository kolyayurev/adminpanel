@php
    $value = $field->getValue($model);
    $list = !is_array($value)? json_decode($value) : $value;
//    dd($value,$list);
@endphp
<div class="form-group mb-2 border-bottom">
    <h4>{{ $field->get('label') }}</h4>
{{--    TODO: remake multi--}}
{{--    @include('adminpanel::multilingual.input-hidden-show')--}}
@if (!empty($list))
<ul>
    @foreach($list as $item)
        <li>{{ $item->text }}</li>
    @endforeach
</ul>
@endif
</div>
