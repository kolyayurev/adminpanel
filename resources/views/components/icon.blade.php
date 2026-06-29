@props(['name','lib' => 'bi'])
@if(!empty($name))
<i {{ $attributes->merge(['class' => $lib.' '.$lib.'-'.$name]) }}></i>
@endif

