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
                        if (isset($sortfield) && $sortfield == 'signoff_created_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('signoff_created_date', '<?php echo $sorttypepass; ?>')">Document Sent Date</th>
                         <th <?php
                        if (isset($sortfield) && $sortfield == 'signoff_modified_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('signoff_modified_date', '<?php echo $sorttypepass; ?>')">Document Signed Date</th>
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
                
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$incident_id.'/'.$uri_segment:$ypid.'/'.$incident_id.'/0'?>">  
                
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
                                <td><?=!empty($data['signoff_created_date'])?configDateTimeFormat($data['signoff_created_date']):lang('NA'); ?></td>
                                <td><?=!empty($data['signoff_modified_date'])?configDateTimeFormat($data['signoff_modified_date']):lang('NA'); ?></td>
                                <td><?=($data['status'] == 'active')?'Approve':'Not Approve'; ?></td>
                                <td><?=!empty($data['create_name'])?$data['create_name']:''; ?></td>
                                <td><?=!empty($data['care_home_name'])?$data['care_home_name']:''; ?></td>
                                <td class="text-center">

                                   <?php if (checkPermission('AAI', 'view')) { ?>
                                    <a href="<?php echo base_url($crnt_view.'/approvalView/'.$data['signoff_approval_id'].'/'.$incident_id);?>" class="btn btn-link"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    
                                    <?php 
                                    $expairedDate = date('Y-m-d H:i:s', strtotime($data['signoff_created_date'].REPORT_EXPAIRED_DAYS));
                                    
                                    if(strtotime($expairedDate)<= strtotime(datetimeformat()))
                                    {
                                    if($data['status'] == 'inactive'){ ?>
                                    <a href="javascript:;" onclick="resendMail(<?php echo $data['signoff_approval_id'] ; ?>,<?php echo $incident_id ; ?>,<?php echo $pp_id ; ?>);" class="btn btn-default width_a" title="Resend">Resend</a>
                                    <?php } } ?>

                                 </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8" class="text-center"><?= lang('common_no_record_found') ?></td>

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
