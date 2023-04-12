@extends('adminpanel::layouts.master')

@section('title', $pageType->getTitle())

@section('page-header')
    <h1>{{ $pageType->getTitle() }}</h1>
    @include('adminpanel::multilingual.language-selector')
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.setting.'.$pageType->getSlug(),$pageType) }}
@endsection

@section('mode','form')

@section('content')
<form action="{{ route('adminpanel.settings.update') }}" class="form-create-edit" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <input type="hidden" name="slug" value="{{ $pageType->getSlug() }}">
    @include('adminpanel::blocks.layout.index',['blocks' => $pageType->getLayout(),'content' => $bodyTemplate ?? 'adminpanel::settings.partials.block-body' ])
    <button class="btn btn-success mt-3" type="submit">Сохранить</button>
</form>
@endsection
