@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.forgot_password_title'))

@section('description', $description ?? null)

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop


@isset($robots)
@section('heads')
    <meta name="robots" content="{{ $robots }}">
@endsection
@endisset


@section('title1', $title_page ?? Lang::get('titles.forgot_password1'))
@section('title2', Lang::get('titles.forgot_password2'))

@section('content')
    <div class="response"></div>
    @component('components.misc.form')
        @slot('row_inside') @endslot
        @slot('form_id') forgotPasswordForm @endslot
        <div class="col-md-3"></div>
        @component('components.misc.form-group-col')
            @slot('label') @endslot
            @slot('name') phone_number @endslot
            @slot('value') @endslot
            @slot('col') col-md-6 @endslot
        @endcomponent
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        @component('components.misc.submit-button')
            @slot('col') col-md-6 @endslot
            @slot('anchor') {{ Lang::get('misc.get_new_password') }} @endslot
            @slot('class') btn-save cursor-pointer @endslot
            @slot('faicon') fa-mobile-phone @endslot
        @endcomponent
        <div class="col-md-3"></div>
    @endcomponent
@endsection
