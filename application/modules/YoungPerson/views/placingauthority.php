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
            <h4 class="modal-title" id="exampleModalLabel">Placing Authority </h4>
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
                <label for="recipient-name" class="control-label">Authority <span class="astrick">*</span></label>                
                <input class="form-control" name="authority" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." placeholder="Enter Authority" minlength="4" maxlength="100" type="text" value="<?PHP
                if ($formAction == "registration") {
                    echo set_value('authority');
                    ?><?php } else { ?><?= !empty($editRecord[0]['authority']) ? $editRecord[0]['authority'] : '' ?><?php } ?>" required="" data-parsley-trigger="keyup" />
            </div>


            <div class="form-group">
                <label for="recipient-name" class="control-label">Address <span class="astrick">*</span></label>                
                <input class="form-control" name="address_1" minlength="4" maxlength="255" placeholder="Enter Address" data-parsley-pattern="^[:/,-.0-9a-zA-Z ]+" data-parsley-pattern-message="Please enter valid address.you can not use special symbols like &(%#$^)" type="text" value="<?PHP
                if ($formAction == "registration") {
                    echo set_value('age');
                    ?><?php } else { ?><?= !empty($editRecord[0]['address_1']) ? $editRecord[0]['address_1'] : '' ?><?php } ?>" required="" data-parsley-trigger="keyup" />
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Town</label>
                <input class="form-control" name="town" id="position" placeholder="Enter town" type="text" value="<?= !empty($editRecord[0]['town']) ? $editRecord[0]['town'] : '' ?>" data-parsley-pattern="^[a-zA-Z ]+" minlength="2" maxlength="50" data-parsley-pattern-message="Please enter only alphabets." data-parsley-trigger="keyup"/> 

            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">County</label>
                <input class="form-control" name="county" id="position" placeholder="Enter county" type="text" value="<?= !empty($editRecord[0]['county']) ? $editRecord[0]['county'] : '' ?>" data-parsley-pattern="^[a-zA-Z ]+" minlength="2" maxlength="50"  data-parsley-pattern-message="Please enter only alphabets." data-parsley-trigger="keyup"/>                     </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Postcode <span class="astrick">*</span></label>
                    <input type="text"  name='postcode' class="form-control" placeholder="Enter Postcode" value="<?php echo set_value('postcode', (isset($editRecord[0]['postcode'])) ? $editRecord[0]['postcode'] : ''); ?>" minlength="2" maxlength="20"  required='true' data-parsley-required-message="Please enter postcode." data-parsley-pattern='/^[A-Za-z-\d\s]+$/' data-parsley-pattern-message="Please enter only alphanumeric." data-parsley-trigger="keyup">
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">EDIT / Out Of Hours</label>
                <input class="form-control" name="out_of_hours" id="position" minlength="1" maxlength="15" placeholder="Enter EDT / Out Of Hours" type="text" data-parsley-type="number" data-parsley-trigger="keyup" value="<?= !empty($editRecord[0]['out_of_hours']) ? $editRecord[0]['out_of_hours'] : '' ?>"  />                     </div>

            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                <input type="hidden" id="form_secret" name="yp_id"  value="<?= !empty($id) ? $id : '' ?>">
                <input type="hidden" id="form_secret" name="placing_authority_id"  value="<?= !empty($editRecord[0]['placing_authority_id']) ? $editRecord[0]['placing_authority_id'] : '' ?>">
                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Submit" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>

