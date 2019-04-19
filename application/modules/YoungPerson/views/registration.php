<?php
if (isset($editRecord) && $editRecord == "updatedata") {
    $record = "updatedata";
} else {
    $record = "insertdata";
}
?>
<script>
    var formAction = "<?php echo $record; ?>";
    var check_initials_url = "<?php echo base_url('YoungPerson/isDuplicateInitials'); ?>";
</script>
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
<?php if (isset($main_content)) { ?>
    <div id="page-wrapper">
        <div class="main-page">
        <?php } ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Add New YP</h4>
                        <div class="col-md-12 error-list">
                            <?= isset($validation) ? $validation : ''; ?>
                        </div>
                    </div>
                    <div class="modal-body">
                        <?php
                        $attributes = array("name" => "registration", "id" => "registration", "data-parsley-validate" => "true");
                        echo form_open_multipart($path, $attributes);
                        ?>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">First Name <span class="astrick">*</span></label>
                            <input class="form-control" name="fname" placeholder="Enter First Name" type="text" value="<?php echo set_value('fname', (!empty($editRecord[0]['yp_fname'])) ? $editRecord[0]['yp_fname'] : '') ?>" required="" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" data-parsley-trigger="keyup" />
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Surname <span class="astrick">*</span></label>
                            <input class="form-control" min-length="2" name="lname" placeholder="Enter Surname" type="text" value="<?= !empty($editRecord[0]['yp_lname']) ? $editRecord[0]['yp_lname'] : '' ?>" required="" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" data-parsley-trigger="keyup" />
                        </div>
                        <div class="form-group">
                <label for="recipient-name" class="control-label">Admission Date <span class="astrick">*</span></label>
                <div class="input-group input-append date" id='admission_date'>
                    <input type="text"   name='care_home_admission_date' class="form-control" placeholder="DD/MM/YYYY" value="<?= (!empty($editRecord[0]['care_home_admission_date']) && $editRecord[0]['care_home_admission_date'] != '0000-00-00') ? configDateTime($editRecord[0]['care_home_admission_date']) : '' ?>" required='true' data-parsley-required-message="Please enter date." data-parsley-errors-container="#DOP_error" data-parsley-trigger="keyup">
                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                <span id="DOP_error"></span>
            </div>  
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Care Home <span class="astrick">*</span></label>

                            <select class="chosen-select form-control" data-parsley-errors-container="#usertype_error"  placeholder="Select Care Home"  name="care_home" id="care_home" required="true" >
                                                    <option value="">Select Care Home</option>
                                            <?php
                                            if(!empty($editRecord[0]['care_home'])){
                                                $salutions_id = $editRecord[0]['care_home'];
                                            }else{
                                                $salutions_id = $care_home_id;
                                            }
                                            ?>

                                            <?php foreach($care_home_data as $row){
                                                if($salutions_id == $row['care_home_id']){?>
                                                    <option selected value="<?php echo $row['care_home_id'];?>"><?php echo $row['care_home_name'];?></option>
                                                <?php }else{?>
                                                    <option value="<?php echo $row['care_home_id'];?>"><?php echo $row['care_home_name'];?></option>
                                                <?php }}?>
                                        </select>
                        </div>

                        
                        <div class="form-group">
                                <label for="initials" class="control-label">Initials <span class="astrick">*</span></label>
                                    <input class="form-control" id="initials" name="initials" autocomplete="false" placeholder="Enter Initials Id" data-parsley-trigger="change" required="" type="text" value="<?= !empty($editRecord[0]['yp_initials']) ? $editRecord[0]['yp_initials'] : ''?>" <?php echo $disable; ?> data-parsley-initials />
                                    <span id="initial_email" style="padding-left: 14px"></span>

                        </div>
                        

                        <div class="modal-footer">
                            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                            <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Create Young Person" />
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>

        </div>
        <?php if (isset($main_content)) { ?>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">

    $('#admission_date').datetimepicker({
        format: 'DD/MM/YYYY', //format: 'L',
        defaultDate:new Date(),
        useCurrent: false,
        
    });
</script>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>

<script type="text/javascript">
    /*script added by Dhara Bhalala for display auto generated email */
    $("#initials").keyup(function(){
    var initials = $("#initials").val();
    var yp_init = initials.substring(0,3)
    if(initials !='')
        $("#initial_email").html(yp_init + '-<?= YP_EMAIL ?>');
    else
        $("#initial_email").html('');
    
});
       window.Parsley.addValidator('initials', function (value, requirement) {
    var response = false;
    var form = $(this);
    var initials = $("#initials").val();
    $.ajax({
        type: "POST",
        url: check_initials_url,
        data: {initialsID:initials}, // <--- THIS IS THE CHANGE
        async: false,
        success: function(result){
			
            if(result == 0){
                response = false;
            }else{
                response = true;
            }
        },
        error: function() {
            var delete_meg ="Error posting feed";
            BootstrapDialog.show(
                {
                    title: 'Information',
                    message: delete_meg,
                    buttons: [{
                        label: 'ok',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }]
                });
        }
    });

    return response;
}, 46)
    .addMessage('en', 'initials', 'Entered Initials or Email have aleady been used, please change Initials');
    </script>   
