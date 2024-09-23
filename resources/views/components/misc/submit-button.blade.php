@isset($col)
    <div class="{{ $col }}">
        @endisset
        @isset($form_group)
            <div class="form-group">
                @isset($label) <label>{{ $label }}</label> @endisset
                @endisset
                <button type="{{ $type ?? 'submit' }}" class="{{ $class ?? 'btn1' }} cursor-pointer"
                        name="{{ $name ?? null }}"
                        value="{{ $value ?? null }}"
                        @isset($alertify)
                        @foreach($alertify as $k=>$a)
                            data-{{$k}}="{{ $a }}"
                        @endforeach
                        @endisset
                        @isset($disabled) disabled @endisset
                >
                    @if(isset($faicon) && empty($endIcon))
                        <i class="fa {{ $faicon }}" aria-hidden="true"></i>
                    @endif
                    {{ $anchor ?? null }}
                    @if(isset($faicon) && isset($endIcon))
                        <i class="fa {{ $faicon }} not-right-left" aria-hidden="true"></i>
                    @endif
                </button>
                @isset($form_group)
            </div>
        @endisset
        @isset($col)
    </div>
@endisset
