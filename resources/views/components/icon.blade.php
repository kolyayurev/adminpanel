@props(['name','lib' => 'bi'])
@if(!empty($name))
<i class="{{$lib}} {{$lib}}-{{$name}}" {{ $attributes }}></i>
@endif

