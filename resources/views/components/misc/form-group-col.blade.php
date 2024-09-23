@unless(Auth::check() && isset($hideForAuthed) || isset($hideIfHasValue) && Auth::check() && Auth::user()->$name)
    @isset($col)
        <div class="{{ $col }} @isset($hidden) hidden @endisset">
            @endisset
            @unless(isset($hideGroup))
                <div class="{{ $group_class ?? 'form-group' }}">
                    @endunless
                    @if(isset($field) && $field == 'checkbox')
                        @isset($values)
                            <div class="row">
                                @foreach($values as $k=>$v)
                                    <div class="col-md-6">
                                        <input type="checkbox" name="{{ $name ?? null }}[]"
                                               @empty($no_id) id="{{ $field_id ?? $name }}{{$k}}" @endempty
                                               class="form-check-input" value="{{ $v['id'] ?? null }}"
                                               @if(in_array($v['id'], $checkedOnes ?? [])) checked @endisset>
                                        <label
                                            for="{{ $field_id ?? $name }}{{ $k }}">{{ $v['translated']['name'] ?? $v['name'] }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endisset
                        @empty($values)
                            <input type="checkbox" name="{{ $name ?? null }}"
                                   @empty($no_id) id="{{ $field_id ?? $name }}"
                                   @endempty
                                   class="{{ $class ?? 'form-check-input' }}" value="{{ $value ?? null }}"
                                   @isset($checked) checked @endisset @isset($disabled) disabled
                                   @endisset @isset($readonly) readonly @endisset>
                        @endempty
                    @endif

                    @if(isset($field) && $field == 'radio')
                        <input type="radio" name="{{ $name ?? null }}" @empty($no_id) id="{{ $field_id ?? $name }}"
                               @endempty
                               class="{{ $class ?? null }}" value="{{ $value ?? null }}"
                               @isset($checked) checked @endisset @isset($disabled) disabled
                               @endisset @isset($readonly) readonly @endisset>
                    @endif

                    @unless(isset($hideGroup))
                        @unless(isset($nolabel))
                            <label @isset($label_class) class="{{ $label_class }}"
                                   @endisset for="{{ $field_id ?? $name }}@if(isset($field) && $field=='image')_up @endif">@if(isset($faicon) && isset($label) && $label && $label != '&nbsp;')
                                    <i class="fa {{ $faicon }}" aria-hidden="true"></i> @endif {!! $label ?? null !!}
                            </label>
                            @isset($explanation)
                                <i class="fa fa-question-circle expl"></i>
                                <div class="explanation">
                                    <div class="explanation-inner">{{ $explanation }}</div>
                                    <div class="arrow-down"></div>
                                </div>
                            @endisset
                        @endunless
                    @endunless

                    @if(isset($field_wrapper_class))
                        <div class="{{ $field_wrapper_class }}">
                            @endif

                            @if(isset($field) && $field == 'select')
                                <select class="{{ $class ?? 'form-control' }}" name="{{ $name ?? null }}"
                                        @empty($no_id) id="{{ $field_id ?? $name ?? null }}"
                                        @endempty @isset($disabled) disabled
                                        @endisset @isset($readonly) readonly @endisset>
                                    @isset($values)
                                        @isset($select_default_value)
                                            <option
                                                value="{{ $select_default_value }}">{{ $select_default_anchor ?? $select_default_value }}</option>
                                        @endisset
                                        @foreach($values as $key => $valuez)
                                            <option value="{{ (isset($select_key)) ? $key : $valuez['id'] ?? $valuez }}"
                                                @isset($value)
                                                    @if(isset($select_key) && $value == $key || !isset($select_key) && $value == ($valuez['id'] ?? $valuez))
                                                        selected
                                                    @endif
                                                @endisset>
                                                {{ (!empty($valuez['translated'])) ? $valuez['translated']['name'] : $valuez['text'] ?? $valuez['name'] ?? $valuez }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            @elseif(isset($field) && $field == 'button')
                                @component('components.misc.submit-button', ['alertify' => $alertify ?? []])
                                    @slot('anchor') {{ $anchor ?? null }} @endslot
                                    @isset($faicon) @slot('faicon') {{ $faicon }} @endslot @endisset
                                    @slot('type') {{ $type ?? 'submit' }} @endslot
                                    @isset($disabled) @slot('disabled') @endslot @endisset
                                    @slot('class') {{ $class }} @endslot
                                    @isset($endIcon) @slot('endIcon') true @endslot @endisset
                                    @slot('name') {{ $name }} @endslot
                                @endcomponent
                            @elseif(isset($field) && $field == 'hidden')
                                <input type="hidden" class="{{ $class ?? null }}" name="{{ $name ?? null }}"
                                       value="{{ $value ?? null }}">
                            @elseif(isset($field) && $field == 'textarea')
                                <textarea autocomplete="off" placeholder="{{ $placeholder ?? $label ?? null }}"
                                          class="{{ $class ?? 'form-control' }}" id="{{ $field_id ?? $name }}"
                                          name="{{ $name ?? null }}">{!! $value ?? null  !!}</textarea>
                            @elseif(isset($field) && $field == 'image')
                                <div class="profile-license-btns">
                                    <label for="{{ $field_id ?? $name ?? null }}" class="btn-upload">
                                        <i class="fa fa-upload"
                                           aria-hidden="true"></i> {{ $upload_text ?? Lang::get('auth.upload') }}
                                    </label>
                                    <input type="file" class="hidden" name="{{ $name ?? null }}"
                                           @empty($no_id) id="{{ $field_id ?? $name ?? null }}"
                                           @endempty @isset($multiple) multiple
                                           @endisset @isset($disabled) disabled
                                           @endisset @isset($readonly) readonly @endisset>
                                    @if(isset($image_path) && !is_array($image_path) && Storage::disk('s3')->exists($image_path))
                                        <a href="{{ $image_ajax ?? 'javascript:void(0)' }}" class="btn-delete"
                                           @isset($image_ajax) data-action="ajax" @endisset id="delete_{{ $name }}">
                                            <i class="fa fa-trash"
                                               aria-hidden="true"></i> {{ $delete_text ?? Lang::get('auth.delete') }}
                                        </a>
                                    @endif
                                </div>
                                @if(isset($image_path))
                                    @if(is_array($image_path))
                                        <div class="row">
                                            @php $c=1; @endphp
                                            @foreach($image_path as $ip)
                                                @if(Storage::disk('s3')->exists($ip))
                                                    <div class="{{ $image_class ?? 'profile-license-img' }}">
                                                        <div class="hovered-bg hidden"></div>
                                                        <img src="{{ Storage::temporaryUrl($ip, now()->addMinutes(5)) }}" alt="{{ $label.' '.$c ?? null }}">
                                                        <div class="hidden hovered-single-image">
                                                            <i class="fa fa-trash delete-veh-image"
                                                               data-path="{{ $ip }}"></i>
                                                        </div>
                                                    </div>
                                                @endif
                                                @php $c++; @endphp
                                            @endforeach
                                        </div>
                                    @elseif(Storage::disk('s3')->exists($image_path))
                                        <div class="{{ $image_class ?? 'profile-license-img' }}">
                                            <img src="{{ Storage::temporaryUrl($image_path, now()->addMinutes(5)) }}" alt="{{ $label ?? null }}">
                                        </div>
                                    @endif
                                @endif
                            @else
                                @if(isset($field) && $field != 'checkbox' && $field != 'radio' || !isset($field))
                                    <input type="{{ $type ?? 'text' }}" @isset($required) required
                                           oninvalid="this.setCustomValidity('{{ $required }}')"
                                           oninput="setCustomValidity('')"
                                           @endisset name="{{ $name ?? null }}"
                                           @empty($no_id) id="{{ $field_id ?? $name }}"
                                           @endempty
                                           class="{{ $class ?? 'form-control' }} {{ $addon ?? null }}"
                                           @isset($start_view) data-date-start-view="{{ $start_view }}" @endisset
                                           placeholder="{{ $placeholder ?? $label ?? null }}"
                                           @if(isset($ignoreAuth))
                                           value="{{ $value ?? null }}"
                                           @else
                                           value="{{ $value ?? Auth::user()->$name ?? null }}"
                                           @endif
                                           @if(isset($addon) && $addon == 'typeahead') data-provide="typeahead"
                                           @isset($typeahead) data-display="{{ $typeahead['display'] }}"
                                           data-remote-url="{{ $typeahead['remote'] }}" @endisset
                                           @endif
                                           autocomplete="off" @isset($disabled) disabled
                                           @endisset @isset($readonly) readonly @endisset>
                                @endunless
                            @endif

                            @if(isset($field_wrapper_class))
                        </div>
                    @endif

                    @unless(isset($hideGroup))
                </div>
            @endunless
            @isset($col)
        </div>
    @endisset

    @isset($addon)
        @if($addon == 'datepicker')
            @push('styles')
            <link href="{{ URL::asset('css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
            @endpush
            @push('scripts')
            <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.min.js') }}"></script>
            <script type="text/javascript" src="{{ URL::asset('js/moment.js') }}"></script>
            @endpush
            @if ( Session::has('locale'))
            @if(Session::get('locale') == 'ka')
                @push('scripts')
                <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.ka.min.js') }}"></script>
                @endpush
            @elseif ( Session::get('locale') == 'ru' )
                @push('scripts')
                <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.ru.min.js') }}"></script>
                @endpush
            @endif
            @endif
        @endif
        @if($addon == 'typeahead')
            @push('scripts')
            <script type="text/javascript" src="{{ URL::asset('js/bloodhound.min.js') }}"></script>
            <script type="text/javascript" src="{{ URL::asset('js/typeahead.bundle.js') }}"></script>
            @endpush
        @endif
    @endisset
@endunless
