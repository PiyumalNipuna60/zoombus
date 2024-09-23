<div class="ticket-passenger">
    <div class="row">
        @component('components.misc.form-group-col')
            @slot('field') hidden @endslot
            @slot('hideGroup') @endslot
            @slot('name') seat_number[] @endslot
            @slot('no_id') @endslot
            @slot('value') {{ $seat_number }} @endslot
        @endcomponent
        <div class="col-md-6 col-lg-4">
            <div class="ticket-passenger-name">
                <div class="ticket-passenger-seat">
                    <span>{{ $seat_number }}</span>
                </div>
                <div
                    class="ticket-passenger-icon-gender @isset($current_user) @if(Auth::check() && Auth::user()->gender_id == 2) woman @else man @endif @endisset @empty($current_user) man @endempty "></div>
                @component('components.misc.form-group-col')
                    @slot('hideGroup') @endslot
                    @slot('name') name[] @endslot
                    @slot('no_id') @endslot
                    @isset($current_user) @slot('value') {{ Auth::user()->name }} @endslot @endisset
                    @slot('placeholder') {{ Lang::get('auth.name').', '.Lang::get('auth.lastname') }} @endslot
                    @if(isset($preserving) && $preserving == 1)
                        @slot('disabled') @endslot
                        @slot('value') {{ Lang::get('auth.name_not_required')}} @endslot
                    @endif
                @endcomponent
            </div>
        </div>
        <div class="col-md-6 col-lg-2">
            <div class="ticket-passenger-gender">
                <div class="select2_wrapper">
                    <div class="select2_inner">
                        @component('components.misc.form-group-col', ['values' => $gender, 'value' => Auth::user()->gender_id ?? 1])
                            @slot('hideGroup') @endslot
                            @slot('field') select @endslot
                            @slot('class') select2 select gender-change @endslot
                            @slot('name') gender_id[] @endslot
                            @slot('no_id') @endslot
                            @isset($name) @slot('value') {{ $name }}  @endslot @endisset
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="ticket-passenger-phone d-flex">
                @component('components.misc.form-group-col')
                    @slot('hideGroup') @endslot
                    @slot('name') phone_number_inp @endslot
                    @slot('class') form-control phone_number_inp @endslot
                    @slot('no_id') @endslot
                    @isset($current_user) @slot('value') {{ Auth::user()->phone_number }} @endslot @endisset
                    @slot('placeholder') {{ Lang::get('auth.mobile_phone') }} @endslot
                    @if(isset($preserving) && $preserving == 1)
                        @slot('disabled') @endslot
                        @slot('value') {{ Lang::get('auth.number_not_required')}} @endslot
                    @endif
                @endcomponent
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="ticket-passenger-id">
                @component('components.misc.form-group-col')
                    @slot('hideGroup') @endslot
                    @slot('name') email[] @endslot
                    @slot('type') email @endslot
                    @slot('no_id') @endslot
                    @isset($current_user) @slot('value') {{ Auth::user()->email }} @endslot @endisset
                    @slot('placeholder') {{ Lang::get('auth.email_address') }} @endslot
                    @if(isset($preserving) && $preserving == 1)
                        @slot('disabled') @endslot
                        @slot('value') {{ Lang::get('auth.email_not_required')}} @endslot
                    @endif
                @endcomponent
                @if(!isset($preserving) || isset($preserving) && $preserving != 1)
                    <label>{{ Lang::get('misc.not_required') }}</label>
                @endif
            </div>
        </div>
    </div>
    <div class="ticket-passenger-close">
        <a href="#">x</a>
    </div>
</div>
