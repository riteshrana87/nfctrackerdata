<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata?id='.$id:'insertdata';
$path = $crnt_view.'/'.$formAction;

?>
<script>
    var url ='<?php echo base_url($path); ?>';
</script>

<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           <?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('add_role')?><?php }else{ ?><?=$this->lang->line('edit_role')?><?php }?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('add_role')?><?php }else{ ?><?=$this->lang->line('edit_role')?><?php }?>></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('add_role')?><?php }else{ ?><?=$this->lang->line('edit_role')?><?php }?></h3>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a> 
                        
                         <?= isset($validation) ? $validation : ''; ?>
                    </div>
                    
                    <!-- form start -->
               <form <?PHP if($formAction == "insertdata"){ ?>id="addrole"<?php }else{ ?>id="addrole1"<?php }?>id="addrole" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate ="">
                        
                        <div class="box-body">
                          <div class="row">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-md-3 required" for="parents_role">
                                    Select Group
                                </label>
                                <div class="col-sm-8">
                                <?php $groupAr = array();
                                 if(!empty($editRecord[0]['group_name'])){
                                        $groupAr = explode(',',$editRecord[0]['group_name']);
                                }?>
                                     <select class="chosen-select form-control" data-parsley-errors-container="#parent_error" placeholder="Select Group" multiple=""  name="group_name[]" id="group_name" required="">
                                        <?php foreach ($GroupNameData as $value) {?>
                                            <option <?=(!empty($groupAr) && in_array($value['group_name'],$groupAr))?'selected':''?> value="<?php echo $value['group_name']; ?>"><?php echo $value['group_name']; ?>
                                            </option>    
                                        <?php } ?>
                                        </select>
                                   <span id="parent_error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-md-3 required" for="parents_role">
                                    Parents Role
                                </label>
                                <div class="col-sm-8">
                                     <select class="chosen-select form-control" data-parsley-errors-container="#parent_error" placeholder="Parents Role"  name="parent_role" id="parent_role" required="">
                                            <?php
                                            $salutions_id = "";
                                            if(!empty($editRecord[0]['parent_role'])){
                                                $salutions_id = $editRecord[0]['parent_role'];
                                            }
                                            ?>
                                            <?php foreach($information as $rows){
                                                if($salutions_id == $rows['role_id']){?>
                                                    <option selected value="<?php echo $rows['role_id'];?>"><?php echo $rows['role_name'];?></option>
                                                <?php }else{?>
                                                    <option value="<?php echo $rows['role_id'];?>"><?php echo $rows['role_name'];?></option>
                                                <?php }}?>
                                        </select>
                                   <span id="parent_error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cat_name" class="control-label col-sm-3 required"><?=$this->lang->line('role_name')?></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="role_name" placeholder="<?=$this->lang->line('role_name')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('role_name');?><?php }else{?><?=!empty($editRecord[0]['role_name'])?htmlentities($editRecord[0]['role_name']):''?><?php }?>" data-parsley-pattern="/^[a-zA-Z0-9-_ ]+$/" parsley-rangelength="[2,50]" pattern="/^[a-zA-Z0-9-_/ ]+$/" required=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="status">
                                    <?=$this->lang->line('role_status')?>
                                </label>
                                <div class="col-sm-8">
                                     <?php
                                    $options = array('1'=>lang('active'),'0'=>lang('inactive'));
                                    $name = "status";
                                    if($formAction == "insertdata"){
                                        $selected = 1;
                                    }else{
                                        $selected = $editRecord[0]['status'];
                                    }
                                    echo dropdown( $name, $options, $selected );
                                ?>
                                <span class="text-danger"><?php echo form_error('status'); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="status">
                                    <?=$this->lang->line('group_ref')?>
                                </label>
                                <div class="col-sm-8">
                                  <input type="number" class="form-control" required="true" name="group_ref_id" id="group_ref_id" data-parsley-type="number" min="0" placeholder="" value="<?=isset($editRecord[0]['group_ref_id'])?$editRecord[0]['group_ref_id']:''?>" data-parsley-errors-container="#errors-containerquantity_left" data-parsley-trigger="change" data-parsley-quantity_left="" max="999" data-parsley-id="13">
                                </div>
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <div class="bottom-buttons">
                                   <input name="role_id" type="hidden" value="<?=!empty($editRecord[0]['role_id'])?$editRecord[0]['role_id']:''?>" />
                                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                    <?php  if($formAction == "insertdata"){?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_role')?>" />
                                    <?php }else{ ?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_role')?>" />
                                    <?php }?>

                                    <input type="reset" class="btn btn-info" name="reset" id="reset" value="Reset" />   
                                    <a href="<?php echo base_url().''.$this->type?>/Rolemaster" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                          </div>
                        </div><!-- /.box-body -->
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
