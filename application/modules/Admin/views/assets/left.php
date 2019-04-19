<?php
$this->type = ADMIN_SITE;
$admin_session = $this->session->userdata('nfc_admin_session');

$table = ROLE_MASTER.' as rm';
$match = "rm.role_id = '" . $admin_session['admin_type'] . "'";
$role_result = $this->common_model->get_records($table, array("rm.role_id,rm.role_name"), '', '', $match);

?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
             <div class="pull-left image">
                <?php if (!empty($admin_session['admin_image'])) { ?> 
                    <img src="<?= $this->config->item('admin_user_small_img_url') . $admin_session['admin_image'] ?>" class="user-image" alt="User Image">
                <?php } else { ?>
                    <img src="<?php echo base_url("uploads/assets/front/images/default-user.jpg")?>" class="user-image" alt="User Image">
                <?php } ?>
            </div> 
            <div class="pull-left info">
                <p><?= !empty($admin_session['name']) ? $admin_session['name'] : '' ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li <?php if ($this->uri->segment(2) == 'Dashboard') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Dashboard') ?>"><i class="fa fa-circle-o text-aqua"></i> <span><?= "DashBoard" ?></span></a></li>
            <?php /*if ($admin_session['admin_type'] == 1) { ?> 
                <li <?php if ($this->uri->segment(2) == 'admin_users') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/admin_users') ?>"><i class="fa fa-list-alt"></i> <span><?= $this->lang->line('admin_user_module_title') ?></span></a></li>
            <?php } */?>
            
            <!-- <li <?php if ($this->uri->segment(2) == 'User') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/User') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "User Master"; ?></span></a></li> -->
            <?php /* ?> 
            <li class="<?php if (($this->uri->segment(2) == 'User') || ($this->uri->segment(2) == 'UserJobInfo')) { ?> active <?php } ?> treeview">
          <a href="#">
            <i class="fa fa-list-alt"></i> <span><?php echo "User Master"; ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              
            <li <?php if ($this->uri->segment(2) == 'User') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/User') ?>"><i class="fa fa-circle-o"></i> User List</a></li>
            
            <li <?php if ($this->uri->segment(2) == 'UserJobInfo') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/UserJobInfo') ?>"><i class="fa fa-circle-o"></i> User Job info</a></li>
            
          </ul>
              <?php */ ?>
             
        </li>
            
            
            <?php if(!empty($role_result[0]['role_name']) && ($role_result[0]['role_name'] == 'SuperAdmin' || $role_result[0]['role_name'] == 'Super-Admin')){?>
            
            <li <?php if ($this->uri->segment(2) == 'Rolemaster') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Rolemaster') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "Role Management"; ?></span></a></li>
            
           <?php /* ?> <li><a href="<?= base_url($this->type . '/ModuleMaster') ?>"><i class="fa fa-list-alt"></i> <span>Module Master</span></a></li>
            <?php */ ?>
            <li <?php if($this->uri->segment(2) == 'MailConfig'){ ?>class="active" <?php } ?>><a href="<?= base_url($this->type . '/MailConfig') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "Email Configuration"; ?></span></a></li>
            <?php 
            $formdata = checkFormBuilderData(PP_FORM);
            if(!empty($formdata)){ ?>
            <li <?php if (isset($form_data['button_data']) && $form_data['button_data'] == 'active') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/AdminPlacementPlan/edit/').'/'.$formdata[0]['pp_form_id'];?>"><i class="fa fa-list-alt"></i><span>Masterform</span></a></li>
            <?php }else{?>
            <li <?php if ($this->uri->segment(2) == 'AdminPlacementPlan') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/AdminPlacementPlan/add') ?>"><i class="fa fa-list-alt"></i> <span>Masterform</span></a></li>
            <?php }?>
            <?php }?>

           <li <?php if ($this->uri->segment(2) == 'Reports' || $this->uri->segment(2) == 'PlacementPlanView' || $this->uri->segment(2) == 'IbpView'|| $this->uri->segment(2) == 'RiskAssesmentView'|| $this->uri->segment(2) == 'DailyObservationView'|| $this->uri->segment(2) == 'KeySessionView'|| $this->uri->segment(2) == 'DocumentsView' || $this->uri->segment(2) == 'MedicalView' || $this->uri->segment(2) == 'CommunicationView') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Reports') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "Reports Management"; ?></span></a></li>
           <li <?php if($this->uri->segment(2) == 'CareHome'){ ?>class="active" <?php } ?>><a href="<?= base_url($this->type . '/CareHome') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "Care Home"; ?></span></a></li>
           <li <?php if($this->uri->segment(2) == 'Sdq'){ ?>class="active" <?php } ?>><a href="<?= base_url($this->type . '/Sdq') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "SDQ"; ?></span></a></li>
           <li <?php if($this->uri->segment(2) == 'Cse'){ ?>class="active" <?php } ?>><a href="<?= base_url($this->type . '/Cse') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "CSE"; ?></span></a></li>
		   
		   
		   <li <?php if($this->uri->segment(2) == 'syncBambooData'){ ?>class="active" <?php } ?>><a href="<?= base_url($this->type . '/AdminAAI/syncBambooData') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "BambooHR"; ?></span></a></li>
		   
		   
           <li <?php if($this->uri->segment(2) == 'PocketMoneySaving'){ ?>class="active" <?php } ?>><a href="<?= base_url($this->type . '/PocketMoneySaving') ?>"><i class="fa fa-list-alt"></i> <span><?php echo "Manage Pocket Money Saving"; ?></span></a></li>
          <li><a href="<?= base_url() ?>"><i class="fa fa-list-alt"></i> <span>Back To Frontend</span></a></li>
             
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>