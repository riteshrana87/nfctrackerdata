<div class="main-content login-main-content" style="background: none;">
        <!-- main content start-->
        <div id="page-wrapper" style="background: none;">
            <div class="main-page login-page">
                <div class="widget-shadow">
                    <div class="login-body">
                         <?php echo $this->session->flashdata('msg'); ?>
                         <h1 class="page-title"><?= lang('CHANGE_PASSWORD') ?></h1>
                            
  <form action="<?php echo base_url();?>MyProfile/updatePassword" name="update_password" id="update_password" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                                
                       <div class="form-group">
                                <label for="cat_name" class="required"><?php echo lang('password'); ?></label>
                                    <input style="padding-left: 15px;" class="form-control" autocomplete="off" id="password" name="password" value="" data-parsley-trigger="change" placeholder="<?php echo lang('PASSWORD'); ?>" type="password" required data-parsley-minlength="6">
                                    
                            </div>
                                <div class="form-group">
                                <label for="cat_description" class="required"><?php echo lang('cpassword'); ?></label>
                                    <input style="padding-left: 15px;" class="form-control" required name="cpassword" id="cpassword" data-parsley-eq="#password" placeholder="<?php echo lang('CONFIRM_PASSWORD'); ?>" data-parsley-trigger="change" type="password" data-parsley-equalto="#password" data-parsley-minlength="6">
                            </div>
                        <input name="Sign In" value="Reset Password" type="submit">
                        <input name="timezone" id="timezone" type="hidden">
                       </form>                    </div>
                </div>
            </div>
        </div>
        <!--footer-->
        
        <!--//footer-->
    </div>