<div class="my_cart_wrapper">
    <div class="my_cart_button @if(count($items) == 0) allow @endif">
        <a href="{{ route('cart') }}">
            <i class="" aria-hidden="true"></i>
            <span @if(count($items) > 0) class="new" @endif>{{ count($items) }}</span>
        </a>
    </div>
    @if(count($items) > 0)
        <div class="my_cart_popup">
            @foreach(array_slice($items, 0, 5, true) as $item)
                <div class="my_cart_item clearfix">
                    <a href="#" class="my_cart_item_close removeFromCart" data-id="{{ $item['id'] }}"><i class="fa fa-times"></i></a>
                    <div class="caption">
                        <div class="txt1">{{ $item['sales']['price'] }} {{ $current_currency_key }}</div>
                        <div class="txt2">
                            <a href="{{ route('single_listing', [
                            'id' => $item['sales']['routes']['id'],
                            'departure_date' => $item['sales']['routes']['departure_date'],
                            'from' => strtolower($item['sales']['routes']['cities_from']['translate_to_en']['name']),
                            'to' => strtolower($item['sales']['routes']['cities_to']['translate_to_en']['name']),
                            ]) }}">
                                {{ $item['sales']['routes']['cities_from']['translated']['name'] }} ->
                                {{ $item['sales']['routes']['cities_to']['translated']['name'] }}
                                <br>{{ $item['sales']['routes']['vehicles']['route_types']['translated']['name'] }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            @isset($total)
                <div class="my_cart_total clearfix">
                    <div class="left">{{ Lang::get('cart.total') }}:</div>
                    <div class="right">{{ $total }} {{ $current_currency_key }}</div>
                </div>
            @endisset
            <div class="my_cart_buttons">
                <a href="{{ route('cart') }}" class="my_cart_button2">{{ Lang::get('cart.view') }}</a>
            </div>
        </div>
    @endif
</div>
