@component('components.misc.form')
    @slot('ajax') {{ route('admin_vehicle_license_change') }} @endslot
    @component('components.misc.form-group-col')
        @slot('field') hidden @endslot
        @slot('name') id @endslot
        @slot('hideGroup') @endslot
        @slot('value') {{ $vehicle['id'] }} @endslot
    @endcomponent
    <div class="panel panel-default vehicle-license-edit">
        <div class="panel-body">
            <h3><i class="fa fa-car"></i> {{ Lang::get('admin.vehicle_license_title') }}</h3>
            <p>{{ Lang::get('admin.vehicle_license_info') }}</p>
        </div>
        <div class="panel-body form-group-separated">
            <div class="panel-body form-group-separated">

                @if(\Illuminate\Support\Facades\Storage::disk('s3')->exists('drivers/vehicles/'.$vehicle['id'].'/front_side.'.$vehicle['front_side_extension']))
                    <div class="form-group">
                        <label class="col-md-3 col-xs-5 control-label">{{ Lang::get('admin.front_side') }}:</label>
                        <div class="col-md-9 col-xs-7">
                            <img src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl('drivers/vehicles/'.$vehicle['id'].'/front_side.'.$vehicle['front_side_extension'], now()->addMinutes(5)) }}"
                                 class="img-fluid max-100" alt="Front side">
                        </div>
                    </div>
                @endif

                @if(\Illuminate\Support\Facades\Storage::disk('s3')->exists('drivers/vehicles/'.$vehicle['id'].'/back_side.'.$vehicle['back_side_extension']))
                    <div class="form-group">
                        <label class="col-md-3 col-xs-5 control-label">{{ Lang::get('admin.back_side') }}:</label>
                        <div class="col-md-9 col-xs-7">
                            <img src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl('drivers/vehicles/'.$vehicle['id'].'/back_side.'.$vehicle['back_side_extension'], now()->addMinutes(5)) }}"
                                 class="img-fluid max-100" alt="Back side">
                        </div>
                    </div>
                @endif

                <div class="minibus-pattern-edit has-vehicle-scheme">
                    @component('components.vehicle-schemes', ['seat_positioning' => $vehicle['seat_positioning']])
                        @slot('route_type') {{ $vehicle['route_types']['key'] }} @endslot
                        @slot('editable') @endslot
                    @endcomponent
                </div>

                @component('components.misc.form-group-col', ['value' => $vehicle['status'] ?? null, 'values' => $vehicle['statuses']])
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
    </div>
@endcomponent
