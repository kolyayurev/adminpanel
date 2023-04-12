<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ config('adminpanel.theme') }}" class="{{ config('adminpanel.theme') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="assets-path" content="{{ route('adminpanel.assets') }}"/>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'AdminPanel')</title>

        <link href="{{ adminpanel_asset('css/element-ui.css') }}" rel="stylesheet">
        <link href="{{ adminpanel_asset('css/app.css') }}" rel="stylesheet">
        @if(!empty(config('adminpanel.icons')))<!-- Icons -->
            @foreach(config('adminpanel.icons') as $icon)<link rel="stylesheet" type="text/css" href="{{ $icon }}">@endforeach
        @endif

        @if(!empty(config('adminpanel.additional_css')))<!-- Additional CSS -->
            @foreach(config('adminpanel.additional_css') as $css)<link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach
        @endif

        @stack('end-head-styles')
        @stack('end-head-scripts')
    </head>

    <body class="adminpanel">
        @stack('start-body-scripts')

        @include('adminpanel::layouts.partials.nav')
        <div class="wrapper" id="contentBody" @if(isset($isModelTranslatable) && $isModelTranslatable) data-multilingual @endif data-mode="@yield('mode')">
            @include('adminpanel::layouts.partials.loader')

            <section class="container-fluid my-3">
                @include('adminpanel::layouts.partials.messages')
            </section>

            <section class="container-fluid my-3">
                @yield('breadcrumbs')
            </section>

            <section class="container-fluid my-3">
                @yield('page-header')
            </section>

            <section class="container-fluid mt-3"  >
                @yield('content')
            </section>
            @include('adminpanel::layouts.partials.footer')
        </div>
        <div id="modalContainer"></div>

        <script>
            var vueFieldInstances = [];
            const adminPrefix = '{{ config('adminpanel.prefix') }}';
            var locale = '{{ app()->getLocale()}}';
            var fallbackLocale = '{{ config('app.fallback_locale')}}';
            const storage = '{{ Str::finish(Storage::disk(config('adminpanel.storage.disk'))->url('/'), '/') }}';
            var dataTablesOptions = {};
        </script>

        @stack('before-app-scripts')
        <script type="text/javascript" src="{{ adminpanel_asset(config('app.debug')?'js/app-dev.js':'js/app.js') }}"></script>
        @if(!empty(config('adminpanel.additional_js')))<!-- Additional Javascript -->
            @foreach(config('adminpanel.additional_js') as $js)<script type="text/javascript" src="{{ asset($js) }}"></script>@endforeach
        @endif

        @stack('vue')
        @stack('end-body-scripts')
    </body>
</html>
