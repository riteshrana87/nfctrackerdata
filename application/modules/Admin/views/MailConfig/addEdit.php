<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path = $form_action_path;
?>
<?php  echo $this->session->flashdata('verify_msg'); ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
         Edit Mail Configuration
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= base_url($this->type . '/User') ?>"><i class="fa"></i>User</a></li>
            <li class="active">
                  Edit Mail Configuration
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
                            Edit Configuration
                        </h3>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a>                          
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php 
                        $attributes = array("name" => "mail_config", "id" => "mail_config", "data-parsley-validate" => "" ,"class" => "form-horizontal");

                        echo form_open_multipart($path, $attributes);
                        
                        ?>
                        <!--<form role="form" name="mail_config" id="mail_config" class="form-horizontal" enctype="multipart/form-data" method="post" data-parsley-validate >-->
                    <div class="box-body">   
                            <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-md-4">Yp Name</label>
                                <div class="col-md-8">                                   
                                        <p><?= (!empty($editRecord[0]['yp_fname'])) ? $editRecord[0]['yp_fname'].' '.$editRecord[0]['yp_lname']:'' ?></p>
                                </div>
                            </div>
                        </div> 
                            <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-md-4">Care home Name</label>
                                <div class="col-md-8">                                   
                                        <p><?= (!empty($editRecord[0]['care_home_name'])) ? $editRecord[0]['care_home_name']:'' ?></p>
                                </div>
                            </div>
                        </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-md-4">Email</label>
                                <div class="col-md-8">                                   
                                        <p><?= (!empty($editRecord[0]['email_id'])) ? $editRecord[0]['email_id']:'' ?></p>
                                </div>
                            </div>
                        </div>
                            
                         <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password" class="control-label col-md-4">Password</label>
                                <div class="col-md-8">                                   
                                        <input class="form-control" id="password" name="password" placeholder="Enter password" type="password" data-parsley-minlength="3"
                                        data-parsley-minlength-message ='Password must be at least 3 characters in length.' data-parsley-maxlength="30" data-parsley-required="true" data-parsley-maxlength-message ='Password field must not more then 30 characters in length.' value="<?php echo set_value('password','')?>" />
<!--                                        <input class="form-control" id="password" name="password" placeholder="Enter password" type="password" data-parsley-minlength="3"
                                        data-parsley-minlength-message ='Password must be at least 3 characters in length.' data-parsley-maxlength="30" data-parsley-required="true" data-parsley-maxlength-message ='Password field must not more then 30 characters in length.' value="<?php echo set_value('password',(!empty($editRecord[0]['email_pass'])) ? $editRecord[0]['email_pass']:'')?>" />-->
                                        <span style="color: red"><?= isset($validation) ? $validation : ''; ?></span> 
<!--                                        <span style="color: #367fa9"><input type="checkbox" name="updateAllPassword" /> Use same password for every YP </span>-->
                                </div><br/>
                               
                            </div>
                            </div>
                         
<!--                         <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email_server" class="control-label col-md-4">Email Server</label>
                                <div class="col-md-8">
                                        <input class="form-control" id="email_server" name="email_server" placeholder="Enter email server" type="text"
                                        data-parsley-required="true" value="<?php echo set_value('email_server',(!empty($editRecord[0]['email_server'])) ? $editRecord[0]['email_server']:'')?>" />
                                        <span style="color: red"><?= isset($validation) ? $validation : ''; ?></span>
                                </div>
                               
                            </div>
                            </div>                         
                         
                         <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email_port" class="control-label col-md-4">Email Port</label>
                                <div class="col-md-8">
                                        <input class="form-control" id="email_port" name="email_port" placeholder="Enter email port" type="number"
                                        data-parsley-required="true" value="<?php echo set_value('email_port',(!empty($editRecord[0]['email_port'])) ? $editRecord[0]['email_port']:'')?>" />
                                        <span style="color: red"><?= isset($validation) ? $validation : ''; ?></span>
                                </div>
                               
                            </div>
                            </div>
                         
                         <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email_encryption" class="control-label col-md-4">Email Encryption</label>
                                <div class="col-md-8">
                                        <input class="form-control" id="email_encryption" name="email_encryption" placeholder="Enter email encryption" type="text"
                                        data-parsley-required="true" value="<?php echo set_value('email_encryption',(!empty($editRecord[0]['email_encryption'])) ? $editRecord[0]['email_encryption']:'')?>" />
                                        <span style="color: red"><?= isset($validation) ? $validation : ''; ?></span>
                                </div>
                               
                            </div>
                            </div>                         
                         
                         <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email_smtp" class="control-label col-md-4">Email SMTP</label>
                                <div class="col-md-8">
                                        <input class="form-control" id="email_smtp" name="email_smtp" placeholder="Enter email smtp" type="text"
                                        data-parsley-required="true" value="<?php echo set_value('email_smtp',(!empty($editRecord[0]['email_smtp'])) ? $editRecord[0]['email_smtp']:'')?>" />
                                        <span style="color: red"><?= isset($validation) ? $validation : ''; ?></span>
                                </div>
                               
                            </div>
                            </div>
                         
                         <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email_smtp_port" class="control-label col-md-4">Email SMTP Port</label>
                                <div class="col-md-8">
                                        <input class="form-control" id="email_smtp_port" name="email_smtp_port" placeholder="Enter email smtp port" type="number"
                                        data-parsley-required="true" value="<?php echo set_value('email_smtp_port',(!empty($editRecord[0]['email_smtp_port'])) ? $editRecord[0]['email_smtp_port']:'')?>" />
                                        <span style="color: red"><?= isset($validation) ? $validation : ''; ?></span>
                                </div>
                               
                            </div>
                            </div>-->
                         
                        <div class="clearfix"></div>
                        <div class="col-sm-6">                       
                            <div class="col-sm-12 text-center">
                                <div class="bottom-buttons">
                                   <?php if(!isset($readonly)){ ?>
                                        <input name="yp_id" id="care_home_id" type="hidden" value="<?=!empty($editRecord[0]['yp_id'])?$editRecord[0]['yp_id']:''?>" />
                                        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Update Password" />
                                    
                                    <a href="<?php echo base_url('Admin/MailConfig') ?>" class="btn btn-default">Cancel</a>
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
var base_url = "<?php echo base_url();?>";
</script>