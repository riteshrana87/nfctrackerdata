<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
$formId = array(
    'main' => AAI_MAIN_ENTRY_FORM,
    'type' => AAI_INCIDENT_TYPE_FORM,
    'L1' => AAI_L1_FORM,
    'L2' => AAI_L2NL3_FORM,
    'L4' => AAI_L4_FORM,
    'L5' => AAI_L5_FORM,
    'L6' => AAI_L6_FORM,
    'L7' => AAI_L7_FORM,
    'L8' => AAI_L8_FORM,
    'L9' => AAI_L9_FORM
);
?>
<div class="row" id="table-view">
    <div class="col-sm-12 m-t-10">    
        <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th <?php
                        if (isset($sortfield) && $sortfield == 'reference_number') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('reference_number', '<?php echo $sorttypepass; ?>')"> Reference Number</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'title') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('title', '<?php echo $sorttypepass; ?>')">Incident Status</th>
                        

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'date_of_incident') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('date_of_incident', '<?php echo $sorttypepass; ?>')">Date Incident Started</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'date_of_incident') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('date_of_incident', '<?php echo $sorttypepass; ?>')">Report Compiler</th>

                       <th <?php
                        if (isset($sortfield) && $sortfield == 'description') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('description', '<?php echo $sorttypepass; ?>')">Details</th>
                        

                            <th class="text-center">Action</th> 
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                            <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $ypId . '/' . $uri_segment : $ypId . '/0' ?>">  
                        </tr>
                    </thead>
                    <tbody>                     
                    <?php if (isset($information) && count($information) > 0) { ?>
                        <?php foreach ($information as $data) { ?>

                            <tr>
                                <td> 
                                    <?= !empty($data['reference_number']) ? $data['reference_number'] : lang('NA') ?>
                                </td>
                                <td> 
                                    <?= !empty($data['title']) ? $data['title'] : lang('NA') ?>
                                </td>
                                <td> 
                                    <?= !empty($data['date_of_incident']) ? configDateTime($data['date_of_incident']) : lang('NA') ?>
                                </td>
                                <td>
                                    <?php $aai_report_com = getUserDetailAAiList($data['list_main_incident_id']); ?>

                                   <?= !empty($aai_report_com) ? $aai_report_com : lang('NA') ?>
                                </td>
                                
                                <td>
                                    <?= (!empty($data['description']) && $data['description'] !='0000-00-00') ? ((strlen (strip_tags($data['description'])) > 50) ? substr (nl2br(strip_tags($data['description'])), 0, 50).'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['incident_id'].'/'.$data['reference_number'].'/description').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data['description']))) :lang('NA') ?>
                                </td>
                                <td class="text-center aai_action_btn">
                                     <a href="<?php echo base_url('AAI/view/'.$data['incident_id'].'/'.$formId[$data['process_id']]);?>" class="btn btn-link"><i class="fa fa-file-text-o" title="View" aria-hidden="true"></i></a>
                                    <?php if($data['draft'] == 1){ ?>
                                    <a href="<?php echo base_url('AAI/edit/'.$data['incident_id'].'/'.$formId[$data['process_id']]);?>" title="Edit" class="btn btn-link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                                    <?php } ?>

                                </td>   

                            </tr>
                    <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9"  class="text-center"><?= lang('common_no_record_found') ?></td>
                         </tr>
    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
            <div class="" id="common_tb_concern">
                <?php if (isset($pagination) && !empty($pagination)) { ?>
                <div class="col-sm-12 padding-0">
                        <?php echo $pagination; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
