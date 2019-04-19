
<div class="panel panel-default incident_process">
    <div class="panel-heading aai-page-header" role="tab" id="headingl9">
        <h4 class="panel-title">
                INCIDENT L9 PROCESS (Insurance Notification) 
        </h4>
    </div>
        <div class="panel-body form-horizontal">
            <div class="row aai-module clear-div-flex">
                <div class="col-md-4 col-sm-6  ">
                        <div class="form-group">
                            <h2 class="aai-header-title col-md-12">Reference ID number <span class="astrick">*</span></h2>
                                <div class="col-md-12">
                                    <input type="text" disabled="true" class="form-control" value="<?= $l9reference_number ?>">

                                    <input type="hidden" name="l9_reference_number" class="form-control" value="<?= $l9reference_number ?>">
                                </div>
                    </div>
                </div> 
              
                <?php 
				
                if (!empty($l9_form_data)) {
                    foreach ($l9_form_data as $row) {
						
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
						
                            
							<?php 
                        } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
                            if($row['name'] == 'l9_date_of_incident'){ ?>
                                <div class="col-md-4 col-sm-6  ">
                               <div class="form-group">
                                   <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                                       <div class="col-md-12">
						<?php 
                                $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'L9');
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
                            
                    <?php 
                        $check_user_data = checkUserDetail($incidentData['incident_id'],$l9reference_number,$incidentData['yp_id'],'L9',$loggedInUser['ID']); 
                        if(empty($check_user_data)){
                            $l9_report_compiler = getUserName($loggedInUser['ID']); 
                            ?>
                           <input type="text" disabled="true" class="form-control" value="<?= (isset($l9_report_compiler))? $l9_report_compiler:'' ?>">
                           
                        <?php } ?> 
                                    </div>
                            </div>
                        </div>    
                         <?php   } ?>
							
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
                                    class="<?=!empty($row['className']) ? $row['className'] : ''?> <?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? '' : ''?> addtime_data"
                                    <?=!empty($row['required']) ? 'required=true' : ''?>
                                    name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>"
                                    <?=!empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : ''?>
                                    <?=!empty($row['min']) ? 'min="' . $row['min'] . '"' : ''?>
                                    <?=!empty($row['max']) ? 'max="' . $row['max'] . '"' : ''?>
                                    <?=!empty($row['step']) ? 'step="' . $row['step'] . '"' : ''?>
                                    placeholder="<?=!empty($row['placeholder']) ? $row['placeholder'] : ''?>"
                                    value="<?php if($row['name']=='l9_date_of_incident'){ echo configDateTime($l1date_of_incident);}elseif($row['name']!='l9_time_incident'){?><?=!empty($l9_form_data[0][$row['name']])?nl2br(html_entity_decode($l9_form_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?><?php } else { echo timeFormatAi($l1time_of_incident);?><?php }?>" <?=($row['type'] == 'date') ? 'readonly' : ''?> data-parsley-errors-container="#errors-container<?=$row['name']?>" <?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? 'readonly' : ''?> />
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
							
			<?php }else if($row['type'] == 'radio-group') { ?>
							
                            <div class="col-sm-12" id="div_<?php echo $row['name'];?>">
                                    <div class="form-group">
                                        <h2 class="aai-header-title col-md-12"> <?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        <div class="col-md-12">
                                         <div class="radio-group">
                                         <?php if (count($row['values']) > 0) {
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
                                        } ?>
                                        </div>
                                    </div>
                                    </div>
                            </div>
							
					 
                                <?php
                        } else if($row['type'] == 'checkbox-group') { ?>
                            <div class="col-md-12 col-sm-6  " id="div_<?php echo $row['name'];?>">
                                <div class="form-group mb16">
                                    <h2 class="aai-header-title-radio aai-header-title-checkbox col-md-2"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                    <div class="col-md-10 check-box-aai">
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
                        } else if ($row['type'] == 'select') { ?>
                            <?php if ($row['className'] == 'bamboo_lookup') { ?>
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
                        } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {  ?>                    
                            <?php if ($row['type'] == 'button') { ?>
                                <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="col-md-12 fb-button form-group">
                                                <button name="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" type="<?= !empty($row['type']) ? $row['type'] : '' ?>" class="<?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" style="<?= !empty($row['style']) ? $row['style'] : '' ?>"><?= !empty($row['label']) ? $row['label'] : '' ?></button>
                                            </div>
                                        </div>
                                </div>
                            <?php } 
                            if ($row['type'] == 'hidden') { ?>
                                <div class="col-md-6">
                                    <input type="hidden" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" />
                                </div>
                            <?php } 
                        } else if ($row['type'] == 'header') { ?>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <h2 class="aai-header-title col-md-12 aai-form-ttle"><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                </div>
                                <?php if(isset($is_other_injured) && $is_other_injured == 1){ 
                                    if(!empty($l1_data) && !empty($l1_data['l1_reference_number'])){
                                      echo $l1_data['l1_reference_number'].'</br>';
                                    }
                                    if(!empty($l2_data) && !empty($l2_data['l2_l3_reference_number'])){ 
                                      echo $l2_data['l2_l3_reference_number'].'</br>';  
                                    }
                                    if(!empty($l4_data) && !empty($l4_data['l4_reference_number'])){ 
                                        echo $l4_data['l4_reference_number'].'</br>';  
                                   }
                                  /* if(!empty($l5_data) && !empty($l5_data['l5_reference_number'])){ 
                                        echo $l5_data['l5_reference_number'].'</br>';  
                                   }*/
                                   if(!empty($l6_data) && !empty($l6_data['l6_reference_number'])){ 
                                        echo $l6_data['l6_reference_number'].'</br>';  
                                   }

                                   /*if(!empty($l7_data) && !empty($l7_data['l7_reference_number'])){ 
                                        echo $l7_data['l7_reference_number'].'</br>';  
                                   }*/
                                    ?>
                            <?php } ?>
								
                            </div>                                        
                        <?php } else if($row['type'] == 'file') { ?>
                        <div class="col-md-4 col-sm-6  ">
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
                        <?php }  } //foreach ?>
					
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-default" name="draft_wrform" id="draft_wrform" style="default" onclick="$('#saveAsDraftL9').val(1);">Save as Draft</button>
                                <button type="submit" class="btn btn-default" name="submit" id="submit3" value="submit" style="default">Completed</button>                                                                                                                                                                             
                            </div> 
                        </div>
                    </div> 
                     <?php } ?>
                </div>
            </div> 
</div>
