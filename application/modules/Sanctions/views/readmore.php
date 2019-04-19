<!-- main content start-->

<div class="modal-dialog readmore_sanction" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel"><?=ucfirst(str_replace('_',' ',$field))?> </h4>
            
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                   <div class="model_content"> <p>
                        <?=!empty($field_val)?$field_val:lang('NA')?>
                    </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            
        </div>

    </div>
</div>