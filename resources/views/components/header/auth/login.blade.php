<div class="login-btn-wrapper @if(Session::get('popup') == 'login') open @endif">
    @if((new \Jenssegers\Agent\Agent)->isMobile())
        <a href="{{ route('mobile.login') }}" class="login-btn">{{ Lang::get('auth.login') }}</a>
    @else
        <button class="login-btn login-button">{{ Lang::get('auth.login') }}</button>
    @endif
    <div class="login-popup-wrapper">
        <button type="button" class="close" aria-label="Close"></button>
        <div class="title">{{ Lang::get('auth.login') }}</div>
        @if(Session::get('alert'))
            <div class="response-persistent mini {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
        @endif
        <div class="response"></div>
        @component('components.misc.form')
            @slot('form_id') popup-form1 @endslot
            @slot('class') form-horizontal loginForm @endslot
            @slot('subtitle') {!! Lang::get('auth.agree_terms_of_use_forgot') !!} @endslot
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.mobile_phone') }} @endslot
                @slot('name') phone_number @endslot
                @slot('field_id') phone_number2 @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.password') }} @endslot
                @slot('name') password @endslot
                @slot('no_id') @endslot
                @slot('type') password @endslot
            @endcomponent
            @component('components.misc.submit-button')
                @slot('anchor') {{ Lang::get('auth.enter_system') }} <i class="fa fa-angle-double-right" aria-hidden="true"></i> @endslot
            @endcomponent
        @endcomponent
    </div>
</div>
