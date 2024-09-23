@extends('layouts.app', ['ishome' => 1])

@section('title', $title ?? Lang::get('seo.home_page_title'))

@section('description', $description ?? null)

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        front language_ge
    @else
        front
    @endif
@stop

@isset($robots)
@section('heads')
    <meta name="robots" content="{{ $robots }}">
@endsection
@endisset

@push('scripts')
<script type="text/javascript" src="{{ URL::asset('js/bloodhound.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/typeahead.bundle.js') }}"></script>
@endpush


@section('content')

    @component('components.front-section-wrapper')
        @slot('section_id') minibus-tickets @endslot
        @slot('txt1') {{ Lang::get('misc.microbus_reserve') }} @endslot
        @slot('txt2')  {{ Lang::get('misc.microbus_tickets') }}  @endslot
        @slot('txt3') {!! Lang::get('misc.home_microbus_sections_small', ['url' => route('listings_rt', ['route_type' => 'minibus'])]) !!}  @endslot
        @slot('small_title') {!! Lang::get('misc.home_microbus_sections_small_text') !!}@endslot

        @component('components.misc.form-group-col')
            @slot('type') hidden @endslot
            @slot('hideGroup') @endslot
            @slot('noId') @endslot
            @slot('name') route_type_array[] @endslot
            @slot('value') 1 @endslot
        @endcomponent

        @component('components.misc.form-group-col')
            @slot('type') hidden @endslot
            @slot('noId') @endslot
            @slot('hideGroup') @endslot
            @slot('name') departure_date @endslot
        @endcomponent

        @component('components.misc.form-group-col')
            @slot('type') hidden @endslot
            @slot('noId') @endslot
            @slot('hideGroup') @endslot
            @slot('name') arrival_date @endslot
        @endcomponent


        @slot('field1')
            @component('components.misc.input')
                @slot('title') {{ Lang::get('misc.travel_from') }} @endslot
                @slot('name') from @endslot
                @slot('noId') @endslot
                @slot('required') {{ Lang::get('validation.please_fill_out_this_field') }}@endslot
                @slot('inner_class') input_pin @endslot
            @endcomponent
        @endslot

        @slot('field2')
            @component('components.misc.input')
                @slot('title') {{ Lang::get('misc.travel_to') }} @endslot
                @slot('name') to @endslot
                @slot('noId') @endslot
                @slot('required') {{ Lang::get('validation.please_fill_out_this_field') }}@endslot
                @slot('inner_class') input_pin @endslot
            @endcomponent
        @endslot

        @slot('field3')
            @component('components.misc.datepicker')
                @slot('title') {{ Lang::get('misc.travel_date') }} @endslot
                @slot('value') {{ \Carbon\Carbon::now()->translatedformat('j\ F Y') }} @endslot
                @slot('input_id') travel_date @endslot
            @endcomponent
        @endslot

        @slot('field4')
            @component('components.misc.input')
                @slot('inner_class') passengers @endslot
                @slot('title') {{ Lang::get('misc.passengers') }} @endslot
                @slot('name') passengers @endslot
                @slot('noId') @endslot
                @slot('value') 1 @endslot
            @endcomponent
        @endslot



    @endcomponent


    @component('components.front-section-wrapper')
        @slot('section_id') bus-tickets @endslot
        @slot('txt1') {{ Lang::get('misc.bus_bus') }} @endslot
        @slot('txt2') {{ Lang::get('misc.bus_tickets') }} @endslot
        @slot('txt3') {!! Lang::get('misc.home_bus_sections_small', ['url' => route('listings_rt', ['route_type' => 'bus'])]) !!} @endslot
        @slot('small_title') {!! Lang::get('misc.home_bus_sections_small_text') !!}@endslot


        @component('components.misc.form-group-col')
            @slot('type') hidden @endslot
            @slot('hideGroup') @endslot
            @slot('noId') @endslot
            @slot('name') route_type_array[] @endslot
            @slot('value') 2 @endslot
        @endcomponent

        @component('components.misc.form-group-col')
            @slot('type') hidden @endslot
            @slot('hideGroup') @endslot
            @slot('noId') @endslot
            @slot('name') departure_date @endslot
        @endcomponent

        @component('components.misc.form-group-col')
            @slot('type') hidden @endslot
            @slot('hideGroup') @endslot
            @slot('noId') @endslot
            @slot('name') arrival_date @endslot
        @endcomponent


        @slot('field1')
            @component('components.misc.input')
                @slot('title') {{ Lang::get('misc.travel_from') }} @endslot
                @slot('name') from @endslot
                @slot('noId') @endslot
                @slot('inner_class') input_pin @endslot
            @endcomponent
        @endslot

        @slot('field2')
            @component('components.misc.input')
                @slot('title') {{ Lang::get('misc.travel_to') }} @endslot
                @slot('name') to @endslot
                @slot('noId') @endslot
                @slot('inner_class') input_pin @endslot
            @endcomponent
        @endslot

        @slot('field3')
            @component('components.misc.datepicker')
                @slot('title') {{ Lang::get('misc.travel_date') }} @endslot
                @slot('value') {{ \Carbon\Carbon::now()->translatedformat('j\ F Y') }} @endslot
                @slot('input_id') travel_date @endslot
            @endcomponent
        @endslot

        @slot('field4')
            @component('components.misc.input')
                @slot('inner_class') passengers @endslot
                @slot('title') {{ Lang::get('misc.passengers') }} @endslot
                @slot('name') passengers @endslot
                @slot('value') 1 @endslot
                @slot('noId') @endslot
            @endcomponent
        @endslot



    @endcomponent



