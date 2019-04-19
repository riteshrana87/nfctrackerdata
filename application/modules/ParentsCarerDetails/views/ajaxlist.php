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
            <th <?php if(isset($sortfield) && $sortfield == 'name'){if($sortby == 'asc'){echo "class = 'sort-dsc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> onclick="apply_sorting('name','<?php echo $sorttypepass;?>')"><?= lang('name')?></th>

            <th <?php if(isset($sortfield) && $sortfield == 'email_address'){if($sortby == 'asc'){echo "class = 'sort-dsc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> onclick="apply_sorting('email_address','<?php echo $sorttypepass;?>')"> <?= lang('emails')?></th>

            <th <?php if(isset($sortfield) && $sortfield == 'contact_number'){if($sortby == 'asc'){echo "class = 'sort-dsc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_number','<?php echo $sorttypepass;?>')">  Mobile Number</th>

            <th <?php if(isset($sortfield) && $sortfield == 'landline_number'){if($sortby == 'asc'){echo "class = 'sort-dsc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('landline_number','<?php echo $sorttypepass;?>')">  Landline Number</th>

            <th <?php if(isset($sortfield) && $sortfield == 'relationship'){if($sortby == 'asc'){echo "class = 'sort-dsc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('relationship','<?php echo $sorttypepass;?>')">  Relationship</th>
			<th <?php if(isset($sortfield) && $sortfield == 'care_home_name'){if($sortby == 'asc'){echo "class = 'sort-dsc'";}else{echo "class = 'sort-asc'";}}else{echo "class = 'sort'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_name','<?php echo $sorttypepass;?>')">  CareHome</th>
            
            <th><?= lang('actions')?></th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <?php if($is_archive_page==0){?>
					<input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
					<?php } else {?>
					<input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$careHomeId.'/'.$is_archive_page.'/'.$uri_segment:$ypid.'/'.$careHomeId.'/'.$is_archive_page.'/0'?>">  
                <?php }?>

        </tr>
                
                </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                       
                            <tr>
            <td><?php echo $data['name'];?></td>
            <td><?php echo $data['email_address'];?></td>
            <td><?php echo $data['contact_number'];?></td>
            <td><?php echo $data['landline_number'];?></td>
            <td><?php echo $data['relationship'];?></td>
            <td><?php echo $data['care_home_name'];?></td>
                <td class="text-center">
				<?php if($is_archive_page==0){?>
                    <?php if(checkPermission('ParentsCarerDetails','edit')){ ?>
                        <a href="javascript:;" data-href="<?php echo base_url() . 'ParentsCarerDetails/editParentCarerInformation/' . $data['yp_id'].'/'.$data['parent_carer_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-link padding-r-0" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <?php }?>
                    <?php } else{?>
					
						<a href="javascript:;" data-href="<?php echo base_url() . 'ParentsCarerDetails/viewParentCarerInformation/' . $data['yp_id'].'/'.$data['parent_carer_id']; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-link padding-r-0" ><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
					<?php }?>
                    <?php if($is_archive_page==0){?>
                    <?php if(checkPermission("ParentsCarerDetails","delete")){ ?>

                        <a class="btn btn-link" data-href="javascript:;" title="<?= lang('delete')?>" onclick="deleteParent(<?php echo $data['yp_id'] ; ?>,<?php echo $data['parent_carer_id'] ; ?>);"  ><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					<?php } } ?>

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
