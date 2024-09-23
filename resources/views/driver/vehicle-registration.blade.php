@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.vehicle_registration_title'))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@push('styles')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/slick.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.vehicle_registration3'))
@section('title2', Lang::get('titles.vehicle_registration4'))

@section('content')
    @include('sections.vehicle-registration')
@endsection
