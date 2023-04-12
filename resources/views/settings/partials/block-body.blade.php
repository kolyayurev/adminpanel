@foreach ($fields as $key => $field)
    @php
        $model = $settings->where('key',$field->get('name'))->first();
    @endphp
{{--    TODO:: сортировать по порядку в $grid--}}
    @if($field->get('name') === $block || $block === '*')
        {!! $field->render($dataType,$model) !!}
{{--        @php($fields->forget($key))--}}
    @endif
@endforeach
