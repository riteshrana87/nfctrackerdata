<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="row " id="table-view">
    <div class="col-xs-12 m-t-10 zui-wrapper">
        <div class="table-responsive zui-scroller">
            <table class="table table-bordered table-striped zui-table">
                <thead>
                    <tr>
                     <th <?php
                        if (isset($sortfield) && $sortfield == 'medication_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('medication_name', '<?php echo $sorttypepass; ?>')" style="min-width: 125px"><?php echo wordwrap('Medication Name',15,"<br>\n") ?>

                    </th>
					<th <?php
                        if (isset($sortfield) && $sortfield == 'CAST(`stock` AS decimal)') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('CAST(`stock` AS decimal)', '<?php echo $sorttypepass; ?>')" style="min-width: 100px"><?php echo wordwrap('Quantity Remaining',15,"<br>\n") ?></th>
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
                        ?> onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')" style="min-width: 130px"><?php echo wordwrap('Prescribed to or Purchased for',15,"<br>\n") ?></th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'medication_type') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('medication_type', '<?php echo $sorttypepass; ?>')" style="min-width: 120px"><?php echo wordwrap('Medication Type',15,"<br>\n") ?></th>
<th <?php
                        if (isset($sortfield) && $sortfield == 'reason') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('reason', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">Details</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'doseage') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('doseage', '<?php echo $sorttypepass; ?>')" style="min-width: 80px">Dosage</th>
                <th class="zui-sticky-col fix-hd"><?= lang('actions') ?></th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$care_home_id.'/'.$uri_segment:$care_home_id.'/0'?>"> 
                </tr>
                </thead>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        <tbody>
                            <tr>
                                <td><?=!empty($data['medication_name'])? wordwrap($data['medication_name'],15,"<br>\n"):lang('NA'); ?>

                                 </td>
								  <td><?=!empty($data['stock'])?number_format($data['stock'],2):0; ?></td>
								    <td><?=!empty($data['name'])?wordwrap($data['name'],15,"<br>\n"):lang('NA'); ?></td>
                                <td><?=!empty($data['medication_type'])?wordwrap($data['medication_type'],15,"<br>\n"):lang('NA'); ?> 
                                </td>


                                <td> <?= (!empty($data['reason']) && $data['reason'] !='') ? ((strlen (strip_tags($data['reason'])) > 25) ? wordwrap(substr (nl2br(strip_tags($data['reason'])), 0, 25),15,"<br>\n")
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore_medication_comment/'.$data['medication_id']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data['reason']))) :(isset($data['reason'])?$data['reason']:'') ?></td>
                                    
                            <td><?=!empty($data['doseage'])?wordwrap($data['doseage'],15,"<br>\n"):lang('NA'); ?> </td>
                                <td class="zui-sticky-col">
                                <a href="<?php echo base_url() . 'MedicationStock/stockCheckLog/'.$data['medical_care_home_id'].'/'.$data['care_home_id']; ?>"  title="" ><span class="btn btn-default btn-sm">View</span></a>
                                <?php if (checkPermission('MedicationStock', 'hidden_archive')) { ?>
                                
                                <button onclick="StockArchive(<?php echo $data['medical_care_home_id'] ; ?>,<?php echo $data['care_home_id'] ; ?>);" aria-hidden="true" data-refresh="true" title="" class="btn btn-default btn-sm" >Archive</button>

                                <?php }  ?>
                                <a data-href="<?php echo base_url() . 'MedicationStock/stockAdjustment/'.$data['medical_care_home_id']; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" href="javascript:;" title="" ><span class="btn btn-default btn-sm">S-Adj</span></a>
                                 <?php if(empty($data['totalcheck']) || ($data['totalcheck'] < 2)){ ?>
                                
                                <a data-href="<?php echo base_url() . 'MedicationStock/stockComment/'.$data['medical_care_home_id']; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" href="javascript:;" title="" ><span class="btn btn-default btn-sm">Check Stock <?=!empty($data['totalcheck'])?'('.$data['totalcheck'].')':''?></span></a>
                                <?php } else { echo '<i class="fa fa-check" aria-hidden="true"></i>';} ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="10" class="text-center"><?= lang('common_no_record_found') ?></td>

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
