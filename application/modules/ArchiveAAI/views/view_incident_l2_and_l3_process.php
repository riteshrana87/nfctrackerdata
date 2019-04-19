<div class="panel panel-default incident_process">
    <div class="panel-heading aai-page-header" role="tab" id="headingFour">
        <h4 class="panel-title">
            <a id="incident_l2_l3_form_link" class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                INCIDENT PROCESS L2 AND L3 
            </a>
        </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
        <div class="panel-body form-horizontal">
            <div class="row aai-module clear-div-flex">
                <div class="col-md-4 col-sm-6  ">
                    <div class="form-group">
                        <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                        <div class="col-md-12">
                         <?= (isset($l2_report_compiler))? $l2_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>                             
                         <input type="hidden" name="l2_report_compiler" class="form-control" value="<?= (isset($l2_report_compiler))? $l2_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>">
                     </div>  
                 </div>
             </div>                
             <?php 
             if (!empty($l2_form_data)) {
                foreach ($l2_form_data as $row) {
                    $dd=$row['name'];
                    if ($row['type'] == 'textarea') { ?>
                        <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                            <div class="form-group">
                                <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                <div class="col-md-12">
                                    <?php if (!empty($preveditl2Data)) {
                                        $diff = new HtmlDiff(html_entity_decode($preveditl2Data["$dd"]['value']), html_entity_decode($row['value']));
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                    <?php } else { ?>
                                        <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') { ?>
                        <?php if($row['name'] == 'l2_other_injured'){ if($is_other_injured == 1){ ?>
                            <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                <div class="form-group">
                                    <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                    <div class="col-md-12">
                                        <?php if (!empty($preveditl2Data)) {
                                            $diff = new HtmlDiff(html_entity_decode($preveditl2Data["$dd"]['value']), html_entity_decode($row['value']));
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                        <?php } else { ?>
                                            <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php }}else{ ?>
                            <div class="col-md-4 col-sm-6" id="div_<?= $row['name'] ?>">
                                <div class="form-group">
                                    <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                    <div class="col-md-12">
                                        <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                                            if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])){
                                                $row['value'] = timeformat($row['value']);
                                            }
                                            if($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])){
                                                $row['value'] = configDateTime($row['value']);
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                <?php } ?>
                                                <div class="<?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date' : '' ?> ">

                                                    <?php if (!empty($preveditl2Data)) {
                                                        $diff = new HtmlDiff(html_entity_decode($preveditl2Data["$dd"]['value']), html_entity_decode($row['value']));
                                                        $diff->build();
                                                        echo $diff->getDifference();
                                                        ?>
                                                    <?php } else { ?>
                                                        <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
                                                    <?php } ?>
                                                </div>
                                                <span id="errors-container<?= $row['name'] ?>"></span>
                                                <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) { ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php if($row['name'] == 'l2_conclude_time'){ ?>
                                <div class="col-md-4 col-sm-6  ">
                                    <div class="form-group">
                                       <h2 class="aai-header-title col-md-12">Total L2 incident duration </h2>
                                       <div class="col-md-12">
                                        <?php if (!empty($preveditl2Data)) {
                                            $diff = new HtmlDiff($l2_prev_total_duration, $l2_total_duration);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                        <?php } else { ?>
                                            <?= (isset($l2_total_duration)? $l2_total_duration:'') ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($row['name']=='total_time_not_pi'){ ?>
                            <div class="col-sm-12">
                                <div class="form-group">
                                  <h2 class="aai-header-title col-md-12 aai-form-ttle">Medical Observations</h2>
                              </div>
                          </div> 


                          <div class="col-sm-12 clear-div-flex" id="add_medical_observation">
                            <?php if(!empty($l2medical_observations)){ 
                                $med=1;
                                foreach ($l2medical_observations as $key=>$value) { ?>
                                    <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_medical_observation_<?php echo $med; ?>">
                                        <div class="form-group">
                                                    <div class="col-sm-6"><label>Medical observation taken</label></div>
                                                    <div class="col-sm-6">
                                                        <div class="radio-group">
                                                            <div class="radio-inline">
                                                                <label>


                                                                    <?php if(isset($value['l2_medical_observation_taken'.$med]) && $value['l2_medical_observation_taken'.$med] == 'Yes'){ 
                                                                       echo '<label class="radio-inline p-l-r-0"><span class="label label-success">Yes</span></label>';
                                                                   }else if(isset($value['l2_medical_observation_taken'.$med]) && $value['l2_medical_observation_taken'.$med] == 'No'){
                                                                       echo '<label class="radio-inline p-l-r-0"><span class="label label-danger">No</span></label>';
                                                                   }else{
                                                                    echo "-";
                                                                } ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                  </div>
                                         </div>


                                    <div class="form-group">
                                                <div class="col-md-6"><label>Medical observations after xx minutes</label></div>
                                                <div class="col-md-6">
                                                <div class="input-group input-append bootstrap-timepicker">
                                                 <?php if (!empty($l2_prev_medical_observations)) {
                                                    $diff = new HtmlDiff($l2_prev_medical_observations[$key]['l2medical_observations_after_minutes'],$value['l2medical_observations_after_minutes']);
                                                    $diff->build();
                                                    echo $diff->getDifference();
                                                    ?>
                                                <?php } else { ?>
                                                    <?= !empty($value['l2medical_observations_after_minutes']) ? $value['l2medical_observations_after_minutes'] : '' ?>
                                                <?php } ?>
                                            </div>
                                            </div>
                                </div>



                                <div class="form-group">
                                            <div class="col-md-6"><label>Time</label></div>
                                            <div class="col-md-6">
                                            <div class="input-group input-append bootstrap-timepicker">
                                               <?php if (!empty($l2_prev_medical_observations)) {
                                                $diff = new HtmlDiff($l2_prev_medical_observations[$key]['l2time_medical'],$value['l2time_medical']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($value['l2time_medical']) ? $value['l2time_medical'] : '' ?>
                                            <?php } ?>
                                        </div>
                                        </div>
                            </div> 

                            <div class="form-group">
                                        <div class="col-md-6"><label>Observation taken by</label></div>
                                        <div class="col-md-6">

                                            <?php if(!empty($bambooNfcUsers)) {
                                                foreach ($bambooNfcUsers as $select) { 
                                                    if(isset($value['l2Observation_taken_by'.$med][0]) && ($value['l2Observation_taken_by'.$med][0] == $select['user_type'].'_'.$select['user_id'])){ ?>

                                                        <?php if (!empty($l2_prev_medical_observations) && $l2_prev_medical_observations[$key]['l2Observation_taken_by'.$med][0] != $value['l2Observation_taken_by'.$med][0]) {
                                                            $diff = new HtmlDiff('',$select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']);
                                                            $diff->build();
                                                            echo $diff->getDifference();
                                                            ?>
                                                        <?php } else { 
                                                            echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;
                                                            ?>

                                                        <?php } ?>
                                                    <?php } } } ?>

                                                </div> 
                                    </div>

                                    <div class="form-group">
                                                <div class="col-md-6"><label>Comments</label></div>
                                                <div class="col-md-6">

                                                   <?php if (!empty($l2_prev_medical_observations)) {
                                                    $diff = new HtmlDiff($l2_prev_medical_observations[$key]['l2comments_mo'],$value['l2comments_mo']);
                                                    $diff->build();
                                                    echo $diff->getDifference();
                                                    ?>
                                                <?php } else { ?>
                                                    <?= !empty($value['l2comments_mo']) ? $value['l2comments_mo'] : '' ?>
                                                <?php } ?>
                                            </div>
                                </div>
                            </div>
                            <hr>
                            <?php  $med++; }  ?>
                            <script>
                                var xmds = "<?php echo $med;?>";
                            </script>
                        <?php } ?>
                    </div>

                <?php }?>


            <?php } ?>
            <?php
        } else if($row['type'] == 'radio-group') { ?>
            <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                    <div class="form-group">
                            <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                        <div class="col-md-12">
                        <div class="radio-group">
                         <?php if(count($row['values']) > 0) {
                            foreach ($row['values'] as $radio) {
                                if(!empty($radio['label'])) {
                                   ?>
                                   <?php 
                                   if(isset($row['value']) && $row['value'] == $radio['value'] && $row['value'] == 'Yes'){
                                       echo '<label class="radio-inline p-l-r-0"><span class="label label-success">Yes</span></label>';
                                   }else if(isset($row['value']) && $row['value'] == $radio['value'] && $row['value'] == 'No'){
                                       echo '<label class="radio-inline p-l-r-0"><span class="label label-danger">No</span></label>';
                                   }else{
                                    if(!isset($row['value'])){
                                        if(!empty($radio['selected']) && $radio['value'] == 'Yes'){
                                            echo '<label class="radio-inline p-l-r-0"><span class="label label-success">Yes</span></label>';
                                        }else{
                                            echo '<label class="radio-inline p-l-r-0"><span class="label label-danger">No</span></label>';  
                                        }    
                                    }

                                }
                                ?>

                            <?php } } } //radio loop ?>
                        </div>
                     </div>
                    </div>
            </div>

            <?php if ($row['name']=='l2_sanction_required'){ ?>
                <div class="col-sm-12">
                    <div class="form-group">
                        <h2 class="aai-header-title col-md-12 aai-form-ttle">Sequence of events</h2>
                    </div>
                </div> 


                <div class="col-sm-12 clear-div-flex" id="l2add_sequence_of_events">
                    <?php if(!empty($l2sequence_events)){ 
                        $se=1;
                        foreach ($l2sequence_events as $key=>$value) {
                            $seq_datal2 = $value['l2sequence_number'];
                            $seq_datal2 = substr($seq_datal2,1);

                            $seq_datal_pre = $l2seqresult_prev[$key]['l2sequence_number'];
                            $seq_datal_pre = substr($seq_datal_pre,1);
                            ?>
                            <div class="col-md-6 col-sm-12 dynamic-div" id="l2item_new_sequence_<?php echo $se; ?>">
                                <div class="form-group">
                                            <div class="col-md-6"><label>Sequence Number</label></div>
                                            <div class="col-md-6">
                                             <?php echo 'S'.$se;?>
                                         </div> 
                             </div>
                             <div class="form-group">
                                        <div class="col-md-6"><label>Who was involved in Incident </label></div>
                                        <div class="col-md-6 content_text_de">

                                           <?php 
                                           $value_who = $value['l2Who_was_involved_in_incident'.$seq_datal2];
                                           $priv_data = $l2seqresult_prev[$key]['l2Who_was_involved_in_incident'.$l2seqresult_prev];    
                                           ?>

                                           <?php if(!empty($bambooNfcUsers)){
                                            foreach ($bambooNfcUsers as $select){ 
                                                $staff_present_data = array_diff($value_who,$priv_data);

                                                if(in_array($select['user_type'].'_'.$select['user_id'],$staff_present_data)){

                                                    $diff = new HtmlDiff('',$select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'].',');
                                                    $diff->build();
                                                    echo $diff->getDifference();

                                                }else{ 
                                                    if(in_array($select['user_type'].'_'.$select['user_id'],$value_who)){
                                                        echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'].',';
                                                    }
                                                } 
                                            }} ?>  


                                            <?php if(!empty($pre_outside_agency)) {
                                                foreach ($pre_outside_agency as $select) {  
                                                    if((in_array($select['value'],$value_who))){
                                                        $pre_data = array_diff($value_who,$priv_data);
                                                        if(in_array($select['value'],$pre_data)){

                                                            $diff = new HtmlDiff('',$select['label'].',');
                                                            $diff->build();
                                                            echo $diff->getDifference();

                                                        }else{ ?>
                                                            <?=!empty($select['label'])?$select['label'].',':''?>
                                                        <?php } } } } ?>


                                                        <?php if(in_array($l2_involved_other_data,$value_who)){

                                                            $pre_data = array_diff($value_who,$priv_data);
                                                            if(in_array($l2_involved_other_data,$pre_data)){

                                                                $diff = new HtmlDiff('',$l2_involved_other_data.',');
                                                                $diff->build();
                                                                echo $diff->getDifference();

                                                            }else{ ?>
                                                                <?=!empty($l2_involved_other_data)?$l2_involved_other_data.',':''?>
                                                            <?php  } }  ?>

                                                        </div> 
                                                   
                                            </div>

                                            <div class="form-group">
                                                        <div class="col-md-6"><label>Position</label></div>
                                                        <div class="col-md-6 content_text_de">

                                                           <?php if (!empty($position_of_yp)) {
                                                            foreach ($position_of_yp as $select) {
                                                                if (isset($value['l2position']) && ($value['l2position'] == $select['value'])) {
                                                                    if (!empty($l2seqresult_prev) && $l2seqresult_prev[$key]['l2position'] != $value['l2position']) {

                                                                        $diff = new HtmlDiff('', $select['label']);
                                                                        $diff->build();
                                                                        echo $diff->getDifference();
                                                                        ?>
                                                                    <?php } else {?>
                                                                        <?=!empty($select['label']) ? $select['label'] : ''?>
                                                                    <?php }}}}?>

                                                                </div> 
                                                    </div>

                                                    <div class="form-group">
                                                        
                                                                <div class="col-md-6"><label>Type</label></div>
                                                                <div class="col-md-6 content_text_de">
                                                                    <?php if (!empty($type)) {
                                                                        foreach ($type as $select) {
                                                                            if (isset($value['l2type']) && ($value['l2type'] == $select['value'])) {

                                                                                if (!empty($l2seqresult_prev) && $l2seqresult_prev[$key]['l2type'] != $value['l2type']) {
                                                                                    $diff = new HtmlDiff('', $select['label']);
                                                                                    $diff->build();
                                                                                    echo $diff->getDifference();
                                                                                    ?>
                                                                                <?php } else {?>
                                                                                    <?=!empty($select['label']) ? $select['label'] : ''?>
                                                                                <?php }}}}?>

                                                                            </div>
                                                                </div>

                                                                <div class="form-group">
                                                                            <div class="col-md-6"><label>Comments</label></div>
                                                                            <div class="col-md-6 content_text_de">
                                                                                <?php if (!empty($l2seqresult_prev)) {
                                                                                    $diff = new HtmlDiff($l2seqresult_prev[$key]['l2comments'],$value['l2comments']);
                                                                                    $diff->build();
                                                                                    echo $diff->getDifference();
                                                                                    ?>
                                                                                <?php } else { ?>
                                                                                    <?= !empty($value['l2comments']) ? $value['l2comments'] : '' ?>
                                                                                <?php } ?>
                                                                            </div>
                                                                </div>                                    

                                                                <div class="form-group">
                                                                   
                                                                            <div class="col-md-6"><label>Time</label></div>
                                                                            <div class="col-md-6 content_text_de">
                                                                            <div class="input-group input-append bootstrap-timepicker">
                                                                             <?php if (!empty($l2seqresult_prev)) {
                                                                                $diff = new HtmlDiff($l2seqresult_prev[$key]['l2time_sequence'],$value['l2time_sequence']);
                                                                                $diff->build();
                                                                                echo $diff->getDifference();
                                                                                ?>
                                                                            <?php } else { ?>
                                                                                <?= !empty($value['l2time_sequence']) ? $value['l2time_sequence'] : '' ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                        </div>
                                                            </div> 
                                                        </div>
                                                        <hr>
                                                        <?php  $se++; }  ?>
                                                        <script>
                                                            var l2xsu = "<?php echo $se;?>";
                                                        </script>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-md-4 col-sm-6  ">
                                                        <div class="form-group">
                                                            <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                                                            
                                                                <div class="col-md-12">
                                                                    <?= (isset($l7_report_compiler))? $l7_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>                             
                                                                </div>
                                                            
                                                        </div> 
                                                </div>

                                            <?php }?>


                                            <?php
                                        } else if($row['type'] == 'checkbox-group') { ?>
                                            <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                                    <div class="form-group">
                                                        <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                        <div class="col-md-12">
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
                                                                    <label class="<?=!empty($row['toggle'])?'kc-toggle':''?>">
                                                                        <input disabled="" class="<?=!empty($row['className'])?$row['className']:''?> <?=!empty($row['toggle'])?'kc-toggle':''?>"
                                                                        name="<?=!empty($row['name'])?$row['name'].'[]':''?>" value="<?=!empty($checkbox['value'])?$checkbox['value']:''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues))?'checked="checked"':!empty($checkbox['selected'])?'checked="checked"':''?>  
                                                                        <?=!empty($row['required'])?'required=true':''?>
                                                                        type="checkbox">
                                                                        <?=!empty($checkbox['label'])?$checkbox['label']:''?></label>
                                                                    </div>
                                                                <?php } } } //radio loop ?>
                                                            </div>
                                                        </div>
                                                        </div>
                                                </div>
                                                <?php
                                            } else if ($row['type'] == 'select') { ?>
                                                <?php if($row['className'] == 'bamboo_lookup'){ ?>
                                                   <?php  if($row['name'] == 'l2_staff_member_list' || $row['name'] == 'l2_involved_employee'){
                                                    if($is_staff_injured == 1 || $row['name'] == 'l2_involved_employee'){ ?>
                                                        <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                                                <div class="form-group">
                                                                    <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                                    <div class="col-md-12">
                                                                    <?php if(!empty($bambooNfcUsers)) {
                                                                        foreach ($bambooNfcUsers as $select) {  
                                                                            if(isset($row['value']) && ($row['value'] == $select['user_type'].'_'.$select['user_id'])){ ?>

                                                                                <?php if (!empty($preveditl2Data) && $preveditl2Data["$dd"]['value'] != $row['value']) {
                                                                                    $diff = new HtmlDiff('',$select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']);
                                                                                    $diff->build();
                                                                                    echo $diff->getDifference();
                                                                                    ?>
                                                                                <?php } else { 
                                                                                    echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;
                                                                                    ?>
                                                                                <?php } } } } ?>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php }} ?>
                                                            <?php }else if($row['className'] == 'bamboo_lookup_multiple') { ?>
                                                               <?php  if($row['name'] == 'l2_staff_member_list' || $row['name'] == 'l2_involved_employee'){
                                                                if($is_staff_injured == 1 || $row['name'] == 'l2_involved_employee'){ ?>

                                                                    <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                                                            <div class="form-group">
                                                                                <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                                                <div class="col-md-12">
                                                                                <?php if(!empty($bambooNfcUsers)){

                                                                                    foreach ($bambooNfcUsers as $select){ 
                                                                                        $staff_present = array_diff($row['value'],$preveditl2Data["$dd"]['value']);

                                                                                        if(in_array($select['user_type'].'_'.$select['user_id'],$staff_present)){

                                                                                            $diff = new HtmlDiff('',$select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'].',');
                                                                                            $diff->build();
                                                                                            echo $diff->getDifference();

                                                                                        }else{ 
                                                                                            if(in_array($select['user_type'].'_'.$select['user_id'],$row['value'])){
                                                                                                echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'].',';
                                                                                            }
                                                                                        } 
                                                                                    }} ?>  
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php }} ?>
                                                                <?php }else{ ?>
                                                                    <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                                                            <div class="form-group">
                                                                                <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                                                <div class="col-md-12">

                                                                                <?php if(!empty($row['name']) && !empty($row['className']) && $row['className'] == 'multiple'){ ?>

                                                                                    <?php if(count($row['values']) > 0) {
                                                                                        $userAr = array();
                                                                                        foreach ($row['values'] as $select) {  
                                                                                            if((in_array($select['value'],$row['value']))){
                                                                                                $staff_present_data = array_diff($row['value'],$preveditl2Data["$dd"]['value']);
                                                                                                if(in_array($select['value'],$staff_present_data)){

                                                                                                    $diff = new HtmlDiff('',$select['label'].',');
                                                                                                    $diff->build();
                                                                                                    echo $diff->getDifference();

                                                                                                }else{ ?>
                                                                                                    <?=!empty($select['label'])?$select['label'].',':''?>
                                                                                                <?php } } } } ?>

                                                                                            <?php }else{ ?>

                                                                                                <?php if(count($row['values']) > 0) {
                                                                                                   foreach ($row['values'] as $select) {
                                                                                                    if(isset($row['value']) && ($row['value'] == $select['value'])){ ?>
                                                                                                       <?php if (!empty($preveditl2Data) && $preveditl2Data["$dd"]['value'] != $row['value']) {
                                                                                                        $diff = new HtmlDiff('',$select['label']);
                                                                                                        $diff->build();
                                                                                                        echo $diff->getDifference();
                                                                                                        ?>
                                                                                                    <?php } else { ?>
                                                                                                        <?=!empty($select['label'])?$select['label']:''?>
                                                                                                    <?php } } } } } ?>




                                                                                                </div>
                                                                                                </div>
                                                                                        </div>
                                                                                    <?php } ?>

                                                                                    <?php
                                                                                } else if ($row['type'] == 'hidden' || $row['type'] == 'button') { ?>
                                                                                    <?php if ($row['type'] == 'button') { ?>
                                                                                        <div class="col-md-4 col-sm-6  ">
                                                                                                <div class="form-group">

                                                                                                    <div class="col-md-12 fb-button form-group">
                                                                                                        <button name="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" type="<?= !empty($row['type']) ? $row['type'] : '' ?>" class="<?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" style="<?= !empty($row['style']) ? $row['style'] : '' ?>"><?= !empty($row['label']) ? $row['label'] : '' ?></button>
                                                                                                    </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                    <?php if ($row['type'] == 'hidden') { ?>
                                                                                        <div class="col-sm-12">
                                                                                            <input type="hidden" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" />
                                                                                        </div>
                                                                                    <?php } 
                                                                                } else if ($row['type'] == 'header') { ?>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="form-group">
                                                                                            <h2 class="aai-form-ttle col-sm-12"><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } else if($row['type'] == 'file') { ?>
                                                                                    <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                                                                            <div class="form-group">
                                                                                                <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                                                                <h2></h2>
                                                                                                <div class="col-md-12">
                                                                                                <?php
                                                                                                $fileViewArray = array(
                                                                                                    'fileArray' => (isset($row['value']) && !empty($row['value']))? $row['value'] : '',
                                                                                                    'filePathMain' => $this->config->item('aai_img_base_url') . $ypId,
                                                                                                    'filePathThumb' => $this->config->item('aai_img_base_url_small') . $ypId,
                                                                                                    'deleteFileHidden' => 'hidden_'.$row['name']
                                                                                                );
                                                                                                echo getFileView($fileViewArray); ?>
                                                                                                <input type="hidden" name="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" id="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" value="">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } 
                                                                            } //foreach ?>
                                                                        <?php } ?>
                                                                    </div>                            
                                                                </div>
                                                            </div>
                                                        </div>
