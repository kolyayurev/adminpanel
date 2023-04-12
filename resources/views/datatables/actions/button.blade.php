@can($action->getPolicyName(),$model)
    <{{ $action->getTag() }}
        {!! $action->convertAttributesToHtml() !!}
        href="{{ $action->getRoute($dataType,$model) }}"
        title="{{ $action->getTitle() }}"
    >
    @if($action->getIcon())
        <x-adminpanel::icon :name="$action->getIcon()"/>
    @endif
    </{{ $action->getTag() }}>
@endcan

