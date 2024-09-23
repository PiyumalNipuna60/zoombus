@extends('layouts.app')

@section('title',  $title ?? Lang::get('seo.rating_title'))

@section('description',  $description ?? null)


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


@section('content')
    @php
        $dif = strtotime($result['routes']['departure_date'].' '.$result['routes']['departure_time'].':00');
        $dif2 = strtotime($result['routes']['arrival_date'].' '.$result['routes']['arrival_time'].':00');
        $tt = $dif2-$dif;
    @endphp
    <div class="ticket-details-info-bottom display-block">
        <div class="ticket-info d-flex">
            <div class="ticket-info-left">
                <div class="info d-flex">
                    <div class="info1">
                        <div class="img1"><img
                                src="{{ Storage::temporaryUrl('cities/'.$result['routes']['cities_from']['id'].'.'.$result['routes']['cities_from']['extension'], now()->addMinutes(5)) }}"
                                alt="{{ $result['routes']['vehicles']['type'] }}" class="img-fluid"></div>
                        <div class="txt1 from_city">{{ $result['routes']['cities_from']['translated']['name'] }}</div>
                        <div class="txt2">
                            {{ Lang::get('driver.departure_address') }}
                            :<br>{{ $result['routes']['address_from']['translated']['name'] }}
                        </div>
                    </div>
                    <div class="info2">
                        <div class="arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></div>
                        <div class="arrow-txt">{{ Lang::get('misc.trip_duration') }}<br>
                            {{ trans_choice('misc.days', gmdate('z', $tt)) }} {{ gmdate('H:i', $tt) }}
                            {{ trans_choice('misc.hours', gmdate('H', $tt)) }}
                            <br>{{ Controller::timeForHumans($result['routes']['stopping_time']) }}
                        </div>
                    </div>
                    <div class="info3">
                        <div class="img1"><img
                                src="{{ Storage::temporaryUrl('cities/'.$result['routes']['cities_to']['id'].'.'.$result['routes']['cities_to']['extension'], now()->addMinutes(5)) }}"
                                alt="{{ $result['routes']['cities_to']['translated']['name'] }}" class="img-fluid"></div>
                        <div class="txt1 to_city">{{ $result['routes']['cities_to']['translated']['name'] }}</div>
                        <div class="txt2">
                            {{ Lang::get('driver.arrival_address') }}:<br>{{ $result['routes']['address_to']['translated']['name'] }}
                        </div>
                    </div>
                </div>
                <div class="ticket-info-googlemap already-open"></div>
            </div>
            <div class="ticket-info-right">
                <div
                    class="info1">{{ \Carbon\Carbon::parse($result['routes']['departure_date'])->translatedformat('j\ F, ').\Carbon\Carbon::parse($result['routes']['departure_date'])->translatedformat('l')  }}</div>
                <div class="info2 clearfix">
                    <figure>
                        <img src="{{ URL::asset('images/route-types/'.$result['routes']['vehicles']['type'].'.png') }}"
                             alt="{{ $result['routes']['vehicles']['type'] }}" class="img-fluid">
                    </figure>
                    <div class="caption">
                        {{ $result['routes']['vehicles']['manufacturers']['manufacturer_name'].' '.$result['routes']['vehicles']['model']  }}
                        <br>
                        {{ strtoupper($result['routes']['vehicles']['license_plate']) }}<br>
                        @component('components.misc.rating',
                        [
                        'average_rating' => $result['routes']['average_rating'],
                        'total_votes' => count($result['routes']['ratings']),
                        'advanced' => true
                        ])
                        @endcomponent
                    </div>
                </div>
                @isset($result['routes']['vehicle_images'])
                    <div id="vehicle-gallery{{ $result['routes']['vehicle_id'] }}" class="hidden">
                        @foreach($result['routes']['vehicle_images'] as $vi)
                            <a href="{{ $vi }}">{{ $result['routes']['vehicles']['manufacturers']['manufacturer_name'].' '.$result['routes']['vehicles']['model']  }}</a>
                        @endforeach
                    </div>
                    <a data-gallery="#vehicle-gallery{{ $result['routes']['vehicle_id'] }}" href="#"
                       class="vehicle-gallery-link vegatr"><i
                            class="fa fa-camera"></i> {{ Lang::get('misc.view_vehicle_images') }}</a>
                @endisset
                <div class="response mini response-blank d-block"></div>
                <div class="title3 title3-1">{{ Lang::get('misc.rate_your_trip') }}</div>
                @component('components.misc.form')
                    @slot('ajax') {{ route('rate_ajax') }} @endslot
                    @component('components.misc.form-group-col')
                        @slot('field') hidden @endslot
                        @slot('name') sale_id @endslot
                        @slot('hideGroup') @endslot
                        @slot('value') {{ $result['id'] ?? 0 }} @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('field') hidden @endslot
                        @slot('hideGroup') @endslot
                        @slot('name') rating @endslot
                        @slot('value') {{ $result['rating']['rating'] ?? 1 }} @endslot
                    @endcomponent
                    @component('components.misc.rating-stars', ['current' => $result['rating']['rating'] ?? 1])
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('name') comment @endslot
                        @slot('nolabel') @endslot
                        @slot('placeholder') {{ Lang::get('misc.comment_optional') }} @endslot
                        @slot('field') textarea @endslot
                        @slot('value') {{ $result['rating']['comment'] ?? null }} @endslot
                    @endcomponent
                    @component('components.misc.submit-button')
                        @slot('anchor') {{ Lang::get('misc.submit') }}@endslot
                        @slot('class') btn-save cursor-pointer @endslot
                    @endcomponent
                @endcomponent
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ URL::asset('js/listingMaps.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google-maps.key') }}&callback=initMap"
            async defer></script>
@endpush
