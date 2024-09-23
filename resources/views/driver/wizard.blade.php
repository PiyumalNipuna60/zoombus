@extends('layouts.app')

@section('title', Lang::get('seo.wizard_title'))


@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@push('styles')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/slick.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('js/moment-with-locales.js') }}"></script>
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



@if($step == 1)
    @section('title1', Lang::get('titles.wizard_step1'))
@elseif($step == 2)
    @section('title1', Lang::get('titles.wizard_step2'))
@elseif($step == 3)
    @section('title1', Lang::get('titles.wizard_step3'))
@elseif($step == 4)
    @section('title1', Lang::get('titles.wizard_step4'))
@endif
@section('title2', Lang::get('titles.wizard2'))

@section('content')
    <div class="user-menu-wrapper">
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                 aria-valuemax="100">{{ Lang::get('titles.wizard_step1') }}</div>
            <div class="progress-bar @if($step < 2) not-active @endif" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                 aria-valuemax="100">{{ Lang::get('titles.wizard_step2') }}</div>
            <div class="progress-bar @if($step < 3) not-active @endif" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                 aria-valuemax="100">{{ Lang::get('titles.wizard_step3') }}</div>
            <div class="progress-bar @if($step < 4) not-active @endif" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                 aria-valuemax="100">{{ Lang::get('titles.wizard_step4') }}</div>
        </div>
    </div>
    <div class="hidden wizard-complete text-center">
        <i class="fa fa-check-circle-o" style="color: #95b75d;font-size:10em;"></i><br><br>
        <h1>{{ Lang::get('misc.wizard_complete') }}</h1>
        <p>{{ Lang::get('misc.wizard_complete2') }}</p>
    </div>
    <div class="wizard-container">
        @if($step == 1)
            @component('sections.edit-profile', ['countries' => $countries, 'genders' => $genders])
                @slot('submit') continue @endslot
            @endcomponent
        @elseif($step == 2)
            @component('sections.drivers-license', ['license_number' => $license_number ?? null, 'front_side' => $front_side ?? null, 'back_side' => $back_side ?? null])
                @slot('submit') continue @endslot
            @endcomponent
        @elseif($step == 3)
            @component('sections.vehicle-registration', [
                        'route_types' => $route_types, 'fuel_types' => $fuel_types, 'countries' => $countries,
                        'vehicle_specs' => $vehicle_specs, 'manufacturers' => $manufacturers, 'scheme' => $scheme, 'year_manufactured' => $year_manufactured,
                        'front_side' => $front_side ?? null, 'back_side' => $back_side ?? null, 'vehicle_images' => $vehicle_images ?? null,
                        'vehicle' => $vehicle ?? null
                        ])
                @slot('submit') continue @endslot
            @endcomponent
        @elseif($step == 4)
            @component('ajax.route-registration', [
                'days' => $days, 'types' => $types, 'current_currency' => $current_currency_route,
                'currencies' => $currencies_all, 'vehicle_id' => $vehicle_id
                ])
                @slot('submit') continue @endslot
            @endcomponent
        @endif
    </div>
@endsection
