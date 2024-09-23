@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.bought_tickets_title'))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@section('content')
    {!! $password_form  ?? null !!}
    <div class="ticket-print d-flex mg-t-30" id="single_ticket">
        <div class="ticket-print-left">
            <div class="ticket-print-header">
                <figure><img src="{{ URL::asset('/images/ticket-print-img-bus.png') }}" alt="" class="img-fluid"></figure>
                <div class="caption">
                    {{ Lang::get('misc.travel_ticket') }}
                </div>
            </div>
            <div class="ticket-print-footer"></div>
            <div class="print-row">
                <div class="print-col print-col40">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.name_of_passenger') }}</div>
                        <div class="txt2">{{ $tickets['users']['name'] }}</div>
                    </div>
                </div>
                <div class="print-col print-col40">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.route_number') }}</div>
                        <div class="txt2">{{ $tickets['routes']['cities_from']['city_code'].$tickets['routes']['id'] }}</div>
                    </div>
                </div>
                <div class="print-col print-col20">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.ticket_number') }}</div>
                        <div class="txt2">{{ $tickets['ticket_number'] }}</div>
                    </div>
                </div>
                <div class="print-col print-col40">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.travel_from') }}</div>
                        <div class="txt2">{{ $tickets['routes']['cities_from']['translated']['name'] }}</div>
                    </div>
                </div>
                <div class="print-col print-col40">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.vehicle_model_number') }}</div>
                        <div class="txt2 ge">{{
                                $tickets['routes']['vehicles']['manufacturers']['manufacturer_name'] . ' ' . $tickets['routes']['vehicles']['model'] . ' / '. $tickets['routes']['vehicles']['license_plate']
                                }}</div>
                    </div>
                </div>
                <div class="print-col print-col20">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.departure_date') }}</div>
                        <div class="txt2">{{ \Carbon\Carbon::parse($tickets['routes']['departure_date'])->translatedFormat('j\ M Y') }}</div>
                    </div>
                </div>
                <div class="print-col print-col80">
                    <div class="print-row">
                        <div class="print-col print-col50">
                            <div class="ticket-print-block">
                                <div class="txt1">{{ Lang::get('misc.travel_to') }}</div>
                                <div class="txt2">{{ $tickets['routes']['cities_to']['translated']['name'] }}</div>
                            </div>
                        </div>
                        <div class="print-col print-col25">
                            <div class="ticket-print-block">
                                <div class="txt1">{{ Lang::get('misc.seat') }}</div>
                                <div class="txt2 ge">{{ $tickets['seat_number'] }}</div>
                            </div>
                        </div>
                        <div class="print-col print-col25">
                            <div class="ticket-print-block">
                                <div class="txt1">{{ Lang::get('driver.time') }}</div>
                                <div class="txt2 ge">{{ $tickets['routes']['departure_time'] }}</div>
                            </div>
                        </div>
                        <div class="print-col print-col50">
                            <div class="ticket-print-block">
                                <div class="txt1">{{ Lang::get('driver.departure_address') }}</div>
                                <div class="txt3">{{ $tickets['routes']['address_from']['translated']['name'] }}</div>
                            </div>
                        </div>
                        <div class="print-col print-col50">
                            <div class="ticket-print-block">
                                <div class="txt1">{{ Lang::get('driver.arrival_address') }}</div>
                                <div class="txt3">{{ $tickets['routes']['address_to']['translated']['name'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="print-col print-col20">
                    <div class="ticket-print-block qr">
                        <figure><img src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl('tickets/'.md5($tickets['ticket_number']).'.png', now()->addMinutes(5)) }}" alt="Your QR Code"
                                     class="img-fluid"></figure>
                    </div>
                </div>
            </div>
        </div>
        <div class="ticket-print-right">
            <div class="ticket-print-header">
                <figure><img src="{{ URL::asset('/images/ticket-print-img-bus.png') }}" alt="Ticket print bus"
                             class="img-fluid"></figure>
            </div>
            <div class="ticket-print-footer"></div>
            <div class="print-row">
                <div class="print-col print-col100">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.name_of_passenger') }}</div>
                        <div class="txt2 smaller">{{ $tickets['users']['name'] }}</div>
                    </div>
                </div>
                <div class="print-col print-col100">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.travel_from') }}</div>
                        <div class="txt2 smaller">{{ $tickets['routes']['cities_from']['translated']['name'] }}</div>
                    </div>
                </div>
                <div class="print-col print-col100">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.travel_to') }}</div>
                        <div class="txt2 smaller">{{ $tickets['routes']['cities_to']['translated']['name'] }}</div>
                    </div>
                </div>
                <div class="print-col print-col100">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.vehicle_model_number') }}</div>
                        <div class="txt2 smaller">{{
                                $tickets['routes']['vehicles']['manufacturers']['manufacturer_name'] . ' ' . $tickets['routes']['vehicles']['model'] . ' / '. $tickets['routes']['vehicles']['license_plate']
                                }}</div>
                    </div>
                </div>
                <div class="print-col print-col100">
                    <div class="print-row">
                        <div class="print-col print-col50">
                            <div class="ticket-print-block">
                                <div class="txt1">{{ Lang::get('misc.departure_date') }}</div>
                                <div class="txt2 smaller">{{ \Carbon\Carbon::parse($tickets['routes']['departure_date'])->translatedFormat('j\ M Y') }}</div>
                            </div>
                        </div>
                        <div class="print-col print-col25">
                            <div class="ticket-print-block">
                                <div class="txt1">{{ Lang::get('driver.time') }}</div>
                                <div class="txt2 ge smaller">{{ $tickets['routes']['departure_time'] }}</div>
                            </div>
                        </div>
                        <div class="print-col print-col25">
                            <div class="ticket-print-block">
                                <div class="txt1">{{ ($current_locale == "ka") ? mb_substr(Lang::get('misc.seat'), 0, 3).'.' : Lang::get('misc.seat') }}</div>
                                <div class="txt2 ge smaller">{{ $tickets['seat_number'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @component('components.misc.form-group-col', ['alertify' => $deleteAlertify ?? []])
        @slot('field') button @endslot
        @slot('type') button @endslot
        @slot('class') btn-cancel ticket_cancel @endslot
        @slot('name') cancel @endslot
        @isset($disableDelete) @slot('disabled') @endslot @endisset
        @slot('faicon') fa-trash @endslot
        @slot('label') &nbsp; @endslot
        @slot('anchor') {{ Lang::get('auth.cancel_ticket') }} @endslot
    @endcomponent
    <div class="print-section">
        <div class="print-ticket"><i class="fa fa-print"></i> {{ Lang::get('misc.print') }}</div>
    </div>
@endsection

@push('scripts')
    <script src="{{ URL::asset('js/alertify.min.js') }}"></script>
@endpush
