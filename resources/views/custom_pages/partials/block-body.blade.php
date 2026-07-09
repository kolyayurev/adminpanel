@foreach ($fields as $widget)
    @if($widget->getSlug() === $block || $block === '*')
        @include('adminpanel::widgets.'.$widget->getType().'.index', ['widget' => $widget, 'customPage' => $customPage])
    @endif
@endforeach
