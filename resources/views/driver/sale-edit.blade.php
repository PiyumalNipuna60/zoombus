@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.driver_sales_title'))


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

    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('js/moment-with-locales.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.driver_sales'))
@section('title2', Lang::get('titles.driver_sales2'))

@section('content')
    <div class="span-bold-ge right">
        {{ Lang::get('misc.sold_in_currency') }}:
        <span class="eng-bold red size20">{{ array_sum(array_column($sale, 'price')) }}</span>
        <img src="{{ URL::asset('images/currencies/GEL.png') }}" alt="GEL"> {{-- Future currency--}}
        <br>
        {{ Lang::get('misc.sold_tickets_number') }}:
        <span class="eng-bold red size20">{{ count($sale['routes']['sales']) }}</span>
    </div>
    <div class="driver-sales-scheme">
        <div
            class="txt1">{{ $sale['routes']['vehicles']['manufacturers']['name'].' '.$sale['routes']['vehicles']['model'].' '.$sale['routes']['vehicles']['license_plate'] }}</div>
        <div class="txt2">{{ $sale['routes']['cities_from']['translated']['name'] }}
            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
            {{ $sale['routes']['cities_to']['translated']['name'] }}</div>
        <div
            class="txt3">{{ \Carbon\Carbon::parse($sale['routes']['departure_date'])->translatedFormat('j\ F Y').' - '.$sale['routes']['departure_time'] }}</div>
        <div class="txt4 mt-15">{{ Lang::get('misc.time_before_route') }}</div>
        <div class="txt5 countdown" data-day="{{ Lang::get('misc.day_word') }}"
             data-days="{{ Lang::get('misc.days_word') }}"
             data-date="{{ \Carbon\Carbon::parse($sale['routes']['departure_date'])->format('Y/m/d').' '.$sale['routes']['departure_time'] }}">
            00:00:00
        </div>

        <!-- Start Seat Schemes -->
    @component('components.vehicle-schemes', [
        'seat_positioning' => $sale['routes']['vehicles']['seat_positioning'],
        'chosen_seats' => $sale['routes']['sales'] ?? [],
        'chosen_pre_seats' => $sale['routes']['reserved_seats'] ?? []
    ])
        @slot('route_type') {{ $all_route_types[$sale['routes']['vehicles']['type']-1]['key'] }} @endslot
        @slot('show_info') @endslot
        @slot('show_info_preserved') @endslot
    @endcomponent
    <!-- End Seat Schemes -->


        <div class="response"></div>
        @component('components.misc.form')
            @slot('form_id') ticketReserve @endslot
            @component('components.misc.form-group-col')
                @slot('field') hidden @endslot
                @slot('name') route_id @endslot
                @slot('value') {{ $sale['route_id'] }} @endslot
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
                @component('components.misc.submit-button',  ['alertify' => $sale['deleteAlertify'] ?? []])
                    @slot('class')
                        btn-cancel2 {{ (count($sale['routes']['sales']) > 0) ? 'route_cancel' :  'route_delete' }} @endslot
                    @slot('type') button @endslot
                    @slot('faicon') fa-times @endslot
                    @slot('anchor') {{ (count($sale['routes']['sales']) > 0) ? Lang::get('misc.cancel_route') :  Lang::get('auth.delete') }} @endslot
                @endcomponent
            </div>
        @endcomponent
    </div>
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
                    <th class="th-sm">{{ Lang::get('misc.your_cut') }}
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
@endsection
