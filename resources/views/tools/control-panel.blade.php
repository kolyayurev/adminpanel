@extends('adminpanel::layouts.master')

@section('title',ap_trans('breadcrumbs.tools.index'))

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.tools.control-panel') }}
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            Админ панель
        </div>
        <div class="card-body">
            Версия : {{ AdminPanel::getVersion() }}
        </div>
    </div>
    <div class="card mt-1">
        <div class="card-header">
            Проект
        </div>
        <div class="card-body">
            <i class="bi bi-git"></i>  Ветка :  {{ AdminPanel::getBranch() }}
        </div>
    </div>
    <div class="card mt-1">
        <div class="card-header">
            Конфигурация
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">Debug :  {{ config('app.debug')?'Да':'Нет' }}</li>
                <li class="list-group-item">Url :  {{ config('app.url') }}</li>
                <li class="list-group-item">Окружение :  {{ app()->environment() }}</li>
            </ul>
        </div>
    </div>
@endsection
