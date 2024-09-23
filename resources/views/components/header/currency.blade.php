<div class="currency dropdown">
    @if(count($currencies) > 1)
        <a class="dropdown-toggle" href="#" role="button" id="dropdownCurrency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{ URL::asset('images/currencies/'.$currencies[$current_currency]['key'].'.png') }}" alt="{{ $currencies[$current_currency]['key'] }}" class="img-fluid">
            <span>{{ $currencies[$current_currency]['key'] }}</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownCurrency">
            @foreach(\Illuminate\Support\Arr::except($currencies, $current_currency) as $curr)
                <a class="dropdown-item switch-currency" href="javascript:void(0);" data-currency="{{ $curr['key'] }}">
                    <img src="{{ URL::asset('images/currencies/'.$curr['key'].'.png') }}" alt="{{ $curr['key'] }}" class="img-fluid">{{ $curr['key'] }}
                </a>
            @endforeach
        </div>
    @else
        <a href="#" class="dropdown-toggle no-after">
            <img src="{{ URL::asset('images/currencies/'.$currencies[$current_currency]['key'].'.png') }}" alt="{{ $currencies[$current_currency]['key'] }}" class="img-fluid">
            <span>{{ $currencies[$current_currency]['key'] }}</span>
        </a>
    @endif

</div>