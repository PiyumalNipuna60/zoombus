<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META SECTION -->
    <title>@yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="shortcut icon" type="image/png" href="{{ URL::asset('images/favicon.png') }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('images/icons/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('images/icons/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('images/icons/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('images/icons/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('images/icons/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('images/icons/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('images/icons/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('images/icons/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('images/icons/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ URL::asset('images/icons/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('images/icons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ URL::asset('images/icons/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('images/icons/favicon-16x16.png')}}">
    <meta name="robots" content="noindex, nofollow">
    <!-- END META SECTION -->
    <link rel="stylesheet" href="{{ URL::asset('css/font-awesome.css') }}">
    @stack('styles_admin')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/css/theme-default.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/css/admin_style.css') }}"/>
</head>
<body>
<!-- START PAGE CONTAINER -->
<div class="page-container">
@component('admin.sidebar', ['sidebar' => $sidebar]) @endcomponent
<!-- PAGE CONTENT -->
    <div class="page-content">
        <!-- START X-NAVIGATION VERTICAL -->
        <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
            <!-- TOGGLE NAVIGATION -->
            <li class="xn-icon-button">
                <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
            </li>
            <!-- END TOGGLE NAVIGATION -->
            <!-- SEARCH -->
            <li class="xn-search">
                <form role="form">
                    <input type="text" name="search" placeholder="Search..."/>
                </form>
            </li>
            <!-- END SEARCH -->
            <!-- POWER OFF -->
            <li class="xn-icon-button pull-right last">
                <a href="javascript:void(0)" class="logout"><span class="fa fa-power-off"></span></a>
            </li>
            <!-- END POWER OFF -->
            <!-- MESSAGES -->
            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-money"></span></a>
                <div class="informer informer-danger">{{ $withdraw_request_count ?? 0 }}</div>
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span
                                class="fa fa-comments"></span> {{ Lang::get('admin_menu.withdraw_requests') }}</h3>
                        <div class="pull-right">
                            <span
                                class="label label-danger">{{ $withdraw_request_count ?? 0 }} {{ Lang::get('misc.new') }}</span>
                        </div>
                    </div>
                    @if(isset($withdraw_requests))
                        <div class="panel-body list-group list-group-contacts scroll" style="height: 200px;">
                            @foreach($withdraw_requests as $wd)
                                <a href="{{ route('admin_payout_edit', ['id' => $wd['id']]) }}" class="list-group-item">
                                    <div class="list-group-status"></div>
                                    <img src="{{ (new App\User())->photoSmallById($wd['user']['id']) }}"
                                         class="pull-left"
                                         alt="{{ $wd['user']['name'] }}"/>
                                    <span class="contacts-title">{{ $wd['user']['name'] }}</span>
                                    <p>{{ $wd['amount'] }}</p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="panel-footer text-center">
                        <a href="{{ route('admin_payouts') }}">{{ Lang::get('admin_menu.all_requests') }}</a>
                    </div>
                </div>
            </li>
            <!-- END MESSAGES -->
            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-car"></span></a>
                <div class="informer informer-danger">{{ $vehicle_request_count ?? 0 }}</div>
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span
                                class="fa fa-comments"></span> {{ Lang::get('admin_menu.vehicle_requests') }}</h3>
                        <div class="pull-right">
                            <span
                                class="label label-danger">{{ $vehicle_request_count ?? 0 }} {{ Lang::get('misc.new') }}</span>
                        </div>
                    </div>
                    @if(isset($vehicle_requests))
                        <div class="panel-body list-group list-group-contacts scroll" style="height: 200px;">
                            @foreach($vehicle_requests as $vr)
                                <a href="{{ route('admin_vehicle_edit', ['id' => $vr['id']]) }}"
                                   class="list-group-item">
                                    <div class="list-group-status"></div>
                                    <img src="{{ (new App\User())->photoSmallById($vr['user']['id']) }}"
                                         class="pull-left"
                                         alt="{{ $vr['user']['name'] }}"/>
                                    <span class="contacts-title">{{ $vr['user']['name'] }}</span>
                                    <p>{{ $vr['manufacturers']['name'].' '.$vr['model'] }}</p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="panel-footer text-center">
                        <a href="{{ route('admin_vehicles_all') }}">{{ Lang::get('admin_menu.all_requests') }}</a>
                    </div>
                </div>
            </li>

            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-id-card-o"></span></a>
                <div class="informer informer-danger">{{ $license_request_count ?? 0 }}</div>
                @if(isset($license_request_count) && $license_request_count > 0)
                    <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                        <div class="panel-heading">
                            <h3 class="panel-title"><span
                                    class="fa fa-comments"></span> {{ Lang::get('admin_menu.license_requests') }}</h3>
                            <div class="pull-right">
                            <span
                                class="label label-danger">{{ $license_request_count ?? 0 }} {{ Lang::get('misc.new') }}</span>
                            </div>
                        </div>
                        <div class="panel-body list-group list-group-contacts scroll" style="height: 200px;">
                            @foreach($license_requests as $lr)
                                <a href="{{ route('admin_user_edit', ['id' => $lr['user']['id']]) }}" class="list-group-item">
                                    <div class="list-group-status"></div>
                                    <img src="{{ (new App\User())->photoSmallById($lr['user']['id']) }}"
                                         class="pull-left"
                                         alt="{{ $lr['user']['name'] }}"/>
                                    <span class="contacts-title">{{ $lr['user']['name'] }}</span>
                                    <p>{{ $lr['license_number'] }}</p>
                                </a>
                            @endforeach
                        </div>
                        <div class="panel-footer text-center">
                            <a href="{{ route('admin_users_drivers') }}">{{ Lang::get('admin_menu.all_requests') }}</a>
                        </div>
                    </div>
                @endif
            </li>

            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-comments"></span></a>
                <div class="informer informer-danger">{{ $sidebar['support_ticket_count'] ?? 0 }}</div>
                @if(isset($sidebar['support_ticket_count']) && $sidebar['support_ticket_count'] > 0)
                    <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                        <div class="panel-heading">
                            <h3 class="panel-title"><span
                                    class="fa fa-comments"></span> {{ Lang::get('admin_menu.users.support_tickets') }}</h3>
                            <div class="pull-right">
                            <span
                                class="label label-danger">{{ $sidebar['support_ticket_count'] ?? 0 }} {{ Lang::get('misc.new') }}</span>
                            </div>
                        </div>
                        <div class="panel-body list-group list-group-contacts scroll" style="height: 200px;">
                            @foreach($support_tickets->toArray() as $st)
                                <a href="{{ route('admin_users_support_ticket_edit', ['id' => $st['id']]) }}" class="list-group-item">
                                    @if(isset($st['user_id']))
                                        <img src="{{ (new App\User())->photoSmallById($st['user_id']) }}" class="pull-left" alt="{{ $st['name'] ?? $st['user']['name'] }}">
                                    @else
                                        <img src="{{ URL::asset('images/users/small/default.png') }}" class="pull-left" alt="{{ $st['name'] ?? $st['user']['name'] }}">
                                    @endif
                                    <span class="contacts-title">{{ $st['name'] ?? $st['user']['name'] }}</span>
                                    <p> {{ $st['latest_message']['message'] }}</p>
                                </a>
                            @endforeach
                        </div>
                        <div class="panel-footer text-center">
                            <a href="{{ route('admin_users_support_tickets') }}">{{ Lang::get('admin_menu.all_tickets') }}</a>
                        </div>
                    </div>
                @endif
            </li>

        </ul>
        <!-- END X-NAVIGATION VERTICAL -->
        <!-- START BREADCRUMB -->
        <ul class="breadcrumb">
            <li><a href="{{ route('admin_dashboard') }}">{{ Lang::get('admin_menu.admin') }}</a></li>
            <li class="active">@yield('breadcrumb')</li>
        </ul>
        <!-- END BREADCRUMB -->
        @component('admin.content')
            @slot('page_title') @yield('page_title') @endslot
            @yield('content')
        @endcomponent
    </div>
    <!-- END PAGE CONTENT -->
