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
                        if (isset($sortfield) && $sortfield == 'medication_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('medication_name', '<?php echo $sorttypepass; ?>')" style="min-width: 125px">Medication Name</th>
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
                        ?> onclick="apply_sorting('medication_type', '<?php echo $sorttypepass; ?>')" style="min-width: 120px">Medication Type</th>
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
                        ?> onclick="apply_sorting('reason', '<?php echo $sorttypepass; ?>')" style="min-width: 165px">Details</th>
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
                        ?> onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')" style="min-width: 210px">Prescribed to or Purchased for</th>

                        <th style="min-width: 142px">Total Stock</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'total_given') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('total_given', '<?php echo $sorttypepass; ?>')" style="min-width: 110px">Quantity Given</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'stock') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('CAST(`stock` AS decimal)', '<?php echo $sorttypepass; ?>')" style="min-width: 140px">Quantity Remaining</th>
                        <th style="min-width: 95px;"><?= lang('actions') ?></th>

                         
                         
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$care_home_id.'/'.$uri_segment:$care_home_id.'/0'?>"> 
                </tr>
                </thead>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        <tbody>
                            <tr>
                                <td><?=!empty($data['medication_name'])?$data['medication_name']:lang('NA'); ?> </td>
                                <td><?=!empty($data['medication_type'])?$data['medication_type']:lang('NA'); ?> </td>
                                 <td> <?= (!empty($data['reason']) && $data['reason'] !='') ? ((strlen (strip_tags($data['reason'])) > 25) ? substr (nl2br(strip_tags($data['reason'])), 0, 25)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore_medication_comment/'.$data['medication_id']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data['reason']))) :(isset($data['reason'])?$data['reason']:'') ?></td>

                                <td><?=!empty($data['doseage'])?$data['doseage']:lang('NA'); ?> </td>
                                <td><?=!empty($data['name'])?$data['name']:lang('NA'); ?></td>
                                
                                <td><?=number_format($data['quntity_remaining']+$data['quntity_given'],2)?></td>
                                <td><?=!empty($data['quntity_given'])?number_format($data['quntity_given'],2):'0'; ?></td>
                                <td><?=!empty($data['quntity_remaining'])?number_format($data['quntity_remaining'],2):0; ?></td>
                                <td>
                                <a href="<?php echo base_url() . 'MedicationStock/stockCheckLog/'.$data['medical_care_home_id'].'/'.$data['care_home_id']; ?>"  title="" ><span class="btn btn-default btn-sm">View Stock</span></a>
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
