<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path ='Masteradmin/updatePasswords';
?>
<div class="main-content login-main-content">
        <!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page login-page ">
                <div class="widget-shadow">
                    <div class="login-top">
                        <img class="logo" src="<?= base_url() ?>uploads/assets/front/images/logo.png" />
                    </div>
                    <div class="login-body">
                        <?php echo $this->session->flashdata('msgs'); ?>
                        
                        <h1 class="page-title"><?php echo lang('updatepassword')?></h1>
		<?php $attributes = array("name" => "updatepassword", "id" => "updatepassword", "data-parsley-validate" => "");
			echo form_open_multipart($path, $attributes);
		?>
                        <div class="form-group">
                        <input class="form-control form-control-login" id="password" name="password" placeholder="<?php echo lang('newpassword')?>" type="password" data-parsley-minlength="6" data-parsley-required="true"  />
                        <span class="text-danger"><?php echo form_error('password'); ?></span>
                        </div>
                        <div class="form-group">
                       <input class="form-control form-control-login" name="cpassword" placeholder="<?php echo lang('CONFIRM_PASSWORD')?>" type="password" data-parsley-equalto="#password" data-parsley-minlength="6" data-parsley-required="true" />
        	<span class="text-danger"><?php echo form_error('cpassword'); ?></span>
            </div>
                       <input type="hidden" id="tokenID" name="tokenID"  value="<?php echo $this->input->get('token');?>">	           
                       <input name="submit_btn" type="submit" value="Submit" class="btn full-width btn-white">
            
                       <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!--footer-->
        
        <!--//footer-->
    </div>