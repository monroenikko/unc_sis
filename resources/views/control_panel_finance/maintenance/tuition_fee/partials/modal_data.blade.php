<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="js-form_tuition_fee">
                {{ csrf_field() }}
                @if ($TuitionFee)
                    <input type="hidden" name="id" value="{{ $TuitionFee->id }}">
                @endif
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        {{ $TuitionFee ? 'Edit Tuition Fee' : 'Add Tuition Fee' }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tuition fee</label>
                        <input type="number" class="form-control" name="tuition_fee" value="{{ $TuitionFee ? $TuitionFee->school_year : '' }}">
                        <div class="help-block text-red text-center" id="js-tuition_fee">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Set as Current Tuition Fee</label>
                        <select name="current_sy" id="current_sy" class="form-control">
                            <option value="1" {{ $TuitionFee ? ($TuitionFee->current == 0 ? 'selected' : '')  : '' }}>Yes</option>
                            <option value="0" {{ $TuitionFee ? ($TuitionFee->current == 0 ? 'selected' : '')  : 'selected' }}>No</option>
                        </select>
                        <div class="help-block text-red text-center" id="js-current_sy">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-flat">Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->