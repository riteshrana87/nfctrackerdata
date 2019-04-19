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
            <h4 class="modal-title" id="exampleModalLabel">Personal Info</h4>
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
            <label for="initials" class="control-label">Initials <span class="astrick">*</span></label>
            <input class="form-control" id="initials" name="initials" autocomplete="false" placeholder="Enter Initials Id" data-parsley-trigger="change" required="" type="text" value="<?= !empty($editRecord[0]['yp_initials']) ? $editRecord[0]['yp_initials'] : ''?>" disabled ="disabled" data-parsley-initials />

            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">D.O.B <span class="astrick">*</span></label>
                <div class="input-group input-append date" id="date_of_birth">
                    <input type="text"  name='date_of_birth' class="form-control" placeholder="DD/MM/YYYY"  value="<?= (!empty($editRecord[0]['date_of_birth']) && $editRecord[0]['date_of_birth'] != '0000-00-00') ? configDateTime($editRecord[0]['date_of_birth']) : '' ?>" required='true' data-parsley-required-message="Please enter date." data-parsley-errors-container="#DOB_error" data-parsley-trigger="keyup">
                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                <span id="DOB_error"></span>
            </div>

            <div class="form-group">
                <label for="recipient-name" class="control-label">Place of Birth</label>
                <input class="form-control" name="place_of_birth" id="position" placeholder="Enter place of birth" type="text" minlength="4" maxlength="100" data-parsley-pattern="^[:/,-.0-9a-zA-Z ]+" data-parsley-pattern-message="Please enter valid address.you can not use special symbol like &(%#$^)." value="<?= !empty($editRecord[0]['place_of_birth']) ? $editRecord[0]['place_of_birth'] : '' ?>" data-parsley-trigger="keyup"/> 
                
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Gender</label>
                <label class="radio-inline">
                    <input class="radio" name="gender"  id="gender" type="radio" value="M" <?= ($editRecord[0]['gender']=='M') ? 'checked' : '' ?>/>Male
                </label>
                <label class="radio-inline">
                    <input class="radio" name="gender" id="gender" type="radio" value="F" <?= ($editRecord[0]['gender']=='F') ? 'checked' : '' ?>/> Female
                </label>                           
            </div>

            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Date of Placement <span class="astrick">*</span></label>
                <div class="input-group input-append date" id='date_of_placement'>
                    <input type="text"   name='date_of_placement' class="form-control" placeholder="DD/MM/YYYY" value="<?= (!empty($editRecord[0]['date_of_placement']) && $editRecord[0]['date_of_placement'] != '0000-00-00') ? configDateTime($editRecord[0]['date_of_placement']) : '' ?>" required='true' data-parsley-required-message="Please enter date." data-parsley-errors-container="#DOP_error" data-parsley-trigger="keyup">
                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                <span id="DOP_error"></span>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">End of Placement</label>
                <div class="input-group input-append date" id='end_of_placement'>
                    <input type="text" name='end_of_placement' class="form-control" placeholder="DD/MM/YYYY" value="<?= (!empty($editRecord[0]['end_of_placement']) && $editRecord[0]['end_of_placement'] != '0000-00-00') ? configDateTime($editRecord[0]['end_of_placement']) : '' ?>" >
                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                <span id="EOP_error"></span>
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Legal Status <span class="astrick">*</span></label>
                <select name="legal_status" class="form-control" placeholder="Enter Legal Status" required="">
                    <option value="">Select Legal Status</option>
                    <?php if($editRecord[0]['legal_status'] == 1){?> selected <?php }?>
                    <option <?php if($editRecord[0]['legal_status'] == 1){?> selected <?php }?> value="1">Section 31 (Full Care Order)</option>
                    <option <?php if($editRecord[0]['legal_status'] == 2){?> selected <?php }?> value="2">Section 20 (Accommodated)</option>
                    <option <?php if($editRecord[0]['legal_status'] == 3){?> selected <?php }?> value="3">Interim Care Order (ICO)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Staffing Ratio</label>
                <input class="form-control" name="staffing_ratio" id="position" placeholder="Enter Staffing Ratio" type="text" value="<?= !empty($editRecord[0]['staffing_ratio']) ? $editRecord[0]['staffing_ratio'] : '' ?>" minlength="3" maxlength="10" 
                data-parsley-pattern="/^([0-9]+).([0-9]+):([0-9]+)|([0-9]+):([0-9]+)$/" data-parsley-pattern-message="Please enter valid ratio. e.g 2:1 or 1.5:1 or 1.3:1 or 1:1" data-parsley-trigger="keyup" />
            </div>
               <div class="form-group">
                <label for="recipient-name" class="control-label">Assessment Date start</label>
                <div class="input-group input-append date" id='assessment_date_start'>
                    <input type="text"   name='assessment_date_start' class="form-control" placeholder="DD/MM/YYYY" value="<?= (!empty($editRecord[0]['assessment_date_start']) && $editRecord[0]['assessment_date_start'] != '0000-00-00') ? configDateTime($editRecord[0]['assessment_date_start']) : '' ?>" data-parsley-required-message="Please enter date." data-parsley-errors-container="#DOP_error" data-parsley-trigger="keyup">
                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Assessment Date End</label>
                <div class="input-group input-append date" id='assessment_date_end'>
                    <input type="text" name='assessment_date_end' class="form-control" placeholder="DD/MM/YYYY" value="<?= (!empty($editRecord[0]['assessment_date_end']) && $editRecord[0]['assessment_date_end'] != '0000-00-00') ? configDateTime($editRecord[0]['assessment_date_end']) : '' ?>">
                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                
            </div>

             <div class="form-group">
                <label for="recipient-name" class="control-label">Assessment Review Date</label>
                <div class="input-group input-append date" id='assessment_review_date'>
                    <input type="text" name='assessment_review_date' class="form-control" placeholder="DD/MM/YYYY" value="<?= (!empty($editRecord[0]['assessment_review_date']) && $editRecord[0]['assessment_review_date'] != '0000-00-00') ? configDateTime($editRecord[0]['assessment_review_date']) : '' ?>" >
                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                
            </div>

            


            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                 <input type="hidden" id="form_secret" name="yp_id"  value="<?= !empty($editRecord[0]['yp_id']) ? $editRecord[0]['yp_id'] : '' ?>">
                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Submit" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">

	$('#date_of_birth').datetimepicker({
		format: 'DD/MM/YYYY', //format: 'L',
		useCurrent: false,
		maxDate:new Date()
    });
	
	$('#date_of_placement').datetimepicker({
		format: 'DD/MM/YYYY', //format: 'L',
		useCurrent: false,
		
	});
	
	$('#end_of_placement').datetimepicker({
		format: 'DD/MM/YYYY', //format: 'L',
        useCurrent: false
    });
	
	$("#date_of_placement").on("dp.show", function (e) {
		
		if(e.date != ''){
			$('#end_of_placement').data("DateTimePicker").minDate(e.date);
		}
		
		if($("#end_of_placement").data("DateTimePicker").date() !=null){
			$('#date_of_placement').data("DateTimePicker").maxDate($("#end_of_placement").data("DateTimePicker").date());
		}
		
	});
	
	$("#end_of_placement").on("dp.show", function (e) {
		
		if(e.date !=''){
			$('#date_of_placement').data("DateTimePicker").maxDate(e.date);
		}
		
		if($("#date_of_placement").data("DateTimePicker").date() !=null){
			$('#end_of_placement').data("DateTimePicker").minDate($("#date_of_placement").data("DateTimePicker").date());
		}
		
	});

