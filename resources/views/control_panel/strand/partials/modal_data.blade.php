<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="js-form_school_year">
                {{ csrf_field() }}
                @if ($Strand)
                    <input type="hidden" name="id" value="{{ $Strand->id }}">
                @endif
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        {{ $Strand ? 'Edit Strand' : 'Add Strand' }}
                        
                    </h4>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="">Student Type </label>
                            <select name="student_type" id="student_type" class="form-control">
                                <option value="">Select Student Type</option>
                                <option value="3" {{ $Strand ? $Strand->student_type_id == 3 ? 'selected' : '' : '' }}>Senior Highschool</option>
                                <option value="4" {{ $Strand ? $Strand->student_type_id == 4 ? 'selected' : '' : '' }}>College</option>
                            </select>
                            <div class="help-block text-red text-center" id="js-student_type">
                            </div>
                        </div>
                    
                        <div class="form-group">
                                <label class="js-type" for="">Strand Name</label>
                                <input type="text" class="form-control" name="strand_name" value="{{ $Strand ? $Strand->strand : '' }}">
                                <div class="help-block text-red text-center" id="js-strand_name">
                                </div>
                        </div>
                        {{-- {{ $StudentInformation ? $StudentInformation->first_name : '' }} --}}

                        <div class="form-group">
                                <label class="js-abv" for="">Strand Name Abbreviation</label>
                                <input type="text" class="form-control" name="abb_name" value="{{ $Strand ? $Strand->abbreviation : '' }}">
                                <div class="help-block text-red text-center" id="js-abb_name">
                                </div>
                        </div>

                    
                
                    {{-- <div class="form-group">
                        <label for="">Set as Current School Year</label>
                        <select name="current_sy" id="current_sy" class="form-control"> --}}
                            {{-- <option value="1" {{ $SchoolYear ? ($SchoolYear->current == 0 ? 'selected' : '')  : '' }}>Yes</option>
                            <option value="0" {{ $SchoolYear ? ($SchoolYear->current == 0 ? 'selected' : '')  : 'selected' }}>No</option> --}}
                        {{-- </select>
                        <div class="help-block text-red text-center" id="js-current_sy">
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-flat">Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

