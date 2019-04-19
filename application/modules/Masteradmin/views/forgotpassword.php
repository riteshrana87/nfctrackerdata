<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path ='Masteradmin/resetpassword';

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
                        <?php echo $this->session->flashdata('msg'); ?>
                        <h1 class="page-title">Reset Password</h1>
     <?php $attributes = array("name" => "resetpassword", "id" => "resetpassword", "data-parsley-validate" => "");
			echo form_open_multipart($path, $attributes);
		?>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" required="" data-parsley-trigger="change" data-parsley-email>
                    </div>
                            <input type="submit" name="Sign In" value="SUBMIT">
                       <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>