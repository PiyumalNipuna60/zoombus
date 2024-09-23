@if(!empty($addNew))
    <a href="{{ $addNew['url'] }}" class="btn btn-primary mtl-15"><i class="fa fa-plus"></i> {{ $addNew['anchor'] }}
    </a>
@endif
<div class="panel-body panel-body-table">
    <div class="table-responsive">
        <table
            data-type="dataTable"
            data-fields="{{ json_encode($columns ?? []) }}"
            data-url="{{ $ajaxUrl ?? null }}"
            data-field-defs="{{ json_encode($columnDefs ?? []) }}"
            data-post-data="{{ json_encode($ajaxData ?? []) }}"
            data-date-defs="{{ json_encode($dateDefs ?? []) }}"
            data-sort-order="{{ json_encode($sort_order ?? []) }}"
            class="table table-bordered table-striped table-actions" width="100%">
            <thead>
            <tr>
                @foreach($columns as $fld)
                    <th>{{ Lang::get('admin.'.$fld) }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@push('scripts_admin')
    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('js/moment-with-locales.js') }}"></script>
@endpush
