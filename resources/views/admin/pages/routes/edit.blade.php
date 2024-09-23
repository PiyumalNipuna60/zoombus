@extends('admin.app')


@push('styles_admin')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/themes/default.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/flexslider.css') }}" rel="stylesheet">
@endpush

@push('scripts_admin')
    <script src="{{ URL::asset('js/alertify.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('js/listingMaps.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google-maps.key') }}&callback=initMap"
            async defer></script>
@endpush


@section('title', 'Editing Route | Zoombus Admin')
@section('page_title', 'Editing Route')
@section('breadcrumb', 'Editing route')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">

                        <div class="col-md-3 col-sm-12 col-xs-12">

                            <div class="panel panel-default form-horizontal">
                                <div class="panel-body">
                                    <h3><span class="fa fa-map-marker"></span> {{ Lang::get('admin.departure') }}</h3>
                                </div>
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">
                                        <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.city') }}</label>
                                        <div class="col-md-8 col-xs-7 line-height-30">{{ $route['cities_from']['translated']['name'] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.departure') }}</label>
                                        <div class="col-md-8 col-xs-7 line-height-30">
                                        {{
                                        (in_array($route['departure_date'], $route['weekDays'])) ?
                                        $route['departure_date'] : \Carbon\Carbon::parse($route['departure_date'])->translatedFormat('j\ F Y')
                                        }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.time') }}</label>
                                        <div class="col-md-8 col-xs-7 line-height-30">{{ $route['departure_time'] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.departure_address') }}</label>
                                        <div class="col-md-8 col-xs-7 line-height-30">{{ $route['address_from']['translated']['name'] }}</div>
                                    </div>
                                </div>

                            </div>



                        </div>


                        <div class="col-md-3 col-sm-12 col-xs-12">

                            <div class="panel panel-default form-horizontal">
                                <div class="panel-body">
                                    <h3><span class="fa fa-map-marker"></span> {{ Lang::get('admin.arrival') }} </h3>
                                </div>
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">
                                        <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.city') }}</label>
                                        <div class="col-md-8 col-xs-7 line-height-30">{{ $route['cities_to']['translated']['name'] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 col-xs-5 control-label"> {{ Lang::get('admin.arrival') }}</label>
                                        <div class="col-md-8 col-xs-7 line-height-30">
                                            {{
                                            (in_array($route['arrival_date'], $route['weekDays'])) ?
                                            $route['arrival_date'] : \Carbon\Carbon::parse($route['arrival_date'])->translatedFormat('j\ F Y')
                                            }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.time') }}</label>
                                        <div class="col-md-8 col-xs-7 line-height-30">{{ $route['arrival_time'] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.departure_address') }}</label>
                                        <div class="col-md-8 col-xs-7 line-height-30">{{ $route['address_to']['translated']['name'] }}</div>
                                    </div>
                                </div>

                            </div>



                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default form-horizontal">
                                <div class="panel-body">


                                    <div class="panel-body form-group-separated">
                                        <div class="form-group">
                                            <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.price') }}</label>
                                            <div class="col-md-8 col-xs-7 line-height-30">{{ $route['price'] }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.currency') }}</label>
                                            <div class="col-md-8 col-xs-7 line-height-30">{{ $route['currency']['key'] }}</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-md-12 col-xs-12">
                                                    <a href="{{ route('admin_route_approve') }}"
                                                       data-action="ajax" data-post-data="{{ json_encode($route['ajaxData'] ?? []) }}"
                                                       class="btn btn-success btn-block btn-rounded"
                                                       @if(isset($route['disabled'][1])) disabled="disabled" @endif
                                                    >
                                                        {{ Lang::get('admin.approve') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-md-12 col-xs-12">
                                                    <a href="{{ route('admin_route_suspend') }}"
                                                       data-action="ajax" data-post-data="{{ json_encode($route['ajaxData'] ?? []) }}"
                                                       class="btn btn-danger btn-block btn-rounded"
                                                       @if(isset($route['disabled'][3])) disabled="disabled" @endif
                                                    >
                                                        {{ Lang::get('admin.suspend') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
