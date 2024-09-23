<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ $title ?? 'Title' }}</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="{{ URL::asset('images/favicon.png') }}"/>
    @hasSection('description')
        <meta name="description" content="@yield('description')"/>
    @endisset
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @isset($response)
        <meta name="response" content="{{ $response }}"/>
    @endisset
    @isset($locale)
        <meta name="locale" content="{{ $locale }}"/>
    @endisset
    <meta name="viewport" content="width=device-width, viewport-fit=cover, user-scalable=no, initial-scale=1, shrink-to-fit=no">
</head>
<body class="{{ (isset($current_locale) && $current_locale == 'ka') ? 'language_ge' : null }}">
<div id="app">
    @yield('content')
</div>
<script src="{{ URL::asset('js/app.js') }}"></script>
</body>
</html>
