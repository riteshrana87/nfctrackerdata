<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
            <a id="main_form_link" class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                MAIN ENTRY FORM
            </a>
        </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body form-margin"> 
                            <?php if(isset($editMode) && $editMode>0){ ?>
                            <div class="col-md-6 col-sm-12 left_line"> Type of incident </div>
                            <div class="col-md-6 col-sm-12 left_line" class="form-group">
                                <?php 
                                if($isCareIncident == 2){
                                    $checkEdu1 = 'checked';
                                    $checkCare2 = '';
                                }else{
                                    $checkCare2 = 'checked';
                                    $checkEdu1 = '';
                                }
                                ?>
                                <label><input type="radio" name="educarecheck" value="2"  value="Education" <?= $checkEdu1 ?>> Education incident</label>&nbsp;&nbsp;
                                <label><input type="radio" name="educarecheck" value="1"  value="Care" <?= $checkCare2 ?>> Care incident</label>&nbsp;&nbsp;
                            </div> 
                            <?php } ?>
                            <div class="col-md-6 col-sm-12 left_line"> Is the incident related to any other recorded incident? </div>
                            <div class="col-md-6 col-sm-12 left_line" class="form-group">
                                <?php
                                $relatedIncidentArray = explode(',', $relatedIncident);
                                if(!isset($createMode) && count($relatedIncidentArray)>0 && $relatedIncident !== ''){
                                    $checkIsLinked1 = 'checked';
                                    $checkIsLinked2 = '';
                                }else{
                                    $checkIsLinked2 = 'checked';
                                    $checkIsLinked1 = '';
                                }
                                ?>
                                <label><input type="radio" name="is_linked" onchange="check_incident_linked()" id="yescheck" value="1" <?= $checkIsLinked1 ?>> YES</label>&nbsp;&nbsp;
                                <label><input type="radio" name="is_linked" onchange="check_incident_linked()" id="nocheck" value="0" <?= $checkIsLinked2 ?>> NO</label>&nbsp;&nbsp;
                            </div> 
                            
                            <div class="row" id="yp_incidents">
                                <div class="col-xs-12 m-t-10">
                                    <div class="table-responsive">
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
                                                <?php foreach ($YPIncidentData as $incidentDetail) { ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="incidentCheckbox" onclick="incident_check_list()" value="<?php echo $incidentDetail['incident_id']; ?>" <?= (in_array($incidentDetail['incident_id'], $relatedIncidentArray))? 'checked':'' ?>>
                                                        </td>
                                                        <td><?php echo $incidentDetail['reference_number']; ?></td>
                                                        <td><?php echo ($incidentDetail['is_care_incident'] == 1)?'care incident':'education incident'; ?></td>
                                                        <td><?= !empty($incidentDetail['care_home_name']) ? $incidentDetail['care_home_name'] : lang('NA') ?></td>
                                                        
                                                    </tr>
                                                <?php } ?>
                                            <input type="hidden" name="related_incident" id="related_incident" value="<?= (isset($relatedIncident))? $relatedIncident:'' ?>">
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
                              </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row aai-module">                
                <?php                 
                if (!empty($entry_form_data)) {
                    foreach ($entry_form_data as $key => $row) {
                        if(isset($createMode) && $createMode == 1){
                            /*if($row['name'] == 'reference_id_number'){
                                $row['value'] = $yp_details[0]['yp_initials'].date('dmy');
                            }*/
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
                            $entry_report_compiler = $loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'];
                        }
                        if ($row['type'] == 'header') {
                            if($row['label'] == 'ABOUT THE USER WHO HAD ACCIDENT/INCIDENT'){
                                echo '<div class="col-md-6 col-sm-12 aai_accident-border">                                    
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body form-margin">                                       
                                    <'.$row['subtype'].' class="modal-title">About the User who had Accident/Incident </'.$row['subtype'].'>';
                            }
                            if($row['label'] == 'ABOUT THE PERSON WHO REPORTING INCIDENT'){
                                echo '</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body form-margin">  
                                    <h2 class="modal-title">About the person who reporting Incident </h2>';
                                
                                echo '<div class="col-md-6 col-sm-12"> Select User';
                                if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                                echo '</div>'; 
                                echo '<div class="col-md-6 col-sm-12">';
                                ?>
                                <select class='form-control chosen-select' id='reporting_user' name='reporting_user' onchange="change_user(this)">
                                    <option value=''> Select user </option>
                                    <?php if(!empty($bambooNfcUsers)) {
                                        foreach ($bambooNfcUsers as $select) {  ?>
                                            <option 
                                                id="<?php echo $select['user_type'].'_'.$select['user_id'];?>"
                                                data-fname="<?php echo $select['first_name'];?>" 
                                                data-lname="<?php echo $select['last_name'];?>" 
                                                data-email="<?php echo $select['email'];?>" 
                                                data-jobtitle="<?php echo $select['job_title'];?>" 
                                                data-location="<?php echo $select['work_location'];?>" 
                                                value="<?php echo $select['user_type'].'_'.$select['user_id'];?>"> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;?>
                                            </option>
                                    <?php } } ?>
                                </select>
                                <?php
                                echo '</div>';
                                
                                echo '<div class="col-md-6 col-sm-12"> Report Compiler';
                                if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                                echo '</div>';
                                echo '<div class="col-md-6 col-sm-12">';
                                ?>
                                <input type="text" disabled="true" class="form-control" value="<?= $entry_report_compiler ?>">                                
                                <input type="hidden" name="report_compiler" class="form-control" value="<?= $entry_report_compiler ?>">                                
                                <?php
                                echo '</div>';                                                             
                            }                            
                        }
                        else if ($row['type'] == 'text' && $row['subtype'] !== 'time') {                            
                            echo '<div class="col-md-6 col-sm-12"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12">';
                            echo '<input type="'.$row['subtype'].'"';
                            if($row['name'] == 'yp_contact'){echo ' minlength="5" maxlength="15" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-pattern-message="Please enter only numbers." data-parsley-trigger="keyup"';}
                            if($row['name'] == 'yp_fname' || $row['name'] == 'yp_surname' || $row['name'] == 'reporter_fname' || $row['name'] == 'reporter_surname') {
                                echo ' data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="50" data-parsley-trigger="keyup"';
                            }
                            if(!empty($row['className'])){echo " class='form-control ".$row['className']."'";}
                            if(!empty($row['required'])){echo " required=true";}
                            if(!empty($row['name'])){echo " name='".$row['name']."'";}
                            if(!empty($row['name'])){echo " id='".$row['name']."'";}
                            if(!empty($row['maxlength'])){echo " maxlength='".$row['maxlength']."'";}                                              
                            if(!empty($row['placeholder'])){echo " placeholder='".$row['placeholder']."'";}
                            if(isset($row['value'])){
                                echo "value='".$row['value']."'";                                
                            }
                            echo '>'; 
                            echo '</div>';
                        }
                        else if ($row['type'] == 'number') {
                            echo '<div class="col-md-6 col-sm-12"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12">';
                            echo '<input type="number"';
                            if(!empty($row['className'])){echo " class='form-control ".$row['className']."'";}
                            if(!empty($row['required'])){echo " required=true";}
                            if(!empty($row['name'])){echo " name='".$row['name']."'";}
                            if(!empty($row['name'])){echo " id='".$row['name']."'";}
                            if(!empty($row['min'])){echo " min='".$row['min']."'";}                                              
                            if(!empty($row['max'])){echo " max='".$row['max']."'";}                                              
                            if(!empty($row['step'])){echo " step='".$row['step']."'";}                                              
                            if(!empty($row['placeholder'])){echo " placeholder='".$row['placeholder']."'";}
                            if(isset($row['value'])){
                                echo "value='".$row['value']."'";                                
                            }
                            echo '>'; 
                            echo '</div>';
                        }
                        else if ($row['type'] == 'date' || $row['subtype'] == 'time') {
                            echo '<div class="col-md-6 col-sm-12"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12">';
                            /*if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])){
                                $row['value'] = timeformat($row['value']);
                            }
                            if($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])){
                                $row['value'] = configDateTime($row['value']);
                            }*/ ?>
                            <div class="<?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date ' : '' ?> ">
                                <input type="<?= (!empty($row['type']) && $row['type'] == 'number') ? 'number' : ((!empty($row['subtype']) && $row['subtype'] != 'time') ? $row['subtype'] : 'text') ?>"  
                                       class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'addtime' : '' ?> addtime_data"  
                                       <?= !empty($row['required']) ? 'required=true' : '' ?>
                                       name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                       <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                       <?= !empty($row['min']) ? 'min="' . $row['min'] . '"' : '' ?>
                                       <?= !empty($row['max']) ? 'max="' . $row['max'] . '"' : '' ?>
                                       <?= !empty($row['step']) ? 'step="' . $row['step'] . '"' : '' ?>
                                       placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                       value="<?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>" <?= ($row['type'] == 'date') ? 'readonly' : '' ?> data-parsley-errors-container="#errors-container<?= $row['name'] ?>" <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'readonly' : '' ?> />
                                       <?php if (!empty($row['subtype']) && $row['subtype'] == 'time') { ?>
                                    <span class="input-group-addon add-on <?= !empty($row['name']) ? $row['name'] : '' ?>"><i class="fa fa-clock-o"></i></span>
                                <?php } ?>

                                <?php if (!empty($row['type']) && $row['type'] == 'date') { ?>
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                <?php } ?>
                            </div>
                                <?php
                            echo '<span id="errors-'.$row['name'].'"></span>';
                            echo '</div>';
                                                       
                        }
                        else if ($row['type'] == 'select'){
                            echo '<div class="col-md-6 col-sm-12"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12">';
                            echo '<select width="100%" data-parsley-errors-container="#errors-'.$row['name'].'" class="chosen-select ';
                            if(!empty($row['className'])){echo $row['className'];}
                            echo '"';
                            if(!empty($row['name'])){echo " name='".$row['name']."'";} 
                            if(!empty($row['name'])){echo " id='".$row['name']."'";}                             
                            if(!empty($row['required'])){ echo 'required=true';}
                            echo '>';
                            echo '<option value="">Select</option>';
                            if (count($row['values']) > 0) {
                                foreach ($row['values'] as $select) {
                                    if (!empty($select['label'])) {
                                        echo '<option value="';
                                        if(isset($select['value'])){echo $select['value'];}
                                        echo '"';
                                        if(isset($row['value']) && ($row['value'] == $select['value'])){echo 'selected="true"';}                                        
                                        echo '>';
                                        if(!empty($select['label'])){echo $select['label'];}
                                        echo '</option>';
                                    }
                                }
                            }
                            echo '</select>';
                            echo '<span id="errors-'.$row['name'].'"></span>';
                            echo '</div>';
                        }
                        else if ($row['type'] == 'textarea') {
                            echo '<div class="col-md-6 col-sm-12"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12">'; ?>
                                <textarea  class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'tinymce') ? 'tinyeditor' : '' ?>" 
                                <?= !empty($row['required']) ? 'required=true' : '' ?>
                                name="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                <?php if ($row['subtype'] != 'tinymce') { ?>
                                    <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                    <?= !empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : '' ?>
                                <?php } ?>
                                id="<?= !empty($row['name']) ? $row['name'] : '' ?>"><?= (isset($row['value']))? preg_replace('/\n+/', "\n", trim(nl2br(html_entity_decode($row['value'])))):''?></textarea>
                             
                            <?php
                            echo '</div>';
                        }                        
                        else if ($row['type'] == 'radio-group') {
                            echo '<div class="col-md-6 col-sm-12"> ';
                            echo $row['label'];
                            if(!empty($row['required'])){echo '<span class="astrick">*</span>';}
                            echo ' </div>';
                            echo '<div class="col-md-6 col-sm-12">';
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
                            echo '<div class="col-md-6 col-sm-12"> ';
                            echo $row['label'];
                            if(!empty($row['required'])){echo '<span class="astrick">*</span>';}
                            echo ' </div>';
                            echo '<div class="col-md-6 col-sm-12">';?>
                                
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
                            echo '<div class="col-md-6 col-sm-12"> '.$row['label'];
                            if(isset($row['required']) && $row['required']==1){echo '<span class="astrick">*</span>';}
                            echo '</div>';
                            echo '<div class="col-md-6 col-sm-12">'; ?>
                            <input type="file" name="<?=!empty($row['name'])?$row['name'].'[]':''?>" id="<?=!empty($row['name'])?$row['name']:''?>"  class="<?=!empty($row['className'])?$row['className']:''?>" 
                                <?=!empty($row['multiple'])?'multiple="true"':''?> <?= !empty($row['required']) ? 'required=true' : '' ?>>
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
                            </div>
                        </div>    
                        <div class="col-xs-12">
                    <div class="pull-right btn-section">
                        <div class="btn-group">
                            <button type="button" onclick="location.reload()" class="btn btn-default" name="Cancel" value="Cancel" style="default">Cancel</button>
                            <button type="submit" class="btn btn-default" name="submit" id="submit" value="submit" style="default">Continue</button> 
                        </div>
                    </div>
                </div>';
                }                    
                ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="is_care_incident" id="is_care_incident" value="<?= $isCareIncident ?>">
<input type="hidden" name="care_home_id" id="care_home_id" value="<?= $YP_details[0]['care_home'] ?>">
<input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 