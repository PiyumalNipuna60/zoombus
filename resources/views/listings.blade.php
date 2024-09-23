@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.listing_title'))

@section('title', $description ?? null)

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
    @isset($robots) listing_search @endisset @empty($robots) listing_page @endempty
@stop

@isset($robots)
@section('heads')
    <meta name="robots" content="{{ $robots }}">
@endsection
@endisset

@push('styles')
    <link href="{{ URL::asset('css/magnific-popup.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bloodhound.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/typeahead.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery.magnific-popup.min.js') }}"></script>
@endpush

@isset($robots)
@section('heads')
    <meta name="robots" content="{{ $robots }}">
@endsection
@endisset


@section('title1', $title1 ?? Lang::get('titles.listing'))
@section('title2', $title2 ?? Lang::get('titles.listing2'))

@section('content')
    <div class="front-section-box">
        @if(Session::get('alert'))
            <div class="response-persistent {{ Session::get('alert') }}">
                {{ Session::get('text') }}
            </div>
        @endif
        <div class="front-section-form">
            <div class="response"></div>
            @component('components.misc.form')
                @slot('method') POST @endslot
                @slot('class') form1 clearfix search-home @endslot
                @slot('form_id') listingForm @endslot
                @slot('action') {{ route('listings_search') }} @endslot
                @slot('row_inside') @endslot
                @component('components.misc.form-group-col')
                    @slot('field') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') lang @endslot
                    @slot('value') {{ config('app.locale') }} @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('field') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') zSkip @endslot
                    @slot('value') {{ config('app.listings_per_page') }} @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('field') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') route_type @endslot
                    @slot('value') {{ $route_type_param ?? '' }} @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('field') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') sort_by @endslot
                    @slot('value') {{ Request::get('sort_by') }} @endslot
                @endcomponent

                @isset($route_type_array)
                    @foreach($route_type_array as $roty)
                        @component('components.misc.form-group-col')
                            @slot('type') hidden @endslot
                            @slot('hideGroup') @endslot
                            @slot('name') route_type_array[] @endslot
                            @slot('field_id') rt_{{$roty}} @endslot
                            @slot('value') {{ $roty }} @endslot
                        @endcomponent
                    @endforeach
                @endisset
                @empty($route_type_array)
                    @foreach($all_route_types as $roty)
                        @component('components.misc.form-group-col')
                            @slot('type') hidden @endslot
                            @slot('hideGroup') @endslot
                            @slot('name') route_type_array[] @endslot
                            @slot('field_id') rt_{{$roty['id']}} @endslot
                            @slot('value') {{ $roty['id'] }} @endslot
                        @endcomponent
                    @endforeach
                @endempty

                @component('components.misc.form-group-col')
                    @slot('type') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') sort_by @endslot
                    @if(isset($sort_by)) @slot('value') {{ $sort_by }} @endslot @else date @endif
                @endcomponent

                @component('components.misc.form-group-col')
                    @slot('type') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') departure_date @endslot
                @endcomponent


                @component('components.misc.form-group-col')
                    @slot('type') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') return_date @endslot
                @endcomponent


                @slot('action') {{ route('listings_search') }} @endslot
                <div class="col-md-6 col-lg-3">
                    @component('components.misc.input')
                        @slot('title') {{ Lang::get('misc.travel_from') }} @endslot
                        @slot('name') from @endslot
                        @slot('required') {{ Lang::get('validation.please_fill_out_this_field') }}@endslot
                        @isset($from) @slot('value') {{ $from }} @endslot @endisset
                        @slot('inner_class') input_pin @endslot
                    @endcomponent
                </div>
                <div class="col-md-6 col-lg-3">
                    @component('components.misc.input')
                        @slot('title') {{ Lang::get('misc.travel_to') }} @endslot
                        @slot('name') to @endslot
                        @slot('required') {{ Lang::get('validation.please_fill_out_this_field') }}@endslot
                        @isset($to) @slot('value') {{ $to }} @endslot @endisset
                        @slot('inner_class') input_pin @endslot
                    @endcomponent
                </div>

                <div class="col-md-6 col-lg-3">
                    @component('components.misc.datepicker')
                        @slot('title') {{ Lang::get('misc.travel_date') }} @endslot
                        @slot('input_id') travel_date @endslot
                        @if(isset($departure_date) && $departure_date != 'Invalid date')  @slot('value') {{ \Carbon\Carbon::createFromTimestamp(strtotime($departure_date))->translatedFormat('j\ F Y') }} @endslot @endif
                    @endcomponent
                </div>
                <div class="col-md-6 col-lg-3">
                    @component('components.misc.input')
                        @slot('inner_class') passengers @endslot
                        @slot('title') {{ Lang::get('misc.passengers') }} @endslot
                        @slot('name') passengers @endslot
                        @slot('value') {{ $passengers ?? 1 }} @endslot
                    @endcomponent
                </div>
                <div class="col-lg-9 col-md-12"></div>
                <div class="col-md-6 col-lg-3">
                    <button type="submit" class="btn-form1-submit">{{ Lang::get('misc.search') }}</button>
                </div>
            @endcomponent
        </div>
    </div>



    <div class="sort-by">
        @component('components.misc.form')
            @slot('class') form6 clearfix @endslot
            @slot('method') POST @endslot
            @slot('form_id') sortListing @endslot
            @component('components.misc.form-group-col', ['values' => $sorts])
                @slot('field') select @endslot
                @slot('label') {{ Lang::get('misc.filter') }}@endslot
                @slot('field_id') sort_change @endslot
                @slot('select_default_value') date @endslot
                @slot('select_default_anchor') {{ Lang::get('misc.date') }} @endslot
                @isset($sort_by) @slot('value') {{ $sort_by }} @endslot @endisset
                @slot('name') sort_by @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('field') checkbox @endslot
                @slot('group_class') form-check @endslot
                @slot('label_class') form-check-label @endslot
                @slot('label') {{ Lang::get('misc.all') }} @endslot
                @slot('field_id') check_all @endslot
                @isset($route_type_array) @if(in_array(1, $route_type_array) && in_array(2, $route_type_array)) @slot('checked') @endslot @endif @endisset
                @if(!isset($route_type_array) && $route_type == [1,2]) @slot('checked') @endslot @endif
            @endcomponent
            @foreach($route_types as $rt)
                @component('components.misc.form-group-col')
                    @slot('field') checkbox @endslot
                    @slot('name') {{ $rt['id'] }} @endslot
                    @slot('group_class') form-check @endslot
                    @slot('label_class') form-check-label @endslot
                    @slot('label') {{ $rt['translated']['name'] }} @endslot
                    @slot('field_id') sort_{{ $rt['id'] }}  @endslot
                    @isset($route_type_array) @if(in_array($rt['id'], $route_type_array)) @slot('checked') @endslot @endif @endisset
                    @if(!isset($route_type_array) && in_array($rt['id'], $route_type)) @slot('checked') @endslot @endif
                @endcomponent
            @endforeach
        @endcomponent
    </div>

    @if(isset($total_results) && $total_results > 0)
        <div class="items">
            @component('components.listing-item', ['results' => $results])
            @endcomponent
        </div>
        @if($total_results > config('app.listings_per_page'))
            <div class="pagination">
                <button type="button" class="btn btn-form1-submit show_more"
                        data-url="{{ route('listing_more') }}">{{ Lang::get('misc.show_more') }}</button>
            </div>
        @endif
    @else
        <h1 class="text-center">{{ Lang::get('misc.no_results') }}</h1>
    @endif

@endsection

@push('scripts')
    <script src="{{ URL::asset('js/listingMaps.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google-maps.key') }}&callback=initMap"
            async defer></script>
@endpush
