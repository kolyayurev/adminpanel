@php($asModalForm = ($modal ?? false) && $action->getPolicyName() === 'update')
@can($action->getPolicyName(),$model)
    <{{ $action->getTag() }}
        {!! $action->convertAttributesToHtml() !!}
        href="{{ $asModalForm ? route('adminpanel.'.$dataType->getSlug().'.modal-form', $model->getKey()) : $action->getRoute($dataType,$model) }}"
        title="{{ $action->getTitle() }}"
        @if($asModalForm) data-modal-form="1" @endif
    >
    @if($action->getIcon())
        <x-adminpanel::icon :name="$action->getIcon()"/>
    @endif
    </{{ $action->getTag() }}>
@endcan

