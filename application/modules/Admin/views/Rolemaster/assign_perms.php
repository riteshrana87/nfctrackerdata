<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertAssginPerms';
$path = $crnt_view . '/' . $formAction;
?>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                        <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
                        </h3>
                    </div>
                    <div class="modal-body">
                        <form id="assignPermission" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform">
                         
                            <div class="form-group">
                                <label for="parent_role">Parents Role:</label>
                                    <select class="chosen-select form-control" data-parsley-errors-container="#usertype_error" placeholder="<?=$this->lang->line('usertype')?>"  name="parent_role" id="parent_role" required="" <?php echo $disable; ?> >
                                        
                                        <option value="">Please Select Parents Role </option>     
                                    <?php
                                            $salutions_id = "";
                                            if(!empty($parent_id)){
                                                $salutions_id = $parent_id;
                                            }
                                            foreach ($parents_role as $key => $values) {
                                        if ($this->uri->segment(4) !== $values['role_id']){
                                            if($salutions_id == $values['role_id']){?>
                                        <option selected value="<?php echo $values['role_id'];?>"><?php echo $values['role_name'];?></option>     <?php }else{ ?>
                                        <option value="<?php echo $values['role_id'];?>"><?php echo $values['role_name'];?></option>
                                
                                        <?php } } } ?>
                                                         
                                 </select>
                                    <span class="text-danger"><?php echo form_error('user_name'); ?></span>

                            </div>
                            
                            <div class="form-group">
                                <label for="usertype"><?= $this->lang->line('user_name') ?>:</label>
                                <?php
                                $options1 = array();
                                $options2 = array();
                                $selected = "";
                                foreach ($userType as $key => $value) {
                                    array_push($options1, $value['role_id']);
                                    array_push($options2, $value['role_name']);
                                }
                                $options = array_combine($options1, $options2);
                                $name = "usertype";
                                if ($formAction == "insertdata") {
                                    $selected = 1;
                                } else {
                                    $selected = $roleId;
                                }
                                echo dropdown($name, $options, $selected);
                                ?>
                                <span class="text-danger"><?php echo form_error('usertype'); ?></span>
                            </div>

                          
                            <div class="form-group" id="CRM_LIST">
                              <div class="table-responsive">
                                <table class="table" >
                                    <thead>

                                    <th><?php echo lang('module_list') ?></th>
                                    <?php
                                    if (count($getPermList) > 0) {
                                        foreach ($getPermList as $perm) {
                                            ?>
                                            <th>
                                              <label class="checkbox-inline">
                                              <input type="checkbox" class="CRM_LIST_parent_horizontal_checkbox" data-tag="child_CRM_LIST_<?php echo $perm['name']; ?>" /><b> <?php echo lang($perm['name']); ?></b>
                                              </label>
                                            </th>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <th>
                                      <label class="checkbox-inline">
                                      <input type="checkbox"  class="CRM_LIST_parent_horizontal_checkbox_All" data-tag="parent_CRM_LIST_<?php echo $perm['name']; ?>"/><b><?php echo lang('all_perm'); ?></b>
                                      </label>
                                      </th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (count($CRM_module_list) > 0) {
                                        foreach ($CRM_module_list as $modObj) {
                                            ?>
                                            <tr>

                                                <td><?php echo $modObj['module_name']; ?></td>
                                                <?php
                                                if (count($getPermList) > 0) {
                                                    foreach ($getPermList as $perm) {
                                                        ?>
                                                        <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_CRM_LIST_<?php echo $perm['name']; ?>"></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="all_CRM_LIST"></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                        
                            <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Assign Permission" />

                            <input type="reset" class="btn btn-info" name="reset" id="reset" value="Reset" />
                            <a href="<?php echo base_url().''.$this->type?>/Rolemaster" class="btn btn-default">Cancel</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>