<div class="profile-personal">
    <div class="response"></div>
    <div class="profile-personal-img">
        <img src="{{ Auth::user()->photo() }}" alt="Avatar {{ Auth::user()->id }}" class="img-fluid avatar">
    </div>
    <div class="profile-personal-btns">
        @component('components.misc.form')
            @slot('form_id') uploadAvatar @endslot
            <label for="avatar" class="btn-upload"><i class="fa fa-upload" aria-hidden="true"></i> {{ Lang::get('auth.upload_avatar') }}</label>
            <input type="file" name="avatar" id="avatar">
            @if(Auth::user()->photoExists())
                <a href="javascript:void(0)" class="btn-delete" id="deleteAvatar">
                    <i class="fa fa-trash" aria-hidden="true"></i> {{ Lang::get('auth.delete_avatar') }}
                </a>
            @endif
        @endcomponent
    </div>

    <div class="profile-personal-form">
        @component('components.misc.form')
            @slot('class') form-horizontal form-default @endslot
            @slot('form_id') @if(isset($form_id)) {{ $form_id }} @else editProfileForm @endif @endslot
            @slot('row_inside') @endslot
            @component('components.misc.form-group-col', ['values' => $countries, 'value' => Auth::user()->country_id])
                @slot('label') {{ Lang::get('auth.country') }} @endslot
                @slot('field') select @endslot
                @slot('name') country_id @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-map-marker @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.city') }} @endslot
                @slot('name') city @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-map-marker @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.name').', '.Lang::get('auth.lastname') }} @endslot
                @slot('name') name @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-user-o @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.birth_date') }} @endslot
                @slot('name') birth_date @endslot
                @slot('value') @isset(Auth::user()->birth_date) {{ \Carbon\Carbon::parse(Auth::user()->birth_date)->translatedformat('j\ F Y') }} @endisset @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-calendar @endslot
                @slot('addon') datepicker @endslot
                @slot('start_view') 3 @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.id_number') }} @endslot
                @slot('name') id_number @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-credit-card-alt @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.mobile_phone') }} @endslot
                @slot('name') phone_number @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-mobile @endslot
                @isset($disable_phone) @slot('disabled') @endslot @endisset
            @endcomponent
            @component('components.misc.form-group-col', ['values' => $genders, 'value' => Auth::user()->gender_id])
                @slot('label') {{ Lang::get('auth.gender') }} @endslot
                @slot('name') gender_id @endslot
                @slot('col') col-md-6 @endslot
                @slot('field') select @endslot
                @slot('faicon') fa-intersex @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('label') {{ Lang::get('auth.email_address') }} @endslot
                @slot('name') email @endslot
                @slot('type') email @endslot
                @slot('col') col-md-6 @endslot
                @slot('faicon') fa-envelope-o @endslot
            @endcomponent
            @if(isset($submit) && $submit == 'continue')
                <div class="col-md-9"></div>
                @component('components.misc.form-group-col')
                    @slot('field') hidden @endslot
                    @slot('hideGroup') @endslot
                    @slot('name') continue @endslot
                    @slot('value') yes @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('field') button @endslot
                    @slot('col') col-md-3 @endslot
                    @slot('class') btn-save float-right @endslot
                    @slot('name') save @endslot
                    @slot('faicon') fa-chevron-right @endslot
                    @slot('endIcon') true @endslot
                    @slot('label') &nbsp; @endslot
                    @slot('anchor') {{ Lang::get('auth.continue') }} @endslot
                @endcomponent
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
        @endcomponent
    </div>
</div>
