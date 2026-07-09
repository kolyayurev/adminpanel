@extends('adminpanel::layouts.master')

@section('title', $customPage->getTitle())

@section('page-header')
    <h1>{{ $customPage->getTitle() }}</h1>
@endsection

@section('content')
    {{-- adminpanel::blocks.layout.index переиспользует общий layout-движок (Row/Col/Card/...);
         его шаблоны жёстко ссылаются на переменную $fields — здесь в ней лежат виджеты. --}}
    @include('adminpanel::blocks.layout.index', [
        'blocks' => $customPage->getLayout(),
        'fields' => $customPage->getWidgets(),
        'content' => 'adminpanel::custom_pages.partials.block-body',
    ])
@endsection
