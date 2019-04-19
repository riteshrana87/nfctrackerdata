<!-- main content start-->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel"><?=ucfirst(str_replace('_',' ',$field))?> </h4>
            
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <p style="word-wrap: break-word;">
                        <?=!empty($documents[0][$field])?$documents[0][$field]:''?>
                    </p>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            
        </div>

    </div>
</div>