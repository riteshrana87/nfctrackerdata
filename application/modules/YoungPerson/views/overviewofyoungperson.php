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
            <h4 class="modal-title" id="exampleModalLabel">Pen Portrait & Risk Oversight </h4>
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
            </div>
        </div>
        <div class="modal-body" id="penPortraitRiskOversight">
            <?php
            $attributes = array("name" => "personal_info", "id" => "registration", "data-parsley-validate" => "true", "onload" => "loadit('registration')");

            echo form_open_multipart($path, $attributes);
            ?>

            <div class="form-group">
                <label for="recipient-name" class="control-label">Pen Portrait</label>                
                <textarea class="form-control tinyeditor" rows="5" name="pen_portrait" id="pen_portrait"><?= !empty($editRecord[0]['pen_portrait']) ? $editRecord[0]['pen_portrait'] : '' ?></textarea>
            </div>
            
            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                <input type="hidden" id="form_secret" name="yp_id"  value="<?= !empty($id) ? $id : '' ?>">
                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Submit" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<?php
if (isset($footerJs) && count($footerJs) > 0) {
                         foreach ($footerJs as $js) {?>
                                <script src="<?php echo $js; ?>" ></script>
                <?php }} ?>


<script type="text/javascript"> $(function () {tinymce.init({ selector:'.tinyeditor',branding: false, ui_container: '#penPortraitRiskOversight' });});</script>

