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
                        <h4 class="modal-title" id="exampleModalLabel">Edit YP</h4>
                        <div class="col-md-12 error-list">
                            <?= isset($validation) ? $validation : ''; ?>
                        </div>

                    </div>
                    <div class="modal-body">
                        <?php if(isset($move_care_home_data) && !empty($move_care_home_data)){
                                $key = array_search($editRecord[0]['care_home'], array_column($care_home_data, 'care_home_id'));
                                ?>
                                <div class="alert alert-info">
                                  <p><strong>Info!</strong> currently YP living at <b><?= $care_home_data[$key]['care_home_name'] ?></b> since <b><?= configDateTime($editRecord[0]['care_home_admission_date']) ?></b>
									<button type="button" class="cancel-btn-sty" onclick="canclecron(<?php echo $editRecord[0]['yp_id'];?>)"; aria-label="Close"><span class="">cancel</span></button>
								  </p>
                                </div>							
                        <?php }
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
                    <?php if(isset($move_care_home_data) && !empty($move_care_home_data) && $move_care_home_data[0]['move_date']!=''){

                            $admissiondate=$move_care_home_data[0]['move_date'];

                    }else{
                            $admissiondate=$editRecord[0]['care_home_admission_date'];

                    }?>
                    <input type="text" id="admission"  name='care_home_admission_date' class="form-control" placeholder="DD/MM/YYYY" value="<?= (!empty($admissiondate) && $admissiondate != '0000-00-00') ? configDateTime($admissiondate) : '' ?>" required='true' data-parsley-required-message="Please enter date." data-parsley-errors-container="#DOP_error" data-parsley-trigger="keyup">
                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                <span id="DOP_error"></span>
            </div>   

                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Care Home <span class="astrick">*</span>
                            </label>
                            <select class="chosen-select form-control" data-parsley-errors-container="#usertype_error"  placeholder="Select Care Home"  name="care_home" id="care_home" required="true" >
                                <option value="">Select Care Home</option>
								
                                <?php if(isset($move_care_home_data) && !empty($move_care_home_data) && isset($move_care_home_data[0]['move_care_home'])){
                                            $salutions_id = $move_care_home_data[0]['move_care_home']; 
                                    }else{
                                            $salutions_id = $editRecord[0]['care_home']; 
                                            }?>

                                <?php foreach ($care_home_data as $row) {
                                        if(isset($move_care_home_data) && !empty($move_care_home_data) && isset($move_care_home_data[0]['move_care_home']) && $move_care_home_data[0]['move_care_home']==$row['care_home_id']){?>
                                                <option selected value="<?php echo $row['care_home_id']; ?>"><?php echo $row['care_home_name']; ?></option>

                                        <?php }
                                    else if ($salutions_id == $row['care_home_id']) {
                                        ?>
                                        <option selected value="<?php echo $row['care_home_id']; ?>"><?php echo $row['care_home_name']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $row['care_home_id']; ?>"><?php echo $row['care_home_name']; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="initials" class="control-label">Initials <span class="astrick">*</span></label>
                            <input class="form-control" id="initials" name="initials" placeholder="Enter Initials Id" type="text" value="<?= !empty($editRecord[0]['yp_initials']) ? $editRecord[0]['yp_initials'] : '' ?>" <?php echo $disable; ?>/>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                            <input type="hidden" id="yp_id" name="yp_id"  value="<?= !empty($editRecord[0]['yp_id']) ? $editRecord[0]['yp_id'] : '' ?>">
                            <input type="hidden" id="current_Care_home" name="current_Care_home"  value="<?= !empty($current_Care_home) ? $current_Care_home : '' ?>">
                            <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Update Young Person" />
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
var url_calcle="<?php echo base_url() . 'YoungPerson/canclecron/'?>";
    $('#admission_date').datetimepicker({
        format: 'DD/MM/YYYY', //format: 'L',
        useCurrent: false,
    });
    if($("#admission").val()!=''){
        $('#admission_date').data("DateTimePicker").minDate($("#admission").val());
    }

</script>

<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>