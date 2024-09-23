@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.support_tickets_title'))

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


@section('title1', $title_page ?? Lang::get('titles.support1'))
@section('title2', Lang::get('titles.support2'))

@section('content')
    <div class="response"></div>
    @component('components.misc.form')
        @slot('row_inside') @endslot
        @slot('form_id') supportForm @endslot
        @component('components.misc.form-group-col')
            @slot('label') @endslot
            @slot('name') name @endslot
            @slot('value') {{ (Auth::check()) ? Auth::user()->name : null }} @endslot
            @slot('placeholder') {{ Lang::get('auth.name') }} @endslot
            @slot('col') col-md-6 @endslot
        @endcomponent
        @component('components.misc.form-group-col')
            @slot('label') @endslot
            @slot('name') email @endslot
            @slot('type') email @endslot
            @slot('placeholder') {{ Lang::get('auth.email_address') }} @endslot
            @slot('value') {{ (Auth::check()) ? Auth::user()->email : null }} @endslot
            @slot('col') col-md-6 @endslot
        @endcomponent
        @component('components.misc.form-group-col')
            @slot('label') @endslot
            @slot('name') message @endslot
            @slot('field') textarea @endslot
            @slot('nolabel') @endslot
            @slot('placeholder') {{ Lang::get('auth.message') }} @endslot
            @slot('value') @endslot
            @slot('class') form-control h-78-px @endslot
            @slot('col') col-md-6 @endslot
        @endcomponent
        <div id="support_element" class="col-md-6"></div>
        @component('components.misc.submit-button')
            @slot('col') col-md-12 @endslot
            @slot('anchor') {{ Lang::get('misc.send_a_message') }} @endslot
            @slot('class') btn-save cursor-pointer @endslot
            @slot('faicon') fa-support @endslot
        @endcomponent
    @endcomponent
@endsection
