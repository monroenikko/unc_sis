<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="js-form_disc_fee">
                {{ csrf_field() }}
                {{-- @if ($DiscountFee)
                    <input type="hidden" name="id" value="{{ $DiscountFee->id }}">
                @endif --}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        {{-- {{ $DiscountFee ? 'Edit Discount Fee' : 'Add Discount Fee' }} --}}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Grade lvl</label>
                        <input type="text" class="form-control" name="disc_type" value="">
                        <div class="help-block text-red text-center" id="js-disc_type">
                        </div>
                    </div>

                    <div class="form-group">
                            <label for="">Tuition Fee</label>
                            <input type="number" class="form-control" name="disc_fee" value="">
                            <div class="help-block text-red text-center" id="js-disc_fee">
                            </div>
                    </div>

                    <div class="form-group">
                        <label for="">Miscelleneous Fee</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">June</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">July</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">August</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">September</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">October</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">November</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">December</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">January</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">February</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">March</label>
                        <input type="number" class="form-control" name="disc_fee" value="">
                        <div class="help-block text-red text-center" id="js-disc_fee">
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