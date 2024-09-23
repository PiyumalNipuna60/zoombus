@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.notifications_title'))

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


@section('title1', $title_page ?? Lang::get('titles.notifications'))

@section('content')
    @component('components.misc.form-group-col')
        @slot('field') hidden @endslot
        @slot('hideGroup') @endslot
        @slot('name') zSkip @endslot
        @slot('value') {{ config('app.notifications_per_page') }} @endslot
    @endcomponent
    <div class="items">
        @component('components.notification-item', ['results' => $results])
        @endcomponent
    </div>
    @if($total_results > config('app.notifications_per_page'))
        <div class="pagination">
            <button type="button" class="btn btn-form1-submit show_more_notifications"
                    data-url="{{ route('notifications_more') }}">{{ Lang::get('misc.show_more') }}</button>
        </div>
    @endif
@endsection
