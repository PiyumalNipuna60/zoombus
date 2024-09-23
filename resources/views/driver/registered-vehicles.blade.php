@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.registered_vehicles_title'))


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
@endpush

@section('title1', Lang::get('titles.registered_vehicles3'))
@section('title2', Lang::get('titles.registered_vehicles4'))

@section('content')
    <div class="transport-registered">
        @if(Session::get('alert'))
            <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
        @endif
        <div class="transport-registered-btns">
            <a href="{{ route('vehicle_registration') }}" class="btn-save"><i class="fa fa-plus" aria-hidden="true"></i> {{ Lang::get('driver.add_new_vehicle') }}</a>
        </div>
        <div class="transport-registered-table">
            <table id="vehicles" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">{{ Lang::get('driver.vehicle_type') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.vehicle_model') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.license_plate') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.year') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.number_of_seats') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.status') }}
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
