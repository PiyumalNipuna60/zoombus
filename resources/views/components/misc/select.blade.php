<div class="select1_wrapper">
    <label for="{{ $select_id ?? $name }}">{{ $title }}</label>
    <div class="select1_inner">
        <select class="select2 select" style="width: 100%" name="{{ $name }}" id="{{ $select_id ?? $name }}">
            @foreach($values as $key=>$value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>