{{--    @component('components.front-section-wrapper')--}}
{{--        @slot('section_id') carpooling @endslot--}}
{{--        @slot('txt1') {{ Lang::get('misc.carpooling_car') }} @endslot--}}
{{--        @slot('txt2') {{ Lang::get('misc.carpooling_sharing') }} @endslot--}}
{{--        @slot('txt3'){!! Lang::get('misc.home_carpooling_sections_small', ['url' => route('listings_rt', ['route_type' => 'carpooling'])]) !!} @endslot--}}
{{--        @slot('small_title') {!! Lang::get('misc.home_carpooling_sections_small_text') !!} @endslot--}}


{{--        @component('components.misc.form-group-col')--}}
{{--            @slot('type') hidden @endslot--}}
{{--            @slot('hideGroup') @endslot--}}
{{--            @slot('noId') @endslot--}}
{{--            @slot('name') route_type_array[] @endslot--}}
{{--            @slot('value') 3 @endslot--}}
{{--        @endcomponent--}}

{{--        @component('components.misc.form-group-col')--}}
{{--            @slot('type') hidden @endslot--}}
{{--            @slot('hideGroup') @endslot--}}
{{--            @slot('noId') @endslot--}}
{{--            @slot('name') departure_date @endslot--}}
{{--        @endcomponent--}}

{{--        @component('components.misc.form-group-col')--}}
{{--            @slot('type') hidden @endslot--}}
{{--            @slot('hideGroup') @endslot--}}
{{--            @slot('noId') @endslot--}}
{{--            @slot('name') arrival_date @endslot--}}
{{--        @endcomponent--}}


{{--        @slot('field1')--}}
{{--            @component('components.misc.input')--}}
{{--                @slot('title') {{ Lang::get('misc.travel_from') }} @endslot--}}
{{--                @slot('name') from @endslot--}}
{{--                @slot('noId') @endslot--}}
{{--                @slot('inner_class') input_pin @endslot--}}
{{--            @endcomponent--}}
{{--        @endslot--}}

{{--        @slot('field2')--}}
{{--            @component('components.misc.input')--}}
{{--                @slot('title') {{ Lang::get('misc.travel_to') }} @endslot--}}
{{--                @slot('name') to @endslot--}}
{{--                @slot('noId') @endslot--}}
{{--                @slot('inner_class') input_pin @endslot--}}
{{--            @endcomponent--}}
{{--        @endslot--}}

{{--        @slot('field3')--}}
{{--            @component('components.misc.input')--}}
{{--                @slot('inner_class') passengers @endslot--}}
{{--                @slot('title') {{ Lang::get('misc.passengers') }} @endslot--}}
{{--                @slot('name') passengers @endslot--}}
{{--                @slot('noId') @endslot--}}
{{--                @slot('value') 1 @endslot--}}
{{--            @endcomponent--}}
{{--        @endslot--}}

{{--        @slot('field4')--}}
{{--            @component('components.misc.datepicker')--}}
{{--                @slot('title') {{ Lang::get('misc.travel_date') }} @endslot--}}
{{--                @slot('value') {{ \Carbon\Carbon::now()->translatedformat('j\ F Y') }} @endslot--}}
{{--                @slot('input_id') travel_date @endslot--}}
{{--            @endcomponent--}}
{{--        @endslot--}}

