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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('review_by', '<?php echo $sorttypepass; ?>')"> Document Signed By</th>
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
                        if (isset($sortfield) && $sortfield == 'modified_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('modified_date', '<?php echo $sorttypepass; ?>')"> Document Signed Date</th>
                        
                        
                        

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'status') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')"> Status</th>
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('sent_by', '<?php echo $sorttypepass; ?>')"> Created By</th>
						
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_id', '<?php echo $sorttypepass; ?>')"> CareHome</th>
                        <th class="text-center">Action</th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $doc_id . '/' . $ypid.'/'. $uri_segment : $doc_id . '/' . $ypid.'/0' ?>">  
                </tr>
                </thead>
                <tbody>
<?php if (isset($doc_signoff_data) && count($doc_signoff_data) > 0) { ?>
                            <?php foreach ($doc_signoff_data as $data) { ?>

                            <tr>
                                <td><?php if($data['user_type'] == 'Parent_data'){ echo 'Parent';}
                                    else if($data['user_type'] == 'Social_worker' || $data['user_type'] == 'Social_data'){ echo 'Social Worker';}
                                    else{ echo $data['user_type'];} ?></td>
                                <td><?php echo (!empty($data['review_by'])) ? $data['review_by'] : lang('NA'); ?></td>
                                 <td> <?php echo (!empty($data['created_date']) && $data['created_date'] != "0000-00-00 00:00:00") ? configDateTimeFormat($data['created_date']) : lang('NA'); ?></td>
                                <td> <?php echo (!empty($data['modified_date']) && $data['modified_date'] != "0000-00-00 00:00:00") ? configDateTimeFormat($data['modified_date']) : lang('NA'); ?></td>
                               
                               
                                <td> <?php echo (!empty($data['status'])) ? (($data['status'] == 'active') ? "Approved" : "Not Approve") : lang('NA'); ?> </td>
                                 <td> <?php echo (!empty($data['sent_by'])) ? $data['sent_by'] : lang('NA'); ?> </td>
                                 <td> <?php echo (!empty($data['care_home_name'])) ? $data['care_home_name'] : lang('NA'); ?> </td>
                                <td class="text-center">    
                                    <a href="<?php echo base_url($crnt_view . '/reviewedConcern/' . $data['docs_id'] . '/' . $data['yp_id'].'/'.$data['docs_signoff_details_id']); ?>" class="btn btn-link"><i class="fa fa-file-text-o" title="View" aria-hidden="true"></i></a>
                                    <?php 
                                    $expairedDate = date('Y-m-d H:i:s', strtotime($data['created_date'].REPORT_EXPAIRED_DAYS));
                                    
                                    if(strtotime($expairedDate)<= strtotime(datetimeformat()))
                                    {
                                    if($data['status'] == 'inactive'){ ?>
                                    <a href="javascript:;" onclick="resendMail(<?php echo $data['docs_signoff_details_id'] ; ?>,<?php echo $data['yp_id'] ; ?>,<?php echo $doc_id; ?>);" class="btn btn-default width_a" title="Resend">Resend</a>
                                    <?php } } ?>
                                </td>
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