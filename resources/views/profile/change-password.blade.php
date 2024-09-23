@extends('layouts.app', ['isprofile' => 1])

@section('title', 'Zoombus')

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@section('title1', Lang::get('titles.edit_profile3'))
@section('title2', Lang::get('titles.edit_profile4'))

@section('content')
    <div class="profile-password">
        <div class="profile-password-form">
            <div class="response"></div>
            @component('components.misc.form')
                @slot('class') form-horizontal form-default @endslot
                @slot('form_id') changePasswordForm @endslot
                @slot('row_inside') @endslot
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.old_password') }} @endslot
                    @slot('name') old_password @endslot
                    @slot('type') password @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('faicon') fa-lock @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.new_password') }} @endslot
                    @slot('name') password @endslot
                    @slot('value') @endslot
                    @slot('type') password @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('faicon') fa-lock @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('label') {{ Lang::get('auth.new_password_confirm') }} @endslot
                    @slot('name') password_confirmation @endslot
                    @slot('type') password @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('faicon') fa-lock @endslot
                @endcomponent
                @component('components.misc.form-group-col')
                    @slot('field') button @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('class') btn-save @endslot
                    @slot('name') save @endslot
                    @slot('faicon') fa-floppy-o @endslot
                    @slot('label') &nbsp; @endslot
                    @slot('anchor') {{ Lang::get('auth.save') }} @endslot
                @endcomponent
            @endcomponent
        </div>
    </div>
@endsection
