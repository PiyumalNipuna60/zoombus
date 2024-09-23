<div class="profile-password">
    <div class="profile-password-form">
        <div class="response-persistent response-info">{{ Lang::get('validation.in_order_to_finish_set_password') }}</div>
        <div class="response"></div>
        @component('components.misc.form')
            @slot('form_id') passwordWithoutOld @endslot
            @slot('row_inside') @endslot
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.password') }} @endslot
                @slot('name') password @endslot
                @slot('value') @endslot
                @slot('type') password @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-lock @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.password_confirm') }} @endslot
                @slot('name') password_confirmation @endslot
                @slot('type') password @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-lock @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('field') button @endslot
                @slot('col') col-md-12 @endslot
                @slot('class') btn-save @endslot
                @slot('name') save @endslot
                @slot('nolabel') @endslot
                @slot('anchor') {{ Lang::get('auth.save') }} @endslot
            @endcomponent
        @endcomponent
    </div>
</div>