<nav class="navbar_ navbar navbar-expand-lg clearfix">
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="nav navbar-nav sf-menu clearfix">
            <li class="nav1 nav-item sub-menu">
                <a href="{{ route('listings') }}" class="nav-link">{{ Lang::get('misc.book') }} <i
                        class="fa fa-chevron-down" aria-hidden="true"></i></a>
                <div class="sf-mega">
                    <ul>
                        <li>
                            <a href="{{ route('listings_rt', ['route_type' => 'minibus']) }}">
                                {{ Lang::get('menu.minibus_tickets') }}
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('listings_rt', ['route_type' => 'bus']) }}">
                                {{ Lang::get('menu.bus_tickets') }}
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </a>
                        </li>
{{--                        <li>--}}
{{--                            <a href="{{ route('listings_rt', ['route_type' => 'carpooling']) }}">--}}
{{--                                {{ Lang::get('menu.carpooling_tickets') }}--}}
{{--                                <i class="fa fa-angle-right" aria-hidden="true"></i>--}}
{{--                            </a>--}}
{{--                        </li>--}}
                    </ul>
                </div>
            </li>
            @if($faqs)
                <li class="nav2 nav-item sub-menu2">
                    <a href="{{ route('faqs') }}" class="nav-link">{{ Lang::get('menu.faq') }} <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                    <div class="sf-mega">
                        <ul>
                            @foreach($faqs as $faq)
                                <li>
                                    <a href="{{ $faq['url'] }}">
                                        {{ $faq['seo_title'] }}
                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif
            <li class="nav3 nav-item">
                <a href="{{ (Auth::check()) ? route('partner_registration') : route('register_as_partner') }}" class="nav-link">
                    {{ Lang::get('auth.become_partner') }}
                </a>
            </li>
            <li class="nav4 nav-item">
                <a href="{{ (Auth::check()) ? route('driver_registration') : route('register_as_driver') }}" class="nav-link">
                    {{ Lang::get('auth.become_driver') }}
                </a>
            </li>
        </ul>
    </div>
</nav>
