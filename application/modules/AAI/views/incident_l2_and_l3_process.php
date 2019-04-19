<div class="panel panel-default incident_process">
    <div class="panel-heading aai-page-header" role="tab" id="headingFour">
        <h4 class="panel-title">
            <a id="incident_l2_l3_form_link" class="text-uppercase" >
                INCIDENT PROCESS L2 AND L3
            </a>
        </h4>
    </div>
   
        <div class="panel-body form-horizontal">
            <div class="row aai-module clear-div-flex">
                <div class="col-md-4 col-sm-6  ">
                    <div class="form-group">
                        <h2 class="aai-header-title col-md-12">Reference ID number <span class="astrick">*</span></h2>
                        <div class="col-md-12">
                            <input type="text" disabled="true" class="form-control" value="<?=(isset($l2reference_number)) ? $l2reference_number : $l2reference_number?>">

                            <input type="hidden" name="l2_l3_reference_number" class="form-control" value="<?=(isset($l2reference_number)) ? $l2reference_number : $l2reference_number?>">
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6  ">
                    <div class="form-group">
                        <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                        <div class="col-md-12">
                            <?php 
                            $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'L2');
                            if(!empty($aai_report_com)){ 
                                foreach ($aai_report_com as $repcom_value) { ?>
                                    <div class="col-lg-12 p-l-r-0">

                                        <ul class="media-list media-xs">
                                            <li class="media">
                                                <div class="media-body">
                                                    <p class="date date-p"><small><?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormat($repcom_value['created_date']); ?></small></p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } } ?>      

                                <?php 
                                $check_user_data = checkUserDetail($incidentData['incident_id'],$l2reference_number,$incidentData['yp_id'],'L2',$loggedInUser['ID']);      
                                if(empty($check_user_data)){
                                    $l2_report_compiler = getUserName($loggedInUser['ID']); ?>
                                    <input type="text" disabled="true" class="form-control" value="<?=(isset($l2_report_compiler)) ? $l2_report_compiler : ''?>">
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (!empty($l2_form_data)) {
                        foreach ($l2_form_data as $row) {

                            if ($row['type'] == 'textarea') {?>
                                <div class="col-md-6 col-sm-12" id="div_<?=$row['name']?>">
                                    <div class="form-group">
                                     <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                                     <div class="col-md-12">
                                        <textarea
                                        class="<?=!empty($row['className']) ? $row['className'] : ''?> <?=(!empty($row['subtype']) && $row['subtype'] == 'tinymce') ? 'tinyeditor' : ''?>"
                                        <?=!empty($row['required']) ? 'required=true' : ''?>
                                        name="<?=!empty($row['name']) ? $row['name'] : ''?>"
                                        placeholder="<?=!empty($row['placeholder']) ? $row['placeholder'] : ''?>"
                                        <?php if ($row['subtype'] != 'tinymce') {?>
                                            <?=!empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : ''?>
                                            <?=!empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : ''?>
                                        <?php }?>
                                        id="<?=!empty($row['name']) ? $row['name'] : ''?>" ><?=(isset($row['value'])) ? nl2br(html_entity_decode($row['value'])) : ''?>
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
                        ?>
                        <div class="col-md-4 col-sm-6  " id="div_<?=$row['name']?>">
                            <div class="form-group">
                             <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                             <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                                if ((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])) {
                                    $row['value'] = timeformat($row['value']);
                                }
                                if ($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])) {
                    $row['value'] = configDateTime($row['value']);
                }
                ?>
                <div class="col-sm-12">
                <?php }?>
                <div class=" col-md-12 <?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : ''?><?=(!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date dob' : ''?> ">
                    <input type="<?=(!empty($row['type']) && $row['type'] == 'number') ? 'number' : ((!empty($row['subtype']) && $row['subtype'] != 'time') ? $row['subtype'] : 'text')?>"
                    class="<?=!empty($row['className']) ? $row['className'] : ''?> <?=($row['type'] == 'date') ? 'aai_adddate' : ''?> <?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? 'aaitime addtime_data' : ''?>"
                    <?=!empty($row['required']) ? 'required=true' : ''?>
                    name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>"
                    <?=!empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : ''?>
                    <?=!empty($row['min']) ? 'min="' . $row['min'] . '"' : ''?>
                    <?=!empty($row['max']) ? 'max="' . $row['max'] . '"' : ''?>
                    <?=!empty($row['step']) ? 'step="' . $row['step'] . '"' : ''?>
                    placeholder="<?=!empty($row['placeholder']) ? $row['placeholder'] : ''?>"
                    value="<?=(isset($row['value'])) ? nl2br(html_entity_decode($row['value'])) : ''?>" <?=($row['type'] == 'date') ? 'readonly' : ''?> data-parsley-errors-container="#errors-container<?=$row['name']?>" <?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? 'readonly' : ''?> />
                    <?php if (!empty($row['subtype']) && $row['subtype'] == 'time') {?>
                        <span class="input-group-addon add-on <?=!empty($row['name']) ? $row['name'] : ''?>"><i class="fa fa-clock-o"></i></span>
                    <?php }?>

                    <?php if (!empty($row['type']) && $row['type'] == 'date') {?>
                        <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                    <?php }?>
                </div>
                <span id="errors-container<?=$row['name']?>"></span>
                <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) {?>
                </div>
            <?php }?>
        </div>
    </div>

    <?php if ($row['name'] == 'l2_conclude_time') { ?>
        <div class="col-md-4 col-sm-6  ">
            <div class="form-group">
             <h2 class="aai-header-title col-md-12">Total Incident Duration </h2>
             <div clss="col-md-12"><input type="text" class="form-control" name="l2_total_duration_main" id="l2_total_duration_main" disabled="true" placeholder="HH:MM" value="<?=(isset($l2_total_duration) ? $l2_total_duration : '')?>" />
                <input type="hidden" class="form-control" name="l2_total_duration" id="l2_total_duration" placeholder="HH:MM" value="<?=(isset($l2_total_duration) ? $l2_total_duration : '')?>" />
            </div>
        </div>
    </div>


<?php }?>

