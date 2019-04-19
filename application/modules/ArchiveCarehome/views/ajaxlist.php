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
                        if (isset($sortfield) && $sortfield == 'created_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')">Created Date</th>
                        
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'past_carehome') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('past_carehome', '<?php echo $sorttypepass; ?>')">Past Care Home</th>


                        <th <?php
                        if (isset($sortfield) && $sortfield == 'current_carehome') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('current_carehome', '<?php echo $sorttypepass; ?>')">Home Moved To</th>

                         <th <?php
                        if (isset($sortfield) && $sortfield == 'move_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('move_date', '<?php echo $sorttypepass; ?>')"> Left NFC Date</th>


                        <th><?= lang('actions') ?></th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />  
                <input type="hidden" id="care_home_id" name="care_home_id" value="<?php echo $care_home_id;?>" />  
                
                 <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$care_home_id.'/'.$uri_segment:$care_home_id.'/0'?>">

                  <input class="" type="hidden" name="primaryId" id="primaryId" value="<?=!empty($care_home_id)?$care_home_id:'0'?>">
                </tr>
                </thead>
                <tbody>

                <?php
                 if (isset($information) && count($information) > 0) { ?>
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
             
            <td><?= !empty($data['created_date']) ? configDateTime($data['created_date']) : lang('NA') ?></td>
            <td><?php echo getCareHomeName($data['past_carehome']);?></td>
            <td><?php echo getCareHomeName($data['current_carehome']);?></td>
            <td><?= !empty($data['move_date']) ? configDateTime($data['move_date']) : lang('NA') ?></td>
                                
                                <td class="text-center">
                                    <?php if(checkPermission('YoungPerson','view')){ ?>
                                    <a href="<?php echo base_url($crnt_view . '/view/' . $data['yp_id'] .'/'. $care_home_id. '/' .$data['id']); ?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
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
