@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.driver_sold_tickets_history'))


@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@push('styles')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('js/moment-with-locales.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.driver_sold_tickets_history'))
@section('title2', Lang::get('titles.driver_sold_tickets_history2'))

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6 span-bold-ge left">
            {{ Lang::get('status.total_unapproved') }}: <span class="eng-bold yellow size20">{{ $total_unapproved ?? 0 }}</span>
            <img src="{{ URL::asset('images/currencies/GEL.png') }}" alt="GEL">
            <br>{{ Lang::get('status.total_unapproved_count') }}: <span class="eng-bold yellow size20">{{ $total_unapproved_count ?? 0 }}</span>
            <br>{{ Lang::get('status.total_refunded') }}: <span class="eng-bold red size20">{{ $total_refunded_count ?? 0 }}</span>
        </div>
        <div class="col-md-6 col-lg-6 span-bold-ge right">
            {{ Lang::get('status.total_approved') }}: <span class="eng-bold green size20">{{ $total_approved ?? 0 }}</span>
            <img src="{{ URL::asset('images/currencies/GEL.png') }}" alt="GEL">
            <br>{{ Lang::get('status.total_approved_count') }}: <span class="eng-bold green size20">{{ $total_approved_count ?? 0 }}</span>
            <br>{{ Lang::get('status.total_sold_by_driver') }}: <span class="eng-bold blue size20">{{ $total_driver_reserved_count ?? 0 }}</span>
        </div>
    </div>
    <div class="sales-driver">
        @if(Session::get('alert'))
            <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
        @endif
        <div class="sales-history">
            <table id="driverSalesHistory" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">{{ Lang::get('driver.route_id') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.customer') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.route') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.vehicle') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.departure_date') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.seat_number') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.your_cut') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.company_cut') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.status') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="vehicle-scheme-info">
            <div class="vehicle-scheme-info-item">
                {{ Lang::get('status.long_parsed_ticket') }} <i class="fa fa-check green"></i>
            </div>
            |
            <div class="vehicle-scheme-info-item">
                {{ Lang::get('status.long_refunded_ticket') }} <i class="fa fa-ban red"></i>
            </div>
            |
            <div class="vehicle-scheme-info-item">
                {{ Lang::get('status.long_sold_ticket') }} <i class="fa fa-check yellow"></i>
            </div>
            |
            <div class="vehicle-scheme-info-item">
                {{ Lang::get('status.long_sold_ticket_driver') }} <i class="fa fa-check blue"></i>
            </div>
        </div>

    </div>
@endsection