/*start Assessment datepicker*/

    $('#assessment_date_start').datetimepicker({
        format: 'DD/MM/YYYY', //format: 'L',
        //useCurrent: false,
        
    });

$('#assessment_review_date').datetimepicker({
        format: 'DD/MM/YYYY', //format: 'L',
        //useCurrent: false,
        
    });
    
    
    $('#assessment_date_end').datetimepicker({
        format: 'DD/MM/YYYY', //format: 'L',
        //useCurrent: false
    });
    
    $("#assessment_date_start").on("dp.show", function (e) {
        
        if(e.date != ''){
            //$('#assessment_date_start').data("DateTimePicker").minDate(e.date);
        }
        
        if($("#assessment_date_end").data("DateTimePicker").date() !=null){
            $('#assessment_date_start').data("DateTimePicker").maxDate($("#assessment_date_end").data("DateTimePicker").date());
        }
        
    });
    
    $("#assessment_date_end").on("dp.show", function (e) {
        
        if(e.date !=''){
            //$('#assessment_date_start').data("DateTimePicker").maxDate(e.date);
        }
        
        if($("#assessment_date_start").data("DateTimePicker").date() !=null){
            $('#assessment_date_end').data("DateTimePicker").minDate($("#assessment_date_start").data("DateTimePicker").date());
        }
        
    });
    $("#assessment_review_date").on("dp.show", function (e) {
        
        if(e.date !=''){
            //$('#assessment_date_end').data("DateTimePicker").maxDate(e.date);
        }
        
        if($("#assessment_date_end").data("DateTimePicker").date() !=null){
            $('#assessment_review_date').data("DateTimePicker").minDate($("#assessment_date_end").data("DateTimePicker").date());
        }
        
    });
/*end Assessment datepicker*/
</script>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>

