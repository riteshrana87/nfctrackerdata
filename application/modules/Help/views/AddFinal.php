<script src='<?= base_url() ?>uploads/custom/js/jquery.blockUI.js'></script>
    <?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($readonly)){
	
	$disable = $readonly['disabled'];
}else{
	$disable = "";
}

if($project_view!='viewdata'){
$formAction = 'saveHelpData';
}else{
	$formAction='';
	
}
if($project_view=='Help/updatedata'){

$formAction = 'updateHelpData';
}else{
	$formAction='';
	
}
if($project_view=='Help'){
$formAction = 'saveHelpData';
}else{
	$formAction='';
	
}

$id =  $from_email = $this->session->userdata('LOGGED_IN')['ID'];
$formPath = $project_view . '/' . $formAction;
//echo $project_view;die;

/*pr($setup);
die('aa');*/
?>    
<!--modal-->
<div class="modal-dialog modal-lg">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
<button title="<?php echo lang('close') ?>" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><b>Help/Feedback</b></h4>

                    </div>
                    <div class="modal-body">
                        <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
        <input type="hidden" name="update_id" id="update_id" <?php if(isset($id)) { ?>value="<?php echo $id;?><?php }?>"/>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label"><?php echo lang('firstname') ?>:- </label>
                                    <?php echo $this->session->userdata('LOGGED_IN')['FIRSTNAME'];?>
        
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label"><?php echo lang('lastname') ?> :- </label>
                                <?php echo $this->session->userdata('LOGGED_IN')['LASTNAME'];?>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label"><?php echo lang('email') ?> :- </label>
                            <?php echo $this->session->userdata('LOGGED_IN')['EMAIL'];?>
                        </div>

                         <div class="form-group">
                            <label for="recipient-name" class="control-label"><?php echo lang('mail_subject') ?> <span class="astrick">*</span></label>
                            <input type="text"   class="form-control" placeholder="<?php echo lang('mail_subject') ?> " id="subject" name="subject"  required <?php if(isset($setup[0]['subject'])) { ?>   value="<?php echo $setup[0]['subject'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        </div>
                        
                        <div class="form-group">
                                <label for="initials" class="control-label"> Description <span class="astrick">*</span></label>
                <textarea id="help_desc" class="form-control" required rows="4" placeholder="Description" name="help_desc" ></textarea>

                        </div>

                        <div class="modal-footer">
                            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                            <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="<?php echo lang('submit'); ?>" />
                            
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
  

<!--modal-->
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Uploads</h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	$('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
		$("#termsError").css("display", "none");
		$('#from-model').parsley();

    $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                $(this).prop('disabled', true);
                $(".close").trigger("click");
                 $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
                $.ajax({
                url: $('#from-model').attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $('#from-model').serialize(),
                success: function(result) {
                    $.unblockUI();
                    if (result.status == 1){
                        BootstrapDialog.show({ message:'<?php echo lang('thankyou');?>' });
                        return false;    
                    }
                    
                }
                
            });
            return false;
            }
        });
	$(document).ready(function() {
        $("form").on('submit', function(e){
            var form = $(this);
             var valid = false;
            form.parsley().validate();
 
            if (form.parsley().isValid()){
                 var valid = true;
                $('input[type="submit"]').prop('disabled', true);
                $('button[type="submit"]').prop('disabled', true);
                
                  $('input[type="submit"]').unbind();
                  $('button[type="submit"]').unbind();
        }

        });
    });
		 
 
    });
 
</script>