<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php if (isset($user_info) && !empty($user_info)) { ?>
<div class="sticky-header header-section ">
            <div class="header-left">
                <!--logo -->
                <div class="logo">
                    <a href="<?= base_url() ?>">
                        <img class="logo" src="<?= base_url() ?>uploads/assets/front/images/logo.png" />
                    </a>
                </div>
                <!--//logo-->
                <div class="clearfix"> </div>
            </div>

            <div class="header-right"> 
        <div class="toggle_re_menu"><a href="javascript:void(0);" class="icon re_icon visible-xs" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a></div>
   <div class="profile_details visible-xs">
                    <ul>
                        <li class="dropdown profile_details_drop">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <div class="profile_img">
                                    <span class="prfil-img">
                <?php if (empty($user_data[0]['profile_img'])) { ?>
                <img src="<?= base_url() ?>uploads/assets/front/images/default-user.jpg" alt="">
            <?php } else {
                  if (!empty($user_data[0]['profile_img']) && @getimagesize($user_data[0]['profile_img'])){ ?>
                        <img src="<?= $user_data[0]['profile_img'] ?>" alt=""/>
                  <?php } else { ?>
                        
                        <img src="<?= base_url() ?>uploads/assets/front/images/default-user.jpg" alt="">    
                  <?php } } ?>
                                    </span>
                                    <div class="user-name">
                                <?php if (isset($user_info) && !empty($user_info)) { ?>
                <p><?php $name= $user_info['FIRSTNAME'] . ' ' . $user_info['LASTNAME']; 
                    echo (strlen($name) < 20)?$name:substr($name,0,20).'..';?></p>
                <span><?=!empty($user_role_data[0]['role_name'])?$user_role_data[0]['role_name']:''?> </span>
                                    <?php }?>
                                    </div>
                                    <i class="fa fa-angle-down lnr"></i>
                                    <i class="fa fa-angle-up lnr"></i>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                            <ul class="dropdown-menu drp-mnu">
                                <li> <a href="<?php echo base_url('MyProfile'); ?>"><i class="fa fa-user"></i> My Profile</a> </li>
                                <li> <a href="<?php echo base_url('ChangeThemeColor'); ?>"><i class="fa fa-cog"></i> Change Theme Colour </a> </li>
                        <?php if(checkPermission('SetLogoutTime','view')){ ?>
                                <li> <a href="<?php echo base_url('SetLogoutTime'); ?>"><i class="fa fa-cog"></i> Set Logout time </a> </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="profile_details_left">
                    <!--notifications of menu start -->
                    <ul class="nofitications-dropdown">
                        <?php if(checkPermission('Admin','view')){ ?>
                        <li class="dropdown head-dpdn <?php if (isset($param['menu_module']) && $param['menu_module'] == "Admin") { ?>active<?php } ?>">
                            <a href="<?= base_url('Admin/Dashboard');?>" class="dropdown-toggle">ADMIN</a>
                        </li>
                        <?php } ?>
                        <li class="dropdown head-dpdn <?php if (isset($param['menu_module']) && $param['menu_module'] == "Dashboard") { ?>active<?php } ?>">
                            <a href="<?= base_url('Dashboard');?>" class="dropdown-toggle">CARE INFO</a>
                        </li>
                        <?php if(checkPermission('YoungPerson','view')){ ?>
                        <li class="dropdown head-dpdn <?php if (isset($param['menu_module']) && $param['menu_module'] == "YoungPerson") { ?>active<?php } ?>">
                            <a href="<?= base_url('YpCareHome'); ?>" class="dropdown-toggle"> CARE HOME</a>
                        </li>
                        <?php } ?>
                         <?php if(checkPermission('AAI','view')){ ?>
                        <li class="dropdown head-dpdn <?php if (isset($param['menu_module']) && $param['menu_module'] == "AAI") { ?>active<?php } ?>">
                            <a href="<?php echo base_url() . 'AAI/checkPlaceType'; ?>" class="dropdown-toggle"> AAI</a>
                        </li>
                        <?php }  ?>
                        <?php if(checkPermission('AAIReport','view')){ ?>
                        <li class="dropdown head-dpdn <?php if (isset($param['menu_module']) && $param['menu_module'] == "AAIReport") { ?>active<?php } ?>">
                            <a href="<?php echo base_url() . 'AAIReport'; ?>" class="dropdown-toggle"> REPORTS</a>
                        </li>
                        <?php }  ?>
                        <?php if(checkPermission('ActivityLog','view')){ ?>
                        <li class="dropdown head-dpdn <?php if (isset($param['menu_module']) && $param['menu_module'] == "ActivityLog") { ?>active<?php } ?>">
                            <a href="<?= base_url('ActivityLog');?>" class="dropdown-toggle"> AUDIT LOG</a>
                        </li>
                        <?php } ?>						
						
                         <li class="dropdown head-dpdn">
                            <a href="<?php echo base_url('Dashboard/logout/'); ?>" class="dropdown-toggle">LOG OUT</a>
                        </li>
                        
                    </ul>
                    <div class="clearfix"> </div>
                </div>
                <!--notification menu end -->
                <div class="profile_details hidden-xs">
                    <ul>
                        <li class="dropdown profile_details_drop">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <div class="profile_img">
                                    <span class="prfil-img">
                <?php if (empty($user_data[0]['profile_img'])) { ?>
                <img src="<?= base_url() ?>uploads/assets/front/images/default-user.jpg" alt="">
            <?php } else {
                  if (!empty($user_data[0]['profile_img']) && @getimagesize($user_data[0]['profile_img'])){ ?>
                        <img src="<?= $user_data[0]['profile_img'] ?>" alt=""/>
                  <?php } else { ?>
                        
                        <img src="<?= base_url() ?>uploads/assets/front/images/default-user.jpg" alt="">    
                  <?php } } ?>
                                    </span>
                                    <div class="user-name">
                                <?php if (isset($user_info) && !empty($user_info)) { ?>
				<p><?php $name= $user_info['FIRSTNAME'] . ' ' . $user_info['LASTNAME']; 
                    echo (strlen($name) < 20)?$name:substr($name,0,20).'..';?></p>
                <span><?=!empty($user_role_data[0]['role_name'])?$user_role_data[0]['role_name']:''?> </span>
                                    <?php }?>
                                    </div>
                                    <i class="fa fa-angle-down lnr"></i>
                                    <i class="fa fa-angle-up lnr"></i>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                            <ul class="dropdown-menu drp-mnu">
                                <li> <a href="<?php echo base_url('MyProfile'); ?>"><i class="fa fa-user"></i> My Profile</a> </li>
                                <li> <a href="<?php echo base_url('ChangeThemeColor'); ?>"><i class="fa fa-cog"></i> Change Theme Colour </a> </li>
                        <?php if(checkPermission('SetLogoutTime','view')){ ?>
                                <li> <a href="<?php echo base_url('SetLogoutTime'); ?>"><i class="fa fa-cog"></i> Set Logout time </a> </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>
<?php } else { ?> 
<div class="sticky-header header-section ">
            <div class="header-left">
                <!--logo -->
                <div class="logo">
                    <a href="#">
                        <img class="logo" src="<?= base_url() ?>uploads/assets/front/images/logo.png" />
                    </a>
                </div>
                <!--//logo-->
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>
<?php }?>
<script>
  
$(document).ready(function(){
      $('.re_icon').click(function(){
             $('.profile_details_left').slideToggle(); 
    });

});
</script>