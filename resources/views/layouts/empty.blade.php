<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    @hasSection('description')
        <meta name="description" content="@yield('description')"/>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ URL::asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/font-awesome.css') }}" rel="stylesheet">
    @stack('styles')
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="@yield('body_class')">
<div id="main">
    @yield('content')
</div>
@routes
<script src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.easing.1.3.js') }}"></script>
<script src="{{ URL::asset('js/superfish.js') }}"></script>
<script src="{{ URL::asset('js/popper.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
@stack('scripts')
<script src="{{ URL::asset('js/scripts.js') }}"></script>
</body>
</html>
