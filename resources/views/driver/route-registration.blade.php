@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.route_registration_title'))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop


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


@section('title1', Lang::get('titles.route_registration3'))
@section('title2', Lang::get('titles.route_registration4'))

@section('content')
    <div class="transport-registration-road">
        <div class="transport-registration-road-form">
            @if(Session::get('alert'))
                <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
            @endif
            <div class="response"></div>
            @component('components.misc.form')
                @slot('class') form-horizontal form-default @endslot
                @slot('form_id') routeVehicleForm @endslot
                @slot('row_inside') @endslot
                @component('components.misc.form-group-col', ['values' => $route_types])
                    @slot('label') {{ Lang::get('auth.service_type') }} @endslot
                    @slot('name') route_type @endslot
                    @slot('field') select @endslot
                    @slot('faicon') fa-bus @endslot
                    @slot('col') col-md-6 @endslot
                @endcomponent
                @component('components.misc.form-group-col', ['values' => $models])
                    @slot('label') {{ Lang::get('auth.vehicle_model') }} @endslot
                    @slot('name') vehicle_model @endslot
                    @slot('field') select @endslot
                    @slot('class') form-control eng @endslot
                    @slot('faicon') fa-bus @endslot
                    @slot('col') col-md-3 @endslot
                @endcomponent
                @component('components.misc.form-group-col', ['values' => $license_plates])
                    @slot('label') {{ Lang::get('auth.license_plate') }} @endslot
                    @slot('name') vehicle_id @endslot
                    @slot('field_id') license_plate @endslot
                    @slot('field') select @endslot
                    @slot('class') form-control eng @endslot
                    @slot('faicon') fa-bus @endslot
                    @slot('col') col-md-3 @endslot
                @endcomponent
                @component('components.misc.submit-button')
                    @slot('class') btn-save @endslot
                    @slot('faicon') fa-plus @endslot
                    @slot('anchor') {{ Lang::get('driver.add_new_route') }}@endslot
                    @slot('col') col-md-12 @endslot
                @endcomponent
            @endcomponent
        </div>
    </div>
@endsection
