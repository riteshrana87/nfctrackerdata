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
                        if (isset($sortfield) && $sortfield == 'report_start_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('report_start_date', '<?php echo $sorttypepass; ?>')">  Report Start Date</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'report_end_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('report_end_date', '<?php echo $sorttypepass; ?>')">  Report End Date</th>

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
                        if (isset($sortfield) && $sortfield == 'created_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')">Created Date</th>
						
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
                        ?> onclick="apply_sorting('care_home_id', '<?php echo $sorttypepass; ?>')">CareHome</th>
                        <th>Sent Report</th>
                         <th>Action</th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
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
                                <td> <?=(!empty($data['report_start_date']) && $data['report_start_date'] !='0000-00-00')?configDateTime($data['report_start_date']):''?></td>
                                <td><?=(!empty($data['report_end_date']) && $data['report_end_date'] !='0000-00-00')?configDateTime($data['report_end_date']):''?></td>
                                <td><?php echo $data['create_name']; ?></td>
                                <td><?php echo configDateTime($data['created_date']); ?></td>
                                <td><?php echo $data['care_home_name']; ?></td>
                                 <td><?=!empty($data['sent_report'])?$data['sent_report']:0?></td>
                                <td class="text-center">
                                 <?php if(empty($data['draft']) && $data['draft'] == '0'){ ?>  
                                <?php if(checkPermission('MDTReviewReport','view')){ ?>
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['mdt_report_id'].'/'.$data['yp_id'].'/'.$careHomeId.'/'.$is_archive_page);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php } } ?>

                                <?php if(!empty($data['draft']) && $data['draft'] == '1'){ ?>
                                <?php if(checkPermission('MDTReviewReport','edit')){ ?>
                                    <a href="<?php echo base_url($crnt_view.'/edit_draft/'.$data['mdt_report_id'].'/'.$data['yp_id']);?>" title="Draft" class="btn btn-link btn-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                   <?php }}?>   
                                     
                                      <?php if($is_archive_page==0){?>
                                    <?php if(checkPermission('MDTReviewReport','delete')){ ?>
<a href="javascript:;" onclick="deleteMDT(<?php echo $data['yp_id'] ; ?>,<?php echo $data['mdt_report_id'] ; ?>);" class="btn btn-link btn-blue" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                      <?php } }?>
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
