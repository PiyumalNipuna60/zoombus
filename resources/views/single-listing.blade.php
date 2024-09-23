@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.single_listing_title'))

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

@push('styles')
    <link href="{{ URL::asset('css/magnific-popup.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=ATDFY67EXkNIzGq23zZMfZuREnS8vuFuY-SYmIZ4Vfj8f3ZwutQ49u16sJReSXpBiQuZn5Kk-iZ68Ft2"></script>
@endpush

@section('title1', $title1 ?? Lang::get('titles.single_listing'))
@section('title2', $title2 ?? Lang::get('titles.single_listing2'))

@section('content')
    <div class="ticket-info d-flex has-vehicle-scheme" itemtype="http://schema.org/Trip" itemscope="">
        <input type="hidden" id="type" value="{{ $result['vehicles']['type'] }}">
        <div class="hidden" itemprop="provider" content="Zoombus"></div>
        <div class="hidden" itemprop="url" content="{{ Request::url() }}"></div>
        <div class="hidden" itemprop="arrivalTime" content="{{ \Carbon\Carbon::parse($result['departure_date'].' '.$result['departure_time'])->format('Y-m-d'.' '.'H:i:s'.'P') }}"></div>
        <div class="hidden" itemprop="departureTime" content="{{ \Carbon\Carbon::parse($result['arrival_date'].' '.$result['arrival_time'])->format('Y-m-d'.' '.'H:i:s'.'P') }}"></div>
        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <div class="hidden" itemprop="price" content="{{ $result['price'] }}"></div>
            <meta itemprop="priceCurrency" content="{{ $result['currency']['currency_key'] }}">
        </div>
        <div class="ticket-info-left">
            <div class="info d-flex">
                <div class="info1">
                    <div class="img1">
                        <img src="{{ Storage::temporaryUrl('cities/'.$result['cities_from']['id'].'.'.$result['cities_from']['extension'], now()->addMinutes(5)) }}"
                             alt="{{ $result['cities_from']['translated']['name'] }}" class="img-fluid">
                    </div>
                    <div class="txt1 from_city" itemprop="itinerary" itemtype="http://schema.org/City">{{ $result['cities_from']['translated']['name'] }}</div>
                    <div class="txt2 bigger">
                        <b>{{ Lang::get('driver.departure_time') }}:</b><br>{{ $result['departure_time'] }}
                    </div>
                    <div class="txt2 bigger">
                        <b>{{ Lang::get('driver.departure_address') }}:</b> <br>{{ $result['address_from']['translated']['name'] }}
                    </div>
                </div>
                <div class="info2">
                    <div class="arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></div>
                    <div class="arrow-txt">{{ Lang::get('misc.trip_duration') }}<br>
                        {{ trans_choice('misc.days', gmdate('z', $tt)) }} {{ gmdate('H:i', $tt) }}
                        {{ trans_choice('misc.hours', gmdate('H', $tt)) }}
                        <br>{{ Controller::timeForHumans($result['stopping_time']) }}
                    </div>
                </div>
                <div class="info3">
                    <div class="img1">
                        <img src="{{ Storage::temporaryUrl('cities/'.$result['cities_to']['id'].'.'.$result['cities_to']['extension'], now()->addMinutes(5)) }}"
                             alt="{{ $result['cities_to']['translated']['name'] }}" class="img-fluid">
                    </div>
                    <div class="txt1 to_city" itemprop="itinerary" itemtype="http://schema.org/City">{{ $result['cities_to']['translated']['name'] }}</div>
                    <div class="txt2 bigger">
                        <b>{{ Lang::get('driver.arrival_time') }}:</b><br>{{ $result['arrival_time'] }}
                    </div>
                    <div class="txt2 bigger">
                        <b>{{ Lang::get('driver.arrival_address') }}:</b><br>{{ $result['address_to']['translated']['name'] }}
                    </div>

                </div>
            </div>
            <div class="ticket-info-googlemap already-open"></div>
        </div>
        <div class="ticket-info-right">
            <div
                class="info1">{{ \Carbon\Carbon::parse($result['departure_date'])->translatedFormat('j\ F, ').\Carbon\Carbon::parse($result['departure_date'])->translatedFormat('l') }}</div>
            <div class="info2 clearfix">
                <figure>
                    <img src="{{ URL::asset('images/route-types/'.$result['vehicles']['type'].'.png') }}" alt="{{ $result['vehicles']['type'] }}" class="img-fluid">
                </figure>
                <div class="caption">
                    {{ $result['vehicles']['manufacturers']['manufacturer_name'].' '.$result['vehicles']['model']  }}
                    <br>
                    {{ strtoupper($result['vehicles']['license_plate']) }}<br>
                    @component('components.misc.rating',
                    [
                    'average_rating' => $result['average_rating'],
                    'total_votes' => count($result['ratings']),
                    'advanced' => true
                    ])
                    @endcomponent
                </div>
            </div>
            @isset($result['vehicle_images'])
                <div id="vehicle-gallery{{ $result['vehicle_id'] }}" class="hidden">
                    @foreach($result['vehicle_images'] as $vi)
                        <a href="{{ $vi }}">{{ $result['vehicles']['manufacturers']['manufacturer_name'].' '.$result['vehicles']['model']  }}</a>
                    @endforeach
                </div>
                <a data-gallery="#vehicle-gallery{{ $result['vehicle_id'] }}" href="#"
                   class="vehicle-gallery-link vegatr">
                    <i class="fa fa-camera"></i> {{ Lang::get('misc.view_vehicle_images') }}
                </a>
            @endisset
            <div class="tabs-selector">
                <a href="#features" class="active">{{ Lang::get('misc.features') }}</a>
                <a href="#reviews">{{ Lang::get('misc.reviews') }}</a>
            </div>
            <div class="tabs">
                <div id="features" class="tab-content">
                    <div class="info4">
                        <div class="row">
                            @foreach($result['vehicles']['fullspecifications'] as $specs)
                                <div class="col-md-6">
                                    <div class="ticket-info-service clearfix">
                                        <figure><img
                                                src="{{ Storage::temporaryUrl('vehicle-features/'.$specs['pivot']['vehicle_specification_id'].'.'.$specs['extension'], now()->addMinutes(5)) }}"
                                                alt="{{ $specs['translated']['name'] }}" class="img-fluid"></figure>
                                        <div class="caption">{{ $specs['translated']['name'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="reviews" class="tab-content hidden">
                    <div class="info5">
                        @component('components.reviews', ['reviews' => $result['ratings'], 'driver_user_id' => $result['user_id'], 'advanced' => 1])
                        @endcomponent
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="ticket-scheme">
        <div class="txt1">{{ Lang::get('misc.choose_seats') }}</div>
        <div class="txt2">{!! Lang::get('misc.choose_seats2') !!}</div>

        @component('components.vehicle-schemes', ['seat_positioning' => $result['vehicles']['seat_positioning'], 'chosen_seats' => $result['sales'] ?? []])
            @slot('route_type') {{ $all_route_types[$result['vehicles']['type']-1]['key'] }} @endslot
            @slot('show_info') @endslot
        @endcomponent
    </div>


    @component('components.misc.form')
        @slot('form_id') ticketBooking @endslot
        @component('components.misc.form-group-col')
            @slot('type') hidden @endslot
            @slot('hideGroup') @endslot
            @slot('name') route_id @endslot
            @slot('value') {{ $result['id'] }}@endslot
        @endcomponent
        @component('components.misc.form-group-col')
            @slot('type') hidden @endslot
            @slot('hideGroup') @endslot
            @slot('name') price @endslot
            @slot('value') {{ $result['price'] }} @endslot
        @endcomponent

        <div class="ticket-passengers hidden">
            <div class="title">{{ Lang::get('misc.passengers') }}</div>
        </div>
        <div class="details-of-payment">
            <div class="response"></div>
            <div class="title">{{ Lang::get('misc.details_of_payment') }}</div>
            <div class="subtitle">{{ Lang::get('misc.payment_method') }}</div>
            <div class="row">
                <div class="col-lg-9">
                    <div class="row border-bottom-grey">
                        <div class="col-md-6">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="payment_method" id="optionsRadios1" value="1" checked>
                                    {{ Lang::get('misc.credit_card') }} <img
                                        src="{{ URL::asset('images/credit-card.png') }}" alt="" class="img-fluid">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="payment_method" id="optionsRadios2" value="2">
                                    PayPal <img src="{{ URL::asset('images/paypal.png') }}" alt="" class="img-fluid">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    @component('components.misc.form-group-col')
                        @slot('disabled') @endslot
                        @slot('name') amount @endslot
                        @slot('group_class') details-of-payment-number @endslot
                        @slot('value') 0 @endslot
                        @slot('label') {{ Lang::get('misc.amount_of_tickets') }} @endslot
                    @endcomponent
                </div>
            </div>


            <div class="details-of-payment-total hidden">
                <div class="txt1">{{ Lang::get('misc.total') }}:</div>
                <div class="txt2"><span></span> <img
                        src="{{ URL::asset('images/currencies/'.$currencies[$current_currency]['key'].'.png') }}"
                        alt="{{ $currencies[$current_currency]['key'] }}"></div>
            </div>

            <div class="details-of-payment-buttons">
                @if(Auth::check())
                    @component('components.misc.submit-button')
                        @slot('class') btn2 @endslot
                        @slot('name') action @endslot
                        @slot('value') cart @endslot
                        @slot('faicon') fa-shopping-cart @endslot
                        @slot('anchor') {{ Lang::get('misc.add_to_cart') }}@endslot
                    @endcomponent
                @endif
                @component('components.misc.submit-button')
                    @slot('class') btn3 @endslot
                    @slot('name') action @endslot
                    @slot('value') buy @endslot
                    @slot('faicon') fa-credit-card-alt @endslot
                    @slot('anchor') {{ Lang::get('misc.pay_now') }}@endslot
                @endcomponent
            </div>
            <div class="details-of-payment-note">
                <span>*</span> {!!  Lang::get('misc.payment_note', ['value' => route('terms_of_use')]) !!}
            </div>
        </div>
    @endcomponent


@endsection

@push('scripts')
    <script src="{{ URL::asset('js/listingMaps.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google-maps.key') }}&callback=initMap"
            async defer></script>
@endpush
