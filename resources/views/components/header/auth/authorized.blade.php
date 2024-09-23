<div class="account dropdown">
    <a class="dropdown-toggle" href="#" role="button" id="dropdownTemplates" data-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false">
        <img src="{{ Auth::user()->photoSmall() }}" alt="Avatar Small {{ Auth::user()->id }}"
             class="img-fluid avatar-small"><span>{{ Lang::get('auth.my_account') }}</span>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownTemplates">
        <a class="dropdown-item" href="{{ route('profile') }}">{{ Lang::get('menu.profile') }}</a>
        <a class="dropdown-item" href="{{ route('bought_tickets') }}">{{ Lang::get('menu.bought_tickets') }}</a>
        @if(!isset($isDriver))
            <a class="dropdown-item" href="{{ route('driver_registration') }}">{{ Lang::get('menu.become_driver') }}</a>
        @else
            <a class="dropdown-item" href="{{ route('driver_profit') }}">{{ Lang::get('menu.driver_profile') }}</a>
        @endif
        @if(!isset($isPartner))
            <a class="dropdown-item"
               href="{{ route('partner_registration') }}">{{ Lang::get('menu.become_partner') }}</a>
        @else
            <a class="dropdown-item" href="{{ route('partner_profit') }}">{{ Lang::get('menu.partner_profile') }}</a>
        @endif

        <a class="dropdown-item" href="{{ route('financial') }}">{{ Lang::get('menu.financial_parameters') }}</a>
        <a class="dropdown-item logout" href="javascript:void(0)">{{ Lang::get('menu.logout') }}</a>
    </div>
</div>
