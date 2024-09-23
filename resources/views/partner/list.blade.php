@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.partners_list_title'))


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

@section('title1', Lang::get('titles.partner_list'))
@section('title2', Lang::get('titles.partner_list2'))

@section('content')
    <div class="transport-registered">
        @if(Session::get('alert'))
            <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
        @endif
        <div class="transport-registered-table">
            <table id="partnersListTable" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">{{ Lang::get('auth.mobile_phone') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.name_last_name') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.account_type') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.vehicle_type') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.number_of_seats') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.tier_1_percent') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.tier_2_percent') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.passenger_percent') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
@endsection
