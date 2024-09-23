@extends('layouts.app', ['isprofile' => 1])

@section('title', 'Zoombus')

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@section('title1', Lang::get('titles.register_as_partner_title'))
@section('title2', Lang::get('titles.register_as_partner_title2'))

@section('content')
    <div class="partner-account">
        <div class="img1"><img src="{{ URL::asset('images/partner-account.png') }}" alt="Partner account" class="img-fluid"></div>
        <div class="txt1">{!! Lang::get('titles.register_as_partner_title3') !!} </div>
        <div class="txt2">{!! Lang::get('titles.register_as_partner_title4')  !!} </div>

        <div class="txt1">
        </div>
        <div class="txt2"></div>
        @if(Session::get('alert'))
            <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
        @endif
        <div class="response"></div>
        @component('components.misc.form')
            @slot('form_id') becomePartnerForm @endslot
            @component('components.misc.submit-button')
                @slot('faicon') fa-handshake-o @endslot
                @slot('anchor') {{ Lang::get('misc.activate_partner_program') }} @endslot
                @slot('class') btn-activate @endslot
            @endcomponent
        @endcomponent

    </div>
@endsection