{{--        @slot('field5')--}}
{{--            @component('components.misc.datepicker')--}}
{{--                @slot('title') {{ Lang::get('misc.travel_date_back') }} @endslot--}}
{{--                --}}{{--@slot('value') {{ \Carbon\Carbon::parse('+7 days')->format('j F Y') }} @endslot--}}
{{--                @slot('input_id') travel_date_back @endslot--}}
{{--                @slot('wrapper_class') transparentify disabled-field return-date @endslot--}}
{{--                @slot('clear_btn') @endslot--}}
{{--            @endcomponent--}}
{{--        @endslot--}}
{{--    @endcomponent--}}


    {{--@component('components.front-section-wrapper')--}}
        {{--@slot('section_id') intercity-taxi-tickets @endslot--}}
        {{--@slot('txt1') ტაქსის @endslot--}}
        {{--@slot('txt2') ბილეთები @endslot--}}
        {{--@slot('txt3') დაჯავშნე <a href="#">საქალაქთაშორისო ტაქსის ბილეთები</a> ონლაინ, ნებისმიერი მიმსრთულებით @endslot--}}
        {{--@slot('small_title') სამგზავრო ბილეთების ონლაინ დაჯავშნის სისტემა<br>დაჯავშნე ბილეთები საქალაქთაშორისო ტაქსზე ნებისმიერი ქალაქიდან ნებისმიერი მიმართულებით @endslot--}}


        {{--@component('components.misc.form-group-col')--}}
            {{--@slot('type') hidden @endslot--}}
            {{--@slot('hideGroup') @endslot--}}
            {{--@slot('name') type @endslot--}}
            {{--@slot('value') 4 @endslot--}}
        {{--@endcomponent--}}

        {{--@slot('field1')--}}
            {{--@component('components.misc.select', ['values' => ['1' => 'თბილისი', '2' => 'ბათუმი', '3' => 'ქუთაისი', '4' => 'თელავი']])--}}
                {{--@slot('title') {{ Lang::get('misc.travel_from') }} @endslot--}}
                {{--@slot('name') taxi_from @endslot--}}
            {{--@endcomponent--}}
        {{--@endslot--}}

        {{--@slot('field2')--}}
            {{--@component('components.misc.select', ['values' => ['1' => 'თბილისი', '2' => 'ბათუმი', '3' => 'ქუთაისი', '4' => 'თელავი']])--}}
                {{--@slot('title') {{ Lang::get('misc.travel_to') }} @endslot--}}
                {{--@slot('name') taxi_to @endslot--}}
            {{--@endcomponent--}}
        {{--@endslot--}}

        {{--@slot('field3')--}}
            {{--@component('components.misc.input')--}}
                {{--@slot('inner_class') passengers @endslot--}}
                {{--@slot('title') {{ Lang::get('misc.passengers') }} @endslot--}}
                {{--@slot('name') passengers @endslot--}}
                {{--@slot('input_id') taxi_passengers @endslot--}}
                {{--@slot('value') 1 @endslot--}}
            {{--@endcomponent--}}
        {{--@endslot--}}

        {{--@slot('field4')--}}
            {{--@component('components.misc.datepicker')--}}
                {{--@slot('title') {{ Lang::get('misc.travel_date') }} @endslot--}}
                {{--@slot('value') {{ \Carbon\Carbon::now()->format('j F Y') }} @endslot--}}
                {{--@slot('name') travel_date @endslot--}}
                {{--@slot('input_id') taxi_travel_date @endslot--}}
            {{--@endcomponent--}}
        {{--@endslot--}}

        {{--@slot('field5')--}}
            {{--@component('components.misc.datepicker')--}}
                {{--@slot('title') {{ Lang::get('misc.travel_date_back') }} @endslot--}}
                {{--@slot('value') {{ \Carbon\Carbon::parse('+7 days')->format('j F Y') }} @endslot--}}
                {{--@slot('name') travel_date_back @endslot--}}
                {{--@slot('wrapper_class') transparentify disabled-field return-date @endslot--}}
                {{--@slot('input_id') taxi_travel_date_back @endslot--}}
                {{--@slot('clear_btn') @endslot--}}
            {{--@endcomponent--}}
        {{--@endslot--}}
    {{--@endcomponent--}}


@endsection
