@extends('adminpanel::layouts.master')

@section('title', $dataType->getTitleForShow())

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.datatype.'.$dataType->getSlug().'.show',$dataType,$model) }}
@endsection

@section('page-header')
    <h1>{{ $dataType->getTitleForShow() }}</h1>
    @include('adminpanel::multilingual.language-selector')
@endsection

@section('mode','show')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="btn-group mb-3" role="group" >
        @can('update',$model)
                <a class="btn btn-info btn-xs" href="{{ route('adminpanel.'.$dataType->getSlug().'.edit', $model->getKey()) }}">
                    <i class="bi bi-pencil-square"></i> {{ ap_trans('common.buttons.edit') }}
                </a>
        @endcan
        </div>

        @php
            $fields = $dataType->getFormFields('show');
        @endphp
        @foreach($fields as $field)
            {!!  $field->render($dataType,$model,'show') !!}
        @endforeach
    </div>
</div>
@endsection

