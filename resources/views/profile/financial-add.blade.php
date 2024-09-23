@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.financial_title'))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@push('styles')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.financial_parameters'))
@section('title2', Lang::get('titles.financial_parameters2'))

@section('content')
    <div class="profile-financial">
        <div class="profile-financial-form">
            <div class="response"></div>
            @component('components.misc.form')
                @slot('form_id') addFinancial @endslot
                @slot('row_inside') @endslot
                <div class="col-md-4">
                    @component('components.misc.form-group-col')
                        @slot('group_class') radio @endslot
                        @slot('name') type @endslot
                        @slot('value') 1 @endslot
                        @slot('field') radio @endslot
                        @slot('checked') @endslot
                        @slot('field_id') type_1 @endslot
                        @slot('label') {!! Lang::get('misc.credit_card') !!} <img
                            src="{{ URL::asset('images/financial-types/1.png') }}" alt="Card"
                            class="img-fluid h-19-px"> @endslot
                    @endcomponent
                    <fieldset>
                        @component('components.misc.form-group-col')
                            @slot('name') card_name @endslot
                            @slot('placeholder') @endslot
                            @slot('label') {{ Lang::get('misc.card_number') }} @endslot
                        @endcomponent
                        <div class="row">
                            @component('components.misc.form-group-col', ['values' => [$valid_till_month]])
                                @slot('name') valid_till_month @endslot
                                @slot('label') {{ Lang::get('misc.valid_till') }} @endslot
                                @slot('field') select @endslot
                                @slot('col') col-lg-6 @endslot
                            @endcomponent
                            @component('components.misc.form-group-col', ['values' => [$valid_till_year]])
                                @slot('name') valid_till_year @endslot
                                @slot('label') &nbsp; @endslot
                                @slot('field') select @endslot
                                @slot('col') col-lg-6 @endslot
                            @endcomponent
                            @component('components.misc.form-group-col')
                                @slot('name') ccv @endslot
                                @slot('placeholder') @endslot
                                @slot('label') CVC2 / CVV2 @endslot
                                @slot('col') col-lg-6 @endslot
                            @endcomponent
                        </div>
                        @component('components.misc.submit-button')
                            @slot('class') btn-save @endslot
                            @slot('faicon') fa-floppy-o @endslot
                            @slot('anchor') {{ Lang::get('misc.add_card') }}@endslot
                        @endcomponent
                    </fieldset>
                </div>
                <div class="col-md-4">
                    @component('components.misc.form-group-col')
                        @slot('group_class') radio @endslot
                        @slot('name') type @endslot
                        @slot('value') 2 @endslot
                        @slot('field_id') type_2 @endslot
                        @slot('field') radio @endslot
                        @slot('label') {{ Lang::get('misc.paypal') }}  <img
                            src="{{ URL::asset('images/financial-types/2.png') }}" alt="PayPal"
                            class="img-fluid h-19-px"> @endslot
                    @endcomponent
                    <fieldset disabled="disabled">
                        @component('components.misc.form-group-col')
                            @slot('name') paypal_email @endslot
                            @slot('placeholder') @endslot
                            @slot('type') email @endslot
                            @slot('label') {{ Lang::get('misc.paypal_email') }} @endslot
                        @endcomponent
                        @component('components.misc.submit-button')
                            @slot('class') btn-save @endslot
                            @slot('anchor') {{ Lang::get('misc.add_paypal') }} @endslot
                            @slot('faicon') fa-floppy-o @endslot
                        @endcomponent
                    </fieldset>
                </div>
                <div class="col-md-4">
                    @component('components.misc.form-group-col')
                        @slot('group_class') radio @endslot
                        @slot('name') type @endslot
                        @slot('value') 3 @endslot
                        @slot('field') radio @endslot
                        @slot('field_id') type_3 @endslot
                        @slot('label') {{ Lang::get('misc.bank') }}  <img
                            src="{{ URL::asset('images/financial-types/3.png') }}" alt="Bank"
                            class="img-fluid h-19-px"> @endslot
                    @endcomponent
                    <fieldset disabled="disabled">
                        @component('components.misc.form-group-col')
                            @slot('name') your_name @endslot
                            @slot('label') {{ Lang::get('auth.name').', '.Lang::get('auth.lastname') }} @endslot
                            @slot('placeholder') @endslot
                        @endcomponent
                        @component('components.misc.form-group-col')
                            @slot('name') bank_name @endslot
                            @slot('label') {{ Lang::get('auth.bank_name') }} @endslot
                            @slot('placeholder') @endslot
                        @endcomponent
                        @component('components.misc.form-group-col')
                            @slot('name') account_number @endslot
                            @slot('label') {{ Lang::get('misc.bank_account_number') }} @endslot
                            @slot('placeholder') @endslot
                        @endcomponent
                        @component('components.misc.form-group-col')
                            @slot('name') swift @endslot
                            @slot('label') {{ Lang::get('misc.swift') }} @endslot
                            @slot('placeholder') @endslot
                        @endcomponent
                        @component('components.misc.submit-button')
                            @slot('class') btn-save @endslot
                            @slot('anchor') {{ Lang::get('misc.add_bank') }} @endslot
                            @slot('faicon') fa-floppy-o @endslot
                        @endcomponent
                    </fieldset>
                </div>
            @endcomponent
        </div>
    </div>
@endsection
