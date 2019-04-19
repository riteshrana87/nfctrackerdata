<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
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
                        if (isset($sortfield) && $sortfield == 'date_of_incident') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('date_of_incident', '<?php echo $sorttypepass; ?>')"> Date Incident Started</th>

                        <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Report Compiler</th>

                         <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Describe what happened leading up to incident</th>

                        <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1">What happened prior / led up to the incident</th>

                        <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1">How did the accident / injury occur</th>
                        <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Complaint details</th>

                        <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Details of Concern / Allegation / Disclosure</th>

                            <th class="text-center">Action</th> 
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                            <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$incident_id.'/'.$ypId.'/'.$uri_segment:$incident_id.'/'.$ypId.'/0'?>">    
                        </tr>
                    </thead>
                    <tbody>                     
                    <?php if (isset($information) && count($information) > 0) { ?>
                        <?php foreach ($information as $data) { 

                                $entry_form_data = json_decode($data['entry_form_data'], TRUE);

                                $describe_leading = '';
                                    if(json_decode($data['l1_form_data'], TRUE)){
                                        $l1_form_data = json_decode($data['l1_form_data'], TRUE);
                                        foreach ($l1_form_data as $value1) {
                                        if(!empty($value1['type']) && $value1['type'] == 'textarea' && $value1['name'] == 'l1_describe_leading'){
                                                $describe_leading = $value1['value'];        
                                        }
                                    }
                                    }else{
                                        $l2_l3_form_data = json_decode($data['l2_l3_form_data'], TRUE); 
                                        if(!empty($l2_l3_form_data)){
                                            foreach ($l2_l3_form_data as $value) {
                                                if(!empty($value['type']) && $value['type'] == 'textarea' && $value['name'] == 'l2_describe_leading'){
                                                    $describe_leading = $value['value'];        
                                                }
                                            }
                                        }
                                    }

                                $what_happened = '';    
                                if(json_decode($data['l4_form_data'], TRUE)){
                                    $l4_form_data = json_decode($data['l4_form_data'], TRUE);
                                        foreach ($l4_form_data as $value4) {
                                            if(!empty($value4['type']) && $value4['type'] == 'textarea' && $value4['name'] == 'what_happened'){
                                                    $what_happened = $value4['value'];        
                                               }
                                        }
                                    }
                                $how_did_accident = '';    
                                    if(json_decode($data['l5_form_data'], TRUE)){
                                    $l5_form_data = json_decode($data['l5_form_data'], TRUE);
                                        foreach ($l5_form_data as $value5) {
                                            if(!empty($value5['type']) && $value5['type'] == 'textarea' && $value5['name'] == 'how_did_accident'){
                                                    $how_did_accident = $value5['value'];        
                                               }
                                        }
                                    }
                                $l6_complaint_detail = '';    
                                    if(json_decode($data['l6_form_data'], TRUE)){
                                    $l6_form_data = json_decode($data['l6_form_data'], TRUE);
                                        foreach ($l6_form_data as $value6) {
                                            if(!empty($value6['type']) && $value6['type'] == 'textarea' && $value6['name'] == 'l6_complaint_detail'){
                                                    $l6_complaint_detail = $value6['value'];        
                                               }
                                        }
                                    }

                                    
                                $detail_concern = '';
                                if(json_decode($data['l7_form_data'], TRUE)){
                                    $l7_form_data = json_decode($data['l7_form_data'], TRUE);
                                        foreach ($l7_form_data as $value7) {
                                            if(!empty($value7['type']) && $value7['type'] == 'textarea' && $value7['name'] == 'detail_concern'){
                                                    $detail_concern = $value7['value'];        
                                               }
                                        }
                                    }
                                
                                
                            ?>
                            <tr>
                                <td> 
                                    <?= !empty($data['reference_number']) ? $data['reference_number'] : lang('NA') ?>
                                </td>
                                <td> 
                                    <?= !empty($data['date_of_incident']) ? configDateTime($data['date_of_incident']) : lang('NA') ?>
                                </td>
                                <td>
                                   <?= !empty($entry_form_data['report_compiler']) ? $entry_form_data['report_compiler'] : lang('NA') ?>
                                </td>
                                <td>
                                   <?php if(json_decode($data['l1_form_data'], TRUE)){ ?>
                                    <?= (!empty($describe_leading) && $describe_leading !='0000-00-00') ? ((strlen (strip_tags($describe_leading)) > 50) ? substr (nl2br(strip_tags($describe_leading)), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['archive_incident_id'].'/describe_leading').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($describe_leading))) :lang('NA') ?>
                                     <?php }else{ ?>  
                                      <?= (!empty($describe_leading) && $describe_leading !='0000-00-00') ? ((strlen (strip_tags($describe_leading)) > 50) ? substr (nl2br(strip_tags($describe_leading)), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['archive_incident_id'].'/describe_leading').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($describe_leading))) :lang('NA') ?>      
                                     <?php } ?>
                                </td>
                                <td><?= (!empty($what_happened) && $what_happened !='0000-00-00') ? ((strlen (strip_tags($what_happened)) > 50) ? substr (nl2br(strip_tags($what_happened)), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['archive_incident_id'].'/what_happened').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($what_happened))) :lang('NA') ?>
                                </td>
                                 <td>
                                    <?= (!empty($how_did_accident) && $how_did_accident !='0000-00-00') ? ((strlen (strip_tags($how_did_accident)) > 50) ? substr (nl2br(strip_tags($how_did_accident)), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['archive_incident_id'].'/how_did_accident').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($how_did_accident))) :lang('NA') ?>
                                </td>
                                <td>
                                    <?= (!empty($l6_complaint_detail) && $l6_complaint_detail !='0000-00-00') ? ((strlen (strip_tags($l6_complaint_detail)) > 50) ? substr (nl2br(strip_tags($l6_complaint_detail)), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['archive_incident_id'].'/l6_complaint_detail').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($l6_complaint_detail))) :lang('NA') ?>
                                </td>
                                <td>
                                    <?= (!empty($detail_concern) && $detail_concern !='0000-00-00') ? ((strlen (strip_tags($detail_concern)) > 50) ? substr (nl2br(strip_tags($detail_concern)), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['archive_incident_id'].'/detail_concern').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($detail_concern))) :lang('NA') ?>
                                </td>

                                
                                <td class="text-center aai_action_btn">
                                     <a href="<?php echo base_url('ArchiveAAI/view/'.$data['incident_id'].'/'.$data['archive_incident_id']);?>" class="btn btn-link"><i class="fa fa-file-text-o" title="View" aria-hidden="true"></i></a>
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
