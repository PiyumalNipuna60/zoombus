@push('scripts_admin')
    <script type="text/javascript" src="{{ URL::asset('admin/js/plugins/bootstrap/bootstrap-select.js') }}"></script>
@endpush
@component('components.misc.form')
    @slot('ajax') {{ $ajax ?? route('admin_users_edit_profile') }} @endslot
    <div class="panel panel-default">
        @if(!empty($user))
            <div class="panel-body">
                <h3><span class="fa fa-user"></span> {{ $user['name'] ?? null }}</h3>
                <p>{{ implode(",", $user['the_type'] ?? null) }}</p>
                <div class="text-center" id="user_image">
                    <img src="{{ $user['avatar'] ?? null }}" alt="Avatar" class="img-thumbnail"/>
                    <br>
                    <label for="avatar" class="btn-upload"><i class="fa fa-upload"
                                                              aria-hidden="true"></i> {{ Lang::get('auth.upload_avatar') }}
                    </label>
                    <input type="file" name="avatar" id="avatar">
                </div>
            </div>
        @elseif(!empty($vehicle))
            <div class="panel-body">
                <h3><span class="fa fa-car"></span>
                    {{ $vehicle['manufacturers']['name'] .' '.$vehicle['model']. ' / '.$vehicle['license_plate'] }}
                </h3>
                <p>{{ $vehicle['route_types']['translated']['name'] }}</p>
                <div class="text-center" id="user_image">
                    <div id="slider" class="flexslider">
                        <ul class="slides">
                            @foreach(json_decode($vehicle['images_extension'], true) as $i => $vi)
                                @if(\Illuminate\Support\Facades\Storage::disk('s3')->exists('drivers/vehicles/'.$vehicle['id'].'/'.$vi))
                                    <li>
                                        <img src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl('drivers/vehicles/'.$vehicle['id'].'/'.$vi, now()->addMinutes(5)) }}"
                                             alt="Image {{ $i }}" class="img-thumbnail"/>
                                    </li>
                                    @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="panel-body form-group-separated">
            @foreach($fields as $val)
                @component('components.misc.form-group-col', ['ignoreAuth' => true, 'value' => $val['value'] ?? null, 'values' => $val['values'] ?? null, 'checkedOnes' => $val['checkedOnes'] ?? [], 'select_key' => $val['select_key'] ?? null])
                    @slot('label_class') {{ $val['label_class'] ?? 'col-md-3 col-xs-5 control-label' }} @endslot
                    @slot('label') {{ $val['label'] }} @endslot
                    @slot('name') {{ $val['name'] }} @endslot
                    @isset($val['disabled']) @slot('disabled') @endslot @endisset
                    @isset($val['readonly']) @slot('readonly') @endslot @endisset
                    @isset($val['group_class']) @slot('group_class') {{ $val['group_class'] }} @endslot @endisset
                    @isset($val['class']) @slot('class') {{ $val['class'] }} @endslot @endisset
                    @isset($val['nolabel']) @slot('nolabel') @endslot @endisset
                    @slot('field') {{ $val['field'] ?? null }} @endslot
                    @slot('field_wrapper_class') {{ $val['field_wrapper_class'] ?? 'col-md-9 col-xs-7' }} @endslot
                    @isset($val['addon']) @slot('addon') {{ $val['addon'] }} @endslot @endisset
                    @isset($val['type']) @slot('type') {{ $val['type'] }} @endslot @endisset
                    @isset($val['placeholder']) @slot('placeholder') {{ $val['placeholder'] }} @endslot @endisset
                @endcomponent
            @endforeach
            @component('components.misc.submit-button')
                @slot('class') btn btn-danger btn-block btn-rounded mtb-15 @endslot
                @slot('anchor') {{ Lang::get('admin.save') }} @endslot
            @endcomponent
        </div>
    </div>
@endcomponent
