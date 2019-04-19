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
                        if (isset($sortfield) && $sortfield == 'mp_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('mp_name', '<?php echo $sorttypepass; ?>')" style="min-width: 110px">  Professional Name</th>
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('appointment_date', '<?php echo $sorttypepass; ?>')" style="min-width: 110px"> <?php echo wordwrap('Appointment Date',15,"<br>\n") ?></th>
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('appointment_time', '<?php echo $sorttypepass; ?>')" style="min-width: 110px"> <?php echo wordwrap('Appointment Time',15,"<br>\n") ?></th>
                        

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'address') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('address', '<?php echo $sorttypepass; ?>')">  Address</th>
                       
                         <th class="text-center" style="min-width: 120px;"> Action
                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>"> 
                        </th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                       
                            <tr>
                                <td><?=!empty($data['mp_name'])?$data['mp_name']:''?></td>
                                <td><?=(!empty($data['appointment_date']) && $data['appointment_date'] !='0000-00-00')?configDateTime($data['appointment_date']):''?> 
                                </td>
                                <td>
                                <?=(!empty($data['appointment_time']) && $data['appointment_time'] !='0000-00-00')? timeformat($data['appointment_time']):''?>
                                
                                </td>
                               
                                <td><?=!empty($data['address'])?$data['address']:''?></td>
                                <td>
                                    <a href="<?php echo base_url($crnt_view . '/appointment_view/' . $data['appointment_id'].'/'.$ypid); ?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                     <?php 
                                    if(checkPermission('Medical','edit')){ ?>
                                    <a href="<?php echo base_url($crnt_view . '/appointment_edit/' . $data['appointment_id'].'/'.$ypid); ?>" class="btn btn-link btn-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if(checkPermission('Medical','delete')){ ?>
                                    <a onclick="deletepopup('<?php echo $data['appointment_id'] ?>','','<?=$ypid?>')" href="javascript:void(0);"class="btn btn-link btn-blue"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
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
  <div class="col-xs-12 m-t-10" id="common_tb">
            <?php if (isset($pagination) && !empty($pagination)) { ?>
               
                    <?php echo $pagination; ?>
               
            <?php } ?>
        </div>
    </div>
</div>
