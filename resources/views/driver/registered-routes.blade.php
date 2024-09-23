@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.registered_routes_title'))


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
<script src="{{ URL::asset('js/alertify.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables.js') }}"></script>
<script src="{{ URL::asset('js/moment.js') }}"></script>
<script src="{{ URL::asset('js/moment-with-locales.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.registered_routes3'))
@section('title2', Lang::get('titles.registered_routes4'))

@section('content')
    <div class="transport-registered">
        <div class="transport-registered-btns">
            <a href="{{ route('route_registration') }}" class="btn-save"><i class="fa fa-plus" aria-hidden="true"></i> {{ Lang::get('driver.add_new_route') }}</a>
        </div>
        <div class="transport-registered-table">
            <table id="routes" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr class="trsize">
                    <th class="th-sm">{{ Lang::get('driver.route_id') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.vehicle') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.route') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.departure_date') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.time') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.stopping_time') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.departure_address') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.arrival_address') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.price') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.currency') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.action') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
@endsection
