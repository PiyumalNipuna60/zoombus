@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.route_edit_title'))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@push('styles')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/themes/default.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/jquery.countdown.min.js') }}"></script>
    <script src="{{ URL::asset('js/alertify.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
@endpush


@push('styles')
<link href="{{ URL::asset('css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script type="text/javascript" src="{{ URL::asset('js/moment.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/moment-with-locales.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
@endpush
@if ( Config::get('app.locale') == 'ka')
    @push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.ka.min.js') }}"></script>
    @endpush
@elseif ( Config::get('app.locale') == 'ru' )
    @push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.ru.min.js') }}"></script>
    @endpush
@endif

@push('scripts')
<script type="text/javascript" src="{{ URL::asset('js/bloodhound.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/typeahead.bundle.js') }}"></script>
@endpush


@section('title1', Lang::get('titles.route_edit3'))
@section('title2', Lang::get('titles.route_edit4'))

@section('content')
    <div class="route-edit">
        <div class="route-form-edit has-vehicle-scheme">
            <input type="hidden" id="route_id" value="{{ $route['id'] }}">
            <input type="hidden" id="type" value="{{ $route['vehicles']['type'] }}">
            @if(Session::get('alert'))
                <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
            @endif
            <div class="response"></div>
            @if(!Request::has('hideFields'))
                @component('ajax.route-registration', $route) @endcomponent
            @endif
            <div class="driver-sales-scheme">
                <div
                    class="txt1">{{ $route['vehicles']['manufacturers']['name'].' '.$route['vehicles']['model'].' '.$route['vehicles']['license_plate'] }}</div>
                <div class="txt2">{{ $route['cities_from']['translated']['name'] }}
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    {{ $route['cities_to']['translated']['name'] }}</div>
                <div
                    class="txt3">{{ \Carbon\Carbon::parse($route['departure_date'])->translatedFormat('j\ F Y').' - '.$route['departure_time'] }}</div>
                <div class="txt4 mt-15">{{ Lang::get('misc.time_before_route') }}</div>
                <div class="txt5 countdown" data-day="{{ Lang::get('misc.day_word') }}"
                     data-days="{{ Lang::get('misc.days_word') }}"
                     data-date="{{ \Carbon\Carbon::parse($route['departure_date'])->format('Y/m/d').' '.$route['departure_time'] }}">
                    00:00:00
                </div>

                <!-- Start Seat Schemes -->
            @component('components.vehicle-schemes', [
                'seat_positioning' => $route['vehicles']['seat_positioning'],
                'chosen_seats' => $route['sales'] ?? [],
                'chosen_pre_seats' => $route['reserved_seats'] ?? [],
                'disabled' => ($route['status'] == 2) ? true : false
            ])
                @slot('route_type') {{ $all_route_types[$route['vehicles']['type']-1]['key'] }} @endslot
                @slot('show_info') @endslot
                @slot('show_info_preserved') @endslot
            @endcomponent
            <!-- End Seat Schemes -->



                @if($route['status'] == 1)
                    <div class="response"></div>
                    @component('components.misc.form')
                        @slot('form_id') ticketReserve @endslot
                        @component('components.misc.form-group-col')
                            @slot('field') hidden @endslot
                            @slot('name') route_id @endslot
                            @slot('value') {{ $route['id'] }} @endslot
                            @slot('hideGroup') @endslot
                        @endcomponent
                        <div class="ticket-passengers hidden">
                            <div class="title">{{ Lang::get('misc.passengers') }}</div>
                        </div>
                        <div class="txt6">
                            @component('components.misc.submit-button')
                                @slot('class') btn-save2 @endslot
                                @slot('name') action @endslot
                                @slot('value') save @endslot
                                @slot('faicon') fa-floppy-o @endslot
                                @slot('anchor') {{ Lang::get('auth.save') }}@endslot
                            @endcomponent
                        </div>
                    @endcomponent
                @endif

            </div>
            <div class="divider2"></div>
            <div class="title5">{{ Lang::get('titles.route_edit5') }}</div>
            <div class="title2">{{ Lang::get('titles.route_edit6') }}</div>
            <div class="driver-sales">
                <div class="driver-sales-table">
                    <table id="driverSalesByRouteId" class="table table-striped table-bordered table-sm" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th class="th-sm">{{ Lang::get('driver.customer') }}
                            </th>
                            <th class="th-sm">{{ Lang::get('driver.route') }}
                            </th>
                            <th class="th-sm">{{ Lang::get('driver.vehicle') }}
                            </th>
                            <th class="th-sm">{{ Lang::get('misc.seat_number') }}
                            </th>
                            <th class="th-sm">{{ Lang::get('misc.amount') }}
                            </th>
                            <th class="th-sm">{{ Lang::get('driver.status') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
