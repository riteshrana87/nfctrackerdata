<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="row" id="table-view">
    <div class="col-sm-12 m-t-10">    
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'user_type') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('user_type', '<?php echo $sorttypepass; ?>')"> User Type</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'review_by') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('review_by', '<?php echo $sorttypepass; ?>')"> Document Reviewed By</th>
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"> Document Sent Date</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'sent_by') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('sent_by', '<?php echo $sorttypepass; ?>')"> Document Sent By</th>

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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')">Care Home Name</th>

                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                    <?php if($past_care_id == 0){ ?>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $weekly_report_id . '/' . $ypid.'/'. $uri_segment : $weekly_report_id . '/' . $ypid.'/0' ?>">  
                <?php }else{ ?>
                   <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $weekly_report_id . '/' . $ypid.'/'.$care_home_id.'/'.$past_care_id.'/'. $uri_segment : $weekly_report_id . '/' . $ypid.'/'.$care_home_id.'/'.$past_care_id.'/0' ?>">   
                <?php } ?>

                </tr>
                </thead>
                <tbody>
<?php if (isset($wr_signoff_data) && count($wr_signoff_data) > 0) { ?>
                            <?php foreach ($wr_signoff_data as $data) { ?>

                            <tr>
                                <td>
                                    
                                    <?php if($data['user_type'] == 'Parent_data'){ echo 'Parent';}
                                    else if($data['user_type'] == 'Social_worker' || $data['user_type'] == 'Social_data'){ echo 'Social Worker';}
                                    else{ echo $data['user_type'];} ?>
                                </td>
                                <td><?php echo (!empty($data['review_by'])) ? $data['review_by'] : lang('NA'); ?></td>
                                <td> <?php echo (!empty($data['created_date']) && $data['created_date'] != "0000-00-00 00:00:00") ? configDateTimeFormat($data['created_date']) : lang('NA'); ?></td>
                                <td> <?php echo (!empty($data['sent_by'])) ? $data['sent_by'] : lang('NA'); ?> </td>
                                <td> <?php echo (!empty($data['care_home_name'])) ? $data['care_home_name'] : lang('NA'); ?> </td>
                            </tr>
                        <?php } ?>
<?php } else { ?>
                        <tr>
                            <td colspan="<?= !empty($form_data) ? count($form_data) + 2 : '10' ?>"  class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
            <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
        <div class="" id="common_tb">
<?php if (isset($pagination) && !empty($pagination)) { ?>
                <div class="col-sm-12 padding-0">
    <?php echo $pagination; ?>
                </div>
<?php } ?>
        </div>
    </div>
</div>