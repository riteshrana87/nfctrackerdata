<div class="panel panel-default incident_process">
   <div class="panel-heading aai-page-header" role="tab" id="headingSeven">
      <h4 class="panel-title">
         INCIDENT L7 PROCESS (SAFEGUARDING)
      </h4>
   </div>
   
      <div class="panel-body form-horizontal">
         <div class="row aai-module clear-div-flex">
            <div class="col-md-4 col-sm-6  ">
                        <div class="form-group">
                            <h2 class="aai-header-title col-md-12">Reference ID number <span class="astrick">*</span></h2>
                                <div class="col-md-12">
                                    <input type="text" disabled="true" class="form-control" value="<?= (isset($l7reference_number))? $l7reference_number:$l7reference_number ?>">

                                    <input type="hidden" name="l7_reference_number" class="form-control" value="<?= (isset($l7reference_number))? $l7reference_number:$l7reference_number ?>">
                                </div>   
                    </div>
                </div> 
            <div class="col-md-4 col-sm-6  ">
                  <div class="form-group">
                     <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                        <div class="col-md-12">
                            <?php 
                                $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'L7');
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
                        $check_user_data = checkUserDetail($incidentData['incident_id'],$l7reference_number,$incidentData['yp_id'],'L7',$loggedInUser['ID']); 
                        if(empty($check_user_data)){
                            $l7_report_compiler = getUserName($loggedInUser['ID']); ?>
                           <input type="text" disabled="true" class="form-control" value="<?= (isset($l7_report_compiler))? $l7_report_compiler:'' ?>"> 
                           
                        <?php } ?>  
                        </div>
                  </div>
            </div>
            <?php 
               if (!empty($l7_form_data)) {
                   foreach ($l7_form_data as $row) {
               
                       if ($row['type'] == 'textarea') { ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
                  <div class="form-group">
                     <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                     <div class="col-md-12">
                     <textarea class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'tinymce') ? 'tinyeditor' : '' ?>" <?= !empty($row['required']) ? 'required=true' : '' ?> name="<?= !empty($row['name']) ? $row['name'] : '' ?>" placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>" <?php if ($row['subtype'] != 'tinymce') { ?> <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?> <?= !empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : '' ?>
                        <?php } ?> id="<?= !empty($row['name']) ? $row['name'] : '' ?>" ><?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?></textarea>
                  </div>
               </div>
            </div>
            <?php
               } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') { ?>
            <?php if($row['name'] == 'is_other_injured'){ if($is_other_injured == 1){ ?>
            <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                  <div class="form-group">
                     <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                     <div class="col-md-12">
                     <input type="<?= $row['subtype'] ?>"  
                        class="<?= !empty($row['className']) ? $row['className'] : '' ?>"  
                        <?= !empty($row['required']) ? 'required=true' : '' ?>
                        name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                        <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>                                                    
                        <?= !empty($row['step']) ? 'step="' . $row['step'] . '"' : '' ?>
                        placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                        value="<?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):''?>" data-parsley-errors-container="#errors-container<?= $row['name'] ?>" />
                     <span id="errors-container<?= $row['name'] ?>"></span>
                  </div>
               </div>
            </div>
            <?php }}else{ ?>
            <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                  <div class="form-group">
                     <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                     <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                        if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])){
                            $row['value'] = timeformat($row['value']);
                        }
                        if($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])){
                            //$row['value'] = $row['value'];
                            $row['value'] = configDateTime($row['value']);
                        }
                        ?>
                        <div class="col-md-12">
                           <?php } ?>
                           <div class=" col-md-12 <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date dob' : '' ?> ">
                              <input type="<?= (!empty($row['type']) && $row['type'] == 'number') ? 'number' : ((!empty($row['subtype']) && $row['subtype'] != 'time') ? $row['subtype'] : 'text') ?>"  autocomplete="off" 
                                 class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= ($row['type'] == 'date') ? 'aai_adddate' : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'aaitime addtime_data' : '' ?> "  
                                 <?= !empty($row['required']) ? 'required=true' : '' ?>
                                 name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                 <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                 <?= !empty($row['min']) ? 'min="' . $row['min'] . '"' : '' ?>
                                 <?= !empty($row['max']) ? 'max="' . $row['max'] . '"' : '' ?>
                                 <?= !empty($row['step']) ? 'step="' . $row['step'] . '"' : '' ?>
                                 placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                 value="<?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>" <?= ($row['type'] == 'date') ? 'readonly' : '' ?> data-parsley-errors-container="#errors-container<?= $row['name'] ?>" <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'readonly' : '' ?> />
                              <?php if (!empty($row['subtype']) && $row['subtype'] == 'time') { ?>
                              <span class="input-group-addon add-on <?= !empty($row['name']) ? $row['name'] : '' ?>"><i class="fa fa-clock-o"></i></span>
                              <?php } ?>
                              <?php if (!empty($row['type']) && $row['type'] == 'date') { ?>
                              <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                              <?php } ?>
                           </div>
                           <span id="errors-container<?= $row['name'] ?>"></span>
                           <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) { ?>
                        </div>
                     <?php } ?>
                  </div>
            </div>
            <?php } ?>
            <?php if ($row['name']=='l7_time_informed_oi'){ ?>
            <div class="col-sm-12">
                  <div class="form-group">
                     <h2 class="aai-header-title col-md-12 aai-form-ttle">Safeguarding updates</h2>
                  </div>
            </div>
            <div class="col-sm-12 clear-div-flex" id="add_safeguarding">
               <?php if(!empty($l7sequence_data)){ 
                  $i=1;
                  foreach ($l7sequence_data as $value) { 
                      //$seq_datal7 = $value['l7sequence_number'];
                     //$seq_datal7 = substr($seq_datal7,1);
                      $seq_datal7 = $i;

                     ?>
               <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_safeguarding_<?php echo $i; ?>">
                  <div class="form-group">
                     <div class="col-md-6"><label>Sequence Number <?php echo $seq_datal7; ?></label></div>
                     <div class="col-md-6">
                         <input name="l7sequence_number[]" class="red form-control input-textar-style" value="<?php echo 'S'.$i?>" type="text" />
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-6"><label>Update By</label></div>
                     <div class="col-md-6">
                        <select data-parsley-errors-container="#errors-1" class="form-control chosen-select" id="1" name="l7update_by[]">
                           <option value=''> Select user </option>
                           <?php if(!empty($bambooNfcUsers)) {
                              foreach ($bambooNfcUsers as $select) {  ?>
                           <option value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?php if(isset($value['l7update_by']) && ($value['l7update_by'] == $select['user_type'].'_'.$select['user_id'])){echo 'selected="true"';}?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;?> </option>
                           <?php } } ?>
                        </select>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-6"><label>Daily action taken</label></div>
                     <div class="col-md-6">
                        <textarea id="what_happned" class="form-control input-textar-style" placeholder="Daily action outcome"  name="l7daily_action_taken[]"><?= !empty($value['l7daily_action_taken']) ? $value['l7daily_action_taken'] : '' ?></textarea>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-6"><label>Daily action outcome</label></div>
                     <div class="col-md-6">
                        <textarea id="what_happned" class="form-control input-textar-style"  placeholder="Daily action outcome" name="l7daily_action_outcome[]"><?= !empty($value['l7daily_action_outcome']) ? $value['l7daily_action_outcome'] : '' ?></textarea>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-6"><label>Supporting documents</label></div>
                     <div class="col-md-6">
                        <input type="file" multiple="" id="l7supporting_documents" value="<?= !empty($value['l7supporting_documents']) ? $value['l7supporting_documents'] : '' ?>" class="form-control" placeholder="Daily action outcome" name="l7supporting_documents<?php echo $i; ?>[]" />
                        <h2></h2>
                        <?php
                           $fileViewArray_doc = array(
                               'fileArray' => (isset($value['l7supporting_documents']) && !empty($value['l7supporting_documents']))? $value['l7supporting_documents'] : '',
                               'filePathMain' => $this->config->item('aai_img_base_url') . $ypId,
                               'filePathThumb' => $this->config->item('aai_img_base_url_small') . $ypId,
                               'deleteFileHidden' => 'hidden_'.'l7supporting_documents'.$seq_datal7
                           );
                           echo getFileView($fileViewArray_doc); ?>
                        <input type="hidden" name="<?=!empty('l7supporting_documents'.$seq_datal7)?'hidden_'.'l7supporting_documents'.$seq_datal7:''?>" id="<?=!empty('l7supporting_documents'.$seq_datal7)?'hidden_'.'l7supporting_documents'.$seq_datal7:''?>" value="">

                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-6"><label>Date</label></div>
                     <div class="col-md-6 m-t-3">
                        <input class="form-control aai_adddate timer-sty" value="<?= !empty($value['l7date_safeguarding']) ? configDateTime($value['l7date_safeguarding']) : '' ?>" name="l7date_safeguarding[]" id="safe_addtime_data" type="text" />
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-6"><label>Time</label></div>
                     <div class="col-md-6  m-t-3">
                     <div class="input-group input-append bootstrap-timepicker ">
                        <input type="text" class="red form-control aaiaddtime addtime_data timer-sty" name="l7time_safeguard[]" id="l7time_safeguard<?php $i;?>" placeholder="" value="<?= !empty($value['l7time_safeguard']) ? $value['l7time_safeguard'] : '' ?>" data-parsley-errors-container="#errors-containertime_event" readonly="">
                        <span class="input-group-addon add-on l7time_safeguard<?php $i;?>"><i class="fa fa-clock-o"></i></span>
                     </div>
                  </div>
                  </div>
                  <div class="col-md-12  add_items_field mb44 del-btn-form">
                     <a class="btn btn-default btn_border">
                     <span class="glyphicon glyphicon-trash" onclick="delete_safeguard_row('item_new_safeguarding_<?php echo $i; ?>');"></span>
                     </a>
                  </div>
               </div>
               <?php  $i++; }  ?>
               <script>
                  var xsu = "<?php echo $i;?>";
               </script>
               <?php } ?>
            </div>
            <div class="col-sm-12 section_seven text-center mb30">
               <input type="hidden" id="delete_safeguard_review_id" name="delete_safeguard_review_id" value="">
               <a id="add_new_safe_updates" class="btn btn-default updat_bn" href="javascript:;">
               <span class="glyphicon glyphicon-plus"></span>&nbsp;Action/Update
               </a>
            </div>
            <div class="col-md-4 col-sm-6  ">
                  <div class="form-group">
                     <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                        <div class="col-md-12">
                           <input type="text" disabled="true" class="form-control" value="<?= (isset($l7_report_compiler))? $l7_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>">                                
                           <input type="hidden" name="l7_report_compiler_safeguarding_Outcome" class="form-control" value="<?= (isset($l7_report_compiler))? $l7_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>">
                        </div>
                     </div>
            </div>
            <?php }?>
            <?php
               } else if($row['type'] == 'radio-group') { ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
                  <div class="form-group">
                     <h2 class="aai-header-title-radio col-md-4 col-sm-6  "><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <div class="col-md-6">
                     <div class="radio-group">
                        <?php if(count($row['values']) > 0) {
                           foreach ($row['values'] as $radio) {
                               if(!empty($radio['label'])) {
                            ?>
                        <div class="<?=!empty($row['inline'])?'radio-inline':'radio-inline'?>">
                           <label ><input name="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?> 
                              class="<?=!empty($row['className'])?$row['className']:''?>" 
                              value="<?=!empty($radio['value'])?$radio['value']:''?>" <?= (isset($row['value']) && $row['value'] == $radio['value'])? 'checked="checked"' :(!isset($row['value']) && !empty($radio['selected']))?'checked="checked"':''?>  type="radio">
                           <?=!empty($radio['label'])?$radio['label']:''?></label>
                        </div>
                        <?php } } } //radio loop ?>
                     </div>
                   </div>
                  </div>
            </div>
            <?php
               } else if($row['type'] == 'checkbox-group') { ?>
            <div class="col-md-12 col-sm-6  " id="div_<?= $row['name'] ?>">
                  <div class="form-group mb16">
                     <h2 class="aai-header-title-radio col-md-2 aai-header-title-checkbox"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <div class=" check-box-aai">
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
            <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                    <div class="form-group">
                        <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                        <div class="col-md-12">
                        <select data-parsley-errors-container="#errors-<?= $row['name'] ?>" class='form-control chosen-select' id='<?= $row['name'] ?>' name='<?= $row['name'] ?>'>
                            <option value=''> Select user </option>
                            <?php if(!empty($bambooNfcUsers)) {
                                foreach ($bambooNfcUsers as $select) {  ?>
                                    <option value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?php if(isset($row['value']) && ($row['value'] == $select['user_type'].'_'.$select['user_id'])){echo 'selected="true"';}?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;?> </option>
                                <?php } } ?>
                            </select>
                            <span id="errors-<?= $row['name'] ?>"></span>
                        </div>
                        </div>
                </div>
            <?php }else if($row['className'] == 'bamboo_lookup_multiple') { ?>

                <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                        <div class="form-group">
                            <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                            <div class="col-md-12">
                            <select <?=(!empty($row['className']) && $row['className'] == 'bamboo_lookup_multiple')?'multiple':''?> data-parsley-errors-container="#errors-<?= $row['name'] ?>" class='form-control chosen-select' id='<?= $row['name'] ?>' name='<?=(!empty($row['name']) && (!empty($row['className'])))?$row['name'].'[]':''?>'>
                                <option value=''> Select user </option>
                                <?php if(!empty($bambooNfcUsers)) {
                                    $userAr = array();
                                    foreach ($bambooNfcUsers as $select) {  ?>
                                        <option value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?=(in_array($select['user_type'].'_'.$select['user_id'], explode(',', $row['value']))?'selected':'')?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;?> 
                                    </option>
                                <?php } } ?>
                            </select>
                            <span id="errors-<?= $row['name'] ?>"></span>
                        </div>
                        </div>
                </div>
            <?php }else{ ?>
                <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                        <div class="form-group">
                            <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                            <div class="col-md-12">

                            <?php if(!empty($row['name']) && !empty($row['className']) && $row['className'] == 'multiple'){ ?>
                               <select multiple <?=(!empty($row['description']) && $row['description'] == 'bamboo_lookup_multiple')?'multiple':''?> data-parsley-errors-container="#errors-<?= $row['name'] ?>" class='form-control chosen-select' id='<?= $row['name'] ?>' name='<?=(!empty($row['name']) && (!empty($row['description'])))?$row['name'].'[]':''?>'>
                                <option value=''> Select </option>
                                <?php if(count($row['values']) > 0) {
                                    $userAr = array();
                                    foreach ($row['values'] as $select) {  ?>
                                        <option id="<?=!empty($select['value'])?$select['value']:''?>" data-detail="<?= !empty($select['label'])?$select['label']:'' ?>" value="<?=!empty($select['value'])?$select['value']:''?>" <?=(in_array($select['value'],explode(',', $row['value']))?'selected':'')?>><?=!empty($select['label'])?$select['label']:''?> 
                                    </option>
                                <?php } } ?>
                            </select>
                        <?php }else{ ?>
                            <select data-parsley-errors-container="#errors-<?= $row['name'] ?>" class="chosen-select <?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?>>
                               <option value="">Select</option>
                               <?php if(count($row['values']) > 0) {
                                   foreach ($row['values'] as $select) {
                                       if(!empty($select['label'])) {
                                          ?>
                                          <option id="<?=!empty($select['value'])?$select['value']:''?>" data-detail="<?= !empty($select['label'])?$select['label']:'' ?>" value="<?=!empty($select['value'])?$select['value']:''?>" <?php if(isset($row['value']) && ($row['value'] == $select['value'])){echo 'selected="true"';}?> ><?=!empty($select['label'])?$select['label']:''?></option>
                                      <?php } } } //select loop ?>
                                  </select>
                              <?php } ?> 


                              <span id="errors-<?= $row['name'] ?>"></span>
                          </div>
                          </div>
                  </div>
              <?php } ?>
            <?php
               } else if ($row['type'] == 'hidden' || $row['type'] == 'button') { ?>
            <?php if ($row['type'] == 'button') { ?>
            <div class="col-sm-12">
                  <div class="form-group">
                     <div class="col-md-12 fb-button form-group">
                        <button name="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" type="<?= !empty($row['type']) ? $row['type'] : '' ?>" class="<?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" style="<?= !empty($row['style']) ? $row['style'] : '' ?>"><?= !empty($row['label']) ? $row['label'] : '' ?></button>
                     </div>
                  </div>
            </div>
            <?php } ?>
            <?php if ($row['type'] == 'hidden') { ?>
            <div class="col-md-6">
               <input type="hidden" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" />
            </div>
            <?php } 
               } else if ($row['type'] == 'header') { ?>
            <div class="col-sm-12">
               <div class="form-group">
                  <h2 class="aai-header-title col-md-12 aai-form-ttle"><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
               </div>
            </div>
            <?php } else if($row['type'] == 'file') { ?>
            <div class="col-md-6 col-sm-12  " id="div_<?= $row['name'] ?>">
                  <div class="form-group">
                     <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <div class="col-md-12">
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
                  </div>
                </div>
            </div>
            <?php } 
               } //foreach ?>
            <div class="col-xs-12">
               <div class="pull-right btn-section">
                  <div class="btn-group">
                     <button type="submit" class="btn btn-default" name="draft_wrform" id="draft_wrform" style="default" onclick="$('#saveAsDraftL7').val(1);">Save as Draft</button>
                            <button type="submit" class="btn btn-default" name="submit" id="submit3" value="submit" style="default">Completed</button>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
   </div>