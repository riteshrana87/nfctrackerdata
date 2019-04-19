<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- LOGO HEADER END-->
<section class="menu-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-collapse collapse ">
                    <?php if (isset($this->session->userdata['LOGGED_IN'])) { ?>
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li <?php if (isset($param['menu_module']) && $param['menu_module'] == "Dashboard") { ?>class="active"<?php } ?>><a href="<?php echo base_url('Dashboard'); ?>">Home</a></li>
                                
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle <?php if (isset($param['menu_module']) && $param['menu_module'] == "masters") { ?>active<?php } ?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Masters <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <?php  if (checkPermission('User', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "Dashboard") { ?>class="active"<?php } ?>><a href="<?php echo base_url('User'); ?>">User Master</a></li>
                                    <?php }  ?>
                                        <?php  if (checkPermission('YoungPerson', 'view')) { ?>
                                        <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "user") { ?>class="active"<?php } ?>><a href="<?php echo base_url('YoungPerson'); ?>">Add Young Person</a></li>
                                        <?php } ?>
 
                                    <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "changepassword") { ?>class="active"<?php } ?>><a href="<?php echo base_url('MyProfile/ChangePassword'); ?>"><?php echo lang('CHANGE_PASSWORD'); ?></a></li>
                                        <?php if (checkPermission('Rolemaster', 'view')) { ?>
                                              <li <?php if (isset($param['menu_child']) && $param['menu_child'] == "changepassword") { ?>class="active"<?php } ?>><a href="<?php echo base_url('Rolemaster');?>">Role Master</a></li>
                                        <?php } ?> 
                                </ul>
                            </li>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- MENU SECTION END-->
