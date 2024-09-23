@extends('layouts.app', ['isprofile' => 1])

@section('title', 'Zoombus')

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@section('title1', Lang::get('titles.edit_profile3'))
@section('title2', Lang::get('titles.edit_profile4'))

@section('content')
    @include('sections.edit-profile')
@endsection
