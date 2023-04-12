{{--@include('adminpanel::multilingual.input-hidden-cell')--}}
@php
    $value = $field->getValue($model);
    $list = !is_array($value)? json_decode($value) : $value;
@endphp
@if (!empty($list))
    <ul>
        @foreach($list as $item)
            <li>{{ $item->text }}</li>
        @endforeach
    </ul>
@endif
