@extends('layouts.app')

@section('title', Lang::get('seo.bought_tickets_title'))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
    single_ticket_mobile
@stop

@section('content')
    <div class="ticket-print d-flex" id="single_ticket">
        <div class="ticket-print-left">
            <div class="print-row">
                <div class="print-col print-col20">
                    <div class="ticket-print-block qr">
                        <figure><img src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl('tickets/'.md5($tickets['ticket_number']).'.png', now()->addMinutes(5)) }}" alt="Your QR Code"
                                     class="img-fluid"></figure>
                    </div>
                </div>
                <div class="print-col print-col80">
                    <div class="ticket-print-block">
                        <div class="txt1">{{ Lang::get('misc.name_of_passenger') }}</div>
                        <div class="txt2">{{ $tickets['users']['name'] }}</div>
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
            </div>
        </div>
    </div>
    {!! $password_form  ?? null !!}
@endsection
