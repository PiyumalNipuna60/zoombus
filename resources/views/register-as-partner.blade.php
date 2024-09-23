@extends('layouts.app')

@section('title', $title ?? Lang::get('seo.register_as_partner'))

@section('description',  $description ?? null)

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@isset($robots)
@section('heads')
    <meta name="robots" content="{{ $robots }}">
@endsection
@endisset


@push('styles')
    <link href="{{ URL::asset('css/slick.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery.appear.js') }}"></script>
    <script src="{{ URL::asset('js/utils.js') }}"></script>
@endpush

@section('title1', $title_page ?? Lang::get('titles.register_as_partner_title'))
@section('title2', Lang::get('titles.register_as_partner_title2'))

@section('content')
    <div class="row">
        <div class="col-md-6">
            {!! Lang::get('texts.register_as_partner') !!}
        </div>
        <div class="col-md-6">
            <div class="register-as-driver-form">
                <div class="title">{{ Lang::get('auth.registration') }}</div>
                <div class="response"></div>
                @component('components.misc.form')
                    @slot('form_id') registerAsPartnerForm @endslot
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.name').', '.Lang::get('auth.lastname') }} @endslot
                        @slot('name') name @endslot
                        @slot('value') @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.mobile_phone') }} @endslot
                        @slot('name') phone_number @endslot
                        @slot('value') @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.email_address') }} @endslot
                        @slot('name') email @endslot
                        @slot('value') @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.password') }} @endslot
                        @slot('name') password @endslot
                        @slot('type') password @endslot
                        @slot('value') @endslot
                    @endcomponent
                    @component('components.misc.form-group-col', ['values' => $countries, 'value' => 80])
                        @slot('label') {{ Lang::get('auth.country') }} @endslot
                        @slot('field') select @endslot
                        @slot('name') country_id @endslot
                    @endcomponent
                    @component('components.misc.form-group-col')
                        @slot('label') {{ Lang::get('auth.partnership_code_not_required') }} @endslot
                        @slot('name') affiliate_code @endslot
                        @slot('value') {{ $affiliateCode ?? null }} @endslot
                    @endcomponent
                    <div id="register_partner_element"></div>
                    @component('components.misc.submit-button')
                        @slot('anchor') {{ Lang::get('auth.registration') }} <i class="fa fa-angle-double-right" aria-hidden="true"></i> @endslot
                    @endcomponent
                    @slot('subtitle') {!! Lang::get('auth.agree_terms_of_use') !!} @endslot
                @endcomponent
            </div>
        </div>
    </div>
@endsection
@section('outside_content')
    <div class="register-as-driver-full">
        <div class="service1">
            <a href="#">
                <figure><img src="{!! URL::asset('images/service01.png') !!}" alt="Service" class="img-fluid"></figure>
                <div class="txt1">{!! Lang::get('texts.register_as_driver_icon_1_title') !!}</div>
                <div class="txt2">{!! Lang::get('texts.register_as_driver_icon_1_text') !!}</div>
            </a>
        </div>
        <div class="service1">
            <a href="#">
                <figure><img src="{!! URL::asset('images/service02.png') !!}" alt="Service" class="img-fluid"></figure>
                <div class="txt1">{!! Lang::get('texts.register_as_driver_icon_2_title') !!}</div>
                <div class="txt2">{!! Lang::get('texts.register_as_driver_icon_2_text') !!}</div>
            </a>
        </div>
{{--        <div class="service1">--}}
{{--            <a href="#">--}}
{{--                <figure><img src="{!! URL::asset('images/service03.png') !!}" alt="Service" class="img-fluid"></figure>--}}
{{--                <div class="txt1">{!! Lang::get('texts.register_as_driver_icon_3_title') !!}</div>--}}
{{--                <div class="txt2">{!! Lang::get('texts.register_as_driver_icon_3_text') !!}</div>--}}
{{--            </a>--}}
{{--        </div>--}}
{{--        <div class="service1">--}}
{{--            <a href="#">--}}
{{--                <figure><img src="{!! URL::asset('images/service04.png') !!}" alt="Service" class="img-fluid"></figure>--}}
{{--                <div class="txt1">{!! Lang::get('texts.register_as_driver_icon_4_title') !!}</div>--}}
{{--                <div class="txt2">{!! Lang::get('texts.register_as_driver_icon_4_text') !!}</div>--}}
{{--            </a>--}}
{{--        </div>--}}
    </div>
    <div id="content2">
        <div class="container">
{{--            <div class="title4">{!! Lang::get('texts.register_as_driver_video_title') !!}</div>--}}
{{--            <div class="video1">--}}
{{--                <a href="https://www.youtube.com/watch?v=Z_F2HvUsjFY" class="fancybox">--}}
{{--                    <figure>--}}
{{--                        <img src="{!! URL::asset('images/video01.jpg') !!}" alt="Video" class="img-responsive">--}}
{{--                        <div class="over1"></div>--}}
{{--                    </figure>--}}
{{--                </a>--}}
{{--            </div>--}}
            <div class="info1-wrapper">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info1ge">
                            <div class="txt1ge"><span class="animated-number" data-duration="2000"
                                                      data-animation-delay="0">{!! $total_drivers !!}</span></div>
                            <div class="txt2">{!! Lang::get('texts.registered_drivers') !!}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info1ge">
                            <div class="txt1ge"><span class="animated-number" data-duration="2000"
                                                      data-animation-delay="0">{!! $total_routes !!}</span></div>
                            <div class="txt2">{!! Lang::get('texts.registered_routes') !!}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info1ge">
                            <div class="txt1ge"><span class="animated-number" data-duration="2000"
                                                      data-animation-delay="0">{!! $total_partners !!}</span></div>
                            <div class="txt2">{!! Lang::get('texts.registered_partners') !!}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info1ge">
                            <div class="txt1ge"><span class="animated-number" data-duration="2000"
                                                      data-animation-delay="0">{!! $sold_tickets !!}</span></div>
                            <div class="txt2">{!! Lang::get('texts.sold_tickets') !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
