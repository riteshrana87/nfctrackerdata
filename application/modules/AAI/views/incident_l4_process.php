<script>    
    var total_missing = <?= (isset($l4missing_yp))? count($l4missing_yp)+1:1 ?>;
</script>
<div class="panel panel-default incident_process">
  <div class="panel-heading aai-page-header" role="tab" id="headingFive">
    <h4 class="panel-title">
      <a id="incident_l4_form_link" class="text-uppercase" >
        INCIDENT L4 PROCESS (YP MISSING FROM CARE)
      </a>
    </h4>
  </div>
  
    <div class="panel-body form-horizontal">
      <div class="row aai-module clear-div-flex">

        <div class="col-md-4 col-sm-6">
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
    //pr($l4_form_data);
        foreach ($l4_form_data as $row) {
          if ($row['type'] == 'textarea') {
            ?>
            <div class="col-sm-12" id="div_<?php echo $row['name']; ?>">
              <div class="form-group">
               <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
               <div class="col-md-12">
                <textarea
                class="input-textar-style form-control abcd <?=!empty($row['className']) ? $row['className'] : ''?> <?=(!empty($row['subtype']) && $row['subtype'] == 'tinymce') ? 'tinyeditor' : ''?>"
                <?=!empty($row['required']) ? 'required=true' : ''?>
                name="<?=!empty($row['name']) ? $row['name'] : ''?>"
                placeholder="<?=!empty($row['placeholder']) ? $row['placeholder'] : ''?>"
                <?php if ($row['subtype'] != 'tinymce') {?>
                  <?=!empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : ''?>
                  <?=!empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : ''?>
                <?php }?>
                id="<?=!empty($row['name']) ? $row['name'] : ''?>" ><?=(isset($row['value'])) ? nl2br(html_entity_decode($row['value'])) : ''?></textarea>
              </div>
            </div>
          </div>
          <?php if ($row['label'] == 'Details') {?>
            <div class="col-md-4 col-sm-5 m_btm_16">
             <div class="pull-left btn-section">
              <div class="btn-group">
                <input class="btn btn-default" id="send_notification_missing_team" type="button" name="sendworker" value="Send Missing YP Alert to missing team" />
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-5  ">
            <div class="form-group">           
              <div class="col-sm-12 notification-input">
                  Last sent : <label id="calculate_notification_missing_label"><?php echo $l4calculate_notification_missing; ?></label>
              </div>
            </div>
          </div>
        <?php } elseif ($row['name'] == 'state') {
          ?>

          <div class="col-sm-12">
            <div class="form-group">
             <h2 class="aai-header-title col-md-12 aai-form-ttle">Person Informed For YP Return</h2>
           </div>
         </div>
         <div class="col-sm-12 clear-div-flex" id="add_person_yp_return">
           <?php if (!empty($l4return_data)) {
            $ipr = 1;
            foreach ($l4return_data as $retkey => $retvalue) {
              ?>
              <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_pret<?php echo $ipr; ?>">
               <div class="form-group">
                <div class="col-md-6"><label>Persons Informed</label><span class="astrick">*</span></div>
                <div class="col-md-6">
                    <select class="chosen-select" required="true" data-parsley-errors-container="#errors-container-person_informed_return_team_<?php echo $ipr; ?>" name="person_informed_return_team[]" id="person_informed_return_team_<?php echo $ipr; ?>">
                   <option value="">Select Persons Informed</option>
                   <?php if (!empty($persons_infromed)) {
                    foreach ($persons_infromed as $select) {?>
                      <option value="<?php echo $select['value']; ?>" <?php if (isset($retvalue['person_informed']) && ($retvalue['person_informed'] == $select['value'])) {echo 'selected="true"';}?>>
                        <?php echo $select['label']; ?>
                      </option>
                    <?php }}?>
                  </select>
                    <span id="errors-container-person_informed_return_team_<?php echo $ipr; ?>"></span>
                    

                </div>
              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Name Of Person Informed</label></div>
                <div class="col-md-6"><input name="name_of_person_informed_return[]" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="50"  class="red form-control input-textar-style" type="text" value="<?php echo $retvalue['person_name']; ?>" /></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Badge Number</label></div>
                <div class="col-md-6"><input class="red form-control input-textar-style" name="badge_number_person_return[]" type="number" max="9999999999" value="<?php echo $retvalue['badge_number']; ?>" /></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Contact Number</label></div>
                <div class="col-md-6"><input class="red form-control input-textar-style" name="contact_number_person_return[]" type="number" max="9999999999999" value="<?php echo $retvalue['contact_number']; ?>" /></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Contact Email</label></div>
                <div class="col-md-6"><input class="red form-control input-textar-style" name="contact_email_person_return[]"  type="email" value="<?php echo $retvalue['contact_email']; ?>" /></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Informed By</label></div>
                <div class="col-md-6">
                    <!--<input class="red form-control input-textar-style" name="informed_by_person_return[]" value="<?php //echo $retvalue[5]; ?>"  type="text" />-->
                <select class="chosen-select" name="informed_by_person_return[]" id="informed_by_person_return<?php echo $ipr; ?>">
                    <option value="">Select Informed By</option>
                    <?php if (!empty($bambooNfcUsers)) {
                    foreach ($bambooNfcUsers as $select) {?>
                      <option
                          id="<?php echo $select['user_type'].'_'.$select['user_id'];?>"
                            data-fname="<?php echo $select['first_name'];?>" 
                            data-lname="<?php echo $select['last_name'];?>" 
                            data-email="<?php echo $select['email'];?>" 
                            data-jobtitle="<?php echo $select['job_title'];?>" 
                            data-location="<?php echo $select['work_location'];?>" 
                            value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?php if (isset($value['informed_by']) && ($value['informed_by'] == $select['user_type'].'_'.$select['user_id'])) {echo 'selected="true"';}?>>
                        <?php echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email']; ?>
                      </option>
                    <?php }}?>
               </select>
                </div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Date</label><span class="astrick">*</span></div>
                <div class="col-md-6 m-t-3">
                    <div class="input-group input-append">
                        <input readonly="true" class="form-control seq_adddate seq_addtime_data timer-sty after_yp_missing_date" name="person_return_date_event[]" id="date_event_return_<?php echo $ipr; ?>" value="<?php echo configDateTime($retvalue['date_informed']); ?>" data-parsley-errors-container="#errors-container-l4date_event_return<?php echo $ipr; ?>" type="text" /><span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                </div>
                    <span id="errors-container-l4date_event_return<?php echo $ipr; ?>"></span>
                </div>
                
                                  
              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Time</label><span class="astrick">*</span></div>
                <div class="col-md-6 m-t-3"> 
                    <div class="input-group input-append bootstrap-timepicker">
                        <input readonly="true" type="text" class="red form-control aaitime addtime_data timer-sty" name="person_return_time_event[]" id="ipr_time_event<?php echo $ipr; ?>" placeholder="" value="<?php echo $retvalue['time_informed']; ?>"  readonly=""><span class="input-group-addon add-on ipr_time_event<?php echo $ipr; ?>" data-parsley-errors-container="#errors-container-l4persons_return_time<?php echo $ipr; ?>"><i class="fa fa-clock-o"></i></span>
                    </div>
                    <span id="errors-container-l4persons_return_time<?php echo $ipr; ?>"></span>
              </div>
            </div>
            <div class="col-md-12  add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_repeat_block('item_new_pret<?php echo $ipr; ?>');"></span></a></div>

          </div>


          <?php $ipr++;}}?>

          <script>
            var person_re = "<?php echo $ipr; ?>";
          </script>
        </div>
        <div class=" col-sm-12 section_four text-center mb30">
          <input type="hidden" id="delete_person_yp_rerurn" name="delete_person_yp_rerurn" value="">
          <a id="add_new_person_informed_yp_return" class="btn btn-default updat_bn" href="javascript:;">
           <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Person to be informed
         </a>
       </div>



     <?php }
   } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
    ?>    
    <div class="col-md-4 col-sm-6  " id="div_<?php echo $row['name']; ?>">
        <div class="form-group">
          <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
          <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                    if ((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])) {
                        $row['value'] = timeformat($row['value']);
                    }
                    if ($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])) {
                        $row['value'] = configDateTime($row['value']);
                    } ?>
              <div class="col-md-12">
              <?php }?>
              <div class=" col-md-12 <?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : ''?><?=(!empty($row['type']) && $row['type'] == 'date') ? 'input-group input-append' : ''?>">
                <input type="<?=(!empty($row['type']) && $row['type'] == 'number') ? 'number' : ((!empty($row['subtype']) && $row['subtype'] != 'time') ? $row['subtype'] : 'text')?>"
                class="<?=!empty($row['className']) ? $row['className'] : ''?> <?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? 'aaitime' : ''?> addtime_data"
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
    <?php if ($row['label'] == 'Time YP returned') {?>
      <div class="col-md-4 col-sm-6  ">
          <div class="form-group">
              <h2 class="aai-header-title col-md-12">Total Time YP Was Missing</h2>
            
            <div class="col-md-12">
              <input type="text"  class="input-textar-style form-control" name="l4_total_duration_main" id="l4_total_duration_main" value="<?php echo $l4_total_duration; ?>" /><input type="hidden"  class="input-textar-style form-control" name="l4_total_duration" id="l4_total_duration" value="<?php echo $l4_total_duration; ?>" />
            </div>
          </div>
          </div>
            <?php }
          } else if ($row['type'] == 'radio-group') {
            ?>
            <div class="col-sm-12" id="div_<?=$row['name']?>">
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
                             if ($row['name'] == 'l4_was_the_yp_injured') {
                              if ($is_yp_injured == 1) {
                                $row['value'] = 'Yes';
                              } else {
                                $row['value'] = 'No';
                              }
                            } elseif ($row['name'] == 'l4_is_the_yp_making_complaint') {
                              if ($is_yp_complaint == 1) {
                                $row['value'] = 'Yes';
                              } else {
                                $row['value'] = 'No';
                              }
                            } elseif ($row['name'] == 'l4_was_staff_member_injured') {
                              if ($is_staff_injured == 1) {
                                $row['value'] = 'Yes';
                              } else {
                                $row['value'] = 'No';
                              }
                            } elseif ($row['name'] == 'l4_was_anyone_else_injured') {
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

                <?php
              } else if ($row['type'] == 'checkbox-group') {
                ?>
                <div class="col-sm-12" id="div_<?php echo $row['name']; ?>">
                    <div class="form-group mb16">
                      <h2 class="aai-header-title-radio aai-header-title-checkbox col-md-4 col-sm-6"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                      <div class="col-md-6 check-box-aai">
                      <div class="checkbox-group">
                       <?php if (count($row['values']) > 0) {
                        $checkedValues = array();
                        if (isset($row['value']) && $row['value'] !== '') {
                            $checkedValues = explode(',', $row['value']);
                        }
                        foreach ($row['values'] as $checkbox) {
                          if (!empty($checkbox['label'])) {?>

                            <div class="<?=!empty($row['inline']) ? 'checkbox-inline' : 'checkbox'?>">
                             <label class="<?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"><input
                              class="<?=!empty($row['className']) ? $row['className'] : ''?> <?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"
                              name="<?=!empty($row['name']) ? $row['name'] . '[]' : ''?>" value="<?=!empty($checkbox['value']) ? $checkbox['value'] : ''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues)) ? 'checked="checked"' : (!empty($checkbox['selected']) && (!isset($row['value']) || $row['value'] == '')) ? 'checked="checked"' : ''?>
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
          <?php if ($row['name'] == 'last_seen') {?>
                <div class="col-xs-12 m_btm_16">
                  <div class="pull-left btn-section">
                    <div class="btn-group">
                      <div class="col-sm-12 p-l-r-0 m-b-0"><input class="btn btn-default updat_bn" id="send_worker_notification" type="button" name="sendworker" value="Send Notification To S.worker" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-6  ">
                    <div class="form-group">
                      <h2 class="aai-header-title col-md-12"></h2>
                      <div class="col-md-12">                          
                          Last sent : <label id="calculate_notification_worker_label"><?php echo $l4calculate_notification_worker; ?></label>
                      </div>
                    </div>
                </div>
              <?php }?>
                  
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
                      if ($row['name'] == 'risk_level') {
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

                                  <div class="col-md-6"><label>Persons Informed</label><span class="astrick">*</span></div>
                                  <div class="col-md-6">
                                      <select class="chosen-select" required="true" name="person_informed_missing_team[]" id="person_informed_missing_team_<?php echo $i; ?>" data-parsley-errors-container="#errors-container-person_informed_missing_team_<?php echo $i; ?>">
                                     <option value="">Select Persons Informed</option>
                                     <?php if (!empty($persons_infromed)) {
                                      foreach ($persons_infromed as $select) {?>
                                        <option value="<?php echo $select['value']; ?>" <?php if (isset($value['person_informed']) && ($value['person_informed'] == $select['value'])) {echo 'selected="true"';}?>>
                                          <?php echo $select['label']; ?>
                                        </option>
                                      <?php }}?>
                                    </select>
                                      <span id="errors-container-person_informed_missing_team_<?php echo $i; ?>"></span>

                                  </div>
                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Name Of Person Informed</label></div>
                                  <div class="col-md-6"><input name="name_of_person_informed_missing[]" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="50" id="name_of_person_informed_missing_<?php echo $i; ?>" class="red form-control input-textar-style" type="text" value="<?php echo $value['person_name']; ?>" /></div>

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Badge Number</label></div>
                                  <div class="col-md-6">
                                      <input class="red form-control input-textar-style" name="badge_number_person_missing[]" id="badge_number_person_missing_<?php echo $i; ?>"  type="number" max="9999999999" value="<?php echo $value['badge_number']; ?>" />
                                  </div>
                                  <span id="errors-container-badge_number_person_missing_<?php echo $i; ?>"></span>

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Contact Number</label></div>
                                  <div class="col-md-6"><input class="red form-control input-textar-style" name="contact_number_person_missing[]" id="contact_number_person_missing_<?php echo $i; ?>"  type="number" max="9999999999999" value="<?php echo $value['contact_number']; ?>" /></div>

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Contact Email</label></div>
                                  <div class="col-md-6"><input class="red form-control input-textar-style" name="contact_email_person_missing[]" id="contact_email_person_missing_<?php echo $i; ?>"  type="email" value="<?php echo $value['contact_email']; ?>" /></div>

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Informed By</label></div>
                                  <div class="col-md-6">
                                      <!--<input class="red form-control input-textar-style" name="informed_by_person_missing[]" id="informed_by_person_missing_<?php echo $i; ?>"  type="text" value="<?php //echo $value[5]; ?>" />-->
                                  <select class="chosen-select" name="informed_by_person_missing[]" id="informed_by_person_missing_<?php echo $i; ?>">
                                     <option value="">Select Informed By</option>
                                      <?php if (!empty($bambooNfcUsers)) {
                                        foreach ($bambooNfcUsers as $select) {?>
                                          <option
                                              id="<?php echo $select['user_type'].'_'.$select['user_id'];?>"
                                                data-fname="<?php echo $select['first_name'];?>" 
                                                data-lname="<?php echo $select['last_name'];?>" 
                                                data-email="<?php echo $select['email'];?>" 
                                                data-jobtitle="<?php echo $select['job_title'];?>" 
                                                data-location="<?php echo $select['work_location'];?>" 
                                                value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?php if (isset($value['informed_by']) && ($value['informed_by'] == $select['user_type'].'_'.$select['user_id'])) {echo 'selected="true"';}?>>
                                            <?php echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email']; ?>
                                          </option>
                                        <?php }}?>
                                    </select>
                                  </div>

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Date</label><span class="astrick">*</span></div>
                                  <div class="col-md-6 m-t-3">
                                      <div class="input-group input-append">
                                       <input class="form-control seq_adddate seq_addtime_data timer-sty after_yp_missing_date" name="date_event[]" id="date_event_<?php echo $i; ?>" data-parsley-errors-container="#errors-container-l4date_event<?php echo $i; ?>" type="text" required="true" value="<?php echo configDateTime($value['date_informed']); ?>" /><span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                     </div>
                                      <span id="errors-container-l4date_event<?php echo $i; ?>"></span>
                                   </div>
                                  

                                </div>
                                <div class="form-group">

                                  <div class="col-md-6"><label>Time</label><span class="astrick">*</span></div>
                                  <div class="col-md-6 m-t-3"> 
                                      <div class="input-group input-append bootstrap-timepicker">
                                          <input type="text" required="true" data-parsley-errors-container="#errors-container-l4persons_missing_time<?php echo $i; ?>" class="red form-control aaitime addtime_data timer-sty" name="time_event[]" id="time_event<?php echo $i; ?>" placeholder="" value="<?php echo timeformat($value['time_informed']); ?>"  readonly=""><span class="input-group-addon add-on time_event<?php echo $i; ?>"><i class="fa fa-clock-o"></i></span>
                                      </div>
                                      <span id="errors-container-l4persons_missing_time<?php echo $i; ?>"></span>

                                </div></div>
                                <div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_repeat_block('item_new_<?php echo $i; ?>');"></span></a></div>
                                
                              </div>
                              <?php $i++;}}?>                            
                            </div>
                            <div class="col-md-12 section_four text-center mb30">
                              <input type="hidden" id="delete_person_yp_missing" name="delete_person_yp_missing" value="">
                              <a id="add_new_person_informed_yp_missing" class="btn btn-default updat_bn" href="javascript:;">
                               <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Person to be informed
                             </a>
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
//pr($seqvalue); ?>
<div class="col-md-6 col-sm-12 dynamic-div" id="item_new_sq_<?php echo $isq; ?>">
 <div class="form-group">
  <div class="col-md-6"><label>Sequence Number</label></div>
  <div class="col-md-6">
      <input name="l4seq_sequence_number[]" class="red form-control input-textar-style" value="<?php echo 'S'.$isq?>" type="text" readonly="true" />
  </div>
</div>
<div class="form-group">

  <div class="col-md-6"><label>Who(staff full name)</label></div>
  <div class="col-md-6"><!--<input class="red form-control input-textar-style" name="l4seq_who[]" value="<?php //echo $seqvalue[1]; ?>"  type="text" />-->
    
    <select class="chosen-select" name="l4_sequence_who[]" id="l4_sequence_who<?php echo $isq; ?>">
        <option value="">Select user</option>
         <?php if (!empty($bambooNfcUsers)) {
           foreach ($bambooNfcUsers as $select) {?>
             <option
                 id="<?php echo $select['user_type'].'_'.$select['user_id'];?>"
                   data-fname="<?php echo $select['first_name'];?>" 
                   data-lname="<?php echo $select['last_name'];?>" 
                   data-email="<?php echo $select['email'];?>" 
                   data-jobtitle="<?php echo $select['job_title'];?>" 
                   data-location="<?php echo $select['work_location'];?>" 
                   value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?php if (isset($seqvalue['who_raised']) && ($seqvalue['who_raised'] == $select['user_type'].'_'.$select['user_id'])) {echo 'selected="true"';}?>>
               <?php echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email']; ?>
             </option>
           <?php }}?>
       </select>

    </div>

  </div>
  <div class="form-group">

    <div class="col-md-6"><label>What happened / what was done (include Senior Cover instructions)</label></div>
    <div class="col-md-6"><textarea id="what_happned" class="form-control input-textar-style" minlength="2" maxlength="500"  placeholder="What happened / what was done (include Senior Cover instructions)"  name="l4seq_what_happned[]"><?php echo $seqvalue['What_happened']; ?></textarea></div>

  </div>
  <div class="form-group">

    <div class="col-md-6"><label>Date</label><span class="astrick">*</span></div>
    <div class="col-md-6 m-t-3">
        <div class="input-group input-append">
         <input class="form-control seq_adddate seq_addtime_data timer-sty after_yp_missing_date" name="l4seq_date_event[]" id="l4seq_date_event<?php echo $isq; ?>" data-parsley-errors-container="#errors-container-l4seq_date_event<?php echo $isq; ?>" type="text" required="true" value="<?php echo configDateTime($seqvalue['date']); ?>" /><span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
       </div>
        <span id="errors-container-l4seq_date_event<?php echo $isq; ?>"></span>
     </div>
  </div>
  <div class="form-group">

    <div class="col-md-6"><label>Time</label><span class="astrick">*</span></div>
    
    <div class="col-md-6 m-t-3">        
      <div class="input-group input-append bootstrap-timepicker">
          <input type="text" class="red form-control aaitime addtime_data timer-sty" name="l4seq_time_event[]" id="seq_time_event<?php echo $isq;?>" placeholder="" value="<?php echo timeformat($seqvalue['time']); ?>" data-parsley-errors-container="#errors-container-l4time_event<?php echo $isq;?>" readonly=""><span class="input-group-addon add-on seq_time_event<?php echo $isq;?>"><i class="fa fa-clock-o"></i></span>
      </div>
      <span id="errors-container-l4time_event<?php echo $isq;?>"></span>
    </div>
    </div>
    <div class="form-group">

      <div class="col-md-6"><label>All communication details</label></div>
      <div class="col-md-6"><textarea id="communication" class="form-control input-textar-style" minlength="2" maxlength="500"  placeholder="All Communication Detail"  name="l4seq_communication[]"><?php echo $seqvalue['communication_details']; ?></textarea></div>

    </div>
    <div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_sq_review_row('item_new_sq_<?php echo $isq; ?>');"></span></a></div>
  </div>
  <?php $isq++;}?>
  <script>
    var l4sq = "<?php echo $isq; ?>";
  </script>
<?php }?>


</div>
<div class="col-sm-12 section_four text-center mb30">
  <input type="hidden" id="delete_cpt_review_id" name="delete_cpt_review_id" value="">
  <a id="l4add_new_seq_event" class="btn btn-default updat_bn" href="javascript:;">
   <span class="glyphicon glyphicon-plus"></span>&nbsp;Add Sequence of events
 </a>
</div>




<?php }?>
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
                  <p class="date date-p"><small><?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormat($repcom_value['created_date']); ?></small></p>
                </div>
              </li>
            </ul>
          </div>
        <?php } } ?>      

        <?php 
        $check_user_data = checkUserDetail($incidentData['incident_id'],$l4reference_number,$incidentData['yp_id'],'L4',$loggedInUser['ID']); 
        if(empty($check_user_data)){
          $l4_report_compiler = getUserName($loggedInUser['ID']); ?>
          <input type="text" disabled="true" class="form-control" value="<?=(isset($l4_report_compiler)) ? $l4_report_compiler : '' ?>">
        <?php } ?>
      </div>
  </div>
