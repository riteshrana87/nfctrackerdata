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
                        <?=$this->lang->line('add_dropdown')?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                        <?=$this->lang->line('edit_dropdown')?>
                    <?php }elseif(isset($readonly)){?>
                        <?=$this->lang->line('view_dropdown')?>
                    <?php }?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= base_url($this->type . '/User') ?>"><i class="fa"></i>User</a></li>
            <li class="active">
                  <?PHP if($formAction == ""){ ?>
                        <?=$this->lang->line('add_dropdown')?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                        <?=$this->lang->line('edit_dropdown')?>
                    <?php }elseif(isset($readonly)){?>
                        <?=$this->lang->line('view_dropdown')?>
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
                        <?=$this->lang->line('add_dropdown')?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                        <?=$this->lang->line('edit_dropdown')?>
                    <?php }elseif(isset($readonly)){?>
                        <?=$this->lang->line('view_dropdown')?>
                    <?php } ?>
                        </h3>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a> 
                         <?= isset($validation) ? $validation : ''; ?>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php
                        $attributes = array("name" => "dropdown_form", "id" => "dropdown_form", "data-parsley-validate" => "" ,"class" => "form-horizontal");

                        echo form_open_multipart($path, $attributes);
                        ?>
                    <div class="hide">
                            <input name="selected_status" id="selected_status" type="hidden" value="<?=isset($editRecord[0]['status'])?$editRecord[0]['status']:''?>"  />
                        </div>
                        <div class="box-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="title" class="control-label col-md-4 required">Title<?php if($disable ==""){?><?php }?></label>
                                    <div class="col-md-8">
                                        <?php if($disable ==""){ ?>
                                            <input class="form-control" id="title" name="title" placeholder="Enter Title" type="text" value="<?php echo set_value('title',(!empty($editRecord[0]['title'])) ? $editRecord[0]['title']:'')?>" 
                                                                                    required="true"  data-parsley-minlength="2"
                                            data-parsley-minlength-message ='The title field must be at least 2 characters in length.' data-parsley-maxlength="100" data-parsley-maxlength-message ='The title field must not more then 100 characters in length.' data-parsley-required="true"
                                                                                    data-parsley-title
                                                                                    data-parsley-trigger="keyup" 
                                                                                    <?php echo $disable; ?> />
                                        <?php }else{?>
                                            <p><?php echo $editRecord[0]['title']; ?></p>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="prefix" class="control-label col-md-4 required">Prefix (this prefix used to link your dropdown to Masterform for AAI Forms only )<?php if($disable ==""){?><?php }?></label>
                                    <div class="col-md-8">
                                        <?php if($disable ==""){ ?>
                                            <input class="form-control" id="prefix" name="prefix" placeholder="Enter Prefix" type="text" value="<?php echo set_value('prefix',(!empty($editRecord[0]['prefix'])) ? $editRecord[0]['prefix']:'')?>" 
                                             required="true" data-parsley-pattern='/^\w+$/' data-parsley-minlength="2"
                                            data-parsley-minlength-message ='The title field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The prefix field must not more then 50 characters in length.' data-parsley-required="true"
                                            data-parsley-pattern-message="Please enter a valid prefix including character and numbers only" data-parsley-prefix data-parsley-trigger="keyup" 
                                                                                    <?php echo $disable; ?> />
                                        <?php }else{?>
                                            <p><?php echo $editRecord[0]['prefix']; ?></p>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="controller_name" class="control-label col-md-4 required">Status</label>
                                    <div class="col-md-8">
                                        <?php if(isset($editRecord[0]['status'])  && $disable ==""){ ?>

                                            <select class="form-control " data-parsley-errors-container="#STATUS_error" placeholder="<?php echo lang('user_status'); ?>"  name="status" id="status" required="true" <?php echo $disable; ?> >
                                                <!-- <option value="">
                      <?= $this->lang->line('user_status') ?>
                      </option> -->
                                                <?php
                                                $options = array('1'=>lang('active'),'0'=>lang('inactive'));
                                                if(isset($editRecord[0]['status']) && $editRecord[0]['status'] != ""){                                                
                                                    $selected = $editRecord[0]['status'];                                               
                                                }else{
                                                    $selected = 1;
                                                }
                                                ?>
                                                <?php foreach($options as $key => $rows){
                                                    if($selected == $key){?>
                                                        <option selected value="<?php echo $key;?>"><?php echo $rows;?></option>
                                                    <?php }else{?>
                                                        <option value="<?php echo $key;?>"><?php echo $rows;?></option>
                                                    <?php }}?>
                                            </select>
                                            <span id="STATUS_error"></span>
                                        <?php }elseif ( (isset($editRecord[0]['login_id']) && $this->session->userdata['nfc_admin_session']['admin_id'] == $editRecord[0]['login_id']) && $disable =="" ){ ?>
                                            <?php if($editRecord[0]['status'] == 'active'){?>
                                                <p><?=lang('active')?></p>
                                            <?php }else{?>
                                                <p><?=lang('inactive')?></p>
                                            <?php }?>
                                        <?php }elseif($disable =="" && $formAction == ""){?>
                                            <select class="form-control " data-parsley-errors-container="#STATUS_error" placeholder="<?php echo lang('user_status'); ?>"  name="status" id="status" required="true" <?php echo $disable; ?> >

                                                <?php
                                                $options = array('1'=>lang('active'),'0'=>lang('inactive'));
                                                if(isset($editRecord[0]['status']) && $editRecord[0]['status'] != ""){                                                
                                                    $selected = $editRecord[0]['status'];                                               
                                                }else{
                                                    $selected = 1;
                                                }
                                                ?>
                                                <?php foreach($options as $key => $rows){
                                                    if($selected == $key){?>
                                                        <option selected value="<?php echo $key;?>"><?php echo $rows;?></option>
                                                    <?php }else{?>
                                                        <option value="<?php echo $key;?>"><?php echo $rows;?></option>
                                                    <?php }}?>
                                            </select>
                                        <?php }elseif($disable =! ""){?>

                                            <?php if( isset($editRecord[0]['status']) && $editRecord[0]['status'] == 'active'){?>
                                                <p><?=lang('active')?></p>
                                            <?php }else{?>
                                                <p><?=lang('inactive')?></p>
                                            <?php }?>

                                        <?php }?>
                                    </div>
                                </div>
                            </div>


                    <div class="clearfix"></div>
                    <div class="col-sm-12 text-center">
                        <div class="bottom-buttons">
                           <?php if(!isset($readonly)){ ?>
                                <input name="dropdown_id" id="dropdown_id" type="hidden" value="<?=!empty($editRecord[0]['dropdown_id'])?$editRecord[0]['dropdown_id']:''?>" />
                                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                <?php if($formAction == ""){?>
                                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Create Dropdown" />
                                <?php }else{?>
                                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Update Dropdown" />
                                <?php }?>

                            <a href="<?php echo base_url('Admin/DropDownController') ?>" class="btn btn-default">Cancel</a>
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