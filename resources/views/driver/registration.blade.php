@extends('layouts.app', ['isprofile' => 1])

@section('title', 'Zoombus')

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@section('title1', Lang::get('titles.register_as_driver_title'))
@section('title2', Lang::get('titles.register_as_driver_title2'))

@section('content')
    <div class="partner-account">
        <div class="img1"><img src="{{ URL::asset('images/partner-account.png') }}" alt="Driver account" class="img-fluid"></div>
        <div class="txt1">{!! Lang::get('titles.register_as_driver_title3') !!} </div>
        <div class="txt2">{!! Lang::get('titles.register_as_driver_title4')  !!} </div>
        <div class="response"></div>
        @component('components.misc.form')
            @slot('form_id') becomeDriverForm @endslot
            @component('components.misc.submit-button')
                @slot('faicon') fa-drivers-license @endslot
                @slot('anchor') {{ Lang::get('misc.activate_driver_program') }} @endslot
                @slot('class') btn-activate @endslot
            @endcomponent
        @endcomponent

    </div>
@endsection
