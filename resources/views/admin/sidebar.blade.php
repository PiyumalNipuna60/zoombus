<div class="page-sidebar">
    <!-- START X-NAVIGATION -->
    <ul class="x-navigation">
        <li class="xn-logo">
            <a href="{{ route('index') }}" target="_blank">Zoombus.net</a>
            <a href="{{ route('index') }}" class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a href="#" class="profile-mini">
                <img src="{{ Auth::user()->photo() }}" alt="Avatar {{ Auth::user()->id }}">
            </a>
            <div class="profile">
                <div class="profile-image">
                    <img src="{{ Auth::user()->photo() }}" alt="Avatar {{ Auth::user()->id }}">
                </div>
                <div class="profile-data">
                    <div class="profile-data-name">{{ Auth::user()->name }}</div>
                    <div class="profile-data-title">Administrator</div>
                </div>
                <div class="profile-controls">
                    <a href="{{ route('admin_user_edit', ['id' => Auth::user()->id ]) }}" class="profile-control-left"><span
                            class="fa fa-info"></span></a>
                </div>
            </div>
        </li>
        <li class="xn-title">Navigation</li>
        <li class="@if(Request::route()->getName() == 'admin_dashboard') active @endif">
            <a href="{{ route('admin_dashboard') }}"><span class="fa fa-dashboard"></span> <span class="xn-text">{{ Lang::get('admin_menu.dashboard') }}</span></a>
        </li>
        <li class="xn-openable
        @if(in_array(Request::route()->getName(),
        [
        'admin_users_administrators',
        'admin_users_drivers',
        'admin_users_partners',
        'admin_users_passengers',
        'admin_users_support_tickets',
        'admin_users_support_ticket_edit',
        'admin_user_edit'
        ])) active @endif ">
            <a href="#"><span class="fa fa-users"></span> <span class="xn-text">{{ Lang::get('admin_menu.users') }}</span></a>
            <ul>
                <li @if(Request::route()->getName() == 'admin_users_administrators') class="active" @endif>
                    <a href="{{ route('admin_users_administrators') }}"><i class="glyphicon glyphicon-user"></i>
                        {{ Lang::get('admin_menu.users.administrators') }}</a>
                </li>
                <li @if(Request::route()->getName() == 'admin_users_drivers') class="active" @endif>
                    <a href="{{ route('admin_users_drivers') }}"><i class="fa fa-drivers-license-o"></i> {{ Lang::get('admin_menu.users.drivers') }}</a>
                    <div class="informer informer-success">{{ $sidebar['driver_count'] ?? 0 }}</div>
                </li>
                <li @if(Request::route()->getName() == 'admin_users_partners') class="active" @endif>
                    <a href="{{ route('admin_users_partners') }}"><i class="fa fa-list-ol"></i> {{ Lang::get('admin_menu.users.partners') }}</a>
                    <div class="informer informer-info">{{ $sidebar['partner_count'] ?? 0 }}</div>
                </li>
                <li @if(Request::route()->getName() == 'admin_users_passengers') class="active" @endif>
                    <a href="{{ route('admin_users_passengers') }}"><i class="glyphicon glyphicon-user"></i> {{ Lang::get('admin_menu.users.passengers') }}</a>
                    <div class="informer informer-warning">{{ $sidebar['passenger_count'] ?? 0 }}</div>
                </li>
                <li @if(Request::route()->getName() == 'admin_users_support_tickets') class="active" @endif>
                    <a href="{{ route('admin_users_support_tickets') }}"><i class="fa fa-comments-o"></i> {{ Lang::get('admin_menu.users.support_tickets') }}</a>
                    <div class="informer informer-info">{{ $sidebar['support_ticket_count'] ?? 0 }}</div>
                </li>
            </ul>
        </li>
        <li class="xn
        @if(in_array(Request::route()->getName(),[
                    'admin_routes',
                    'admin_routes_edit',
                    ])) active @endif ">
            <a href="{{ route('admin_routes') }}"><span class="fa fa-road"></span> <span
                    class="xn-text">  {{ Lang::get('admin_menu.routes') }}</span></a>
            <div class="informer informer-success">{{ $sidebar['routes_total_count'] ?? 0 }}</div>
        </li>
        <li class="xn-openable @if(in_array(Request::route()->getName(),
        [
        'admin_vehicles',
        'admin_vehicles_all',
        'admin_vehicle_edit',
        ])) active @endif ">
            <a href="#"><span class="fa fa-car"></span> <span class="xn-text">{{ Lang::get('admin_menu.vehicles') }}</span></a>
            <ul>
                <li @if(Request::route()->getName() == 'admin_vehicles_all') class="active" @endif>
                    <a href="{{ route('admin_vehicles_all') }}"><i
                            class="fa fa-car"></i> {{ Lang::get('admin_menu.vehicles.all') }}
                    </a>
                    <div class="informer informer-success">{{ array_sum(array_column($sidebar['vehicle_types'], 'vehicle_count')) ?? 0 }}</div>
                </li>
                @foreach($sidebar['vehicle_types'] as $vt)
                    <li @if(request()->type == $vt['key']) class="active" @endif>
                        <a href="{{ route('admin_vehicles', ['type' => $vt['key']]) }}"><i
                                class="fa {{ $vt['faicon'] ?? 'fa-car' }}"></i> {{ Lang::get('admin_menu.vehicles.'.$vt['key']) }}
                        </a>
                        <div class="informer informer-success">{{ $vt['vehicle_count'] ?? 0 }}</div>
                    </li>
                @endforeach
            </ul>
        </li>

        <li class="xn-openable
        @if(in_array(Request::route()->getName(),
        [
        'admin_sales',
        'admin_payouts',
        'admin_payout_edit'
        ])) active @endif ">
            <a href="#"><span class="fa fa-dollar"></span><span class="xn-text">{{ Lang::get('admin_menu.finance') }}</span></a>
            <ul>
                <li @if(in_array(Request::route()->getName(),[
                    'admin_sales',
                    ])) class="active" @endif>
                    <a href="{{ route('admin_sales') }}"><i class="fa fa-ticket"></i> {{ Lang::get('admin_menu.finance.ticket_sales') }}</a>
                    <div class="informer informer-success">{{ $sidebar['sales_total_count'] ?? 0 }}</div>
                </li>
                <li @if(in_array(Request::route()->getName(),[
                    'admin_payouts',
                    'admin_payout_edit',
                    ])) class="active" @endif>
                    <a href="{{ route('admin_payouts') }}"><i class="fa fa-money"></i> {{ Lang::get('admin_menu.finance.withdrawals') }}</a>
                    <div class="informer informer-success">{{ $sidebar['withdraw_total_count'] ?? 0 }}</div>
                </li>
            </ul>
        </li>

        <li class="xn-openable
        @if(in_array(Request::route()->getName(),
        [
        'admin_cities',
        'admin_cities_edit',
        'admin_cities_add',
        'admin_address',
        'admin_address_add',
        'admin_address_edit',
        'admin_specs',
        'admin_specs_add',
        'admin_specs_edit',
        ])) active @endif ">
            <a href="#"><span class="fa fa-th"></span><span class="xn-text">{{ Lang::get('admin_menu.misc') }}</span></a>
            <ul>
                <li @if(in_array(Request::route()->getName(),[
                    'admin_cities',
                    'admin_cities_edit',
                    'admin_cities_add'])) class="active" @endif>
                    <a href="{{ route('admin_cities') }}">
                        <i class="fa fa-building-o"></i> {{ Lang::get('admin_menu.misc.cities') }}
                    </a>
                </li>
                <li @if(in_array(Request::route()->getName(),[
                    'admin_address',
                    'admin_address_add',
                    'admin_address_edit'])) class="active" @endif>
                    <a href="{{ route('admin_address') }}">
                        <i class="fa fa-map-pin"></i> {{ Lang::get('admin_menu.misc.addresses') }}
                    </a>
                </li>
                <li @if(in_array(Request::route()->getName(),[
                    'admin_specs',
                    'admin_specs_add',
                    'admin_specs_edit'])) class="active" @endif>
                    <a href="{{ route('admin_specs') }}">
                        <i class="fa fa-car"></i> {{ Lang::get('admin_menu.misc.vehicle_specs') }}
                    </a>
                </li>
            </ul>
        </li>


        <li class="xn
        @if(in_array(Request::route()->getName(),[
                    'admin_pages',
                    'admin_pages_add',
                    'admin_pages_edit'])) active @endif ">
            <a href="{{ route('admin_pages') }}"><span class="fa fa-file-text"></span> <span
                    class="xn-text">  {{ Lang::get('admin_menu.pages') }}</span></a>
        </li>

    </ul>
    <!-- END X-NAVIGATION -->
</div>
