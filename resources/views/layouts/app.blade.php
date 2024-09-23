<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="{{ URL::asset('images/favicon.png') }}"/>
    @hasSection('description')
        <meta name="description" content="@yield('description')"/>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ URL::asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/jquery.fancybox.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/intlTelInput.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('css/cropper.css') }}">
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/themes/default.min.css') }}" rel="stylesheet">
    @stack('styles')
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
    @hasSection('heads')
        @yield('heads')
    @endif
</head>
<body class="@yield('body_class')" data-spy="scroll" data-target=".scrollspy-menu" data-offset="0">
<div id="main">
    <div class="loading-web">
        <img src="{{ URL::asset('images/loading-web.gif') }}">
    </div>
    @include('components.cropper')
    <div class="site-content-wrapper">
        <div class="corner-top-orange"></div>
        <div class="serving-since">
            <div class="txt1">20<br>19</div>
            <div class="txt2">{{ Lang::get('misc.online_since') }}</div>
        </div>
        <div class="site-content">
            @include('components.header.app')
            @if(isset($ishome))
                @include('components.header.fixed-menu')
                @yield('content')
                @include('components.header.fixed-menu-mobile')
            @else
                @component('components.content')
                    @if(View::hasSection('title1')) @slot('title1') @yield('title1') @endslot @endif
                    @if(View::hasSection('title2'))  @slot('title2') @yield('title2') @endslot @endif
                    @isset($videoUrl) @slot('videoUrl') {{ $videoUrl }} @endslot @endisset
                    @isset($isDriver)
                        @slot('isDriver') @endslot
                    @endisset
                    @isset($isPartner)
                        @slot('isPartner') @endslot
                    @endisset
                    @isset($isprofile)
                        @slot('isprofile') @endslot
                        @slot('title3') @yield('title3') @endslot
                        @slot('title4') @yield('title4') @endslot
                    @endisset
                    @yield('content')
                @endcomponent
                @yield('outside_content')
            @endif
        </div>
    </div>
    @include('components.footer.app')
</div>
@routes
<script src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.easing.1.3.js') }}"></script>
<script src="{{ URL::asset('js/owl.carousel.js') }}"></script>
<script src="{{ URL::asset('js/superfish.js') }}"></script>
<script src="{{ URL::asset('js/select2.js') }}"></script>
<script src="{{ URL::asset('js/popper.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.fancybox.js') }}"></script>
<script src="{{ URL::asset('js/intlTelInput-jquery.min.js') }}"></script>
@stack('scripts')
@if(!Auth::check() || Request::routeIs(['support','register_as_partner','register_as_driver','support']))
    <script>
        var onloadCallback = function () {
            @if(!Auth::check())
            grecaptcha.render('register_element', {
                'sitekey': '{{ config('services.google-recaptcha.site') }}'
            });
            @endif
            @if(Request::routeIs('register_as_partner'))
            grecaptcha.render('register_partner_element', {
                'sitekey': '{{ config('services.google-recaptcha.site') }}'
            });
            @endif
            @if(Request::routeIs('register_as_driver'))
            grecaptcha.render('register_driver_element', {
                'sitekey': '{{ config('services.google-recaptcha.site') }}'
            });
            @endif
            @if(Request::routeIs('support'))
            grecaptcha.render('support_element', {
                'sitekey': '{{ config('services.google-recaptcha.site') }}'
            });
            @endif
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl={{ config('app.locale') }}"
            async defer>
    </script>
@endif
<script src="{{ URL::asset('js/scripts.js') }}"></script>
</body>
</html>
