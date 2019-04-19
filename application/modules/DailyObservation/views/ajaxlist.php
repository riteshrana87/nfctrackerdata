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
                        if (isset($sortfield) && $sortfield == 'daily_observation_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('daily_observation_date', '<?php echo $sorttypepass; ?>')">  Date</th>

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
                        ?> onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')">Care home Name</th>
                        <th>All Fields Filled</th>
                        <th>View</th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <?php 
                    /*
                       Ritesh Rana
                       need to change when client will approved for archive functionality
                    */
                    ?>
                <?php if($past_care_id == 0){ ?>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
                <?php } else { ?>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$care_home_id.'/'.$past_care_id.'/'.$uri_segment:$ypid.'/'.$care_home_id.'/'.$past_care_id.'/0'?>">  
                <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { 
                        $current_date = "";
                           if(date('d/m/Y',strtotime($data['daily_observation_date'])) == date('d/m/Y')){
                                $current_date = "bg-green";
                           } 
                        ?>
                       
                            <tr class="<?php echo $current_date ?>">
                                <td><?php echo date('d/m/Y',strtotime($data['daily_observation_date'])); ?></td>
                                <td><?php echo $data['create_name']; ?></td>
                                <td><?= !empty($data['care_home_name']) ? $data['care_home_name'] : lang('NA') ?></td>
                                <td>
                                    <?php if(!empty($data['is_filled_field']) && !empty($data['is_filled_field_overview'])){ 
                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                     } else {
                                          echo '<i class="fa fa-remove" aria-hidden="true"></i>';
                                        } ?>
                                </td>
                                <td class="text-center">
                    <?php 
                    /*
                       Ritesh Rana
                       need to change when client will approved for archive functionality
                    */
                    ?>
                                     <?php 
                                    if($past_care_id == 0){
                                     if(checkPermission('DailyObservation','view')){ ?>
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['do_id'].'/'.$data['yp_id']);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php }} else { ?>
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['do_id'].'/'.$data['yp_id'].'/'. $care_home_id.'/'.$past_care_id);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php } ?>


                                    <?php 
                                    if($past_care_id == 0){
                                    if(checkPermission('DailyObservation','delete')){ ?>
                                        <a href="javascript:;" onclick="deleteDo(<?php echo $data['yp_id'] ; ?>,<?php echo $data['do_id']; ?>);" class="btn btn-link btn-blue" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    <?php } } ?>
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
    <div id="common_tb">
      <?php if (isset($pagination) && !empty($pagination)) { ?>
      <div class="col-sm-12">
        <?php echo $pagination; ?>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
