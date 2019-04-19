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
            <table class="table table-bordered table-striped m-t-10 m-b-10 dataTable">
                <thead>
                    <tr>
                     <th <?php
                        if (isset($sortfield) && $sortfield == 'mp_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sorting_desc'";
                            } else {
                                echo "class = 'sorting_asc'";
                            }
                        } else {
                            echo "class = 'sorting'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('mp_name', '<?php echo $sorttypepass; ?>')">  Professional Name</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'appointment_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sorting_desc'";
                            } else {
                                echo "class = 'sorting_asc'";
                            }
                        } else {
                            echo "class = 'sorting'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('appointment_date', '<?php echo $sorttypepass; ?>')">  Appointment Date</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'appointment_time') {
                            if ($sortby == 'asc') {
                                echo "class = 'sorting_desc'";
                            } else {
                                echo "class = 'sorting_asc'";
                            }
                        } else {
                            echo "class = 'sorting'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('appointment_time', '<?php echo $sorttypepass; ?>')">  Appointment Time</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'comments') {
                            if ($sortby == 'asc') {
                                echo "class = 'sorting_desc'";
                            } else {
                                echo "class = 'sorting_asc'";
                            }
                        } else {
                            echo "class = 'sorting'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('comments', '<?php echo $sorttypepass; ?>')">  Comments</th>
                       
                         <th> Action
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
                                <?=(!empty($data['appointment_time']) && $data['appointment_time'] !='0000-00-00')?timeformat($data['appointment_time']):''?>
                                </td>
                                <td>
                                    <?= (!empty($data['comments'])) ? ((strlen ($data['comments']) > 50) ? $substr = substr (trim(strip_tags($data['comments'])), 0, 50) . '...<a data-href="'.base_url('Medical'.'/readmore_appointment/'.$data['appointment_id']).'/comments" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data['comments']))):'' ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('Admin/'.$crnt_view . '/appointment_view/' . $data['appointment_id']); ?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                   
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
