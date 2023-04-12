@extends('adminpanel::layouts.master')

@section('title', $dataType->getTitleForList())

@section('page-header')
<h1>
    <x-adminpanel::icon :name="$dataType->getIcon()"></x-adminpanel::icon>
    {{ $dataType->getTitleForList() }}
</h1>
@include('adminpanel::multilingual.language-selector')
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.datatype.'.$dataType->getSlug().'.index',$dataType) }}
@endsection

@section('mode','list')

@section('content')
    <div class="btn-container mb-2">
        @can('create',$dataType->getModel())
        <a href="{{ route('adminpanel.'.$dataType->getSlug().'.create') }}" class="btn btn-primary">{{ ap_trans('common.buttons.create') }}</a>
        @endcan

        @if($dataType->showOrderPage())
        <a href="{{ route('adminpanel.'.$dataType->getSlug().'.order') }}" class="btn btn-outline-primary">{{ ap_trans('common.buttons.order') }}</a>
        @endif
        @stack('top-left-buttons')
        <div class="float-end">
            @stack('top-right-buttons')
        </div>
    </div>
    @yield('before-datatable')
    <x-adminpanel::datatable :dataType="$dataType"/>
    @yield('after-datatable')
@endsection

@push('before-app-scripts')
    <script>
        dataTablesOptions = {!! printObject($dataType->getDataTablesOptions()) !!};
    </script>
@endpush
