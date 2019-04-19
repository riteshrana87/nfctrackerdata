<style>
    .careHomeDiv{
        text-align: center;
        width: 100%;
        padding-bottom: 8px;
    }
    .gallery span.preview .disp-table-cell {
        display: block;
    }
</style>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
$role_id = $this->config->item('super_admin_role_id');
$master_user_id = $this->config->item('master_user_id');
?>

<?php if(isset($information)){ ?>
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
                   <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                
                </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        
                            <tr>
                                
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
                                    <a href="<?php echo base_url('YoungPerson/view/' . $data['yp_id']); ?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                       <?php if(checkPermission('YoungPerson','edit')){ ?> 
                                    <a data-href="<?php echo base_url('YoungPerson/edit/' . $data['yp_id']); ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-link btn-blue" ><i aria-hidden="true" class="fa fa-pencil-square-o"></i></a>
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
  <div class="clearfix"></div>
    <div id="common_tb">
        <?php if (isset($pagination) && !empty($pagination)) { ?>
            <div class="col-sm-12">
                <?php echo $pagination; ?>
            </div>
        <?php } ?>
    </div>

</div>

<?php }else{ ?>
<!-- new code -->
<div class="row" id="table-view" style="">
 <?php if (isset($care_home_data) && count($care_home_data) > 0) { ?>
    <div class="col-xs-12 m-t-10">
        <div class="">
            <div class="row">
            <?php
            $i = 1;
            $count = count($care_home_data);
            foreach ($care_home_data as $key => $value) { ?>
            <?php
            $care_home_id = $value['care_home_id'];
                ?>

                <div class="col-lg-2 col-sm-4">
                    <div class="panel panel-default tile gallery">
                        <div class="panel-body">
                           
                            <?php 
                            $name = $value['care_home_name'];
                            if (file_exists(FCPATH . $this->config->item('yp_care_home_img_upload_url') . $value['profile_img']) && !empty($value['profile_img'])) { ?>
           
                        <span id="<?php echo $i; ?>" class="preview">
                            
                        <div class="disp-table-cell">
                                <a href='<?= base_url('YoungPerson/index/'.$care_home_id); ?>'>
                                    <img src="<?= base_url() . $this->config->item('yp_care_home_img_upload_url') . $value['profile_img'] ?>" alt="" />
                                </a>
                        </div>
                        <div class="careHomeDiv">
                            <a href='<?= base_url('YoungPerson/index/'.$care_home_id); ?>'><?= !empty($value['care_home_name']) ? $value['care_home_name'] : '' ?></a>
                            </div>
                        </span>
                    <?php }else{ ?>
                       
                     <span id="<?php echo $i; ?>" class="preview">                         
                      <div class="disp-table-cell">                            
                            <a href='<?= base_url('YoungPerson/index/'.$care_home_id); ?>'>
                            <img src="<?= base_url("/uploads/assets/front/images/care_home_default.png")?>" alt="" />
                            </a>
                      </div>
                       <div class="careHomeDiv">
                            <a href='<?= base_url('YoungPerson/index/'.$care_home_id); ?>'><?= !empty($value['care_home_name']) ? $value['care_home_name'] : '' ?></a>
                        </div>
                    </span>

            <?php } ?>
                           
                        </div>
                    </div>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>
    </div>
    </div>
    <?php
        
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
    
</div>
<?php } ?>
