<div class="panel panel-default form-horizontal">
    <div class="panel-body">
        <h3><span class="fa fa-car"></span> {{ Lang::get('admin.vehicle_info') }}</h3>
        <p>{{ Lang::get('admin.vehicle_info_small') }}</p>
    </div>
    <div class="panel-body form-group-separated">
        @if(!empty($vehicle))
            <div class="form-group">
                <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.name_of_driver') }}</label>
                <div class="col-md-8 col-xs-7 line-height-30">
                    <a href="{{ route('admin_user_edit', ['id' => $vehicle['user']['id']]) }}" target="_blank" class="badge badge-info">
                        <i class="fa fa-user"></i> {{ $vehicle['user']['name'] }}
                    </a>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.phone_number') }}</label>
                <div class="col-md-8 col-xs-7 line-height-30">
                    <a href="{{ route('admin_user_edit', ['id' => $vehicle['user']['id']]) }}" target="_blank" class="badge badge-info">
                        <i class="fa fa-phone"></i> {{ $vehicle['user']['phone_number'] }}
                    </a>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin_titles.registered_routes') }}</label>
                <div class="col-md-8 col-xs-7 line-height-30"><span
                        class="badge badge-success">{{ $vehicle['routes_count'] ?? 0 }}</span></div>
            </div>
            <div class="form-group">
                <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.number_of_seats') }}</label>
                <div class="col-md-8 col-xs-7 line-height-30"><span
                        class="badge badge-success">{{ $vehicle['number_of_seats'] ?? 0 }}</span></div>
            </div>

        @endif
    </div>

</div>
