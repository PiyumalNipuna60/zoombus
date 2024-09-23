@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.vehicle_edit_title', ['model' => $vehicle['manufacturer']]))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@push('styles')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/slick.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.vehicle_edit3'))
@section('title2', Lang::get('titles.vehicle_edit4'))

@section('content')
    <div class="transport-registration-edit has-vehicle-scheme">
        <div class="transport-registration-form">
            @if(isset($status) && $status == 3)
                <div class="response-persistent response-danger">
                    {!! (isset($message)) ? $message.' <a href="'.route('support_ticket_secure', ['id' => $ticketId, 'latest_message' => $latestMessage]).'">'.Lang::get('misc.answer').'</a>' : Lang::get('auth.vehicle_rejected') !!}
                </div>
            @endif
            <div class="response"></div>
            @component('components.misc.form')
                @slot('form_id') vehicleEditForm @endslot
                @slot('class') form-horizontal form-default  @endslot
                @slot('multipart') @endslot
                @slot('row_inside') @endslot
                <div class="col-md-6 mb-3 mb-md-0">
                    @component('components.misc.form-group-col', ['values' => $route_types, 'value' => $vehicle['type']])
                        @slot('name') id @endslot
                        @slot('value') {{request()->route('id')}}@endslot
                        @slot('field') hidden @endslot
                        @slot('hideGroup') @endslot
                    @endcomponent
                    <div class="row">
                        @component('components.misc.form-group-col', ['values' => $route_types, 'value' => $vehicle['type']])
                            @slot('label') {{ Lang::get('auth.service_type') }} @endslot
                            @slot('name') type @endslot
                            @slot('field') select @endslot
                            @slot('col') col-md-6 @endslot
                            @slot('faicon') fa-bus @endslot
                            @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @endcomponent
                        @component('components.misc.form-group-col', ['values' => $fuel_types, 'value' => $vehicle['fuel_type']])
                            @slot('label') {{ Lang::get('auth.fuel_type') }} @endslot
                            @slot('name') fuel_type @endslot
                            @slot('field') select @endslot
                            @slot('col') col-md-6 @endslot
                            @slot('faicon') fa-fire @endslot
                            @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @endcomponent
                        @component('components.misc.form-group-col', ['values' => $manufacturers, 'value' => $vehicle['manufacturer']])
                            @slot('label') {{ Lang::get('auth.manufacturer') }} @endslot
                            @slot('name') manufacturer @endslot
                            @slot('field') select @endslot
                            @slot('col') col-md-6 @endslot
                            @slot('faicon') fa-car @endslot
                            @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @endcomponent
                        @component('components.misc.form-group-col')
                            @slot('label') {{ Lang::get('auth.vehicle_model') }} @endslot
                            @slot('name') model @endslot
                            @slot('value') {{ $vehicle['model'] }}@endslot
                            @slot('col') col-md-6 @endslot
                            @slot('faicon') fa-car @endslot
                            @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @endcomponent
                        @component('components.misc.rowbreak') @endcomponent
                        @component('components.misc.form-group-col', ['values' => $countries, 'value' => $vehicle['country_id']])
                            @slot('label') {{ Lang::get('auth.country') }} @endslot
                            @slot('name') country_id @endslot
                            @slot('field') select @endslot
                            @slot('col') col-md-6 @endslot
                            @slot('faicon') fa-globe @endslot
                            @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @endcomponent
                        @component('components.misc.form-group-col')
                            @slot('label') {{ Lang::get('auth.license_plate') }} @endslot
                            @slot('name') license_plate @endslot
                            @slot('value') {{ $vehicle['license_plate'] }}@endslot
                            @slot('placeholder') {{ Lang::get('auth.license_placeholder') }}@endslot
                            @slot('col') col-md-6 @endslot
                            @slot('faicon') fa-vcard-o @endslot
                            @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @endcomponent
                        @component('components.misc.rowbreak') @endcomponent
                        @component('components.misc.form-group-col', ['values' => $year_manufactured, 'value' => $vehicle['year']])
                            @slot('label') {{ Lang::get('auth.year_manufactured') }} @endslot
                            @slot('name') year @endslot
                            @slot('field') select @endslot
                            @slot('col') col-md-6 @endslot
                            @slot('faicon') fa-calendar @endslot
                            @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @endcomponent
                        @component('components.misc.form-group-col')
                            @slot('label') {{ Lang::get('auth.date_of_registration') }} @endslot
                            @slot('name') date_of_registration @endslot
                            @slot('col') col-md-6 @endslot
                            @slot('value') {{ \Carbon\Carbon::parse($vehicle['date_of_registration'])->translatedformat('j\ F Y') }}@endslot
                            @slot('addon') datepicker @endslot
                            @slot('faicon') fa-calendar @endslot
                            @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @endcomponent
                        @component('components.misc.rowbreak')@endcomponent
                        @foreach($vehicle_specs as $vs)
                            @component('components.misc.form-group-col')
                                @slot('name') vehicle_features[] @endslot
                                @slot('field_id') feature_{{ $vs['id'] }} @endslot
                                @slot('field') checkbox @endslot
                                @if(in_array($vs['id'], array_column($vehicle['fullspecifications'], 'vehicle_specification_id')))
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
                        @if($front_side)
                            @slot('image_path') {{ $front_side }} @endslot
                            @slot('image_class') transport-registration-form-img @endslot
                        @endif
                        @slot('field') image @endslot
                        @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @slot('faicon') fa-id-card-o @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.vehicle_back_side') }} @endslot
                        @slot('name') vehicle_license_back_side @endslot
                        @if($vehicle['status'] == 1) @slot('disabled') @endslot @endif
                        @if($back_side)
                            @slot('image_path') {{ $back_side }} @endslot
                            @slot('image_class') transport-registration-form-img @endslot
                        @endif
                        @slot('field') image @endslot
                        @slot('faicon') fa-id-card-o @endslot
                    @endcomponent

                    @component('components.misc.form-group-col', ['image_path' => $vehicle_images ?? []])
                        @slot('label') {{ Lang::get('auth.vehicle_images') }} @endslot
                        @slot('name') vehicle_images[] @endslot
                        @slot('field_id') vehicle_images @endslot
                        @slot('multiple') @endslot
                        @if(isset($vehicle_images) && is_array($vehicle_images) && count($vehicle_images) == 10) @slot('disabled') @endslot @endif
                        @if(isset($vehicle_images))
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
                        {!!  Lang::get('auth.select_number_of_seats_and_positioning2') !!} <br>
                        <a href="https://www.youtube.com/watch?v=Z_F2HvUsjFY" class="fancybox view_video">
                            {{ Lang::get('misc.view_video') }} <i class="fa fa-video-camera"></i>
                        </a>
                    </div>
                    <div class="col-md-12 text-center">
                        <span class="new_row new_one row_{{ $vehicle['route_types']['key'] }} @if($vehicle['route_types']['id'] == 3 || $vehicle['status'] == 1) hidden @endif">
                            <i class="fa fa-plus"></i> {{ Lang::get('driver.new_row') }}</span>
                    </div>
                    <div class="minibus-pattern-edit">
                        @component('components.vehicle-schemes', ['seat_positioning' => $vehicle['seat_positioning']])
                            @slot('route_type') {{ $vehicle['route_types']['key'] }} @endslot
                            @if($vehicle['status'] != 1) @slot('editable') @endslot @endif
                            @if($vehicle['status'] == 1) @slot('disabled') 1 @endslot @endif
                            @slot('show_info') @endslot
                        @endcomponent
                    </div>

                    <div class="clearfix"></div>
                    @component('components.misc.form-group-col')
                        @slot('field') button @endslot
                        @slot('col') col-md-12 @endslot
                        @slot('class') btn-save @endslot
                        @slot('name') save @endslot
                        @slot('faicon') fa-floppy-o @endslot
                        @slot('label') &nbsp;@endslot
                        @slot('anchor') {{ Lang::get('auth.save') }} @endslot
                    @endcomponent
                </div>
            @endcomponent
        </div>
    </div>
@endsection
