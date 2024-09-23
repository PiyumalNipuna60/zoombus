<div class="user-menu-wrapper">
    <ul class="user-menu d-flex justify-content-between sf-menu">
        <li>
            <a href="#"
               @if(in_array(Request::route()->getName(), ['profile','drivers_license','edit_password','bought_tickets','financial'])) class="active" @endif>
                <i class="fa fa-user" aria-hidden="true"></i> {{Lang::get('menu.profile')}}
                <em class="fa fa-chevron-down" aria-hidden="true"></em>
            </a>
            <div class="sf-mega">
                <ul>
                    <li class="@if(Request::route()->getName() == 'profile') active @endif">
                        <a href="{{ route('profile') }}">{{Lang::get('menu.personal_settings')}} </a>
                    </li>
                    @isset($isDriver)
                        <li class="@if(Request::route()->getName() == 'drivers_license') active @endif">
                            <a href="{{ route('drivers_license') }}">{{Lang::get('menu.drivers_license')}}</a>
                        </li>
                    @endisset
                    <li class="@if(Request::route()->getName() == 'edit_password') active @endif">
                        <a href="{{ route('edit_password') }}">{{Lang::get('menu.change_password')}}</a>
                    </li>
                    @if(isset($isDriver) || isset($isPartner))
                        @if(isset($isDriver))
                        <li class="@if(Request::route()->getName() == 'bought_tickets') active @endif">
                            <a href="{{ route('bought_tickets') }}">{{Lang::get('menu.bought_tickets')}}</a>
                        </li>
                        @endif
                        <li class="@if(Request::route()->getName() == 'financial') active @endif">
                            <a href="{{ route('financial') }}">{{Lang::get('menu.financial_parameters')}}</a>
                        </li>
                    @endisset
                </ul>
            </div>
        </li>
        @if(isset($isDriver))
            <li>
                <a href="#"
                   @if(in_array(Request::route()->getName(), ['vehicle_registration','vehicles','routes','route_registration'])) class="active" @endif>
                    <i class="fa fa-car" aria-hidden="true"></i> {{Lang::get('menu.transport_and_routes')}}
                    <em class="fa fa-chevron-down" aria-hidden="true"></em>
                </a>
                <div class="sf-mega">
                    <ul>
                        <li class="@if(Request::route()->getName() == 'vehicle_registration') active @endif">
                            <a href="{{ route('vehicle_registration') }}">{{Lang::get('menu.vehicle_registration')}}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'vehicles') active @endif">
                            <a href="{{ route('vehicles_list') }}">{{Lang::get('menu.registered_vehicles')}}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'route_registration') active @endif">
                            <a href="{{ route('route_registration') }}">{{Lang::get('menu.route_registration')}}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'routes') active @endif">
                            <a href="{{ route('routes_list') }}">{{Lang::get('menu.routes')}}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="{{ route('driver_profit') }}"
                   @if(in_array(Request::route()->getName(), ['driver_profit','sales','driver_payouts','sales_history','current_sales','fines'])) class="active" @endif>
                    <i class="fa fa-drivers-license" aria-hidden="true"></i>
                    {{ Lang::get('menu.driver_profile') }}
                    <em class="fa fa-chevron-down" aria-hidden="true"></em></a>
                <div class="sf-mega">
                    <ul>
                        <li class="@if(Request::route()->getName() == 'current_sales') active @endif">
                            <a href="{{ route('current_sales') }}">{{ Lang::get('menu.current_sales') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'sales') active @endif">
                            <a href="{{ route('sales') }}">{{ Lang::get('menu.sales_history') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'sales_history') active @endif">
                            <a href="{{ route('sales_history') }}">{{ Lang::get('menu.sales_ticket_history') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'driver_profit') active @endif">
                            <a href="{{ route('driver_profit') }}">{{ Lang::get('menu.profit_balance') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'fines') active @endif">
                            <a href="{{ route('fines') }}">{{ Lang::get('menu.fines') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'driver_payouts') active @endif">
                            <a href="{{ route('driver_payouts') }}">{{ Lang::get('menu.payout_history') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @else
            <li>
                <a href="{{ route('bought_tickets') }}"
                   class="@if(Request::route()->getName() == 'bought_tickets') active @endif">
                    <i class="fa fa-address-card" aria-hidden="true"></i> {{Lang::get('menu.bought_tickets')}}
                </a>
            </li>
            <li>
                <a href="{{ route('driver_registration') }}" class="noDrop @if(Request::route()->getName() == 'driver_registration') active @endif">
                    <i class="fa fa-drivers-license" aria-hidden="true"></i>
                    {{ Lang::get('menu.register_as_driver') }}
                </a>
            </li>
        @endif
        @if(isset($isPartner) && Auth::user()->partner()->active()->exists())
            <li>
                <a href="{{ route('partner_profit') }}"
                   @if(in_array(Request::route()->getName(), ['partner_profit','partner_code','partner_list','partner_sales','partner_payouts'])) class="active" @endif>
                    <i class="fa fa-handshake-o" aria-hidden="true"></i> {{ Lang::get('menu.partner_account') }} <em
                        class="fa fa-chevron-down" aria-hidden="true"></em>
                </a>
                <div class="sf-mega">
                    <ul>
                        <li class="@if(Request::route()->getName() == 'partner_profit') active @endif">
                            <a href="{{ route('partner_profit') }}">{{ Lang::get('menu.profit_balance') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'partner_code') active @endif">
                            <a href="{{ route('partner_code') }}">{{ Lang::get('menu.register_your_partner') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'partner_list') active @endif">
                            <a href="{{ route('partner_list') }}">{{ Lang::get('menu.list_of_partners') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'partner_sales') active @endif">
                            <a href="{{ route('partner_sales') }}">{{ Lang::get('menu.partner_sales') }}</a>
                        </li>
                        <li class="@if(Request::route()->getName() == 'partner_payouts') active @endif">
                            <a href="{{ route('partner_payouts') }}">{{ Lang::get('menu.payout_history') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @else
            <li>
                <a href="{{ route('partner_registration') }}" class="noDrop @if(Request::route()->getName() == 'partner_registration') active @endif">
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                    {{ Lang::get('menu.register_as_partner') }}
                </a>
            </li>
        @endif

    </ul>
</div>
