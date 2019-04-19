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
$formAction = !empty($editRecord)?'edit':'registration';
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
          <?PHP if($formAction == "registration"){ ?>
                        <?=$this->lang->line('add_care_home')?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                        <?=$this->lang->line('edit_care_home')?>
                    <?php }elseif(isset($readonly)){?>
                        <?=$this->lang->line('view_care_home')?>
                    <?php }elseif($formAction == "registration"){ ?>
                        <?=$this->lang->line('UPLOAD_NOTE_IMPORTANT')?>
                    <?php }?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= base_url($this->type . '/User') ?>"><i class="fa"></i>User</a></li>
            <li class="active">
                  <?PHP if($formAction == "registration"){ ?>
                        <?=$this->lang->line('add_care_home')?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                        <?=$this->lang->line('edit_care_home')?>
                    <?php }elseif(isset($readonly)){?>
                        <?=$this->lang->line('view_care_home')?>
                    <?php }elseif($formAction == "registration"){ ?>
                        <?=$this->lang->line('UPLOAD_NOTE_IMPORTANT')?>
                    <?php }?>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content add-care-home">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                    <?PHP if($formAction == "registration"){ ?>
                        <?=$this->lang->line('add_care_home')?>
                    <?php }elseif($formAction == "edit" && !isset($readonly)){ ?>
                        <?=$this->lang->line('edit_care_home')?>
                    <?php }elseif(isset($readonly)){?>
                        <?=$this->lang->line('view_care_home')?>
                    <?php }elseif($formAction == "registration"){ ?>
                        <?=$this->lang->line('UPLOAD_NOTE_IMPORTANT')?>
                    <?php }?>
                        </h3>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a> 
                         <?= isset($validation) ? $validation : ''; ?>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php
                        $attributes = array("name" => "registration", "id" => "registration", "data-parsley-validate" => "" ,"class" => "form-horizontal");

                        echo form_open_multipart($path, $attributes);
                        ?>
                    <div class="hide">
                            <input name="selected_status" id="selected_status" type="hidden" value="<?=isset($editRecord[0]['status'])?$editRecord[0]['status']:''?>"  />
                        </div>
                        <div class="box-body">

                             <div class="col-sm-6">
                            <div class="form-group">
                                <label for=" ofsted_inspection_grade" class="control-label col-md-4 required">Ofsted Inspection Grade <?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="ofsted_inspection_grade" name="ofsted_inspection_grade" placeholder="Enter Ofsted Inspection Grade" type="text" value="<?php echo set_value('ofsted_inspection_grade',(!empty($editRecord[0]['ofsted_inspection_grade'])) ? $editRecord[0]['ofsted_inspection_grade']:'')?>" 
                                        required="true" data-parsley-pattern='/^[A-Za-z-\d\s]+$/'  data-parsley-minlength="1"
                                        data-parsley-minlength-message ='The care home name field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The care home name field must not more then 50 characters in length.' data-parsley-required="true"
                                        data-parsley-ofsted_inspection_grade
                                        data-parsley-trigger="keyup" 
                                        <?php echo $disable; ?> />
                                    <?php }else{?>
                                        <p><?php echo $editRecord[0]['ofsted_inspection_grade']; ?></p>
                                    <?php }?>
                                </div>
                            </div>
                        </div>

                         <div class="col-sm-6">
                            <div class="form-group">
                                <label for="last_ofsted_inspection_date" class="control-label col-md-4 required">Last Ofsted Inspection Date: <?php if($disable ==""){?><?php }?></label>
                                <div class="input-group dateadd col-sm-8" id="care_home_date">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="care_home_date" name="last_ofsted_inspection_date" placeholder="Enter Ofsted Inspection Grade" type="text" value="<?php echo set_value('last_ofsted_inspection_date',(!empty($editRecord[0]['last_ofsted_inspection_date'])) ? configDateTime($editRecord[0]['last_ofsted_inspection_date']):'')?>" 
                                        required="true" data-parsley-pattern='/^[A-Za-z-\d\s]+$/'  data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The Last Ofsted Inspection Date field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The Last Ofsted Inspection Date field must not more then 50 characters in length.' data-parsley-required="true"
                                        data-parsley-last_ofsted_inspection_date
                                        data-parsley-trigger="keyup" 
                                        <?php echo $disable; ?> />
                                          <div class="input-group-addon">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </div>
                                    <?php }else{ ?>
                                        <p><?php echo configDateTime($editRecord[0]['last_ofsted_inspection_date']); ?></p>
                                    <?php }?>
                                </div>
                            </div>
                        </div>

