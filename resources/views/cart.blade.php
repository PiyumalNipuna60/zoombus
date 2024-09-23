@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.cart_title'))

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


@section('title1', $title1 ?? Lang::get('titles.cart'))
@section('title2', $title2 ?? Lang::get('titles.cart2'))

@section('content')
    <div class="details-of-payment">
        @if(count($cart_items) > 0)
            <div class="details-of-payment-table">
                <div class="table-responsive table2-wrapper">
                    <table class="table table2" id="cartTable">
                        <thead>
                        <tr>
                            <th class="col1">{{ Lang::get('cart.route') }}</th>
                            <th class="col2">{{ Lang::get('cart.user') }}</th>
                            <th class="col3">{{ Lang::get('cart.date') }}</th>
                            <th class="col4">{{ Lang::get('cart.time') }}</th>
                            <th class="col5">{{ Lang::get('cart.type') }}</th>
                            <th class="col6">{{ Lang::get('cart.seat') }}</th>
                            <th class="col7">{{ Lang::get('cart.price') }}</th>
                            <th class="col8"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cart_items as $item)
                            <tr>
                                <td class="col1">
                                    {{ $item['sales']['routes']['cities_from']['translated']['name'] }} -
                                    {{ $item['sales']['routes']['cities_to']['translated']['name'] }} - #
                                    {{ $item['sales']['routes']['cities_from']['code'].$item['sales']['routes']['id'] }}
                                </td>
                                <td class="col2">{{ $item['sales']['users']['name'] }}</td>
                                <td class="col3">{{ \Carbon\Carbon::parse($item['sales']['routes']['departure_date'])->translatedFormat('d M Y') }}</td>
                                <td class="col4">{{ $item['sales']['routes']['departure_time'] }}</td>
                                <td class="col5">{{ $item['sales']['routes']['vehicles']['route_types']['translated']['name'] }}</td>
                                <td class="col6">{{ $item['sales']['seat_number'] }}</td>
                                <td class="col7">{{ $item['sales']['price'] }} {{ $current_currency_key ?? 'GEL' }}</td>
                                <td class="col8">
                                    <button type="button" class="close-btn removeFromCart" data-id="{{ $item['id'] }}" aria-label="Close"></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="subtitle">{{ Lang::get('misc.payment_method') }}</div>


            @component('components.misc.form')
                @slot('form_id') cartCheckout @endslot
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
                            @slot('value') {{ count($cart_items) }} @endslot
                            @slot('label') {{ Lang::get('misc.amount_of_tickets') }} @endslot
                        @endcomponent
                    </div>
                </div>

                <div class="details-of-payment-total">
                    <div class="txt1">{{ Lang::get('misc.total') }}:</div>
                    <div class="txt2"><span>{{ array_sum(array_column(array_column($cart_items,'sales'), 'price')) }}</span> <img
                                src="{{ URL::asset('images/currencies/'.$currencies[$current_currency]['key'].'.png') }}"
                                alt="{{ $currencies[$current_currency]['key'] }}"></div>
                </div>

                <div class="details-of-payment-buttons">
                    <a href="{{ route('listings') }}"
                       class="btn-continue-shopping">{{ Lang::get('cart.continue_shopping') }} <i
                                class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                    @component('components.misc.submit-button')
                        @slot('class') btn3 @endslot
                        @slot('name') action @endslot
                        @slot('value') buy @endslot
                        @slot('faicon') fa-credit-card-alt @endslot
                        @slot('anchor') {{ Lang::get('misc.pay_now') }}@endslot
                    @endcomponent
                </div>
            @endcomponent

        @else
            <h1 class="text-center pt-5 pb-5">{{ Lang::get('cart.empty') }}</h1>
        @endif

    </div>
@endsection
