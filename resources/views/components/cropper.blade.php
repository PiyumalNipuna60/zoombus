<div class="modal" id="cropperModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ Lang::get('misc.adjust_avatar') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="cropper-container">
                    <img id="currentAvatar">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-save" id="saveAvatar">{{ Lang::get('misc.save') }}</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/cropper.min.js') }}"></script>
@endpush


