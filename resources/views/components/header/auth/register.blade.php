<div class="signup-btn-wrapper">
    @if((new \Jenssegers\Agent\Agent)->isMobile())
        <a href="{{ route('mobile.signup') }}" class="signup-btn">{{ Lang::get('auth.registration') }}</a>
    @else
        <button class="signup-btn signup-button">{{ Lang::get('auth.registration') }}</button>
    @endif

    <div class="signup-popup-wrapper">
        <button type="button" class="close" aria-label="Close"></button>
        <div class="title">{{ Lang::get('auth.registration') }}</div>
        <div class="response mini"></div>
        @component('components.misc.form')
            @slot('form_id') popup-form2 @endslot
            @slot('class') form-horizontal registerForm @endslot
            @slot('subtitle') {!! Lang::get('auth.agree_terms_of_use') !!} @endslot
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.mobile_phone') }} @endslot
                @slot('name') phone_number @endslot
                @slot('no_id') @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.name') }}, {{ Lang::get('auth.lastname') }} @endslot
                @slot('name') name @endslot
                @slot('no_id') @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.email_address') }} @endslot
                @slot('name') email @endslot
                @slot('no_id') @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.password') }} @endslot
                @slot('name') password @endslot
                @slot('no_id') @endslot
                @slot('type') password @endslot
            @endcomponent
            @component('components.misc.form-group-col', ['values' => $gender])
                @slot('field') select @endslot
                @slot('no_id') @endslot
                @slot('name') gender_id @endslot
                @slot('label') {{ Lang::get('auth.gender') }} @endslot
            @endcomponent
            <div id="register_element"></div>
            @component('components.misc.submit-button')
                @slot('anchor') {{ Lang::get('auth.registration') }} <i class="fa fa-angle-double-right" aria-hidden="true"></i> @endslot
            @endcomponent
        @endcomponent
    </div>
</div>