<?php if ($row['name'] == 'total_time_not_pi') {
    ?>
    <div class="col-sm-12">
        <div class="form-group">
            <h2 class="aai-header-title aai-form-ttle col-md-12">Medical Observations</h2>
        </div>
    </div>


    <div class="col-sm-12 clear-div-flex" id="add_medical_observation">
        <?php if (!empty($l2medical_observations)) {
            $med = 1;
            foreach ($l2medical_observations as $value) {
                ?>
                <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_medical_observation_<?php echo $med; ?>">
                    <div class="form-group">
                        <div class="col-md-6"><label>Medical observation taken</label></div>
                        <div class="col-md-6">
                            <div class="radio-group">
                                <div class="radio-inline ">
                                    <label>
                                        <input <?=(isset($value['l2_medical_observation_taken']) && $value['l2_medical_observation_taken'] == 'Yes') ? 'checked="checked"' : ''?> name="l2_medical_observation_taken<?php echo $med; ?>" class="" value="Yes" type="radio" data-parsley-multiple="l2_sanction_required">
                                    Yes</label>
                                </div>
                                <div class="radio-inline">
                                    <label><input <?=(isset($value['l2_medical_observation_taken']) && $value['l2_medical_observation_taken'] == 'No') ? 'checked="checked"' : ''?> name="l2_medical_observation_taken<?php echo $med; ?>" class="" value="No" type="radio" data-parsley-multiple="l2_sanction_required">
                                    No</label>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="form-group">

                        <div class="col-md-6"><label>Medical observations after xx minutes</label></div>
                        <div class="col-md-6 m-t-3"> <div class="input-group input-append bootstrap-timepicker">
                            <input type="text" class="red form-control aaiaddtime addtime_data timer-sty" name="l2medical_observations_after_minutes[]" id="time_safeguard<?php echo $med; ?>" placeholder="" value="<?=!empty($value['medical_observations_after_xx_minutes']) ? $value['medical_observations_after_xx_minutes'] : ''?>" data-parsley-errors-container="#errors-containertime_event" readonly="">
                            <span class="input-group-addon add-on time_safeguard<?php echo $med; ?>"><i class="fa fa-clock-o"></i></span>

                        </div>
                    </div>
                </div>



                <div class="form-group">

                    <div class="col-md-6"><label>Time</label></div>
                    <div class="col-md-6 m-t-3"> <div class="input-group input-append bootstrap-timepicker">
                        <input type="text" class="red form-control aaiaddtime addtime_data timer-sty" name="l2time_medical[]" id="medical_observations_time_safeguard<?php echo $med ?>" placeholder="" value="<?=!empty($value['time']) ? $value['time'] : ''?>" data-parsley-errors-container="#errors-containertime_event" readonly="">
                        <span class="input-group-addon add-on medical_observations_time_safeguard<?php echo $med ?>"><i class="fa fa-clock-o"></i></span>

                    </div>
                </div>
            </div>

            <div class="form-group m-t-3">

                <div class="col-md-6"><label>Observation taken by</label></div>
                <div class="col-md-6">
                    <select data-parsley-errors-container="#errors-1" class="form-control chosen-select" id="1" name="l2Observation_taken_by[]">

                       <option value=''> Select user </option>

                       <?php if (!empty($bambooNfcUsers)) {
                        foreach ($bambooNfcUsers as $select) {?>
                            <option value="<?php echo $select['user_type'] . '_' . $select['user_id']; ?>" <?php if (isset($value['observation_taken_by']) && ($value['observation_taken_by'] == $select['user_type'] . '_' . $select['user_id'])) {echo 'selected="true"';}?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']; ?> </option>
                        <?php }}?>

                    </select>


                </div>
            </div>

            <div class="form-group">

                <div class="col-md-6"><label>Comments</label></div>
                <div class="col-md-6 m-t-3">
                    <textarea id="what_happned" class="form-control timer-sty"  placeholder="Daily action outcome" name="l2comments_mo[]"><?=!empty($value['comments']) ? $value['comments'] : ''?></textarea>
                </div>
            </div>

            <div class="col-md-12 add_items_field mb44 del-btn-form">
                <a class="btn btn-default btn_border">
                    <span class="glyphicon glyphicon-trash" onclick="delete_medical_observation('item_new_medical_observation_<?php echo $med; ?>');"></span>
                </a>
            </div>
        </div>

        <?php $med++;}?>
        <script>
            var xmds = "<?php echo $med; ?>";
        </script>
    <?php }?>
</div>
<div class="col-sm-12 section_seven text-center mb30 col-md-12">
    <input type="hidden" id="delete_safeguard_review_id" name="delete_safeguard_review_id" value="">
    <a id="l2add_new_medical_observations" class="btn btn-default updat_bn" href="javascript:;">
        <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Medical Observations
    </a>
</div>


<?php }?>



