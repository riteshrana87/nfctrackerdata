<div class="panel panel-default incident_process">
  <div class="panel-heading aai-page-header" role="tab" id="headingFive">
    <h4 class="panel-title">
        INCIDENT L4 PROCESS (YP MISSING FROM CARE)
    </h4>
  </div>
  
  <div class="panel-body form-horizontal">
    <div class="row aai-module clear-div-flex">
      <div class="col-md-4 col-sm-6  ">
          <div class="form-group">
            <h2 class="aai-header-title col-md-12">Reference ID number <span class="astrick">*</span></h2>
              <div class="col-md-12">
                <input type="text" disabled="true" class="form-control" value="<?= $l4reference_number ?>">

                <input type="hidden" name="l4_reference_number" class="form-control" value="<?= $l4reference_number ?>">
              </div>                                    
            </div>
      </div>

      <?php
      if (!empty($l4_form_data)) {
        foreach ($l4_form_data as $row) {
            $dd = $row['name'];
          if ($row['type'] == 'textarea') {
            ?>
            <div class="col-md-4 col-sm-12  " id="div_<?php echo $row['name']; ?>">
                <div class="form-group">
                  <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                  <div class="col-md-12">
                    <?=!empty($l4_form_data[0][$row['name']]) ? nl2br(html_entity_decode($l4_form_data[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '')?>
                  </div>
                </div>
            </div>

            <?php if ($row['name'] == 'state') {?>

              <div class="col-sm-12">
                  <div class="form-group">
                    <h2 class="aai-header-title col-md-12 aai-form-ttle">Person Informed For YP Return</h2>
                  </div>
              </div>
              <div class="col-sm-12 clear-div-flex" id="add_person_yp_return">
               <?php if (!empty($l4return_data)) {foreach ($l4return_data as $retkey => $retvalue) {?>
                 <div class="col-md-6 col-sm-12 dynamic-div content_box_de" id="item_new">
                   <div class="form-group">
                    <div class="col-md-6"><label>Persons Informed</label></div>
                    <div class="col-md-6">

                     <?php if (!empty($persons_infromed)) {
                      foreach ($persons_infromed as $select) { 
                       if (isset($retvalue['person_informed']) && ($retvalue['person_informed'] == $select['value'])) { echo $select['label'];}
                       ?>

                     <?php }}?>


                   </div>
                 </div>
                 <div class="form-group">

                  <div class="col-md-6"><label>Name Of Person Informed</label></div>
                  <div class="col-md-6"><?php echo $retvalue['person_name']; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Badge Number</label></div>
                  <div class="col-md-6"><?php echo $retvalue['badge_number']; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Contact Number</label></div>
                  <div class="col-md-6"><?php echo $retvalue['contact_number']; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Contact Email</label></div>
                  <div class="col-md-6"><?php echo $retvalue['contact_email']; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Informed By</label></div>
                  <div class="col-md-6">
                  <?php if (!empty($bambooNfcUsers)) {
                      foreach ($bambooNfcUsers as $select) { 
                       if (isset($retvalue['informed_by']) && ($retvalue['informed_by'] == $select['user_type'].'_'.$select['user_id'])) { echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email'];}
                        }
                  }?>
                 </div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Date</label></div>
                  <div class="col-md-6"><?php echo configDateTime($retvalue['date_informed']); ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Time</label></div>
                  <div class="col-md-6"> <div class="input-group input-append bootstrap-timepicker"><?php echo timeFormatAi($retvalue['time_informed']); ?></div>
                </div>
              </div>

              <div class="clearfix"></div>
            </div>


          <?php }}?>
        </div>



      <?php }
    } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
      ?>

      <div class="col-md-4 col-sm-6  " id="div_<?=$row['name']?>">
                                    <div class="form-group">
                                        <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                                        <div class="col-md-12">
                                        <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                                            if ((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])) {
                                                $row['value'] = timeFormatAi($row['value']);
                                            }
                                            if ($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])) {
                    $row['value'] = configDateTime($row['value']);
                }
                ?>
                    
                    <?php }?>
                    <div class="<?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : ''?><?=(!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date' : ''?> ">

                        <?php if (isset($preveditl4Data) && !empty($preveditl4Data)) {
                            $diff = new HtmlDiff(html_entity_decode($preveditl4Data["$dd"]['value']), html_entity_decode($row['value']));
                            $diff->build();
                            echo $diff->getDifference();
                            ?>
                        <?php } else {?>
                            <?=(isset($row['value'])) ? nl2br(html_entity_decode($row['value'])) : ''?>
                        <?php }?>
                    </div>
                    <span id="errors-container<?=$row['name']?>"></span>
                    <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) {?>
                    
            <?php }?>
        </div></div>
