<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ config('adminpanel.theme') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="none" />
    <title>Вход в систему</title>
    <link href="{{ adminpanel_asset('css/app.css') }}" rel="stylesheet">

    @stack('end-head-styles')
    @stack('end-head-scripts')
</head>
    <body>
        @yield('content')
        @stack('end-body-scripts')
    </body>
</html>
