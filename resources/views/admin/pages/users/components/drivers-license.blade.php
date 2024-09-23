@component('components.misc.form')
    @slot('ajax') {{ route('admin_drivers_license_change') }} @endslot
    @component('components.misc.form-group-col')
        @slot('field') hidden @endslot
        @slot('name') user_id @endslot
        @slot('value') {{ $driver['user_id'] }} @endslot
    @endcomponent
    <div class="panel panel-default drivers-license-edit">
        <div class="panel-body">
            <h3>{{ Lang::get('admin.drivers_license_title') }}</h3>
            <p>{{ Lang::get('admin.drivers_license_info') }}</p>
        </div>
        <div class="panel-body form-group-separated">
            @component('components.misc.form-group-col')
                @slot('label_class') col-md-3 col-xs-5 control-label @endslot
                @slot('label') {{ Lang::get('admin.license_number') }} @endslot
                @slot('name') license_number @endslot
                @slot('value') {{ $driver['license_number'] ?? null }} @endslot
                @slot('disabled') @endslot
                @slot('field_wrapper_class') col-md-9 col-xs-7 @endslot
            @endcomponent

            @if(\Illuminate\Support\Facades\Storage::disk('s3')->exists('drivers/license/'.$driver['user_id'].'/front_side.'.$driver['front_side_extension']))
                <div class="form-group">
                    <label class="col-md-3 col-xs-5 control-label">{{ Lang::get('admin.front_side') }}:</label>
                    <div class="col-md-9 col-xs-7">
                        <img src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl('drivers/license/'.$driver['user_id'].'/front_side.'.$driver['front_side_extension'], now()->addMinutes(5)) }}"
                             class="img-fluid max-100" alt="Front side">
                    </div>
                </div>
            @endif
                @if(\Illuminate\Support\Facades\Storage::disk('s3')->exists('drivers/license/'.$driver['user_id'].'/back_side.'.$driver['back_side_extension']))
                <div class="form-group">
                    <label class="col-md-3 col-xs-5 control-label">{{ Lang::get('admin.back_side') }}:</label>
                    <div class="col-md-9 col-xs-7">
                        <img src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl('drivers/license/'.$driver['user_id'].'/back_side.'.$driver['back_side_extension'], now()->addMinutes(5)) }}"
                             class="img-fluid max-100" alt="Back side">
                    </div>
                </div>
            @endif

            @component('components.misc.form-group-col', ['value' => $driver['status'] ?? null, 'values' => $driver['statuses']])
                @slot('label_class') col-md-3 col-xs-5 control-label @endslot
                @slot('label') {{ Lang::get('admin.status') }} @endslot
                @slot('name') status @endslot
                @slot('field') select @endslot
                @slot('field_wrapper_class') col-md-9 col-xs-7 @endslot
            @endcomponent

            @component('components.misc.submit-button')
                @slot('class') btn btn-danger btn-block btn-rounded mtb-15 @endslot
                @slot('anchor') {{ Lang::get('admin.save') }} @endslot
            @endcomponent
        </div>
    </div>
@endcomponent
