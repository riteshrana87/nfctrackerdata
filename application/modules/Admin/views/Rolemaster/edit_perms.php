<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertAssginPerms/';
$path = $crnt_view . '/' . $formAction;
?>
<script>
    var url ='<?php echo base_url($path); ?>';
</script>

<?php  echo $this->session->flashdata('verify_msg'); ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>
            <a href="<?= base_url($this->type . '/Rolemaster') ?>"><i class=""></i> Role Master List</a>
            </li>
            <li class="active">
             <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $this->lang->line('edit_perms') ?></h3>
                         
                         <?= isset($validation) ? $validation : ''; ?>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                         <form id="assignPermission" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform">
                        
                    <div class="modal-body">
                         <div class="form-group">
                                <label for="parent_role">Group Name:</label>
                                 <?=!empty($parent_role_data[0]['group_name'])?$parent_role_data[0]['group_name']:''?>
                        </div>
                        <div class="form-group">
                                <label for="parent_role">Parents Role:</label>
                                    <select class="chosen-select form-control" placeholder="<?=$this->lang->line('usertype')?>"  name="parent_role" id="parent_role" required="true" data-parsley-errors-container="#select-errors">
                                        <option value="">Please Select Parents Role </option>
										<?php
                                            $salutions_id = "";
                                            if(!empty($parednt_data)){
                                                $salutions_id = $parednt_data;
                                            }
                                            foreach ($parents_role as $key => $values) {
												if ($this->uri->segment(4) !== $values['role_id']){
													if($salutions_id == $values['role_id']){
											?>
														<option selected value="<?php echo $values['role_id'];?>"><?php echo $values['role_name'];?></option>
												<?php }else{ ?>
														<option value="<?php echo $values['role_id'];?>"><?php echo $values['role_name'];?></option>
												<?php 	} 
												} 
											} ?>
                                            
                                 </select>
								 <div id="select-errors"></div>
                            </div>
                        
                        
                            <div class="form-group">
                                <label for="usertype"><?= $this->lang->line('user_name') ?>:</label>
                                    <?php
                                    foreach ($userType as $key => $value) {
                                        if ($this->uri->segment(4) == $value['role_id']) {
                                            $current_role = $value['role_name'];
                                            echo $value['role_name'];
                                            echo "<input type='hidden' name='usertype' value='".$value['role_id']."'>";
                                        }
                                    }
                                    ?>
                                    <span class="text-danger"><?php echo form_error('user_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="usertype">Group Ref: </label>
                                    <?php
                                    foreach ($userType as $key => $value) {
                                        if ($this->uri->segment(4) == $value['role_id']) {
                                            echo $value['group_ref_id'];
                                        }
                                    }
                                    ?>
                                    <span class="text-danger"><?php echo form_error('user_name'); ?></span>
                            </div>

                            <div class="form-group" id="edit_CRM_LIST">
                              <div class="table-responsive">
                                    <table class="table" >
                                        <thead class="verticle-top">
                                        <th><?php echo lang('module_list') ?></th>
                                        <?php
                                        if (count($getPermList) > 0) { 
                                            foreach ($getPermList as $perm) {
                                                ?>

                                                <th>
                                                  <label class="checkbox-inline">
                                                  <input type="checkbox" class="edit_CRM_LIST_parent_horizontal_checkbox" data-tag="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_CRM_LIST_<?php echo $perm['name']; ?>" /> <b> <?php echo lang($perm['name']); ?></b>
                                                  </label>
                                                  </th>

                                                <?php
                                            }
                                        }
                                        ?>
                                          
                                            <th>
                                              <label class="checkbox-inline">
                                              <input type="checkbox"  class="edit_CRM_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_CRM_LIST_"<?php echo $perm['name']; ?>"/><b><?php echo lang('all_perm'); ?></b>
                                              </label>
                                            </th>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (count($CRM_module_list) > 0) { 
                                            foreach ($CRM_module_list as $modObj) {
                                                if($current_role != 'Site Admin' && $current_role != 'Site-Admins') {
                                                    if($modObj['module_name'] == 'Admin')
                                                    {
                                                        continue;
                                                    }
                                                }
                                                $counter = 0;
                                                ?>
                                                <tr>
                                                    <td><?php echo $modObj['module_name']; ?></td>
                                                    <?php
                                                    if (count($getPermList) > 0) {
                                                        foreach ($getPermList as $perm) {
                                                            ?>

                                                            <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id']; ?>"
                                                                    <?php
                                                                    foreach ($view_perms_to_role_list as $assignData) {
                                                                        $checked = '';
                                                                        if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                            echo $checked = "checked=true";
                                                                            $counter++;
                                                                        } else {
                                                                            echo $checked = "";
                                                                        }
                                                                    }
                                                                    ?>

                                                                       class="child <?php echo $modObj['module_unique_name']; ?> child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" ></td>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                    <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_CRM_LIST" ></td>
                                                    <!--  <td><input type="checkbox" name=""></td> -->
                                                </tr>
                                                <?php
                                            }
                                        }
                                    
                                        ?>
                                        </tbody>
                                    </table>
                              </div>

                                </div>
                        <input type="hidden" name="id" value="<?php echo $this->uri->segment(4); ?>">
                                    <input type="hidden" name="editPerm" value="Edit Permissions">
                                    <?php if($formAction == "insertAssginPerms"){?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Assign Permission" />
                                    <?php }else{?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('edit_perms')?>" />
                                    <?php }?>
 <a href="<?php echo base_url().''.$this->type?>/Rolemaster" class="btn btn-default">Cancel</a>

                                    <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" />
                                    
                    </center>

                        </div>
                        
                     </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
