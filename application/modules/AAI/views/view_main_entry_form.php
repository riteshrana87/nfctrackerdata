<div class="panel panel-default">
    <div class="panel-heading aai-page-header" role="tab" id="headingOne">
        <h4 class="panel-title">MAIN ENTRY FORM
            <span class="archive-btn"><a href="#">Archive</a><a href="#">Print</a></span>
        </h4>
    </div>
        <div class="panel-body form-horizontal">
            <div class="row clear-div-flex">
                <div class="col-sm-12 m-b-p">
                    <div class="form-group">
                            <?php if(isset($editMode) && $editMode>0){ ?>
                            <div class="col-md-12 col-sm-12 "><span class="type-incident-radio"> Type of incident </span>
                                <?php 
                                if($isCareIncident == 2){
                                    $checkEdu1 = 'checked';
                                    $checkCare2 = '';
                                }else{
                                    $checkCare2 = 'checked';
                                    $checkEdu1 = '';
                                }
                                ?>
                                <label>
                                    <?php if(!empty($checkEdu1) && $checkEdu1 == 'checked'){ 
                                        echo 'Education incident';
                                    }else{ 
                                        echo 'Care incident';
                                    } ?>
                                </label>
                                &nbsp;&nbsp;
                            </div> 
                        </div>
                            <?php } ?>
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12"> <span class="type-incident-radio">Is the incident related to any other recorded incident?</span>
                                <?php
                                $relatedIncidentArray = explode(',', $relatedIncident);
                                if(!isset($createMode) && count($relatedIncidentArray)>0 && (string)$relatedIncident !== ''){
                                    $checkIsLinked1 = 'checked';
                                    $checkIsLinked2 = '';
                                }else{
                                    $checkIsLinked2 = 'checked';
                                    $checkIsLinked1 = '';
                                }
                                ?>
                                <div class="hidden">
                                    <label><input type="radio" name="is_linked" onchange="check_incident_linked()" id="yescheck" value="1" <?= $checkIsLinked1 ?>> YES</label>&nbsp;&nbsp;
                                    <label class=""><input type="radio" name="is_linked" onchange="check_incident_linked()" id="nocheck" value="0" <?= $checkIsLinked2 ?>> NO</label>&nbsp;&nbsp;    
                                </div>
                                
                                <?php if(!empty($checkIsLinked1) && $checkIsLinked1 == 'checked'){ 
                                        echo '<label class="radio-inline p-l-r-0"><span class="label label-success">Yes</span></label>';
                                    }else{ 
                                        echo '<label class="radio-inline p-l-r-0"><span class="label label-danger">No</span></label>';
                                    } ?>
                            </div> 
                            </div>
                            <div class="row" id="yp_incidents">
                                <div class="col-xs-12 m-t-10">
                                    <div class="table-responsive aai_table_heights">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>incident reference number</th>
                                                    <th>incident type</th>
                                                    <th>Care home Name</th>                                                    
                                                    
                                                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                                                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (isset($YPIncidentData) && count($YPIncidentData) > 0) { ?>
                                                <?php foreach ($YPIncidentData as $incidentDetail) { 
                                                    if(in_array($incidentDetail['incident_id'], $relatedIncidentArray)){ ?>
                                                    <tr>
                                                        <td>
                                                            <input disabled="" type="checkbox" class="incidentCheckbox" onclick="incident_check_list()" value="<?php echo $incidentDetail['incident_id']; ?>" <?= (in_array($incidentDetail['incident_id'], $relatedIncidentArray))? 'checked':'' ?>>
                                                        </td>
                                                        <td><?php echo $incidentDetail['incident_reference_number']; ?></td>
                                                        <td><?php echo ($incidentDetail['is_care_incident'] == 1)?'care incident':'education incident'; ?></td>
                                                        <td><?= !empty($incidentDetail['care_home_name']) ? $incidentDetail['care_home_name'] : lang('NA') ?></td>
                                                        
                                                    </tr>
                                                <?php }} ?>
                                            <input type="hidden" name="related_incident" id="related_incident" value="<?= (isset($relatedIncident))? $relatedIncident:'' ?>">
                                            <?php } else { ?>
<!--                                                    <tr>
                                                        <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>

                                                    </tr>-->
                                                <?php } ?>
                                            
                                                <?php if(in_array('history', $relatedIncidentArray)){ ?>
                                                <tr>
                                                    <td>
                                                        <input disabled="" type="checkbox" class="incidentCheckbox" onclick="incident_check_list()" value="history" <?= (in_array('history', $relatedIncidentArray))? 'checked':'' ?>>
                                                    </td>
                                                    <td colspan="3">Incident is related to Historical(older than 14 Days)</td>
                                                </tr>
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                              <div class="clearfix"></div>                                
                              </div>
                          </div>
                </div>          
                <?php                 
                if (!empty($entry_form_data)) {
                    foreach ($entry_form_data as $key => $row) {
                        $dd=$row['name'];
                        if(isset($createMode) && $createMode == 1){
                            
                            if($row['name'] == 'yp_fname'){
                                $row['value'] = $yp_details[0]['yp_fname'];
                            }
                            if($row['name'] == 'yp_surname'){
                                $row['value'] = $yp_details[0]['yp_lname'];
                            }
                            if($row['name'] == 'yp_email'){
                                $row['value'] = $yp_details[0]['email_id'];
                            }
                            if($row['name'] == 'yp_dob'){
                                $row['value'] = configDateTime($yp_details[0]['date_of_birth']);
                            }
                            if($row['name'] == 'yp_gender'){
                                if($yp_details[0]['gender'] == 'M'){
                                    $row['value'] = 'Male';
                                }elseif($yp_details[0]['gender'] == 'F'){
                                    $row['value'] = 'Female';
                                }
                            }                            
                        }
                        if ($row['type'] == 'header') {
                            if($row['label'] == 'ABOUT THE YOUNG PERSON WHO HAD THE ACCIDENT/INCIDENT'){
                                echo '<div class="col-md-6 col-sm-12 ">   
                                <div class="form-margin border-zero for-cl">                                       
                                    <'.$row['subtype'].' class="main-entry-title col-md-12">About the Young Person who had the Accident/Incident </'.$row['subtype'].'>';
                            }
                            if($row['label'] == 'ABOUT THE PERSON REPORTING THE INCIDENT'){
                                echo '</div>
                            </div>
                        <div class="col-md-6 col-sm-12">
                                <div class="form-margin for-cl">  
                                    <h2 class="main-entry-title col-md-12">About the person reporting the Incident </h2>';

                                     echo '<div class="col-md-6 col-sm-12 aai_accident-border"> Report Compiler';
                                if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                                echo '</div>';
                                echo '<div class="col-md-6 col-sm-12 aai_accident-border">';
                                ?>
                                <?php 
                                 $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'mainform');
                                 if(!empty($aai_report_com)){ 
                                  foreach ($aai_report_com as $repcom_value) { ?>
                                    <div class="col-lg-12 p-l-r-0">

                                      <ul class="media-list media-xs">
                                        <li class="media">
                                          <div class="media-body">
                                              <p class="date"><small><?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormatAi($repcom_value['created_date']); ?></small></p>
                                          </div>
                                        </li>
                                      </ul>
                                     </div>
                                  <?php } } ?>                                
                                <?php
                                echo '</div>';  
                                
                                
                                echo '<div class="col-md-6 col-sm-12 aai_accident-border"> Reporting Staff';
                                if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                                echo '</div>'; 
                                echo '<div class="col-md-6 col-sm-12 aai_accident-border">';
                                ?>
                                    <?php if(!empty($bambooNfcUsers)) {
                                        foreach ($bambooNfcUsers as $select) {  ?>

                                        <?php if(isset($reporting_user) && ($reporting_user == $select['user_type'].'_'.$select['user_id'])){

                                    if (!empty($prevedit_entry_form_data) && $pre_reporting_user != $reporting_user) {
                                        $diff = new HtmlDiff('',$select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                    <?php } else { 
                                        echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;
                                         } }
                                         ?>
                                            
                                    <?php } } ?>
                                <?php
                                echo '</div>';
                                
                                                                                          
                            }                            
                        }
                        else if ($row['type'] == 'text' && $row['subtype'] !== 'time') {                            
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border">';
                            if (!empty($prevedit_entry_form_data)) {
                                        $diff = new HtmlDiff(html_entity_decode($prevedit_entry_form_data["$dd"]['value']), html_entity_decode($row['value']));
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else { 
                                            echo nl2br(html_entity_decode($row['value']));
                                    } 
                            echo '</div>';
                        }
                        else if ($row['type'] == 'number') {
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border">';
                            if (!empty($prevedit_entry_form_data)) {
                                        $diff = new HtmlDiff(html_entity_decode($prevedit_entry_form_data["$dd"]['value']), html_entity_decode($row['value']));
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else { 
                                            echo nl2br(html_entity_decode($row['value']));
                                    } 
                            echo '</div>';
                        }
                        else if ($row['type'] == 'date' || $row['subtype'] == 'time') {
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border">';
                            if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])){
                                $row['value'] = timeFormatAi($row['value']);
                            }
                            if($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])){
                                $row['value'] = configDateTime($row['value']);
                            } ?>
                            <div class="<?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date ' : '' ?> ">

                                <?php 
                                if (!empty($prevedit_entry_form_data)) {
                                        $diff = new HtmlDiff(html_entity_decode($prevedit_entry_form_data["$dd"]['value']), html_entity_decode($row['value']));
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
                                     <?php } ?>
                            </div>
                                <?php
                            echo '<span id="errors-'.$row['name'].'"></span>';
                            echo '</div>';
                                                       
                        }
                        else if ($row['type'] == 'select'){
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border">';
                            
                            if (count($row['values']) > 0) {
                                foreach ($row['values'] as $select) {
                                    if (!empty($select['label'])) {

                                    if(isset($row['value']) && ($row['value'] == $select['value'])){
                                        if (!empty($prevedit_entry_form_data) && $prevedit_entry_form_data["$dd"]['value'] != $row['value']) {
                                                            $diff = new HtmlDiff('',$select['label']);
                                                            $diff->build();
                                                            echo $diff->getDifference();
                                                        ?>
                                    <?php } else { ?>
                                        <?=!empty($select['label'])?$select['label']:''?>
                                    <?php } 

                                        }
                                    }
                                }
                            } 
                            echo '<span id="errors-'.$row['name'].'"></span>';
                            echo '</div>';
                        }
                        else if ($row['type'] == 'textarea') {
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border">'; 
                            ?>
                            <?php if (!empty($prevedit_entry_form_data)) {
                                        $diff = new HtmlDiff(html_entity_decode($prevedit_entry_form_data["$dd"]['value']), html_entity_decode($row['value']));
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= (isset($row['value']))? preg_replace('/\n+/', "\n", trim(nl2br(html_entity_decode($row['value'])))):''?> 
                                     <?php } ?>
                             
                            <?php
                            echo '</div>';
                        }                        
                        else if ($row['type'] == 'radio-group') {
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border"> ';
                            echo $row['label'];
                            if(!empty($row['required'])){echo '<span class="astrick">*</span>';}
                            echo ' </div>';
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border">';
                            echo '<div class="form-group">';
                            if (count($row['values']) > 0) {
                                foreach ($row['values'] as $radio) {
                                    if (!empty($radio['label'])) { ?>
                                        <div class="<?= !empty($row['inline']) ? 'radio-inline' : 'radio' ?>">
                                        <label>
                                            <input name="<?= !empty($row['name']) ? $row['name'] : '' ?>" <?= !empty($row['required']) ? 'required=true' : '' ?> 
                                            value="<?= !empty($radio['value']) ? $radio['value'] : '' ?>" 
                                            <?= (isset($row['value']) && $row['value'] == $radio['value']) ? 'checked="checked"':(!isset($row['value']) && !empty($radio['selected'])) ? 'checked="checked"' : '' ?>
                                            class="<?= !empty($row['className']) ? $row['className'] : '' ?>" type="radio">
                                            <?= !empty($radio['label']) ? $radio['label'] : '' ?>
                                        </label>
                                        </div>
                                  <?php  }
                                }
                            }                                                            
                            echo '</div>';
                            echo '</div>';
                        }
                        else if ($row['type'] == 'checkbox-group') {
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border"> ';
                            echo $row['label'];
                            if(!empty($row['required'])){echo '<span class="astrick">*</span>';}
                            echo ' </div>';
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border">';?>
                                
                            <div class="checkbox-group">
                                <?php if(count($row['values']) > 0) {
                                   $checkedValues =array();
                                   if(isset($row['value']) && $row['value'] !== '')
                                   {
                                       $checkedValues = explode(',',$row['value']);
                                   }
                                foreach ($row['values'] as $checkbox) {
                                    if(!empty($checkbox['label'])) {
                                 ?>
                                <div class="<?=!empty($row['inline'])?'checkbox-inline':'checkbox'?>">
                                    <label class="<?=!empty($row['toggle'])?'kc-toggle':''?>"><input 
                                       class="<?=!empty($row['className'])?$row['className']:''?> <?=!empty($row['toggle'])?'kc-toggle':''?>"
                                      name="<?=!empty($row['name'])?$row['name'].'[]':''?>" value="<?=!empty($checkbox['value'])?$checkbox['value']:''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues))?'checked="checked"':!empty($checkbox['selected'] && empty($checkedValues))?'checked="checked"':''?>  
                                           <?=!empty($row['required'])?'required=true':''?>
                                           type="checkbox">
                                    <?=!empty($checkbox['label'])?$checkbox['label']:''?></label>
                                </div>
                               <?php } } } //radio loop ?>
                            </div>
                                <?php  
                                echo '</div>';
                        }                        
                        else if ($row['type'] == 'hidden'){ ?>
                            <div class="col-sm-12">
                                <input type="hidden" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" />
                            </div>
                            <?php 
                        }
                        else if ($row['type'] == 'file') {
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12 aai_accident-border">'; ?>
                                <h2></h2>
                                <?php
                                $fileViewArray = array(
                                    'fileArray' => (isset($row['value']) && !empty($row['value']))? $row['value'] : '',
                                    'filePathMain' => $this->config->item('aai_img_base_url') . $ypId,
                                    'filePathThumb' => $this->config->item('aai_img_base_url_small') . $ypId,
                                    'deleteFileHidden' => 'hidden_'.$row['name']
                                );
                                echo getFileView($fileViewArray); ?>
                            <input type="hidden" name="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" id="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" value="">
                            <?php
                            echo '</div>';                            
                        }                       
                    }  
                    echo'</div>
                        </div>';
                }                    
                ?>
        </div>
    </div>

</div>     
<input type="hidden" name="is_care_incident" id="is_care_incident" value="<?= $isCareIncident ?>">
<input type="hidden" name="care_home_id" id="care_home_id" value="<?= $YP_details[0]['care_home'] ?>">
<input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 