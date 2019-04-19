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
                        if (isset($sortfield) && $sortfield == 'created_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"> Date</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'start_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('start_date', '<?php echo $sorttypepass; ?>')"> Start Date</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'end_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('end_date', '<?php echo $sorttypepass; ?>')"> End Date</th>

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
                        ?> onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')">Created By</th>
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
                        ?> onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')">Care home Name</th>
                         <th>View</th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
                </tr>
                </thead>
                <?php if (isset($edit_data) && count($edit_data) > 0) { ?>
                    <?php foreach ($edit_data as $data) { ?>
                        <tbody>
                            <tr>
                                <td><?php echo configDateTime($data['created_date']); ?></td>
                                <td><?php echo configDateTime($data['start_date']); ?></td>
                                <td><?php echo configDateTime($data['end_date']); ?></td>
                                <td><?php echo $data['name']; ?></td>
                                <td><?= !empty($data['care_home_name']) ? $data['care_home_name'] : lang('NA') ?></td>
                                <td class="text-center"><a href="<?php echo base_url($crnt_view.'/view/'.$data['weekly_report_id'].'/'.$data['yp_id']);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                <?php if(checkPermission('ArchiveWr','hidden_archive')){ ?>
                                <a href="<?php echo base_url($crnt_view.'/undoArchive/'.$data['weekly_report_id'].'/'.$data['yp_id']);?>" class="btn btn-link btn-blue"><i class="fa fa fa-mail-reply" aria-hidden="true"></i></a></td>
                                <?php } ?></td>
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
</div>
