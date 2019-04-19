<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'updatedata' : 'view_perms_to_role_list';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $this->lang->line('viewPerms') ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>
            <a href="<?= base_url($this->type . '/Rolemaster') ?>"><i class=""></i> Role Master List</a>
            </li>
            <li class="active"><?= $this->lang->line('viewPerms') ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php echo $this->session->flashdata('verify_msg'); ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $this->lang->line('viewPerms') ?></h3>
                        <?= isset($validation) ? $validation : ''; ?>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a> 
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php
                    $attributes = array("name" => "permissionform");
                    echo form_open(base_url($path));
                    ?>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="parent_role">Group Name:</label>
                            <?=!empty($parent_role_data[0]['group_name'])?$parent_role_data[0]['group_name']:''?>
                        </div>
                        <div class="form-group">
                            <label for="parent_role">Parents Role:</label>
                            <?php
                            $salutions_id = "";
                            if (!empty($parednt_data)) {
                                $salutions_id = $parednt_data;
                            }
                            if(!empty($parents_role))
                            {
                            foreach ($parents_role as $key => $values) {
                                if ($this->uri->segment(4) !== $values['role_id']) {
                                    if ($salutions_id == $values['role_id']) {
                                        ?>
                                        <?php echo $values['role_name']; ?>
                                        <?php
                                    }
                                }
                            }
                        }
                            ?>
                        </div>

                        <div class="form-group">
                            <label for="usertype"><?= $this->lang->line('user_name') ?>: </label>
                                <?php
                                //$options = array('1'=>"Super Admin",'2'=>"Admin");
                                $options1 = array();
                                $options2 = array();
                                $selected = "";
                                if(!empty($userType))
                                {
                                foreach ($userType as $key => $value) {
                                    if ($value['role_id'] == $this->uri->segment(4)) {
                                        echo $value['role_name'];
                                    }
                                }
                                }
                                ?>
                           
                        </div>
                        <div class="form-group">
                            <label for="userType">Group Ref:  </label>
                                <?php
                                $options1 = array();
                                $options2 = array();
                                $selected = "";
                                if(!empty($userType))
                                {
                                foreach ($userType as $key => $value) {
                                    if ($value['role_id'] == $this->uri->segment(4)) {
                                        echo $value['group_ref_id'];
                                    }
                                }
                                }
                                ?>
                        </div>
                        <div class="form-group" id="CRM_LIST">
                          <div class="table-responsive">
                            <table class="table" >
                                <thead>

                                <th><?php echo lang('module_list') ?></th>
                                <?php
                                if (!empty($getPermList)) {
                                    foreach ($getPermList as $perm) {
                                        ?>
                                        <th><?php echo lang($perm['name']); ?></th>
                                        <?php
                                    }
                                }
                                ?>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($CRM_module_list)) {
                                        foreach ($CRM_module_list as $modObj) {
                                            ?>
                                            <tr>

                                                <td><?php echo $modObj['module_name']; ?></td>
                                                <?php
                                                if (isset($getPermList) && !empty($getPermList)) {
                                                    foreach ($getPermList as $perm) {
                                                        ?>

                                                        <td>
                                                            <?php
                                                            foreach ($view_perms_to_role_list as $assignData) {
                                                                $checked = '';
                                                                if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                    echo $checked = '<i class="fa fa-check"></i>';
                                                                } else {
                                                                    echo $checked = "";
                                                                }
                                                            }
                                                            ?>

                                                        </td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                          </div>
                        </div>

                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
