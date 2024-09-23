@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.404_title'))

@section('description', $description ?? null)

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop


@isset($robots)
@section('heads')
    <meta name="robots" content="noindex, nofollow">
@endsection
@endisset


@section('title1', $title1 ?? Lang::get('titles.not_found'))

@section('content')
    <div class="not-found">
        <h1 class="text-center pt-5 pb-5">404</h1>
        <h2 class="text-center pt-5-pb-5">{{ Lang::get('titles.not_found_text') }}</h2>
    </div>
@endsection
