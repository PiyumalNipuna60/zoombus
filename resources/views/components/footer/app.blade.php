<div class="site-footer-wrapper">
    <div class="site-footer clearfix2">
        <div class="container">
            <div class="footer-block">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        @component('components.footer.reviews', ['reviews' => $site_reviews])
                            @slot('title') {{ Lang::get('misc.footer_reviews') }} @endslot
                        @endcomponent
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-block-title">{{ Lang::get('misc.footer_menu') }}</div>
                        <ul class="footer-block-menu">
                            <li class="red"><a
                                    href="{{ route('register_as_partner') }}">{{ Lang::get('auth.become_partner') }}</a>
                            </li>
                            <li class="blue"><a
                                    href="{{ route('register_as_driver') }}">{{ Lang::get('auth.become_driver') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('listings_rt', ['route_type' => 'minibus']) }}">{{ Lang::get('menu.minibus_tickets') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('listings_rt', ['route_type' => 'bus']) }}">{{ Lang::get('menu.bus_tickets') }}</a>
                            </li>
                            {{--                            <li>--}}
                            {{--                                <a href="{{ route('listings_rt', ['route_type' => 'carpooling']) }}">{{ Lang::get('menu.carpooling_tickets') }}</a>--}}
                            {{--                            </li>--}}
                        </ul>
                        @if(Auth::check())
                            <div class="footer-block-cart">
                                <a href="{{ route('cart') }}">
                                    <div class="txt1">{{ count($cart_items ?? []) }}</div>
                                    <div class="txt2">{{ Lang::get('misc.items_in_your_cart') }}</div>
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="footer-block-title">{{ Lang::get('misc.footer_about') }}</div>
                        <div class="footer-block-text">
                            {!! Lang::get('misc.footer_about_text') !!}
                        </div>
                        {{--                        <div class="footer-block-title">სოციალური ქსელები</div>--}}
                        {{--                        <ul class="footer-block-social clearfix">--}}
                        {{--                            <li class="pinterest"><a href="#"></a></li>--}}
                        {{--                            <li class="twitter"><a href="#"></a></li>--}}
                        {{--                            <li class="behance"><a href="#"></a></li>--}}
                        {{--                            <li class="dribbble"><a href="#"></a></li>--}}
                        {{--                            <li class="facebook"><a href="#"></a></li>--}}
                        {{--                            <li class="linkedin"><a href="#"></a></li>--}}
                        {{--                        </ul>--}}
                    </div>
                </div>
            </div>
            <div class="pre-footer">
                <div class="row">
                    <div class="col-md-3">
                        <div class="logo-footer clearfix">
                            <figure>
                                <img src="{{ URL::asset('images/logo-footer.png') }}" alt="" class="img-fluid">
                            </figure>
                            <div class="caption">
                                <div class="txt1">zoombus.net</div>
                                <div class="txt2">Copyright © All rights Reserved</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="pre-footer-block">
                            <figure>
                                <img src="{{ URL::asset('images/icon-globe.png') }}" alt="" class="img-fluid">
                            </figure>
                            <div class="caption">
                                {{ Lang::get('misc.footer_feature_1') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="pre-footer-block">
                            <figure>
                                <img src="{{ URL::asset('images/icon-hands.png') }}" alt="" class="img-fluid">
                            </figure>
                            <div class="caption">
                                {{ Lang::get('misc.footer_feature_2') }}
                            </div>
                        </div>
                    </div>
                    @if(isset($footerNews))
                        <div class="col-md-3">
                            <div class="pre-footer-block pt-0">
                                <ul class="footer-block-menu">
                                    @foreach($footerNews as $fn)
                                        <li class="blue pb-0">
                                            <a href="{{ $fn['url'] }}">{{ $fn['title'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="copyrights">made by <a href="#">web technology l.t.d.</a></div>
        </div>
    </div>
</div>
