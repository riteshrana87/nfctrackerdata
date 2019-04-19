<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="row" id="table-view" style="<?= ($yp_list_type == 'display-table') ? 'display: block;' : 'display: none;' ?>">
    <div class="col-xs-12 m-t-10">
        <div class="table-responsive">
            <input type="hidden" name="uri_segment" id="uri_segment" value="<?php echo $uri_segment;?>" />
             <input type="hidden" name="ypid" id="ypid" value="<?php echo $ypid;?>" />
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1"> Name</th>
                        <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1"> Gender</th>
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
                        ?> onclick="apply_sortingsdq('user_type', '<?php echo $sorttypepass; ?>')">SDQ Completed by</th>

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
                        ?> onclick="apply_sortingsdq('created_date', '<?php echo $sorttypepass; ?>')"> Created Date</th>

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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sortingsdq('modified_date', '<?php echo $sorttypepass; ?>')"> Modified Date</th>

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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sortingsdq('status', '<?php echo $sorttypepass; ?>')"> Status</th>
						
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sortingsdq('care_home_id', '<?php echo $sorttypepass; ?>')"> CareHome</th>
						 <?php if($is_archive_page==0){?>
                        <th><?= lang('actions') ?></th>
						 <?php }?>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />  
                </tr>
                </thead>
                <tbody>
                    <?php if (isset($sdq_record_list) && count($sdq_record_list) > 0) { ?>
                        <?php foreach ($sdq_record_list as $data) { //pr($data);die;						?>

                            <tr>
                                <td><?php echo !empty($data['yp_fname']) ? $data['yp_fname'] . ' ' . $data['yp_lname'] : lang('NA') ?></td>
                                <td>
                                    <?php
                                    if (!empty($data['gender']) && $data['gender'] == 'F') {
                                        echo "Female";
                                    } elseif (!empty($data['gender']) && $data['gender'] == 'M') {
                                        echo "Male";
                                    } else {
                                        echo lang('NA');
                                    }
                                    ?>           
                                </td>
                                <td><?= !empty($data['user_type']) ? $data['user_type'] : lang('NA') ?></td>
                                <td><?= (!empty($data['created_date']) && $data['created_date'] != '0000-00-00') ? configDateTime($data['created_date']) : lang('NA') ?></td>
                                <td><?= (!empty($data['modified_date']) && $data['modified_date'] != '0000-00-00') ? configDateTime($data['modified_date']) : lang('NA') ?></td>
                                <td>
                                    <?php
                                    if (!empty($data['status']) && $data['status'] == '1') {
                                        echo "Active";
                                    } elseif (!empty($data['status']) && $data['status'] == '0') {
                                        echo "Inactive";
                                    } else {
                                        echo lang('NA');
                                    }
                                    ?>           
                                </td>
								<td><?= !empty($data['care_home_name']) ? $data['care_home_name'] : lang('NA') ?></td>
								<?php if($is_archive_page==0){?>
									<td class="text-center">
									
										<?php if (checkPermission('SdqReport', 'edit')) { 
										if($current_care_home_id==$data['care_home_id']){
										?>
										<a href="<?php echo base_url($crnt_view . '/edit/' . $data['yp_id'] . '/' . $data['id']); ?>" class="btn btn-link btn-blue"><i class="fa fa-edit" aria-hidden="true"></i></a>
										<?php  } }?>
									</td>
								<?php } ?>
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
    <div id="common_tb_sdq">
            <?php if (isset($pagination) && !empty($pagination)) { ?>
            <div class="col-sm-12">
            <?php echo $pagination; ?>
            </div>
<?php } ?>
    </div>

</div>
<?php 
				/*ghelani nikunj
					10/1/2018
					if in care to care archive then no need to show button
					*/
				if($is_archive_page==0){?>
<?php if(checkPermission('SdqReport','add')){  ?>
<div class="row">
    <div class="col-md-12 m-t-10">
        <a href="<?php echo base_url() . 'SdqReport/sdqQuestion/' . $ypid; ?>" title="" class="btn btn-default" >Complete an SDQ Assessment</a>

    </div>
</div>
<?php
				} }?>

<?= $this->load->view('/Common/common', '', true); ?>