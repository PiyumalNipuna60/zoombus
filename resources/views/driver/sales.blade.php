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
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('js/moment-with-locales.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.driver_sales'))
@section('title2', Lang::get('titles.driver_sales2'))

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6"></div>
        <div class="col-md-6 col-lg-6 span-bold-ge right">
            {{ Lang::get('misc.total') }}: <span class="eng-bold red size20">{{ $total_sold_currency ?? 0 }}</span>
            <img src="{{ URL::asset('images/currencies/GEL.png') }}" alt="GEL">
            <br>{{ Lang::get('misc.sold_tickets_number') }}: <span class="eng-bold red size20">{{ $total_sold ?? 0 }}</span>
        </div>
    </div>
    <div class="transport-registered">
        @if(Session::get('alert'))
            <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
        @endif
        <div class="transport-registered-table">
            <table id="driverSales" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">{{ Lang::get('driver.route_id') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.route') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.vehicle') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.departure_date') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.sold_tickets_number') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.price') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.your_cut') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.company_cut') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.actions') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
@endsection