<?php
} else if ($row['type'] == 'radio-group') {
    ?>
    <div class="col-sm-12 radio-select-btn" id="div_<?=$row['name']?>">
        <div class="form-group">
         <h2 class="aai-header-title-radio col-md-4 col-sm-6  "><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
         <div class="col-md-6">

            <div class="radio-group">
                <?php if (count($row['values']) > 0) {
                    foreach ($row['values'] as $radio) {
                        if (!empty($radio['label'])) {
                            ?>
                            <div class="<?=!empty($row['inline']) ? 'radio-inline' : 'radio-inline'?>">
                                <label>

                                 <?php
                                 if ($row['name'] == 'l2_was_the_yp_injured') {
                                    if ($is_yp_injured == 1) {
                                        $row['value'] = 'Yes';
                                    } else {
                                        $row['value'] = 'No';
                                    }
                                } elseif ($row['name'] == 'l2_is_the_yp_making_complaint') {
                                    if ($is_yp_complaint == 1) {
                                        $row['value'] = 'Yes';
                                    } else {
                                        $row['value'] = 'No';
                                    }
                                } elseif ($row['name'] == 'l2_was_staff_member_injured') {
                                    if ($is_staff_injured == 1) {
                                        $row['value'] = 'Yes';
                                    } else {
                                        $row['value'] = 'No';
                                    }
                                } elseif ($row['name'] == 'l2_was_anyone_else_injured') {
                                    if ($is_other_injured == 1) {
                                        $row['value'] = 'Yes';
                                    } else {
                                        $row['value'] = 'No';
                                    }
                                }

                                if (isset($row['value']) && $row['value'] == $radio['value']) {
                                    $checked = 'checked="checked"';
                                } elseif (!isset($row['value']) && !empty($radio['selected'])) {
                                    $checked = 'checked="checked"';
                                } else {
                                    $checked = '';
                                }
                                ?>


                                <input name="<?=!empty($row['name']) ? $row['name'] : ''?>" <?=!empty($row['required']) ? 'required=true' : ''?>
                                class="<?=!empty($row['className']) ? $row['className'] : ''?>"
                                value="<?=!empty($radio['value']) ? $radio['value'] : ''?>" <?php echo $checked; ?> type="radio">
                                <?=!empty($radio['label']) ? $radio['label'] : ''?></label>
                            </div>
                        <?php }}} //radio loop ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($row['name'] == 'l2_sanction_required') {
            ?>
            <div class="col-sm-12">
                <div class="form-group">
                  <h2 class="aai-header-title aai-form-ttle col-md-12">Sequence of events</h2>
              </div>
          </div>


          <div class="col-sm-12 clear-div-flex" id="l2add_sequence_of_events">
            <?php if (!empty($l2sequence_events)) {
                $se = 1;
                foreach ($l2sequence_events as $value) { 
                   $incident_id = $value['incident_id'];
                   $l2_l3_sequence_event_id = $value['l2_l3_sequence_event_id'];
                   ?>
                   <div class="col-md-6 col-sm-12 dynamic-div" id="l2item_new_sequence_<?php echo $se; ?>">
                    <div class="form-group">
                        <input type="hidden" name="sqpid" id="sqpid<?php echo $se; ?>" value="<?=$value['l2_l3_sequence_event_id'];?>">
                        <div class="col-md-6"><label class="sds">Sequence Number</label></div>
                        <div class="col-md-6">
                        <input name="l2sequence_number[]" class="red form-control input-textar-style" value="<?php echo 'S'.$se?>" type="text" />
                        
                    </div>

                </div>
                <div class="form-group m-t-3">

                    <div class="col-md-6"><label>Who was involved in Incident</label></div>
                    <div class="col-md-6">
                        <select multiple data-parsley-errors-container="#errors-1" class="form-control chosen-select l2Who_was_involved_in_incident<?php echo $se; ?> cred" id="1" name="l2Who_was_involved_in_incident<?php echo $se; ?>[]">
                           <option value=''> Select user </option>

                           <?php if (!empty($bambooNfcUsers)) {
                            foreach ($bambooNfcUsers as $select) { ?>
                                <option class="mydd" value="<?php echo $select['user_type'] . '_' . $select['user_id']; ?>" <?=(in_array($select['user_type'] . '_' . $select['user_id'],$value_who) ? 'selected' : '')?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']; ?>
                            </option>
                        <?php }}?>

                        <?php if (!empty($pre_outside_agency)) {
                            foreach ($pre_outside_agency as $select) {?>
                                <option class="mydd" value="<?php echo $select['value']; ?>" <?=(in_array($select['value'], $value_who) ? 'selected' : '')?>> <?php echo $select['label']; ?>
                            </option>
                        <?php }}?>

                        <?php
                        if ($row['type'] == 'text') {
                            if ($row['name'] == 'l2_involved_other') {?>
                               <option class="mydd" value="<?php echo $row['value']; ?>" <?=(in_array($row['value'], $value_who) ? 'selected' : '')?>> <?php echo $row['value']; ?>
                           </option>
                       <?php }}?>




                   </select>



               </div>
           </div>

                   <div class="form-group m-t-3">

                    <div class="col-md-6"><label>Position</label></div>
                    <div class="col-md-6">
                        <select data-parsley-errors-container="#errors-1" class="form-control chosen-select" id="1" name="l2position[]">
                           <option value=''> Select Position </option>

                           <?php if (!empty($position_of_yp)) {
                            foreach ($position_of_yp as $select) { ?>
                                <option value="<?php echo $select['value']; ?>" <?php if (isset($value['position']) && ($value['position'] == $select['value'])) {echo 'selected="true"';}?>> <?php echo $select['label']; ?> </option>
                            <?php }}?>

                        </select>

                    </div>

                </div>

                <div class="form-group m-t-3">

                    <div class="col-md-6"><label>Type</label></div>
                    <div class="col-md-6">
                        <select data-parsley-errors-container="#errors-1" class="form-control chosen-select" id="1" name="l2type[]">
                            <option value=''> Select type </option>
                            <?php if (!empty($type)) {
                                foreach ($type as $select) {?>
                                    <option value="<?php echo $select['value']; ?>" <?php if (isset($value['type']) && ($value['type'] == $select['value'])) {echo 'selected="true"';}?>> <?php echo $select['label']; ?> </option>
                                <?php }}?>



                            </select>


                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-md-6"><label>Comments</label></div>
                        <div class="col-md-6 m-t-3">
                            <textarea id="what_happned" class="form-control timer-sty"  placeholder="Comments" name="l2comments[]"><?=!empty($value['comments']) ? $value['comments'] : ''?></textarea>
                        </div>

                    </div>

                    <div class="form-group">

                        <div class="col-md-6"><label>Time</label></div>
                        <div class="col-md-6 m-t-3"> <div class="input-group input-append bootstrap-timepicker">
                            <input type="text" class="red form-control aaiaddtime addtime_data timer-sty" name="l2time_sequence[]" id="l2time_sequence_data<?php echo $se; ?>" placeholder="" value="<?=!empty($value['time']) ? $value['time'] : ''?>" data-parsley-errors-container="#errors-containertime_event" readonly="">
                            <span class="input-group-addon add-on l2time_sequence_data<?php echo $se; ?>"><i class="fa fa-clock-o"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 add_items_field mb44 del-btn-form">
                    <a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash"
                       onclick="delete_sequence_row('l2item_new_sequence_<?php echo $se; ?>')"></span></a>
                   </div>

                   
               </div>

               <?php $se++;}?>
               <script>
                var l2xsu = "<?php echo $se; ?>";
            </script>
        <?php }?>
    </div>
                                            <div class="clearfix"></div>
                                            <div class=" section_seven text-center mb30 col-md-12">
                                                <input type="hidden" id="delete_safeguard_review_id" name="delete_safeguard_review_id" value="">

                                                <a id="l2add_new_sequence_of_events_updates" class="btn btn-default updat_bn" href="javascript:;">
                                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add Sequence of events
                                                </a>
                                            </div>
                                            <div class="col-md-4 col-sm-6  ">
                                                <div class="form-group">
                                                    <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>

                                                    <div class="col-md-12">
                                                        <input type="text" disabled="true" class="form-control" value="<?=(isset($l7_report_compiler)) ? $l7_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
                                                        <input type="hidden" name="l7_report_compiler_safeguarding_Outcome" class="form-control" value="<?=(isset($l7_report_compiler)) ? $l7_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
                                                    </div>
                                                </div>
                                            </div>

                                        <?php }?>


                                        <?php
                                    } else if ($row['type'] == 'checkbox-group') {
                                        ?>
                                        <div class="col-sm-12   " id="div_<?=$row['name']?>">
                                            <div class="form-group mb16">
                                             <h2 class="aai-header-title-radio aai-header-title-checkbox col-md-2"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                                             <div class="col-md-10 check-box-aai">
                                                <div class="checkbox-group">
                                                    <?php if (count($row['values']) > 0) {
                                                        $checkedValues = array();
                                                        if (isset($row['value']) && $row['value'] !== '') {
                                                            $checkedValues = explode(',', $row['value']);
                                                        }
                                                        foreach ($row['values'] as $checkbox) {
                                                            if (!empty($checkbox['label'])) {
                                                                ?>
                                                                <div class="<?=!empty($row['inline']) ? 'checkbox-inline' : 'checkbox'?>">
                                                                    <label class="<?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"><input
                                                                     class="<?=!empty($row['className']) ? $row['className'] : ''?> <?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"
                                                                     name="<?=!empty($row['name']) ? $row['name'] . '[]' : ''?>" value="<?=!empty($checkbox['value']) ? $checkbox['value'] : ''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues)) ? 'checked="checked"' : !empty($checkbox['selected']) ? 'checked="checked"' : ''?>
                                                                     <?=!empty($row['required']) ? 'required=true' : ''?>
                                                                     type="checkbox">
                                                                     <?=!empty($checkbox['label']) ? $checkbox['label'] : ''?></label>
                                                                 </div>
                                                             <?php }}} //radio loop ?>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                             <?php
                                         } else if ($row['type'] == 'select') {
                                            ?>

                                    <?php if ($row['className'] == 'bamboo_lookup') {
                                        ?>
                                        <div class="col-md-4 col-sm-6  " id="div_<?=$row['name']?>">
                                            <div class="form-group">
                                             <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                                             <div class="col-md-12">
                                                <select data-parsley-errors-container="#errors-<?=$row['name']?>" class='form-control chosen-select' id='<?=$row['name']?>' name='<?=$row['name']?>'>
                                                    <option value=''> Select user </option>
                                                    <?php if (!empty($bambooNfcUsers)) {
                                                        foreach ($bambooNfcUsers as $select) {?>
                                                            <option value="<?php echo $select['user_type'] . '_' . $select['user_id']; ?>" <?php if (isset($row['value']) && ($row['value'] == $select['user_type'] . '_' . $select['user_id'])) {echo 'selected="true"';}?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']; ?> </option>
                                                        <?php }}?>
                                                    </select>
                                                    <span id="errors-<?=$row['name']?>"></span>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } else if ($row['className'] == 'bamboo_lookup_multiple') {
                                        ?>
                                        <div class="col-md-4 col-sm-6  " id="div_<?=$row['name']?>">
                                            <div class="form-group">
                                                <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                                                <div class="col-md-12">
                                                    <select <?=(!empty($row['className']) && $row['className'] == 'bamboo_lookup_multiple') ? 'multiple' : ''?> data-parsley-errors-container="#errors-<?=$row['name']?>" class='form-control chosen-select' id='<?=$row['name']?>' name='<?=(!empty($row['name']) && (!empty($row['className']))) ? $row['name'] . '[]' : ''?>'>
                                                        <option value=''> Select user </option>
                                                        <?php if (!empty($bambooNfcUsers)) {
                                                            $userAr = array();
                                                            foreach ($bambooNfcUsers as $select) { ?>
                                                                <option value="<?php echo $select['user_type'] . '_' . $select['user_id']; ?>" <?=(@in_array($select['user_type'] . '_' . $select['user_id'], explode(',', $row['value'])) ? 'selected' : '')?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']; ?>
                                                            </option>
                                                        <?php }}?>
                                                    </select>
                                                    <span id="errors-<?=$row['name']?>"></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else {
                                        ?>
                                        <div class="col-md-4 col-sm-6  " id="div_<?=$row['name']?>">
                                            <div class="form-group">
                                             <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                                             <div class="col-md-12">
                                                <?php if (!empty($row['name']) && !empty($row['className']) && $row['className'] == 'multiple') {
                                                    ?>
                                                    <select multiple <?=(!empty($row['description']) && $row['description'] == 'bamboo_lookup_multiple') ? 'multiple' : ''?> data-parsley-errors-container="#errors-<?=$row['name']?>" class='form-control chosen-select' id='<?=$row['name']?>' name='<?=(!empty($row['name']) && (!empty($row['description']))) ? $row['name'] . '[]' : ''?>'>
                                                        <option value=''> Select </option>
                                                        <?php if (count($row['values']) > 0) {
                                                            $userAr = array();
                                                            foreach ($row['values'] as $select) {?>
                                                                <option id="<?=!empty($select['value']) ? $select['value'] : ''?>" data-detail="<?=!empty($select['label']) ? $select['label'] : ''?>" value="<?=!empty($select['value']) ? $select['value'] : ''?>" <?=(in_array($select['value'], explode(',', $row['value'])) ? 'selected' : '')?>><?=!empty($select['label']) ? $select['label'] : ''?>
                                                            </option>
                                                        <?php }}?>
                                                    </select>
                                                <?php } else { ?>
                                                    <select data-parsley-errors-container="#errors-<?=$row['name']?>" class="chosen-select <?=!empty($row['className']) ? $row['className'] : ''?>" name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>" <?=!empty($row['required']) ? 'required=true' : ''?>>
                                                       <option value="">Select</option>
                                                       <?php if (count($row['values']) > 0) {
                                                        foreach ($row['values'] as $select) {
                                                            if (!empty($select['label'])) {
                                                                ?>
                                                                <option id="<?=!empty($select['value']) ? $select['value'] : ''?>" data-detail="<?=!empty($select['label']) ? $select['label'] : ''?>" value="<?=!empty($select['value']) ? $select['value'] : ''?>" <?php if (isset($row['value']) && ($row['value'] == $select['value'])) {echo 'selected="true"';}?> ><?=!empty($select['label']) ? $select['label'] : ''?></option>
                                                            <?php }}} //select loop ?>
                                                        </select>
                                                    <?php }?>


                                                    <span id="errors-<?=$row['name']?>"></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>

                                            <?php
                                        } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {
                                            ?>
                                            <?php if ($row['type'] == 'button') {?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <div class="col-md-12 fb-button form-group">
                                                                <button name="<?=!empty($row['name']) ? $row['name'] : ''?>" value="" type="<?=!empty($row['type']) ? $row['type'] : ''?>" class="<?=!empty($row['className']) ? $row['className'] : ''?>" name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>" style="<?=!empty($row['style']) ? $row['style'] : ''?>"><?=!empty($row['label']) ? $row['label'] : ''?></button>
                                                            </div>
                                                        </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($row['type'] == 'hidden') { ?>
                                                <div class="col-md-6">
                                                    <input type="hidden" name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>" value="" />
                                                </div>
                                            <?php }
                                        } else if ($row['type'] == 'header') { ?>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <h2 class="aai-form-ttle col-sm-12"><?=!empty($row['label']) ? $row['label'] : ''?></h2>
                                                </div>
                                            </div>
                                        <?php } else if ($row['type'] == 'file') {
                                            ?>
                                            <div class="col-md-6 col-sm-12" id="div_<?=$row['name']?>">
                                                <div class="panel panel-default tile tile-profile">
                                                    <div class="panel-body">
                                                        <h2 class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                                                        <input type="file" name="<?=!empty($row['name']) ? $row['name'] . '[]' : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>"  class="<?=!empty($row['className']) ? $row['className'] : ''?>"
                                                        <?=!empty($row['multiple']) ? 'multiple="true"' : ''?> <?=!empty($row['required']) ? 'required=true' : ''?>>
                                                        <h2></h2>
                                                        <?php
                                                        $fileViewArray = array(
                                                            'fileArray'        => (isset($row['value']) && !empty($row['value'])) ? $row['value'] : '',
                                                            'filePathMain'     => $this->config->item('aai_img_base_url') . $ypId,
                                                            'filePathThumb'    => $this->config->item('aai_img_base_url_small') . $ypId,
                                                            'deleteFileHidden' => 'hidden_' . $row['name'],
                                                        );
                                                        echo getFileView($fileViewArray);?>
                                                        <input type="hidden" name="<?=!empty($row['name']) ? 'hidden_' . $row['name'] : ''?>" id="<?=!empty($row['name']) ? 'hidden_' . $row['name'] : ''?>" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                    } //foreach ?>

                                    <div class="col-xs-12">
                                        <div class="pull-right btn-section">
                                            <div class="btn-group">
                                             <button type="submit" class="btn btn-default" name="draft_wrform" id="draft_wrform" style="default" onclick="$('#saveAsDraftL2').val(1);">Save as Draft</button>
                                             <button type="submit" class="btn btn-default" name="submit" id="submit3" value="submit" style="default">Completed</button>
                                         </div>
                                     </div>
                                 </div>
                             <?php }?>
                         </div>
                     </div>
                 </div>
