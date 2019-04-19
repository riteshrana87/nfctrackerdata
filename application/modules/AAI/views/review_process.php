<div class="panel panel-default incident_process">
   <div class="panel-heading" role="tab" id="headingSeven">
      <h4 class="panel-title">
         <a class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseReview" aria-expanded="false" aria-controls="collapseReview">
         REVIEW BY YP WITH SENIOR STAFF
         </a>
      </h4>
   </div>
   <div id="collapseReview" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
      <div class="panel-body">
         <div class="row aai-module">
           
            <?php 
               if (!empty($review_form_data)) {
                   foreach ($review_form_data as $row) {
               
                       if ($row['type'] == 'textarea') { ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                     <textarea class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'tinymce') ? 'tinyeditor' : '' ?>" <?= !empty($row['required']) ? 'required=true' : '' ?> name="<?= !empty($row['name']) ? $row['name'] : '' ?>" placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>" <?php if ($row['subtype'] != 'tinymce') { ?> <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?> <?= !empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : '' ?>
                        <?php } ?> id="<?= !empty($row['name']) ? $row['name'] : '' ?>" ><?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?></textarea>
                  </div>
               </div>
            </div>
            <?php
               } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') { ?>
            <?php if($row['name'] == 'is_other_injured'){ if($is_other_injured == 1){ ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
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
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                     <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                        if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])){
                            $row['value'] = timeformat($row['value']);
                        }
                        if($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])){
                            $row['value'] = $row['value'];
                            //$row['value'] = configDateTime($row['value']);
                        }
                        ?>
                     <div class="row">
                        <div class="col-sm-4">
                           <?php } ?>
                           <div class="<?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date' : '' ?> ">
                              <input type="<?= (!empty($row['type']) && $row['type'] == 'number') ? 'number' : ((!empty($row['subtype']) && $row['subtype'] != 'time') ? $row['subtype'] : 'text') ?>"  
                                 class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= ($row['type'] == 'date') ? 'adddate addtime_data' : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'addtime' : '' ?> "  
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
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <?php } ?>
            <?php
               } else if($row['type'] == 'radio-group') { ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <div class="radio-group">
                        <?php if(count($row['values']) > 0) {
                           foreach ($row['values'] as $radio) {
                               if(!empty($radio['label'])) {
                            ?>
                        <div class="<?=!empty($row['inline'])?'radio-inline':'radio'?>">
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
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
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
               } else if ($row['type'] == 'select') { 
                   ?>
            <?php if($row['description'] == 'bamboo_lookup'){ ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <select data-parsley-errors-container="#errors-<?= $row['name'] ?>" class='form-control chosen-select' id='<?= $row['name'] ?>' name='<?= $row['name'] ?>'>
                        <?php if(!empty($bambooNfcUsers)) {
                           foreach ($bambooNfcUsers as $select) {  ?>
                        <option value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?php if(isset($row['value']) && ($row['value'] == $select['user_type'].'_'.$select['user_id'])){echo 'selected="true"';}?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;?> </option>
                        <?php } } ?>
                     </select>
                     <span id="errors-<?= $row['name'] ?>"></span>
                  </div>
               </div>
            </div>
            <?php }else if($row['description'] == 'bamboo_lookup_multiple') { ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <select <?=(!empty($row['description']) && $row['description'] == 'bamboo_lookup_multiple')?'multiple':''?> data-parsley-errors-container="#errors-<?= $row['name'] ?>" class='form-control chosen-select' id='<?= $row['name'] ?>' name='<?=(!empty($row['name']) && (!empty($row['description'])))?$row['name'].'[]':''?>'>
                        <?php if(!empty($bambooNfcUsers)) {
                           $userAr = array();
                           foreach ($bambooNfcUsers as $select) {  ?>
                        <option value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?=(in_array($select['user_type'].'_'.$select['user_id'], $row['value'])?'selected':'')?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;?> 
                        </option>
                        <?php } } ?>
                     </select>
                     <span id="errors-<?= $row['name'] ?>"></span>
                  </div>
               </div>
            </div>
            <?php }else{ ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <select data-parsley-errors-container="#errors-<?= $row['name'] ?>" class="chosen-select <?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?>>
                        <option value="">Select</option>
                        <?php if(count($row['values']) > 0) {
                           foreach ($row['values'] as $select) {
                               if(!empty($select['label'])) {
                            ?>
                        <option id="<?=!empty($select['value'])?$select['value']:''?>" data-detail="<?= !empty($select['label'])?$select['label']:'' ?>" value="<?=!empty($select['value'])?$select['value']:''?>" <?php if(isset($row['value']) && ($row['value'] == $select['value'])){echo 'selected="true"';}?> ><?=!empty($select['label'])?$select['label']:''?></option>
                        <?php } } } //select loop ?>
                     </select>
                     <span id="errors-<?= $row['name'] ?>"></span>
                  </div>
               </div>
            </div>
            <?php } ?>
            <?php
               } else if ($row['type'] == 'hidden' || $row['type'] == 'button') { ?>
            <?php if ($row['type'] == 'button') { ?>
            <div class="col-sm-12">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <div class="fb-button form-group">
                        <button name="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" type="<?= !empty($row['type']) ? $row['type'] : '' ?>" class="<?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" style="<?= !empty($row['style']) ? $row['style'] : '' ?>"><?= !empty($row['label']) ? $row['label'] : '' ?></button>
                     </div>
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
               <div class="">
                  <h1 class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></h1>
               </div>
            </div>
            <?php } else if($row['type'] == 'file') { ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2 class="page-title"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
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
                     <button type="button" onclick="location.reload()" class="btn btn-default" name="Cancel" value="Cancel" style="default">Cancel</button>
                     <button type="submit" class="btn btn-default" name="submit" id="submit3" value="submit" style="default">Continue</button> 
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
   </div>
</div>