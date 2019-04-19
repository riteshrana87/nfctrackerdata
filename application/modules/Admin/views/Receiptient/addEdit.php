<?php
if(isset($editRecord) && $editRecord == "updatedata"){
    $record = "updatedata";
}else{
    $record = "insertdata";
}
?>
<script>
    var formAction = "<?php echo $record;?>";    
</script>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'edit':'';
$path = $form_action_path;
if(isset($readonly)){
    $disable = $readonly['disabled'];
}else{
    $disable = "";
}
$main_user_data = $this->session->userdata('nfc_admin_session');
$main_user_id = $main_user_data['admin_id'];
?>
<?php  echo $this->session->flashdata('verify_msg'); ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          <?PHP if($formAction == ""){ ?>
                        <?php echo "Add Recipient";?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                       <?php echo "Edit Recipient";?>
                    <?php }elseif(isset($readonly)){?>
                        <?php echo "View Recipient";?>
                    <?php }?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= base_url($this->type . '/User') ?>"><i class="fa"></i>User</a></li>
            <li class="active">
                  <?PHP if($formAction == ""){ ?>
                         <?php echo "Add Recipient";?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                         <?php echo "Edit Recipient";?>
                    <?php }elseif(isset($readonly)){?>
                        <?php echo "View Recipient";?>
                    <?php }?>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                    <?PHP if($formAction == ""){ ?>
                        <?php echo "Add Recipient";?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                        <?php echo "Edit Recipient";?>
                    <?php }elseif(isset($readonly)){?>
                        <?php echo "View Recipient";?>
                    <?php } ?>
                        </h3>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a> 
                         <?= isset($validation) ? $validation : ''; ?>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php
                        $attributes = array("name" => "recipient", "id" => "recipient", "data-parsley-validate" => "" ,"class" => "form-horizontal");

                        echo form_open_multipart($path, $attributes);
                        ?>
                   
                        <div class="box-body">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Recipient Type" class="control-label col-md-4 required">Recipient Type<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="recipient_type" name="recipient_type" placeholder="Enter Recipient Type" type="text" value="<?php echo set_value('receipt_type',(!empty($editRecord[0]['receipt_type'])) ? $editRecord[0]['receipt_type']:'')?>" 
										required="true"  data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The Recipient Type field must be at least 2 characters in length.' data-parsley-maxlength="100" data-parsley-maxlength-message ='The Recipient Type field must not more then 100 characters in length.' data-parsley-required="true"
										data-parsley-subject
										data-parsley-trigger="keyup" 
										<?php echo $disable; ?> />
                                    <?php }else{?>
                                        <p><?php echo $editRecord[0]['receipt_type']; ?></p>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
						
						<div class="col-sm-12">
                            <div class="form-group">
                                <label for="email" class="control-label col-md-4 required">Email<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="email" name="email" placeholder="Enter email" type="text" value="<?php echo set_value('receipt_email',(!empty($editRecord[0]['receipt_email'])) ? $editRecord[0]['receipt_email']:'')?>" 
										required="true"  
										<?php echo $disable; ?> />
										 <span id="email_error" class="error-danger" style="display: none;">Please enter a valid email address.</span>
                                    <?php }else{?>
                                        <p><?php echo $editRecord[0]['receipt_email']; ?></p>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                         <div class="clearfix"></div>
                      
                    <div class="clearfix"></div>
                    <div class="col-sm-12 text-center">
                        <div class="bottom-buttons">
                           <?php if(!isset($readonly)){ ?>
                                <input name="template_id" id="template_id" type="hidden" value="<?=!empty($editRecord[0]['template_id'])?$editRecord[0]['template_id']:''?>" />
                                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                <?php if($formAction == ""){?>
                                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Create Email Template" />
                                <?php }else{?>
                                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Update Email Template" />
                                <?php }?>

                            <a href="<?php echo base_url('Admin/AIEmailTemplate') ?>" class="btn btn-default">Cancel</a>
                            <?php } ?>
                        </div>
                    </div>
                        </div><!-- /.box-body -->
<?php echo form_close(); ?> 
</div>
      
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
var edit_id = "<?php echo $id; ?>"; 

</script>