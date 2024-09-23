@extends('layouts.app')

@section('title', $title ?? 'Page | Zoombus')

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


@section('title1', $title_page ?? 'Page title')

@section('content')
    {!!  $text_page ?? null !!}
@endsection
