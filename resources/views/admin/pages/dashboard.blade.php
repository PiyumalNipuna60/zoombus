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


@section('title', 'Dashboard | Zoombus Admin')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        Dashboard page
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
