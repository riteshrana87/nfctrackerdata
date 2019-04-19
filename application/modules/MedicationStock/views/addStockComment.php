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
            <h4 class="modal-title" id="exampleModalLabel"><?=empty($is_stock_check)?'Stock Adjustment':'Stock Check'?></h4>
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
                <label for="recipient-name" class="control-label">Medication Name : <span class="astrick"></span>
                </label>
                    <?php echo set_value('medication_name', (isset($medication_data[0]['medication_name'])) ? $medication_data[0]['medication_name'] : ''); ?>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Description :  <span class="astrick"></span>
                </label>
                    <?php echo set_value('medication_name', (!empty($medication_data[0]['reason'])) ? strip_tags(html_entity_decode($medication_data[0]['reason'])) : lang('NA')); ?>
            </div>

            <div class="form-group">
                <label for="recipient-name" class="control-label">Comments <span class="astrick">*</span></label>
                    <textarea type="text"  name='comments' class="form-control" placeholder="Enter comments" minlength="2" data-parsley-required-message="Please enter comment." required="" maxlength="500" data-parsley-trigger="keyup" ><?php echo set_value('comments', (isset($editRecord[0]['comments'])) ? $editRecord[0]['comments'] : ''); ?></textarea>
            </div>
            <?php 
            if(empty($is_stock_check)){
           ?>
            <hr>
            
            <div class="form-group">
            <label for="recipient-name" class="control-label">Quantity Increase/Decrease
            <span class="astrick">*</span></label>
            <select class="chosen-select form-control" name="quantity_type" id="quantity_type" required="true">
                  <option value="1">Increase</option>
                  <option value="2">Decrease</option>
            </select>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Old Remaining Stock :  <span class="old_stock"></span> <?= !empty($medication_data[0]['stock']) ? $medication_data[0]['stock'] : 0 ?> </label>
                
                <p>

                <label class="control-label">Adjustment amount</label>
                    <input type="number"  name='new_quntity' class="new_quntity form-control" placeholder="Adjustment amount" minlength="1" step=".01" max="999" min="0" onkeypress="return isNumberPerKey(event)" maxlength="6" data-parsley-trigger="keyup" >

                  
                <label for="recipient-name" class="control-label">New Remaining Stock : <span class="new_stock"><?= !empty($medication_data[0]['stock']) ? $medication_data[0]['stock'] : 0 ?></span> </label>
                </p>
                
            </div>
            <?php } ?>
            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                <input type="hidden" id="form_secret" name="yp_id"  value="<?= !empty($medication_data[0]['yp_id']) ? $medication_data[0]['yp_id'] : '' ?>">
                <input type="hidden" id="form_secret" name="medication_id"  value="<?= !empty($medication_data[0]['medication_id']) ? $medication_data[0]['medication_id'] : '' ?>">
                <input type="hidden" id="form_secret" name="medical_care_home_id"  value="<?= !empty($medication_data[0]['medical_care_home_id']) ? $medication_data[0]['medical_care_home_id'] : '' ?>">
                <input type="hidden" id="form_secret" name="care_home_id"  value="<?= !empty($medication_data[0]['care_home_id']) ? $medication_data[0]['care_home_id'] : '' ?>">
                <input type="hidden" id="remaing_stock" name="remaing_stock"  value="<?= !empty($medication_data[0]['stock']) ? $medication_data[0]['stock'] : 0 ?>">
                <?php if(empty($is_stock_check)){ ?>
                <input type="hidden" id="new_remaing_stock" name="new_remaing_stock"  value="">
                <?php } ?>
                <input type="hidden" id="is_stock_check" name="is_stock_check"  value="<?=!empty($is_stock_check)?$is_stock_check:''?>">

                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Submit" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script type="text/javascript">
function isNumberPerKey(evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

$('body').on('change', '.new_quntity', function (e) {
    var quantity_type = $('#quantity_type').val();
    var new_quntity = $(this).val();
    var remaing_stock = $('#remaing_stock').val();
    if(new_quntity != '')
    {
        if(quantity_type == 1)
        {
            $('.new_quntity').attr('max','999');
            var new_remaing_stock = (parseFloat(new_quntity)+parseFloat(remaing_stock)).toFixed(2);
                
            $('#new_remaing_stock').val(new_remaing_stock);
            $('.new_stock').text(new_remaing_stock);
        }
        else
        {
            $('.new_quntity').attr('max',$('#remaing_stock').val());
            var new_remaing_stock = (parseFloat(remaing_stock)- parseFloat(new_quntity)).toFixed(2);
                
            $('#new_remaing_stock').val(new_remaing_stock);
            $('.new_stock').text(new_remaing_stock);
        }
    }
    else
    {
         $('#new_remaing_stock').val(remaing_stock);
         $('.new_stock').text(remaing_stock);
    }
    
});

$('body').on('change', '#quantity_type', function (e) {
    var quantity_type = $(this).val();
    var new_quntity = $('.new_quntity').val();
    var remaing_stock = $('#remaing_stock').val();
    if(new_quntity != '')
    {
        if(quantity_type == 1)
        {
            $('.new_quntity').attr('max','999');
            var new_remaing_stock = (parseFloat(new_quntity)+parseFloat(remaing_stock)).toFixed(2);
                
            $('#new_remaing_stock').val(new_remaing_stock);
            $('.new_stock').text(new_remaing_stock);
        }
        else
        {
            $('.new_quntity').attr('max',$('#remaing_stock').val());
            var new_remaing_stock = (parseFloat(remaing_stock)- parseFloat(new_quntity)).toFixed(2);
                
            $('#new_remaing_stock').val(new_remaing_stock);
            $('.new_stock').text(new_remaing_stock);
        }
    }
    else
    {
         $('#new_remaing_stock').val(remaing_stock);
         $('.new_stock').text(remaing_stock);
    }
});
$(function(){
    $('.new_quntity').keyup(function(){
        var new_quntity = $(this).val();
        var remaing_stock = $('#remaing_stock').val();
        var quantity_type = $('#quantity_type').val();
        
        if(new_quntity != '')
        {
            if(quantity_type == 1)
            {
                var new_remaing_stock = (parseFloat(new_quntity)+parseFloat(remaing_stock)).toFixed(2);
            
                $('#new_remaing_stock').val(new_remaing_stock);
                $('.new_stock').text(new_remaing_stock);
            }
            else
            {
                var new_remaing_stock = (parseFloat(remaing_stock)- parseFloat(new_quntity)).toFixed(2);
                
                $('#new_remaing_stock').val(new_remaing_stock);
                $('.new_stock').text(new_remaing_stock);
            }
            
        }
        else
        {
             $('#new_remaing_stock').val(remaing_stock);
             $('.new_stock').text(remaing_stock);
        }

        
    })
});
$(document).ready(function() {
        $("#registration").on('submit', function(e){
            e.preventDefault();
            var form = $(this);
             var valid = false;
            form.parsley().validate();

            if (form.parsley().isValid()){
                 var valid = true;
                $('input[type="submit"]').prop('disabled', true);
                $('button[type="submit"]').prop('disabled', true);
            }
            if (valid) this.submit();
        });
    });
</script>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>

