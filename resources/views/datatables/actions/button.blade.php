@use('KY\AdminPanel\DataTables\Actions\EditAction')
@use('KY\AdminPanel\DataTables\Actions\ShowAction')
{{-- Во встроенной таблице правка и просмотр открываются модалкой на той же странице:
     оба экшена размечаются одним data-modal-open, перехват клика — в компоненте таблицы.
     Ветвимся по классу экшена, а не по политике: у своего экшена может быть та же
     политика, но собственный маршрут — подменять его нельзя. --}}
@php($modalRoute = ($modal ?? false) ? match (true) {
    $action instanceof EditAction => route('adminpanel.'.$dataType->getSlug().'.modal-form', $model->getKey()),
    $action instanceof ShowAction => route('adminpanel.'.$dataType->getSlug().'.modal-show', $model->getKey()),
    default => null,
} : null)
@can($action->getPolicyName(),$model)
    <{{ $action->getTag() }}
        {!! $action->convertAttributesToHtml() !!}
        href="{{ $modalRoute ?? $action->getRoute($dataType,$model) }}"
        title="{{ $action->getTitle() }}"
        @if($modalRoute) data-modal-open="1" @endif
    >
    @if($action->getIcon())
        <x-adminpanel::icon :name="$action->getIcon()"/>
    @endif
    </{{ $action->getTag() }}>
@endcan
