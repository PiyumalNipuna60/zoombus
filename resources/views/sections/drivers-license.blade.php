<div class="profile-license">
    <div class="profile-license-form">
        @if(isset($status) && $status == 2)
            <div class="response-persistent response-warning">
                {{ Lang::get('auth.license_pending') }}
            </div>
        @elseif(isset($status) && $status == 1)
            <div class="response-persistent response-success">
                {{ Lang::get('auth.license_approved') }}
            </div>
        @elseif(isset($status) && $status == 3)
            <div class="response-persistent response-danger">
                {!! (isset($message)) ? $message.' <a href="'.route('support_ticket_secure', ['id' => $ticketId, 'latest_message' => $latestMessage]).'">'.Lang::get('misc.answer').'</a>' : Lang::get('auth.license_rejected') !!}
            </div>
        @else
            @if(Session::get('alert'))
                <div class="response-persistent {{ Session::get('alert') }}">
                    {{ Session::get('text') }}
                </div>
            @endif
        @endif


        <div class="response"></div>
        @component('components.misc.form')
            @slot('class') form-horizontal form-default @if(isset($status) && $status == 1) transparentify @endif @endslot
            @slot('form_id') driversLicenseForm @endslot
            @if(isset($status) && $status == 1) @slot('disabled') @endslot @endif
            @slot('row_inside') @endslot
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.drivers_license_number') }} @endslot
                @slot('name') license_number @endslot
                @isset($license_number) @slot('value') {{ $license_number }}@endslot @endisset
                @slot('placeholder') {{ Lang::get('auth.drivers_license_number_placeholder') }}@endslot
                @slot('col') @if(isset($submit) && $submit == 'continue') col-md-12 @else col-md-6 @endif @endslot
                @slot('faicon') fa-id-card-o @endslot
            @endcomponent
            @if(!isset($submit))
                @component('components.misc.form-group-col')
                    @slot('field') button @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('class') btn-save @endslot
                    @slot('name') save @endslot
                    @slot('faicon') fa-floppy-o @endslot
                    @slot('label') &nbsp; @endslot
                    @slot('anchor') {{ Lang::get('auth.save') }} @endslot
                @endcomponent
            @endif
            @component('components.misc.rowbreak') @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.front_side') }} @endslot
                @slot('name') front_side @endslot
                @if(isset($front_side) && $front_side) @slot('image_path') {{ $front_side  }} @endslot @endisset
                @slot('field') image @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-id-card-o @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.back_side') }} @endslot
                @slot('name') back_side @endslot
                @if(isset($back_side) && $back_side) @slot('image_path') {{ $back_side  }} @endslot @endisset
                @slot('field') image @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-id-card-o @endslot
            @endcomponent
            @if(isset($submit) && $submit == 'continue')
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
            @endif
        @endcomponent

    </div>
</div>
