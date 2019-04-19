<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path = $form_action_path;

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
                        <h4 class="modal-title" id="exampleModalLabel">YP Sign off</h4>
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
                            <label for="recipient-name" class="control-label">User type <span class="astrick">*</span></label>
                            <select name="user_type" id="user_type" required="" class="form-control">
                                <option value="">Select User</option>
                                <option value="parent" user-type="parent">Parent</option>
                                <?php if(!empty($parent_data)){
                                    foreach ($parent_data as $row) {
                                        ?>
                                        <option value="<?=$row['parent_carer_id']?>" user-type="parent_data"><?= !empty($row['firstname']) ? $row['firstname'] : '' ?> <?= !empty($row['surname']) ? $row['surname'] : '' ?></option>
                                        <?php
                                    }
                                    }?>
                                <option value="social_worker" user-type="social_worker">Social Worker</option>
                                <?php if(!empty($social_worker_data)){
                                    foreach ($social_worker_data as $row) {
                                        ?>
                                        <option value="<?=$row['social_worker_id']?>" user-type="social_data">Social Worker (<?= !empty($row['social_worker_firstname']) ? $row['social_worker_firstname'] : '' ?> <?= !empty($row['social_worker_surname']) ? $row['social_worker_surname'] : '' ?>)</option>
                                        <?php
                                    }
                                    }?>
                                <option value="other" user-type="other">Other</option>
                            </select>
                        </div>

                        <div class="whitebox" id="common_div">
                            <?php $this->load->view('signoff_ajax'); ?>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                            <input type="submit" class="btn btn-default" disabled name="submit_btn" id="submit_btn" value="Submit" />
                            <input type="hidden" name="ypid" id="ypid" value='<?=!empty($ypid)?$ypid:''?>'>
                            <input type="hidden" name="is_id" value='<?=!empty($is_id)?$is_id:''?>'>
                            <input type="hidden" name="care_home_id" value='<?=!empty($care_home_id)?$care_home_id:''?>'>
                            
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
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>