<div class="mobile-tickets-wrapper">
    <div class="container">
        <ul class="mobile-tickets">
            <li><a href="{{ route('listings', ['route_type' => 'minibus']) }}">{{ Lang::get('menu.minibus_tickets') }}</a></li>
            <li><a href="{{ route('listings', ['route_type' => 'bus']) }}">{{ Lang::get('menu.bus_tickets') }}</a></li>
{{--            <li><a href="{{ route('listings', ['route_type' => 'carpooling']) }}">{{ Lang::get('menu.carpooling_tickets') }}</a></li>--}}
        </ul>
    </div>
</div>
