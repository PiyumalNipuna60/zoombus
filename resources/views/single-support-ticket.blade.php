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

@section('content')
    @foreach($ticket['messages'] as $m)
        <div class="comment-block clearfix @if($m['admin'] > 0) in @endif">
            <figure>
                <img src="@if($m['admin'] == 0) {{ $userAvatar }} @else {{ URL::asset('images/logo.svg') }} @endif" alt="{{ $ticket['name'] }}" class="img-responsive @if($m['admin'] > 0) support @endif">
            </figure>
            <div class="caption">
                <div class="txt1">
                    @if($m['admin'] == 0) {{ $ticket['name'] }}
                    @else {{ $m['user']['name'] }} @endif
                </div>
                <div class="date"><span>{{ \Carbon\Carbon::parse($m['created_at'])->translatedFormat('j\ F Y - H:i:s') }}</span></div>
                <div class="txt2 ltst-msg" @if((new \Hashids\Hashids('',16))->encode($m['id']) == request()->latest_message) id="{{ request()->latest_message }}" @endif>
                    @if((new \Hashids\Hashids('',16))->encode($m['id']) == request()->latest_message)
                        <span class="yellowOverlay"></span>
                    @endif
                    @if($m['admin'] == 0) {{ $m['message'] }} @else {!! $m['message'] !!} @endif
                </div>
            </div>
        </div>
    @endforeach

    <div class="response"></div>
    @component('components.misc.form')
        @slot('row_inside') @endslot
        @slot('form_id') @if(!empty(Request::get('latest_message'))) supportReplyForm @else supportReplyFormSecure @endif @endslot
        @component('components.misc.form-group-col')
            @slot('name') id @endslot
            @slot('value') {{ (new \Hashids\Hashids('', 16))->encode($ticket['id']) }} @endslot
            @slot('field') hidden @endslot
            @slot('hideGroup') @endslot
        @endcomponent
        @if(!empty(request()->latest_message))
            @component('components.misc.form-group-col')
                @slot('name') latest_message @endslot
                @slot('field') hidden @endslot
                @slot('hideGroup') @endslot
                @slot('value') {{ request()->latest_message }} @endslot
            @endcomponent
        @endif
        @component('components.misc.form-group-col')
            @slot('label') @endslot
            @slot('name') message @endslot
            @slot('field') textarea @endslot
            @slot('nolabel') @endslot
            @slot('placeholder') {{ Lang::get('auth.message') }} @endslot
            @slot('value') @endslot
            @slot('class') form-control h-78-px @endslot
            @slot('col') col-md-12 @endslot
        @endcomponent
        @component('components.misc.submit-button')
            @slot('col') col-md-6 @endslot
            @slot('anchor') {{ Lang::get('misc.send_a_message') }} @endslot
            @slot('class') btn-save cursor-pointer @endslot
            @slot('faicon') fa-support @endslot
        @endcomponent
        @component('components.misc.submit-button')
            @slot('col') col-md-6 @endslot
            @slot('anchor') {{ Lang::get('misc.close') }} @endslot
            @slot('class') btn-cancel cursor-pointer @if(!empty(request()->latest_message)) close_ticket_secure @else close_ticket @endif @endslot
            @slot('faicon') fa-times @endslot
        @endcomponent
    @endcomponent
@endsection
