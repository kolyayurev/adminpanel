@extends('adminpanel::layouts.master')

@section('title',ap_trans('breadcrumbs.tools.index'))

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.tools.index') }}
@endsection

@section('content')
    <ul class="list-group">
        <li class="list-group-item"><i class="bi bi-sliders"></i> <a href="{{ route('adminpanel.tools.control-panel') }}">{{ ap_trans('breadcrumbs.tools.control_panel') }}</a></li>
        <li class="list-group-item"><i class="bi bi-terminal"></i> <a href="{{ route('adminpanel.tools.commands') }}">{{ ap_trans('breadcrumbs.tools.commands') }}</a></li>
        <li class="list-group-item"><i class="bi bi-file-text"></i> <a href="{{ route('adminpanel.tools.logs') }}">{{ ap_trans('breadcrumbs.tools.logs') }}</a></li>
        <li class="list-group-item"><i class="bi bi-archive"></i> <a href="{{ route('adminpanel.tools.docs') }}">{{ ap_trans('breadcrumbs.docs') }}</a></li>
        <li class="list-group-item"><i class="bi bi-cast"></i> <a href="{{ route('adminpanel.media.index') }}">{{ ap_trans('breadcrumbs.media') }}</a></li>
    </ul>
@endsection
