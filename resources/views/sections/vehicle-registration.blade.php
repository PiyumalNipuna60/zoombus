<div class="transport-registration has-vehicle-scheme">
    <div class="transport-registration-form">
        @if(isset($status) && $status != 1)
            <div class="response-persistent response-info">
                {!! Lang::get('auth.license_not_approved') !!}
            </div>
        @else
            @if(Session::get('alert'))
                <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
            @endif
        @endif
        <div class="response"></div>
        @component('components.misc.form')
            @slot('class') form-horizontal form-default @if(isset($status) && $status != 1) transparentify @endif @endslot
            @slot('form_id') @if(!empty($vehicle)) vehicleEditForm @else vehicleRegistrationForm @endif @endslot
            @if(!empty($vehicle))
                @component('components.misc.form-group-col')
                    @slot('field') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') id @endslot
                    @slot('value') {{ $vehicle['id'] }} @endslot
                @endcomponent
            @endif
            @slot('multipart') @endslot
            @if(isset($status) && $status != 1) @slot('disabled') @endslot @endif
            @slot('row_inside') @endslot
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="row">
                    @component('components.misc.form-group-col', ['values' => $route_types, 'value' => $vehicle['type'] ?? null])
                        @slot('label') {{ Lang::get('auth.service_type') }} @endslot
                        @slot('name') type @endslot
                        @slot('field') select @endslot
                        @slot('col') col-md-6 @endslot
                        @slot('faicon') fa-bus @endslot
                    @endcomponent
                    @component('components.misc.form-group-col', ['values' => $fuel_types, 'value' => $vehicle['fuel_type'] ?? null])
                        @slot('label') {{ Lang::get('auth.fuel_type') }} @endslot
                        @slot('name') fuel_type @endslot
                        @slot('field') select @endslot
                        @slot('col') col-md-6 @endslot
                        @slot('faicon') fa-fire @endslot
                    @endcomponent
                    @component('components.misc.form-group-col', ['values' => $manufacturers, 'value' => $vehicle['manufacturer'] ?? null])
                        @slot('label') {{ Lang::get('auth.manufacturer') }} @endslot
                        @slot('name') manufacturer @endslot
                        @slot('field') select @endslot
                        @slot('col') col-md-6 @endslot
                        @slot('faicon') fa-car @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.vehicle_model') }} @endslot
                        @slot('name') model @endslot
                        @slot('value') {{ $vehicle['model'] ?? null }}@endslot
                        @slot('col') col-md-6 @endslot
                        @slot('faicon') fa-car @endslot
                    @endcomponent
                    @component('components.misc.rowbreak') @endcomponent
                    @component('components.misc.form-group-col', ['values' => $countries, 'value' => $vehicle['country_id'] ?? 80])
                        @slot('label') {{ Lang::get('auth.vehicle_country') }} @endslot
                        @slot('name') country_id @endslot
                        @slot('field') select @endslot
                        @slot('col') col-md-6 @endslot
                        @slot('faicon') fa-globe @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.license_plate') }} @endslot
                        @slot('name') license_plate @endslot
                        @slot('value') {{ $vehicle['license_plate'] ?? null }} @endslot
                        @slot('placeholder') {{ Lang::get('auth.license_placeholder') }} @endslot
                        @slot('col') col-md-6 @endslot
                        @slot('faicon') fa-vcard-o @endslot
                    @endcomponent
                    @component('components.misc.rowbreak') @endcomponent
                    @component('components.misc.form-group-col', ['values' => $year_manufactured, 'value' => $vehicle['year'] ?? null])
                        @slot('label') {{ Lang::get('auth.year_manufactured') }} @endslot
                        @slot('name') year @endslot
                        @slot('field') select @endslot
                        @slot('col') col-md-6 @endslot
                        @slot('faicon') fa-calendar @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.date_of_registration') }} @endslot
                        @slot('name') date_of_registration @endslot
                        @slot('col') col-md-6 @endslot
                        @isset($vehicle['date_of_registration'])
                            @slot('value') {{ \Carbon\Carbon::parse($vehicle['date_of_registration'])->translatedformat('j\ F Y') }} @endslot
                        @endisset
                        @slot('addon') datepicker @endslot
                        @slot('faicon') fa-calendar @endslot
                    @endcomponent
                    @component('components.misc.rowbreak')@endcomponent
                    @foreach($vehicle_specs as $vs)
                        @component('components.misc.form-group-col')
                            @slot('name') vehicle_features[] @endslot
                            @slot('field_id') feature_{{ $vs['id'] }} @endslot
                            @slot('field') checkbox @endslot
                            @if(isset($vehicle['fullspecifications']) && in_array($vs['id'], array_column($vehicle['fullspecifications'], 'vehicle_specification_id')))
                                @slot('checked') @endslot
                            @endif
                            @slot('group_class') form-group form-check @endslot
                            @slot('col') col-md-3 @endslot
                            @slot('value') {{ $vs['id'] }} @endslot
                            @slot('label_class') form-check-label @endslot
                            @slot('label') {{ $vs['translated']['name'] }} @endslot
                        @endcomponent
                    @endforeach
                </div>
            </div>
            <div class="col-md-6">
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.vehicle_front_side') }} @endslot
                    @slot('name') vehicle_license_front_side @endslot
                    @slot('field') image @endslot
                    @if(isset($front_side) && !empty($front_side))
                        @slot('image_path') {{ $front_side }} @endslot
                        @slot('image_class') transport-registration-form-img @endslot
                    @endif
                    @slot('faicon') fa-id-card-o @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.vehicle_back_side') }} @endslot
                    @slot('name') vehicle_license_back_side @endslot
                    @slot('field') image @endslot
                    @if(isset($back_side) && !empty($back_side))
                        @slot('image_path') {{ $back_side }} @endslot
                        @slot('image_class') transport-registration-form-img @endslot
                    @endif
                    @slot('faicon') fa-id-card-o @endslot
                @endcomponent
                @component('components.misc.form-group-col', ['image_path' => $vehicle_images ?? []])
                    @slot('label') {{ Lang::get('auth.vehicle_images') }} @endslot
                    @slot('name') vehicle_images[] @endslot
                    @slot('field_id') vehicle_images @endslot
                    @slot('multiple') @endslot
                    @if(isset($vehicle_images) && !empty($vehicle_images))
                        @slot('image_class') transport-registration-form-img-multiple col-md-2 col-sm-6
                        col-xs-12 @endslot
                    @endif
                    @slot('field') image @endslot
                    @slot('faicon') fa-car @endslot
                @endcomponent
            </div>

            <div class="col-md-12">
                <div class="transport-registration-form-text text-center mt-5 fs-18">
                    {{ Lang::get('auth.select_number_of_seats_and_positioning') }}
                </div>
                <div class="title2">
                    {!! Lang::get('auth.select_number_of_seats_and_positioning2')  !!}
                    <br>
                    <a href="https://www.youtube.com/watch?v=Z_F2HvUsjFY" class="fancybox view_video">
                        {{ Lang::get('misc.view_video') }} <i class="fa fa-video-camera"></i>
                    </a>
                </div>

                <div class="col-md-12 text-center">
                        <span class="new_row new_one row_minibus"><i
                                class="fa fa-plus"></i> {{ Lang::get('driver.new_row') }}</span>
                </div>
                <div class="scheme_container">
                    @component('components.vehicle-schemes', ['seat_positioning' => $vehicle['seat_positioning'] ?? $scheme])
                        @slot('route_type') {{ $vehicle['route_types']['key'] ?? 'minibus' }} @endslot
                        @slot('editable') @endslot
                        @slot('show_info') @endslot
                    @endcomponent
                </div>
                <div class="clearfix"></div>
                @if(isset($submit) && $submit == 'continue')
                    <div class="row">
                        @component('components.misc.form-group-col')
                            @slot('field') hidden @endslot
                            @slot('hideGroup') @endslot
                            @slot('name') continue @endslot
                            @slot('value') yes @endslot
                        @endcomponent
                        @component('components.misc.form-group-col')
                            @slot('field') button @endslot
                            @slot('col') col-md-2 @endslot
                            @slot('class') btn-save wizard-back @endslot
                            @slot('name') back @endslot
                            @slot('faicon') fa-chevron-left @endslot
                            @slot('label') &nbsp; @endslot
                            @slot('anchor') {{ Lang::get('auth.back') }} @endslot
                        @endcomponent
                        <div class="col-md-7"></div>
                        @component('components.misc.form-group-col')
                            @slot('field') button @endslot
                            @slot('col') col-md-3 @endslot
                            @slot('class') btn-save @endslot
                            @slot('name') save @endslot
                            @slot('endIcon') true @endslot
                            @slot('faicon') fa-chevron-right @endslot
                            @slot('label') &nbsp; @endslot
                            @slot('anchor') {{ Lang::get('auth.continue') }} @endslot
                        @endcomponent
                    </div>
                @else
                    @component('components.misc.form-group-col')
                        @slot('field') button @endslot
                        @slot('col') col-md-12 @endslot
                        @slot('class') btn-save @endslot
                        @slot('name') save @endslot
                        @slot('faicon') fa-floppy-o @endslot
                        @slot('label') &nbsp; @endslot
                        @slot('anchor') {{ Lang::get('auth.save') }} @endslot
                    @endcomponent
                @endif
            </div>
        @endcomponent
    </div>
</div>
