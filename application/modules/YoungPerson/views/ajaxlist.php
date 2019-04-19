<?php
//pr($information);exit;
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
$role_id = $this->config->item('super_admin_role_id');
$master_user_id = $this->config->item('master_user_id');
?>
<div class="row" id="table-view" style="<?=($yp_list_type == 'display-table')?'display: block;':'display: none;'?>">
    <div class="col-xs-12 m-t-10">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')">  Name</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'age') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('age', '<?php echo $sorttypepass; ?>')">Age</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'gender') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('gender', '<?php echo $sorttypepass; ?>')"> Gender</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'staffing_ratio') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('staffing_ratio', '<?php echo $sorttypepass; ?>')"> Staffing Ratio</th>
                        
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'care_home_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')"> Care Home</th>

                        <th><?= lang('actions') ?></th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />  
                <input type="hidden" id="care_home_id" name="care_home_id" value="<?php echo $care_home_id;?>" />   <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                  <input class="" type="hidden" name="primaryId" id="primaryId" value="<?=!empty($care_home_id)?$care_home_id:'0'?>">
                </tr>
                </thead>
                <tbody>
				
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        
                            <tr class="td-bg">
                                
            <td><?= !empty($data['name']) ? $data['name'] : lang('NA') ?></td>
            <td><?= !empty($data['age']) ? $data['age'] : lang('NA') ?></td>
            <td>
                <?php if(!empty($data['gender']) && $data['gender'] == 'M'){
                echo "Male";
            }elseif(!empty($data['gender']) && $data['gender'] == 'F'){
                echo "Female";
            }else{  
                echo lang('NA');
            }
             ?>           
            <td><?= !empty($data['staffing_ratio']) ? $data['staffing_ratio'] : lang('NA') ?></td>
            <td><?= !empty($data['care_home_name']) ? $data['care_home_name'] : lang('NA') ?></td>
                                
                                <td class="text-center">
                                    <?php if(checkPermission('YoungPerson','view')){ ?>
                                    <a href="<?php echo base_url($crnt_view . '/view/' . $data['yp_id']); ?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                       <?php if(checkPermission('YoungPerson','edit')){ ?> 
                                    <a href="javascript:;" data-href="<?php echo base_url($crnt_view . '/edit/' . $data['yp_id']); ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-link btn-blue" ><i aria-hidden="true" class="fa fa-pencil-square-o"></i></a>
                                    <?php } ?>

                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!--<ul class="pagination m-tb-0">
        <li class="disabled"><a href="#">&laquo;</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">&raquo;</a></li>
    </ul>
    -->
  <div class="clearfix"></div>
    <div id="common_tb">
        <?php if (isset($pagination) && !empty($pagination)) { ?>
            <div class="col-sm-12">
                <?php echo $pagination; ?>
            </div>
        <?php } ?>
    </div>

</div>
<div class="row m-t-10 uni_div_clear" id="list-view" style="<?=($yp_list_type == 'display-list')?'display: block;':'display: none;'?>">

    <?php if (isset($information) && count($information) > 0) { ?>
        <?php foreach ($information as $data) { //pr($data['date_of_placement']);?>
		
            <div class="col-lg-4 col-sm-6">
                <div class="panel panel-default tile tile-profile care_home_y">
                    <?php if(checkPermission('YoungPerson','edit')){ ?> 
                                    <a href="javascript:;" data-href="<?php echo base_url($crnt_view . '/edit/' . $data['yp_id']); ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="edit_icon_right" ><i aria-hidden="true" class="fa fa-pencil-square-o"></i></a>
                                    <?php } ?>
                    <div class="panel-body">
						
                        <a href="<?php echo base_url($crnt_view . '/view/' . $data['yp_id']); ?>">
                             <?php if (empty($data['profile_img'])) { ?>
                                    <img class="pic" src="<?php echo base_url(); ?>uploads/images/profile-default.png" />
                                    <?php
                                } else {
                                    if (file_exists(FCPATH . $this->config->item('yp_img_upload_path') . $data['profile_img'])) {
                                        ?>
                                        <img class="pic" src="<?= base_url() . $this->config->item('yp_img_upload_path') . $data['profile_img'] ?>" />
                                    <?php } else { ?>
                                    <img class="pic" src="<?php echo base_url(); ?>uploads/images/profile-default.png" />
                                        <?php
                                    }
                                }
                                ?>
                            

                               

                            <div class="yp-short-info">

                                <h2><?php echo $data['name']; ?> 
                                <?php if ($data['gender'] == "F") { ?>
                                    <i>(Female)</i>
                                <?php } elseif ($data['gender'] == "M") { ?>
                                    <i>(Male)</i>
                                <?php } else { ?>
                                    <i>(<?php echo lang('NA'); ?>)</i>
                                <?php } ?>

                            </h2>


                                <h6>Age: <b><?php echo date_diff(date_create($data['date_of_birth']), date_create('today'))->y; ?></b></h6>
                                <h6>DOB: <b><?= (!empty($data['date_of_birth']) && $data['date_of_birth'] != '0000-00-00') ? configDateTime($data['date_of_birth']) : lang('NA') ?></b></h6>
                                <h6>Staffing Ratio: <b>
                                    <?= !empty($data['staffing_ratio']) ? $data['staffing_ratio'] : lang('NA') ?>
                                    </b></h6>
                            </div>
                        </a>                                               
                    </div>
                </div>
            </div>
            <?php
        }
    }
    else
    {
        ?>
        <div class="col-lg-12 col-sm-6">
                <div class="panel panel-default tile">
                    <div class="panel-body">
                        <label><?= lang('common_no_record_found') ?></label>
                    </div>
                </div>
        </div>
        <?php
    }
    ?>

    <!--        <ul class="pagination m-tb-0">
                <li class="disabled"><a href="#">&laquo;</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">&raquo;</a></li>
            </ul>-->
  <div class="clearfix"></div>
  <div class="" id="common_tb">
        <?php if (isset($pagination) && !empty($pagination)) { ?>
            <div class="col-sm-12">
                <?php echo $pagination; ?>
            </div>
        <?php } ?>
    </div>

</div>
<!-- <?php if(checkPermission('YoungPerson','add')){ ?>
<div class="row">
    <div class="col-md-12 m-t-10">
        <a data-href="<?php echo base_url() . 'YoungPerson/registration'; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-default" >Add New YP</a>

    </div>
</div>
<?php } ?> -->