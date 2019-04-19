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
                        if (isset($sortfield) && $sortfield == 'user_type') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('user_type', '<?php echo $sorttypepass; ?>')">User Type</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'fname') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('fname', '<?php echo $sorttypepass; ?>')">Document Signed By</th>
                        
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
                        ?> onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')">Document Sent Date</th>
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
                        ?> onclick="apply_sorting('modified_date', '<?php echo $sorttypepass; ?>')">Document Signed Date</th>
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
                        ?> onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')">Status</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'create_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('create_name', '<?php echo $sorttypepass; ?>')">Created By</th>
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
                        ?> onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')">Care Home Name</th>
                         <th>Action</th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <?php
                /* condition added by Ritesh Rana on 03/10/2018 to archive functionaity */
                 if($past_care_id == 0){ ?>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$rmp_id.'/'.$uri_segment:$ypid.'/'.$rmp_id.'/0'?>">  
                <?php }else{ ?>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$rmp_id.'/'.$care_home_id.'/'.$past_care_id.'/'.$uri_segment:$ypid.'/'.$rmp_id.'/'.$care_home_id.'/'.$past_care_id.'/0'?>">  
                <?php } ?>
                
                </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                       
                            <tr>
                                <td>
                                    <?php if($data['user_type'] == 'Parent_data'){ echo 'Parent';}
                                    else if($data['user_type'] == 'Social_worker' || $data['user_type'] == 'Social_data'){ echo 'Social Worker';}
                                    else{ echo $data['user_type'];} ?>
                                </td>
                                <td><?=!empty($data['fname'])?$data['fname']:''; ?> <?=!empty($data['lname'])?$data['lname']:''; ?></td>
                                <td><?=(!empty($data['created_date']) && $data['created_date'] != "0000-00-00 00:00:00")?configDateTimeFormat($data['created_date']):lang('NA'); ?></td>
                                <td><?=(!empty($data['modified_date']) && $data['modified_date'] != "0000-00-00 00:00:00")?configDateTimeFormat($data['modified_date']):lang('NA'); ?></td>
                                <td><?=($data['status'] == 'active')?'Approve':'Not Approve'; ?></td>
                                <td><?=!empty($data['create_name'])?$data['create_name']:''; ?></td>
                                <td><?=!empty($data['care_home_name'])?$data['care_home_name']:''; ?></td>
                                <td class="text-center">
                                    <?php if($past_care_id == 0){ ?>
                                    <a href="<?php echo base_url($crnt_view.'/viewApprovalRMP/'.$data['rmp_signoff_details_id'].'/'.$ypid);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php 
                                    $expairedDate = date('Y-m-d H:i:s', strtotime($data['created_date'].REPORT_EXPAIRED_DAYS));
                                    
                                    if(strtotime($expairedDate)<= strtotime(datetimeformat()))
                                    {
                                    if($data['status'] == 'inactive'){ ?>
                                    <a href="javascript:;" onclick="resendMail(<?php echo $data['rmp_signoff_details_id'] ; ?>,<?php echo $data['yp_id'] ; ?>,<?php echo $rmp_id; ?>);" class="btn btn-default width_a" title="Resend">Resend</a>
                                    <?php } } ?>
                                    <?php }else{ ?>
                                    <a href="<?php echo base_url($crnt_view.'/viewApprovalRMP/'.$data['rmp_signoff_details_id'].'/'.$ypid.'/'.$care_home_id.'/'.$past_care_id);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php } ?>

                                 </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center"><?= lang('common_no_record_found') ?></td>

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
</div>
