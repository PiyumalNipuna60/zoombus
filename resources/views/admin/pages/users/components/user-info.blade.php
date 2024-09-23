<div class="panel panel-default form-horizontal">
    <div class="panel-body">
        <h3><span class="fa fa-user"></span> {{ Lang::get('admin.user_information') }}</h3>
        <p>{{ Lang::get('admin.user_information_small') }}</p>
    </div>
    <div class="panel-body form-group-separated">
        @if(!empty($driverInfo))
            <div class="form-group">
                <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.registered_vehicles') }}</label>
                <div class="col-md-8 col-xs-7 line-height-30"><span
                        class="badge badge-success" data-alias="tab-vehicles">0</span></div>
            </div>
            <div class="form-group">
                <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.tickets_sold') }}</label>
                <div class="col-md-8 col-xs-7 line-height-30"><span class="badge badge-info" data-alias="tab-sales">0</span></div>
            </div>
        @endif
        @if(!empty($partnerInfo))
                <div class="form-group">
                    <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.tier1_affiliates') }}</label>
                    <div class="col-md-8 col-xs-7 line-height-30"><span
                            class="badge badge-info">{{ $partnerInfo['tier_1_count'] ?? 0 }}</span></div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.tier2_affiliates') }}</label>
                    <div class="col-md-8 col-xs-7 line-height-30"><span
                            class="badge badge-info">{{ $partnerInfo['tier_2_count'] ?? 0 }}</span></div>
                </div>
        @endif
        <div class="form-group">
            <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.balance') }}</label>
            <div class="col-md-8 col-xs-7"><span
                   class="badge badge-success">{{ $userInfo['balance'] ?? 0 }} GEL</span></div> {{-- FUTURE currency--}}
        </div>

        <div class="form-group">
            <label class="col-md-4 col-xs-5 control-label">{{ Lang::get('admin.withdrawn') }}</label>
            <div class="col-md-8 col-xs-7 line-height-30"><span
                    class="badge badge-danger">{{ $userInfo['total_withdrawn'] ?? 0 }} GEL {{-- FUTURE currency--}}</span></div>
        </div>
    </div>

</div>
