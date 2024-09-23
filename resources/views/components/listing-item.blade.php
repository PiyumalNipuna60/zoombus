@foreach($results as $result)
    <div class="ticket-details-info">
        <div class="ticket-details-info-top">
            <div class="ticket-details d-flex">
                <div class="ticket-details-left d-flex">
                    <div class="ticket-details-left-col1">
                        <div class="info clearfix">
                            <img
                                src="{{ URL::asset('images/route-types/'.$result['vehicles']['type'].'-small.png') }}"
                                alt="{{ $result['vehicles']['type'] }}" class="img-responsive">
                            <div class="caption">
                                <div
                                    class="txt1">{{ $result['vehicles']['manufacturers']['manufacturer_name'].' '.$result['vehicles']['model'] }}</div>
                                <div class="txt2">{{ $result['vehicles']['license_plate'] }}</div>
                            </div>
                        </div>
                        <div class="txt3">
                            <span>{{ $result['vehicles']['number_of_seats'] }}</span> {{ Lang::get('misc.seats') }}
                        </div>
                        <div class="rating">
                            @component('components.misc.rating',
                            ['average_rating' => $result['average_rating']])
                            @endcomponent
                        </div>
                    </div>
                    <div class="ticket-details-left-col2">
                        <div class="info d-flex">
                            <div class="info1">
                                <div class="time1">{{ $result['departure_time'] }}</div>
                                <div class="date1">{{
                                        \Carbon\Carbon::parse($result['departure_date'])->translatedFormat('d M, ').
                                        \Carbon\Carbon::parse($result['departure_date'])->translatedFormat('l')
                                        }}</div>
                                <div
                                    class="place1 from_city">{{ $result['cities_from']['translated']['name'] }}</div>
                            </div>
                            <div class="info2">
                                <div class="txt1">
                                    @php
                                        $dif = strtotime($result['departure_date'].' '.$result['departure_time'].':00');
                                        $dif2 = strtotime($result['arrival_date'].' '.$result['arrival_time'].':00');
                                        $tt = $dif2-$dif;
                                    @endphp
                                    {{ trans_choice('misc.days', gmdate('z', $tt)) }}
                                    {{ gmdate('H:i', $tt) }}
                                    {{ trans_choice('misc.hours_short', gmdate('H', $tt)) }}
                                </div>
                                <div class="line1"></div>
                                <div
                                    class="txt2">{{ Controller::timeForHumans($result['stopping_time']) }}</div>
                            </div>
                            <div class="info3">
                                <div class="time1">{{ $result['arrival_time'] }}</div>
                                <div class="date1">{{
                                        \Carbon\Carbon::parse($result['arrival_date'])->translatedFormat('d M, ').
                                        \Carbon\Carbon::parse($result['departure_date'])->translatedFormat('l')
                                        }}
                                </div>
                                <div
                                    class="place1 to_city">{{ $result['cities_to']['translated']['name'] }}</div>
                            </div>
                        </div>
                        <div class="details"><a href="#">{{ Lang::get('misc.details') }}</a></div>
                    </div>
                    <div class="ticket-details-left-col3">
                        <div class="txt1">{{ $result['price'] }}<span></span></div>
                        <div class="txt2"><span>{{ Lang::get('misc.refundable') }}</span></div>
                    </div>
                </div>
                <div class="ticket-details-right">
                    <div class="txt1">
                        <a href="{{ route('single_listing', [
                                'id' => $result['id'],
                                'from' => strtolower($result['cities_from']['translate_to_en']['name']),
                                'to' => strtolower($result['cities_to']['translate_to_en']['name']),
                                'departure_date' =>
                                (isset($departure_date)  && $departure_date != "Invalid date")
                                ? $departure_date
                                : \Carbon\Carbon::parse($result['departure_date'])->format('Y-m-d') ]) }}"
                        >
                            {{ Lang::get('misc.book') }}
                        </a>
                    </div>
                    <div class="txt2">
                        <span>{{ Lang::get('misc.remaining', ['value' => $result['remaining_seats']['remaining_seats'] ?? $result['vehicles']['number_of_seats'] ] ) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="ticket-details-info-bottom">
            <div class="ticket-info d-flex">
                <div class="ticket-info-left">
                    <div class="info d-flex">
                        <div class="info1">
                            <div class="img1"><img
                                    src="{{ Storage::temporaryUrl('cities/'.$result['cities_from']['id'].'.'.$result['cities_from']['extension'], now()->addMinutes(5)) }}"
                                    alt="{{ $result['vehicles']['type'] }}" class="img-fluid"></div>
                            <div class="txt1">{{ $result['cities_from']['translated']['name'] }}</div>
                            <div class="txt2">
                                {{ Lang::get('driver.departure_address') }}
                                :<br>{{ $result['address_from']['translated']['name'] }}
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
                            <div class="img1"><img
                                    src="{{ Storage::temporaryUrl('cities/'.$result['cities_to']['id'].'.'.$result['cities_to']['extension'], now()->addMinutes(5)) }}"
                                    alt="{{ $result['cities_to']['translated']['name'] }}" class="img-fluid">
                            </div>
                            <div class="txt1">{{ $result['cities_to']['translated']['name'] }}</div>
                            <div class="txt2">
                                {{ Lang::get('driver.arrival_address') }}
                                :<br>{{ $result['address_to']['translated']['name'] }}
                            </div>
                        </div>
                    </div>
                    <div class="ticket-info-googlemap"></div>
                </div>
                <div class="ticket-info-right">
                    <div
                        class="info1">{{ \Carbon\Carbon::parse($result['departure_date'])->translatedformat('j\ F, ').\Carbon\Carbon::parse($result['departure_date'])->translatedformat('l')  }}</div>
                    <div class="info2 clearfix">
                        <figure>
                            <img
                                src="{{ URL::asset('images/route-types/'.$result['vehicles']['type'].'.png') }}"
                                alt="{{ $result['vehicles']['type'] }}" class="img-fluid">
                        </figure>
                        <div class="caption">
                            {{ $result['vehicles']['manufacturers']['manufacturer_name'].' '.$result['vehicles']['model']  }}
                            <br>
                            {{ strtoupper($result['vehicles']['license_plate']) }}<br>
                            @component('components.misc.rating',
                            ['average_rating' => $result['average_rating']])
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
                           class="vehicle-gallery-link vegatr"><i
                                class="fa fa-camera"></i> {{ Lang::get('misc.view_vehicle_images') }}</a>
                    @endisset
                    <div class="info4">
                        <div class="row">
                            @foreach($result['vehicles']['specifications'] as $specs)
                                <div class="col-md-6">
                                    <div class="ticket-info-service clearfix">
                                        <figure><img
                                                src="{{ Storage::temporaryUrl('vehicle-features/'.$specs['pivot']['vehicle_specification_id'].'.'.$specs['extension'], now()->addMinutes(5)) }}"
                                                alt="{{ $specs['translated']['name'] }}" class="img-fluid">
                                        </figure>
                                        <div class="caption">{{ $specs['translated']['name'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr class="divider3 mg-0">
                    <div class="info5">
                        <div class="row">
                            @component('components.reviews', ['reviews' => $result['ratings_limited']])
                            @endcomponent
                        </div>
                        {{--<a href="{{ route('listing', ['id' => $result['id']]) }}" class="text-right display-block">{{ Lang::get('misc.detailed_info') }}</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
