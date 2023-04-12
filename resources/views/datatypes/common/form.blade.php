@extends('adminpanel::layouts.master')

@php
    $edit = !is_null($model?->getKey());
@endphp

@section('title', $dataType->getTitleForForm($edit))

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.datatype.'.$dataType->getSlug().'.'.($edit?'edit':'create'),$dataType,$model) }}
@endsection

{{--TODO: remake--}}
@section('page-header')
    <h1>{{  $dataType->getTitleForForm($edit) }}</h1>
    @include('adminpanel::multilingual.language-selector')
@endsection

@section('mode','form')

@section('content')
    @yield('before-form')
    <form
            method="post"
            action="{{ route('adminpanel.'.$dataType->getSLug().'.'.($edit?'update':'store'), $model->getKey()) }}"
            class="form-create-edit"
            enctype="multipart/form-data"
    >
        @csrf
        @if($edit)
            @method('PUT')
        @endif
        @php
            $fields = $dataType->getFormFields(($edit?'edit':'create'));
        @endphp
        @include('adminpanel::blocks.layout.index',['blocks' => $dataType->getLayout(),'content' => $bodyTemplate ?? 'adminpanel::datatypes.partials.block-body' ])
        @include('adminpanel::datatypes.partials.submit')
    </form>
    @yield('after-form')
    {{--    <form id="uploadForm" action="{{ route('adminpanel.upload') }}" target="form_target" method="post"--}}
    {{--          enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">--}}
    {{--        <input name="image" id="upload_file" type="file"--}}
    {{--               onchange="$('#uploadForm').submit();this.value='';">--}}
    {{--        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->getSlug() }}">--}}
    {{--        @csrf--}}
    {{--    </form>--}}
@endsection