</div>
<script type="text/javascript" src="{{ URL::asset('admin/js/plugins/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin/js/plugins/jquery/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin/js/plugins/bootstrap/bootstrap.min.js') }}"></script>
<!-- END PLUGINS -->

<!-- START THIS PAGE PLUGINS-->
<script type='text/javascript' src='{{ URL::asset('admin/js/plugins/icheck/icheck.min.js') }}'></script>
<script type="text/javascript"
        src="{{ URL::asset('admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js') }}"></script>

<!-- END THIS PAGE PLUGINS-->
<script type='text/javascript' src='{{ URL::asset('admin/js/plugins/noty/jquery.noty.js') }}'></script>
<script type='text/javascript' src='{{ URL::asset('admin/js/plugins/noty/layouts/topCenter.js') }}'></script>
<script type='text/javascript' src='{{ URL::asset('admin/js/plugins/noty/layouts/topLeft.js') }}'></script>
<script type='text/javascript' src='{{ URL::asset('admin/js/plugins/noty/layouts/topRight.js') }}'></script>

@stack('scripts_admin')

<script type='text/javascript' src='{{ URL::asset('admin/js/plugins/noty/themes/default.js') }}'></script>

<script type="text/javascript" src="{{ URL::asset('admin/js/plugins.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin/js/actions.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin/js/admin_scripts.js') }}"></script>
</body>
</html>