</div>


<?php }?>
<?php
} else if ($row['type'] == 'hidden' || $row['type'] == 'button') {
  ?>
  <?php if ($row['type'] == 'button') {?>
    <div class="col-md-4 col-sm-6  " id="div_<?php echo $row['name']; ?>">
        <div class="form-group">
          <div class="fb-button form-group">
            <button name="<?=!empty($row['name']) ? $row['name'] : ''?>" value="" type="<?=!empty($row['type']) ? $row['type'] : ''?>" class="<?=!empty($row['className']) ? $row['className'] : ''?>" name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>" style="<?=!empty($row['style']) ? $row['style'] : ''?>"><?=!empty($row['label']) ? $row['label'] : ''?></button>
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
  <div class="col-sm-12" id="div_<?php echo $row['name']; ?>">
    <div class="form-group">
      <h2 class="aai-header-title col-md-12 aai-form-ttle "><?=!empty($row['label']) ? $row['label'] : ''?></h2>
    </div>
  </div>
<?php } else if ($row['type'] == 'file') {
  ?>
  <div class="col-md-4 col-sm-6  " id="div_<?php echo $row['name']; ?>">
      <div class="form-group">
        <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
        <div class="col-md-12">
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
    } //foreach
    ?>
    <input type="hidden" name="calculate_notification_missing" id="calculate_notification_missing" value="<?php echo $l4calculate_notification_missing; ?>" />
    <input type="hidden" name="calculate_notification_worker" id="calculate_notification_worker" value="<?php echo $l4calculate_notification_worker; ?>" />      
    <div class="col-xs-12">
      <div class="pull-right btn-section">
        <div class="btn-group">
          <button type="submit" class="btn btn-default" name="draft_l4form" id="draft_l4form" style="default" onclick="$('#saveAsDraftL4').val(1);">Save as Draft</button>
          <button type="submit" class="btn btn-default" name="continue" id="continue" value="submit" style="default">Completed</button>
        </div>
      </div>
    </div>
  <?php }?>

</div>
</div>
</div>

