@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.faqs_title'))

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


@section('title1', $title_page ?? 'FAQs')

@section('content')
    @foreach($allFAQs as $faq)
        <div class="title7">
            <a href="{{ $faq['url'] }}">{{ $faq['seo_title'] }}</a>
        </div>
    @endforeach
@endsection
