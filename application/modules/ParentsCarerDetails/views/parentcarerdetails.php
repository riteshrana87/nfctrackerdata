<?php
if (isset($editRecord) && $editRecord == "updatedata") {
    $record = "updatedata";
} else {
    $record = "insertdata";
}
?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'edit' : 'registration';
$path = $form_action_path;
if (isset($readonly)) {
    $disable = $readonly['disabled'];
} else {
    $disable = "";
}
$main_user_data = $this->session->userdata('LOGGED_IN');
$main_user_id = $main_user_data['ID'];
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Parents / Carers Information</h4>
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
            </div>
        </div>
        <div class="modal-body">
            <?php
            $attributes = array("name" => "personal_info", "id" => "registration", "data-parsley-validate" => "true", "onload" => "loadit('registration')");

            echo form_open_multipart($path, $attributes);
            ?>

            <div class="form-group">
                <label for="recipient-name" class="control-label">First Name <span class="astrick">*</span></label>                
                <input class="form-control" name="firstname" placeholder="Enter first name" type="text" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" value="<?= !empty($editRecord[0]['firstname']) ? $editRecord[0]['firstname'] : '' ?>" data-parsley-trigger="keyup" data-parsley-required-message="Please enter first name." required="" />
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Surname <span class="astrick">*</span></label>                
                <input class="form-control" name="surname" placeholder="Enter sur name" type="text" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" value="<?= !empty($editRecord[0]['surname']) ? $editRecord[0]['surname'] : '' ?>" data-parsley-trigger="keyup" data-parsley-required-message="Please enter sur name." required="" />
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Relationship <span class="astrick">*</span></label>                
                <input class="form-control" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" name="relationship" placeholder="Enter relationship"  type="text" value="<?= !empty($editRecord[0]['relationship']) ? $editRecord[0]['relationship'] : '' ?>" data-parsley-trigger="keyup" data-parsley-required-message="Please enter relationship." required="" />
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Address <span class="astrick">*</span></label>                
                <input class="form-control" minlength="2" maxlength="100" name="address" placeholder="Enter address"  type="text" value="<?= !empty($editRecord[0]['address']) ? $editRecord[0]['address'] : '' ?>" data-parsley-trigger="keyup" data-parsley-required-message="Please enter address." required="" />
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Mobile Number <span class="astrick">*</span></label>                
                <input type="text" id='mobile_number' name='contact_number' class="form-control" placeholder="Enter contact number" value="<?php echo set_value('contact_number', (isset($editRecord[0]['contact_number']) ? $editRecord[0]['contact_number'] : '')) ?>" required='true' data-parsley-required-message="Please enter contact number." minlength="10" maxlength="15" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-pattern-message="Please enter only numbers." data-parsley-trigger="keyup"/>
                
            </div>

            <div class="form-group">
                <label for="recipient-name" class="control-label">Landline Number <span class="astrick">*</span></label>                
                <input type="text" id='landline_number' name='landline_number' class="form-control" placeholder="Enter Landline Number" value="<?php echo set_value('landline_number', (isset($editRecord[0]['landline_number']) ? $editRecord[0]['landline_number'] : '')) ?>" required='true' data-parsley-required-message="Please enter Landline Number." minlength="10" maxlength="15" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-pattern-message="Please enter only numbers." data-parsley-trigger="keyup"/>
                
            </div>

            <div class="form-group">
                <label for="recipient-name" class="control-label">Email Address <span class="astrick">*</span></label>
                
                <input class="form-control" id="email_address" name="email_address" autocomplete="false" placeholder="Enter email address" data-parsley-trigger="change" required="" data-parsley-required-message="Please enter email address." type="email" value="<?= !empty($editRecord[0]['email_address']) ? $editRecord[0]['email_address'] : '' ?>" data-parsley-trigger="keyup" data-parsley-email />
                
            </div>
                
            <div class="form-group">
                <label for="recipient-name" class="control-label">YP Authorised Communication <span class="astrick">*</span></label>
                <label class="radio-inline">
                    <input class="radio" required="true" data-parsley-required-message="Please enter YP authorised communication." data-parsley-errors-container="#error_yp_authorised_communication" name="yp_authorised_communication"  id="yp_authorised_communication" type="radio" value="Yes" <?= (!empty($editRecord[0]['yp_authorised_communication']) && $editRecord[0]['yp_authorised_communication']=='Yes') ? 'checked' : '' ?>/>Yes
                </label>
                <label class="radio-inline">
                    <input class="radio" required="true" data-parsley-required-message="Please enter YP authorised communication." data-parsley-errors-container="#error_yp_authorised_communication" name="yp_authorised_communication" id="yp_authorised_communication" type="radio" value="No" <?= (!empty($editRecord[0]['yp_authorised_communication']) && $editRecord[0]['yp_authorised_communication']=='No') ? 'checked' : '' ?>/> No
                </label>     
                <span id="error_yp_authorised_communication"></span> 
                    
                
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Carers Authorised Communication <span class="astrick">*</span></label>
                 <label class="radio-inline">
                    <input class="radio" required="true" data-parsley-required-message="Please enter carers authorised communication." data-parsley-errors-container="#error_carer_authorised_communication" name="carer_authorised_communication"  id="carer_authorised_communication" type="radio" value="Yes" <?= (!empty($editRecord[0]['carer_authorised_communication']) && $editRecord[0]['carer_authorised_communication']=='Yes') ? 'checked' : '' ?> />Yes
                </label>
                <label class="radio-inline">
                    <input class="radio" required="true" data-parsley-required-message="Please enter YP authorised communication." data-parsley-errors-container="#error_carer_authorised_communication" name="carer_authorised_communication" id="carer_authorised_communication" type="radio" value="No" <?= (!empty($editRecord[0]['carer_authorised_communication']) && $editRecord[0]['carer_authorised_communication']=='No') ? 'checked' : '' ?>/> No
                </label>     
                 <span id="error_carer_authorised_communication"></span>   
                
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Comments </label>
                    <textarea type="text"  name='comments' class="form-control" placeholder="Enter comments" minlength="2" maxlength="500" data-parsley-trigger="keyup" ><?php echo set_value('comments', (isset($editRecord[0]['comments'])) ? $editRecord[0]['comments'] : ''); ?></textarea>
            </div>

            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                <input type="hidden" id="form_secret" name="yp_id"  value="<?= !empty($yp_id) ? $yp_id : '' ?>">
                <input type="hidden" id="form_secret" name="parent_carer_id"  value="<?= !empty($editRecord[0]['parent_carer_id']) ? $editRecord[0]['parent_carer_id'] : '' ?>">
                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Submit" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<script type="text/javascript">
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

            if (valid) {setTimeout(

  function() 
  {
    $(this).submit();
    //do something special
  }, 2000); 
            }
        });
    });
    
</script>