</div>
      <?php if ($row['label'] == 'Time YP returned') {
        echo '<div class="col-md-4 col-sm-6  ">
        <div class="form-group">
        <h2 class="aai-header-title col-md-12">Total Time YP Was Missing (Hour:Minute)</h2>
        <div class="col-md-12">
        '.$l4_total_duration.'</div></div></div>';
      }
    } else if ($row['type'] == 'radio-group') { ?>
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
                  <div class="<?=!empty($row['inline'])?'radio-inline':'radio'?> hidden">
                   <label >
                    <input hidden="true" name="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?> 
                    value="<?=!empty($radio['value'])?$radio['value']:''?>" <?= (isset($row['value']) && $row['value'] == $radio['value'])? 'checked="checked"' :(!isset($row['value']) && !empty($radio['selected']))?'checked="checked"':''?> type="radio">
                    <?php 
                    if(isset($row['value']) && $row['value'] == $radio['value']){ ?>
                      <?=!empty($radio['label'])?$radio['label']:''?>                                                                        
                    <?php } ?> 
                  </label>
                  </div>

                <?php } } } //radio loop ?>
              </div>
            </div>
            </div>
        </div>

        <?php
      } else if ($row['type'] == 'checkbox-group') {
        ?>
        <div class="col-md-4 col-sm-6  " id="div_<?php echo $row['name']; ?>">
            <div class="form-group">
              <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
              <div class="col-md-12">
              <div class="checkbox-group">
               <?php if (count($row['values']) > 0) {
                $checkedValues = array();
                if (!empty($edit_data[0][$row['name']])) {
                  $checkedValues = explode(',', $edit_data[0][$row['name']]);
                }
                foreach ($row['values'] as $checkbox) {
                  if (!empty($checkbox['label'])) {?>

                    <div class="<?=!empty($row['inline']) ? 'checkbox-inline' : 'checkbox'?>">
                     <label class="<?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"><?php if($checkbox['value']=='Yes'){?><span class="label label-success">Yes</span>
                     <?php } else{ ?><span class="label label-danger">No</span> <?php }?>
                   </label>
                 </div>

               <?php }}} //radio loop ?>
             </div>
           </div>
           </div>
       </div>
       <?php
     } else if ($row['type'] == 'select') {
      ?>
      <?php if($row['className'] == 'bamboo_lookup'){ ?>

        <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
            <div class="form-group">
              <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
              <div class="col-md-12">
              <?php if(!empty($bambooNfcUsers)) {
                foreach ($bambooNfcUsers as $select) {  
                  if(isset($row['value']) && ($row['value'] == $select['user_type'].'_'.$select['user_id'])){ ?>

                    <?php if (isset($preveditl4Data) && !empty($preveditl4Data) && $preveditl4Data["$dd"]['value'] != $row['value']) {
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

            <?php }else if($row['className'] == 'bamboo_lookup_multiple') { ?>
              <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                  <div class="form-group">
                    <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                    <div class="col-md-12">
                    <?php if(!empty($bambooNfcUsers)){
                      foreach ($bambooNfcUsers as $select){ 
//                        $staff_present = array_diff($row['value'],$preveditl4Data["$dd"]['value']);

//                        if(in_array($select['user_type'].'_'.$select['user_id'],$staff_present)){
//
//                          $diff = new HtmlDiff('',$select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'].',');
//                          $diff->build();
//                          echo $diff->getDifference();
//
//                        }else{ 
                          if(in_array($select['user_type'].'_'.$select['user_id'],explode(',', $row['value']))){
                            $printStaff[] = $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'];
                          }
//                        } 
                      }
                      if(isset($printStaff) && !empty($printStaff)){
                          echo implode(',', $printStaff);
                      }
                      
                          } ?>  
                    </div>
                  </div>
                </div>

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
                              $staff_present_data = array_diff($row['value'],$preveditl4Data["$dd"]['value']);
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
                                 <?php if (isset($preveditl4Data) && !empty($preveditl4Data) && $preveditl4Data["$dd"]['value'] != $row['value']) {
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
                        if ($row['name'] == 'l4_form_status') { ?>
                         <div class="col-md-4 col-sm-6  ">
                            <div class="form-group">
                              <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                                <div class="col-md-12">
                                 <?php 
                                 $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'L4');
                                 if(!empty($aai_report_com)){ 
                                  foreach ($aai_report_com as $repcom_value) { ?>
                                    <div class="col-lg-12 p-l-r-0">

                                      <ul class="media-list media-xs">
                                        <li class="media">
                                          <div class="media-body">
                                            <p class="date"><small><?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormat($repcom_value['created_date']); ?></small></p>
                                          </div>
                                        </li>
                                      </ul>
                                     </div>
                                  <?php } } ?>  
                                </div>
                          </div>
                        </div>

                      <?php } elseif ($row['name'] == 'risk_level') {
                        ?>

                        <div class="col-sm-12">
                            <div class="form-group">
                              <h2 class="aai-header-title col-md-12 aai-form-ttle">Person Informed For YP Missing</h2>
                            </div>
                        </div>
                        <div class="col-sm-12 clear-div-flex" id="add_person_yp_missing">
                          <?php if (!empty($l4missing_yp)) {
                            $i = 1;
                            foreach ($l4missing_yp as $key => $value) {
                              ?>  <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_<?php echo $i; ?>">
                                <div class="form-group">

                                  <div class="col-md-6"><label>Persons Informed</label></div>
                                  <div class="col-md-6">
                                   <?php if (!empty($persons_infromed)) {
                                    foreach ($persons_infromed as $select) { 
                                     if (isset($value['person_informed']) && ($value['person_informed'] == $select['value'])) { echo $select['label'];}
                                     ?>
                                   <?php }}?>
                                 </div>
                               </div>
                               <div class="form-group">

                                <div class="col-md-6"><label>Name Of Person Informed</label></div>
                                <div class="col-md-6"><?php echo $value['person_name']; ?></div>

                              </div>
                              <div class="form-group">

                                <div class="col-md-6"><label>Badge Number</label></div>
                                <div class="col-md-6"><?php echo $value['badge_number']; ?></div>

                              </div>
                              <div class="form-group">

                                <div class="col-md-6"><label>Contact Number</label></div>
                                <div class="col-md-6"><?php echo $value['contact_number']; ?></div>

                              </div>
                              <div class="form-group">

                                <div class="col-md-6"><label>Contact Email</label></div>
                                <div class="col-md-6"><?php echo $value['contact_email']; ?></div>

                              </div>
                              <div class="form-group">

                                <div class="col-md-6"><label>Informed By</label></div>
                                <div class="col-md-6">
                                <?php if (!empty($bambooNfcUsers)) {
                                    foreach ($bambooNfcUsers as $select) { 
                                     if (isset($value['informed_by']) && ($value['informed_by'] == $select['user_type'].'_'.$select['user_id'])) { echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email'];}
                                      }
                                }?>
                                </div>

                              </div>
                              <div class="form-group">

                                <div class="col-md-6"><label>Date</label></div>
                                <div class="col-md-6"><?php echo configDateTime($value['date_informed']); ?></div>

                              </div>
                              <div class="form-group">

                                <div class="col-md-6"><label>Time</label></div>
                                <div class="col-md-6"> <div class="input-group input-append bootstrap-timepicker"><?php echo timeFormatAi($value['time_informed']); ?></div>

                              </div></div>

                            </div>
                            <?php $i++;}}?>
                          </div>



                          <div class="col-sm-12">
                              <div class="form-group">
                                <h2 class="aai-header-title col-md-12 aai-form-ttle">Sequence Of Event</h2>
                              </div>
                          </div>
                          <div class="col-sm-12 clear-div-flex" id="l4add_seqevent">
                           <?php if (!empty($l4sequence_data)) {
                             $isq = 1;
                             foreach ($l4sequence_data as $seqkey => $seqvalue) { 
                              $seq_datal4 = $seqvalue['sequence_number'];
                              $seq_datal4 = substr($seq_datal4,1);
                              ?>
                              <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_sq_<?php echo $isq; ?>">
                               <div class="form-group">
                                <div class="col-md-6"><label>Sequence Number</label></div>
                                <div class="col-md-6"><?php echo 'S'.$isq;?><?php /* echo $seqvalue['l4seq_sequence_number'];*/ ?></div>

                              </div>
                              <div class="form-group">

                                <div class="col-md-6"><label>Who(staff full name)</label></div>
                                <div class="col-md-6">
                                    <?php if (!empty($bambooNfcUsers)) {
                                        foreach ($bambooNfcUsers as $select) { 
                                         if (isset($seqvalue['who_raised']) && ($seqvalue['who_raised'] == $select['user_type'].'_'.$select['user_id'])) { echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email'];}
                                          }
                                    }?>
                                </div>

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>What happened / what was done (include Senior Cover instructions)</label></div>
                                  <div class="col-md-6"><?php echo $seqvalue['What_happened']; ?></div>

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Date</label></div>
                                  <div class="col-md-6"><?php echo configDateTime($seqvalue['date']); ?></div>

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Time</label></div>
                                  <div class="col-md-6 "><div class="input-group input-append bootstrap-timepicker"><?php echo timeFormatAi($seqvalue['time']); ?></div>
                                </div>
                              </div>
                              <div class="form-group">

                                <div class="col-md-6"><label>All communication details</label></div>
                                <div class="col-md-6"><?php echo $seqvalue['communication_details']; ?></div>

                              </div>
                            </div>
                            <?php $isq++; }}?>

                            <script>
                              var l4sq = "<?php echo $isq;?>";
                            </script>
                          </div>


                        <?php }?>
                        <?php
                        if ($row['name'] == 'l4_form_status') {?>

<!--                          <div class="col-md-4 col-sm-6  ">
                            <div class="form-group">
                             <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                              <div class="col-md-12">
                               <input type="text" disabled="true" class="form-control" value="<?=(isset($l4_report_compiler)) ? $l4_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
                               <input type="hidden" name="l4_report_compiler" class="form-control" value="<?=(isset($l4_report_compiler)) ? $l4_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
                             </div>
                         </div>
                     </div>-->

                   <?php } ?>
                   <?php
                 } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {
                  ?>
                  <?php if ($row['type'] == 'button') {?>
                    <div class="col-md-4 col-sm-6  ">
                        <div class="form-group">
                          <div class="col-md-12">
                          <div class="fb-button form-group">
                            <button name="<?=!empty($row['name']) ? $row['name'] : ''?>" value="" type="<?=!empty($row['type']) ? $row['type'] : ''?>" class="<?=!empty($row['className']) ? $row['className'] : ''?>" name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>" style="<?=!empty($row['style']) ? $row['style'] : ''?>"><?=!empty($row['label']) ? $row['label'] : ''?></button>
                          </div>
                        </div>
                        </div>
                    </div>
                  <?php }
                  if ($row['type'] == 'hidden') {?>
                    <div class="col-md-6">
                      <input type="hidden" name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>" value="" />
                    </div>

                  <?php }
                } else if ($row['type'] == 'header') {?>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <h2 class="aai-header-title col-md-12 aai-form-ttle"><?=!empty($row['label']) ? $row['label'] : ''?></h2>
                    </div>
                  </div>
                <?php } else if ($row['type'] == 'file') {
                  ?>
                  <div class="col-md-4 col-sm-6  ">
                      <div class="form-group">
                        <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                        <input type="file" name="<?=!empty($row['name']) ? $row['name'] . '[]' : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>"  class="<?=!empty($row['className']) ? $row['className'] : ''?>"
                        <?=!empty($row['multiple']) ? 'multiple="true"' : ''?> <?=!empty($row['required']) ? 'required=true' : ''?>>
                        <h2></h2>
                        <div class="col-md-12">
                        <?php
                        /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                        $fileViewArray = array(
                          'fileArray'        => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])) ? $edit_data[0][$row['name']] : $row['value'],
                          'filePathMain'     => $this->config->item('ks_img_base_url') . $ypid,
                          'filePathThumb'    => $this->config->item('ks_img_base_url_small') . $ypid,
                          'deleteFileHidden' => 'hidden_' . $row['name'],
                        );
                        echo getFileView($fileViewArray);
                        ?>
                        <input type="hidden" name="<?=!empty($row['name']) ? 'hidden_' . $row['name'] : ''?>" id="<?=!empty($row['name']) ? 'hidden_' . $row['name'] : ''?>" value="">
                      </div>
                      </div>
                  </div>
                <?php }
    } //foreach
    ?>


  <?php }?>
</div>
</div>

</div>
