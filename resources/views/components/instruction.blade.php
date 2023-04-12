@props(['text'])
@if(!empty($text))
    <small class="text-muted ml-1"><em>{!! $text !!}</em></small>
@endif