<div class="clearfix"></div>
                <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sc_number" class="control-label col-md-4 required">SC Number:<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="sc_number" name="sc_number" placeholder="Enter SC Number" type="text" value="<?php echo set_value('sc_number',(!empty($editRecord[0]['sc_number'])) ? $editRecord[0]['sc_number']:'')?>" 
                                        required="true" data-parsley-pattern='/^[A-Za-z-\d\s]+$/'  data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The SC Number field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The SC Number field must not more then 50 characters in length.' data-parsley-required="true"
                                        data-parsley-sc_number
                                        data-parsley-trigger="keyup" 
                                        <?php echo $disable; ?> />
                                    <?php }else{?>
                                        <p><?php echo $editRecord[0]['sc_number']; ?></p>
                                    <?php }?>
                                </div>
                            </div>
                        </div>


                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="component_name" class="control-label col-md-4 required">Care Home Name<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="care_home_name" name="care_home_name" placeholder="Enter Care Home Name" type="text" value="<?php echo set_value('care_home_name',(!empty($editRecord[0]['care_home_name'])) ? $editRecord[0]['care_home_name']:'')?>" 
										required="true" data-parsley-pattern='/^[A-Za-z-\d\s]+$/'  data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The care home name field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The care home name field must not more then 50 characters in length.' data-parsley-required="true"
										data-parsley-care_home_name
										data-parsley-trigger="keyup" 
										<?php echo $disable; ?> />
                                    <?php }else{?>
                                        <p><?php echo $editRecord[0]['care_home_name']; ?></p>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="address" class="control-label col-md-4">Address<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                    <textarea class="form-control" name="address" placeholder="Enter Address" <?php echo $disable; ?>><?php echo set_value('address',(!empty($editRecord[0]['address'])) ? $editRecord[0]['address']:'')?></textarea>
                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['address']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                         
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="town" class="control-label col-md-4">Town<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" name="town" placeholder="Enter town" type="text" data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The town name field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The town name field must not more then 50 characters in length.' value="<?php echo set_value('town',(!empty($editRecord[0]['town'])) ? $editRecord[0]['town']:'')?>" data-parsley-pattern='/^[A-Za-z\s]+$/' <?php echo $disable; ?> />
                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['town']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
<div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="town" class="control-label col-md-4">City<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" name="city" placeholder="Enter City" type="text" data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The City name field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The town name field must not more then 50 characters in length.' value="<?php echo set_value('town',(!empty($editRecord[0]['city'])) ? $editRecord[0]['city']:'')?>" data-parsley-pattern='/^[A-Za-z\s]+$/' <?php echo $disable; ?> />
                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['city']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="County" class="control-label col-md-4">County<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" name="county" placeholder="Enter County" type="text" data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The country name field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The country name field must not more then 50 characters in length.' value="<?php echo set_value('county',(!empty($editRecord[0]['county'])) ? $editRecord[0]['county']:'')?>" data-parsley-pattern='/^[A-Za-z\s]+$/' <?php echo $disable; ?> />
                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['county']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="Postcode" class="control-label col-md-4">Postcode<?php if($disable ==""){?><?php } ?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The postcode name field must be at least 2 characters in length.' data-parsley-maxlength="20" data-parsley-maxlength-message ='The postcode name field must not more then 20 characters in length.' name="postcode" placeholder="Enter Postcode" type="text" value="<?php echo set_value('postcode',(!empty($editRecord[0]['postcode'])) ? $editRecord[0]['postcode']:'')?>" data-parsley-pattern='/^[A-Za-z-\d\s]+$/' <?php echo $disable; ?> />

                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['postcode']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="Postcode" class="control-label col-md-4">Contact Number<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input type="text" id='contact_number' name='contact_number' class="form-control" placeholder="Enter Contact Number" value="<?php echo set_value('contact_number', (isset($editRecord[0]['contact_number']) ? $editRecord[0]['contact_number'] : '')) ?>" required='true' data-parsley-required-message="Please enter Contact Number." minlength="10" maxlength="15" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-pattern-message="Please enter only numbers." data-parsley-trigger="keyup"/>
                                    
                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['contact_number']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="Postcode" class="control-label col-md-4">Email<?php if($disable ==""){?><?php }?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="care_home_email" name="care_home_email" autocomplete="false" placeholder="Enter email id" data-parsley-trigger="change" required="" data-parsley-required-message="Please enter email id." type="email" value="<?= !empty($editRecord[0]['care_home_email']) ? $editRecord[0]['care_home_email'] : '' ?>" data-parsley-trigger="keyup" data-parsley-email />
                                    
                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['care_home_email']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="controller_name" class="control-label col-md-4 required">Status</label>
                                <div class="col-md-8">
                                    <?php if(isset($editRecord[0]['status'])  && $disable ==""){?>

                                        <select class="form-control " data-parsley-errors-container="#STATUS_error" placeholder="<?php echo lang('user_status'); ?>"  name="status" id="status" required="true" <?php echo $disable; ?> >
                                            <!-- <option value="">
                  <?= $this->lang->line('user_status') ?>
                  </option> -->
                                            <?php
                                            $options = array(array('s_status'=>lang('active')) ,array('s_status'=>lang('inactive')));
                                            if(isset($editRecord[0]['status']) && $editRecord[0]['status'] != ""){
                                                $selected = $editRecord[0]['status'];
                                            }else{
                                                $selected = lang('active');
                                            }
                                            ?>
                                            <?php foreach($options as $rows){
                                                if($selected == $rows['s_status']){?>
                                                    <option selected value="<?php echo $rows['s_status'];?>"><?php echo $rows['s_status'];?></option>
                                                <?php }else{?>

                                                    <option value="<?php echo $rows['s_status'];?>"><?php echo $rows['s_status'];?></option>
                                                <?php }}?>
                                        </select>
                                        <span id="STATUS_error"></span>
                                    <?php }elseif ( (isset($editRecord[0]['login_id']) && $this->session->userdata['nfc_admin_session']['admin_id'] == $editRecord[0]['login_id']) && $disable =="" ){ ?>
                                        <?php if($editRecord[0]['status'] == 'active'){?>
                                            <p><?=lang('active')?></p>
                                        <?php }else{?>
                                            <p><?=lang('inactive')?></p>
                                        <?php }?>
                                    <?php }elseif($disable =="" && $formAction == "registration"){?>
                                        <select class="form-control " data-parsley-errors-container="#STATUS_error" placeholder="<?php echo lang('user_status'); ?>"  name="status" id="status" required="true" <?php echo $disable; ?> >
                                            <!-- <option value="">
                  <?= $this->lang->line('user_status') ?>
                  </option> -->
                                            <?php
                                            $options = array(array('s_status'=>lang('active')) ,array('s_status'=>lang('inactive')));
                                            //$options = array('1'=>lang('active'),'0'=>lang('inactive'));
                                            if(isset($editRecord[0]['status']) && $editRecord[0]['status'] != ""){
                                                $selected = $editRecord[0]['status'];
                                            }else{
                                                $selected = lang('active');
                                            }
                                            ?>
                                            <?php foreach($options as $rows){
                                                if($selected == $rows['s_status']){?>
                                                    <option selected value="<?php echo $rows['s_status'];?>"><?php echo $rows['s_status'];?></option>
                                                <?php }else{?>

                                                    <option value="<?php echo $rows['s_status'];?>"><?php echo $rows['s_status'];?></option>
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="managers_name" class="control-label col-md-4 required">Managers Name:<?php if($disable ==""){ ?><?php } ?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="managers_name" name="managers_name" placeholder="Enter Care Home Name" type="text" value="<?php echo set_value('managers_name',(!empty($editRecord[0]['managers_name'])) ? $editRecord[0]['managers_name']:'')?>" 
                                        required="true" data-parsley-pattern='/^[A-Za-z-\d\s]+$/'  data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The Managers Name field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The Managers Name field must not more then 50 characters in length.' data-parsley-required="true"
                                        data-parsley-managers_name
                                        data-parsley-trigger="keyup" 
                                        <?php echo $disable; ?> />
                                    <?php }else{?>
                                        <p><?php echo $editRecord[0]['managers_name']; ?></p>
                                    <?php }?>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="managers_email" class="control-label col-md-4">Managers Email:<?php if($disable ==""){ ?><?php } ?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="managers_email" name="managers_email" autocomplete="false" placeholder="Enter email id" data-parsley-trigger="change" required="" data-parsley-required-message="Please enter email id." type="email" value="<?= !empty($editRecord[0]['managers_email']) ? $editRecord[0]['managers_email'] : '' ?>" data-parsley-trigger="keyup" data-parsley-email />
                                    
                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['managers_email']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="team_leaders_name" class="control-label col-md-4 required">Team Leaders Name:<?php if($disable ==""){ ?><?php } ?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="team_leaders_name" name="team_leaders_name" placeholder="Enter Team Leaders Name" type="text" value="<?php echo set_value('team_leaders_name',(!empty($editRecord[0]['team_leaders_name'])) ? $editRecord[0]['team_leaders_name']:'')?>" 
                                        required="true" data-parsley-pattern='/^[A-Za-z-\d\s]+$/'  data-parsley-minlength="2"
                                        data-parsley-minlength-message ='The Managers Name field must be at least 2 characters in length.' data-parsley-maxlength="50" data-parsley-maxlength-message ='The Managers Name field must not more then 50 characters in length.' data-parsley-required="true"
                                        data-parsley-team_leaders_name
                                        data-parsley-trigger="keyup" 
                                        <?php echo $disable; ?> />
                                    <?php }else{?>
                                        <p><?php echo $editRecord[0]['team_leaders_name']; ?></p>
                                    <?php }?>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tls_email" class="control-label col-md-4">TL’s Email:<?php if($disable ==""){ ?><?php } ?></label>
                                <div class="col-md-8">
                                    <?php if($disable ==""){ ?>
                                        <input class="form-control" id="tls_email" name="tls_email" autocomplete="false" placeholder="Enter email id" data-parsley-trigger="change" required="" data-parsley-required-message="Please enter email id." type="email" value="<?= !empty($editRecord[0]['tls_email']) ? $editRecord[0]['tls_email'] : '' ?>" data-parsley-trigger="keyup" data-parsley-email />
                                    
                                    <?php }else{ ?>
                                        <p><?php echo $editRecord[0]['tls_email']; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <div class="form-group dropzone">
                <div id="dragAndDropFiles" class="uploadArea bd-dragimage dropzone-carehome col-md-6">
                    <?php if(isset($readonly)) {?>
                        <label for="controller_name" class="control-label col-md-4 cust-label"> Image : </label>
                    <?php } else{ ?>
                         <div class="image_part" style="height: 100px;">
                        <label name="fileUpload">
                            <h1 style="top: -162px;"> <i class="fa fa-cloud-upload"></i>
                                Select Files Here
                            </h1>
                            <input type="file" onchange="showimagepreview(this)" name="fileUpload" style="display: none" id="upl" />
                        </label>
                    </div>
                    <?php } ?>
                    <?php
                     if (empty($editRecord[0]['profile_img'])) { 
                        ?>
                        <?php
                    } else {
                        if (file_exists(FCPATH . $this->config->item('yp_care_home_img_upload_url') . $editRecord[0]['profile_img'])) { 
                            ?>
                            <img class="view-care-home-profile-pic" id="uploadPreview1" src="<?= base_url() . $this->config->item('yp_care_home_img_upload_url') . $editRecord[0]['profile_img'] ?>"  width="100"  height="100" />
                    <?php
                        }
                    }
                    ?>



                </div>
            </div>
                                
                            
                        </div>

                        
                        <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <div class="bottom-buttons">
                                   <?php if(!isset($readonly)){ ?>
                                        <input name="care_home_id" id="care_home_id" type="hidden" value="<?=!empty($editRecord[0]['care_home_id'])?$editRecord[0]['care_home_id']:''?>" />
                                        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                        <?php if($formAction == "registration"){?>
                                            <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Create Care Home" />
                                        <?php }else{?>
                                            <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Edit Care Home" />
                                        <?php }?>
                                    
                                    <a href="<?php echo base_url('Admin/CareHome') ?>" class="btn btn-default">Cancel</a>
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
var care_home_name_duplicate_link = "<?php echo base_url('Admin/CareHome/isDuplicateCareHomeName'); ?>"; 
var edit_id = "<?php echo $id; ?>"; 
</script>