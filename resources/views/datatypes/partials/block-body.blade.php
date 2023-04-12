@foreach ($fields as $key => $field)
    {{--    TODO:: сортировать по порядку в $grid--}}
    @if($field->get('name') === $block || $block === '*')
        {!! $field->render($dataType,$model) !!}
        @php($fields->forget($key))
    @endif
@endforeach
