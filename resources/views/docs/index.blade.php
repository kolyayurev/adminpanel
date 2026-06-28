@extends('adminpanel::layouts.master')


@section('title',ap_trans('breadcrumbs.docs'))

@push('end-head-styles')
    {{-- стили подсветки кода highlight.js (тема monokai) --}}
    <link href="{{ adminpanel_asset('css/docs.css') }}" rel="stylesheet">
@endpush

@section('breadcrumbs')
    {{ Breadcrumbs::render('adminpanel.docs') }}
@endsection

@section('content')
<div class="container-md" id="docs">
    <div class="row">
        <div class="col-12 col-md-3">
            <aside class="border-end">
                {{ $menu }}
            </aside>
        </div>
        <div class="col-12 col-md-9">
            <main id="content">
            </main>
        </div>
    </div>
</div>
@endsection

@push('end-body-scripts')
    <script type="text/javascript" src="{{ adminpanel_asset('js/docs.js') }}"></script>
@endpush

