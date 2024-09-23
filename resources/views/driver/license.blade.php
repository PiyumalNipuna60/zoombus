@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.drivers_license_title'))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@section('title1', Lang::get('titles.drivers_license3'))
@section('title2', Lang::get('titles.drivers_license4'))

@section('content')
    @include('sections.drivers-license')
@endsection
