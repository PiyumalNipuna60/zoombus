@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.affiliate_code_title'))


@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop


@section('title1', Lang::get('titles.affiliate_code'))
@section('title2', Lang::get('titles.affiliate_code2'))

@section('content')
    <div class="profile-personal">
        <div class="profile-personal-form">
            <div class="response"></div>
            @component('components.misc.form')
                @slot('class') form-horizontal form-default @endslot
                @slot('form_id') @if(isset($form_id)) {{ $form_id }} @else registerNewPartnerForm @endif @endslot
                @slot('row_inside') @endslot
                <input type="hidden" name="lang" value="{{ config('app.locale') }}">
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.name').', '.Lang::get('auth.lastname') }} @endslot
                    @slot('name') name @endslot
                    @slot('value') @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('faicon') fa-user-o @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.mobile_phone') }} @endslot
                    @slot('name') phone_number @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('value') @endslot
                    @slot('faicon') fa-mobile @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.email_address') }} @endslot
                    @slot('name') email @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('value') @endslot
                    @slot('faicon') fa-envelope-o @endslot
                @endcomponent
                @component('components.misc.form-group-col', ['values' => $registerAs])
                    @slot('label') {{ Lang::get('auth.register_as') }} @endslot
                    @slot('field') select @endslot
                    @slot('name') account_type @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('faicon') fa-user-o @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.affiliate_code') }} @endslot
                    @slot('name') affiliate_code @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('value') {{ $affiliateCode ?? null }} @endslot
                    @slot('faicon') fa-user-o @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('field') button @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('class') btn-save @endslot
                    @slot('name') save @endslot
                    @slot('faicon') fa-floppy-o @endslot
                    @slot('label') &nbsp; @endslot
                    @slot('anchor') {{ Lang::get('auth.registration') }} @endslot
                @endcomponent
            @endcomponent
        </div>
    </div>

@endsection
