<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="row" id="table-view">
    <div class="col-xs-12 m-t-10">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>                    
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'appointment_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('appointment_date', '<?php echo $sorttypepass; ?>')" style="min-width: 130px"> <?php echo wordwrap(' Appointment /Event Date',15,"<br>\n") ?></th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'appointment_time') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('appointment_time', '<?php echo $sorttypepass; ?>')" style="min-width: 135px"> <?php echo wordwrap('Appointment /Event Time',15,"\n") ?></th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'appointment_type') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('appointment_type', '<?php echo $sorttypepass; ?>')" style="min-width: 130px"><?php echo wordwrap('Type of Appointment / Event',15,"<br>\n") ?></th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'comments') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('comments', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">  Comments</th>
						<th <?php
                        if (isset($sortfield) && $sortfield == 'care_home_id') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_id', '<?php echo $sorttypepass; ?>')" style="min-width: 135px">  CareHome</th>

                        <th class="text-center" style="min-width: 100px;"> Action
                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                        </th>
                        <?php if($is_archive_page==0){?>
                                 <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>"> 
                                <?php } else {?>
                                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$careHomeId.'/'.$is_archive_page.'/'.$uri_segment:$ypid.'/'.$careHomeId.'/'.$is_archive_page.'/0'?>">  
                        <?php }?>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>                       
                    <tr>                                
                        <td><?=(!empty($data['appointment_date']) && $data['appointment_date'] !='0000-00-00')?configDateTime($data['appointment_date']):''?> 
                        </td>
                        <td>
                        <?=(!empty($data['appointment_time']) && $data['appointment_time'] !='0000-00-00')?  timeformat($data['appointment_time']):''?>

                        </td>
                        <td>
                        <?=(!empty($data['appointment_type']))?$data['appointment_type']:''?>
                        </td>
                        <td>

                            <?= (!empty($data['comments'])) ? ((strlen ($data['comments']) > 50) ? $substr = wordwrap(substr (nl2br(strip_tags($data['comments'])), 0, 50),15,"<br>\n") . '...<a data-href="'.base_url('Appointments'.'/readmore_appointment/'.$data['planner_id']).'/comments" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data['comments']))):'' ?>
                        </td>
                       <td>
                        <?=(!empty($data['care_home_name']))?$data['care_home_name']:''?>
                        </td>
                        <td>
                        <?php if($is_archive_page==0){?>
                        <a href="<?php echo base_url($crnt_view . '/appointment_view/' . $data['planner_id'].'/'.$ypid); ?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                         <?php 
                        if(checkPermission('Appointments','edit')){ ?>
                        <a href="<?php echo base_url($crnt_view . '/appointment_edit/' . $data['planner_id'].'/'.$ypid); ?>" class="btn btn-link btn-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <?php } ?>
                        <?php if(checkPermission('Appointments','delete')){ ?>
                        <a onclick="deletepopup('<?php echo $data['planner_id'] ?>','','<?=$ypid?>')" href="javascript:void(0);"class="btn btn-link btn-blue"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        <?php } ?>
                        <?php } else{?>
                        <a href="<?php echo base_url($crnt_view . '/appointment_view/' . $data['planner_id'].'/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                        <?php }?>
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
    <div class="col-xs-12 m-t-10" id="common_tb">
        <?php if (isset($pagination) && !empty($pagination)) { ?> <?php echo $pagination; ?> <?php } ?>
    </div>
</div>
