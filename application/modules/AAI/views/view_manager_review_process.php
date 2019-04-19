<div class="panel panel-default incident_process">
   <div class="panel-heading" role="tab" id="headingSeven">
      <h4 class="panel-title">
         <a class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseManagerReview" aria-expanded="false" aria-controls="collapseManagerReview">
         MANAGERS REVIEW
         </a>
      </h4>
   </div>
   <div id="collapseManagerReview" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
      <div class="panel-body">
         <div class="row aai-module">
            <?php 
               if (!empty($manager_review_form_data)) {
                   foreach ($manager_review_form_data as $row) {
                             $dd=$row['name'];
                       if ($row['type'] == 'textarea') { ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                     <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
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
                     <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
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
                              <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
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
            <label class="<?=!empty($row['toggle'])?'kc-toggle':''?>">
            <input disabled="" <?=!empty($row['toggle'])?'kc-toggle':''?>"
               name="<?=!empty($row['name'])?$row['name'].'[]':''?>" value="<?=!empty($checkbox['value'])?$checkbox['value']:''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues))?'checked="checked"':!empty($checkbox['selected'])?'checked="checked"':''?>  
               <?=!empty($row['required'])?'required=true':''?>
               type="checkbox">
            <?php if(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues)){ ?>
            <?=!empty($checkbox['label'])?$checkbox['label']:''?>
            <?php } ?>
            </label>
            </div>
            <?php } } } //radio loop ?>
            </div>
            </div>
            </div>
            </div>
            <?php
               } else if ($row['type'] == 'select') { ?>
            <?php if($row['description'] == 'bamboo_lookup'){ ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <?php if(!empty($bambooNfcUsers)) {
                        foreach ($bambooNfcUsers as $select) {  
                            if(isset($row['value']) && ($row['value'] == $select['user_type'].'_'.$select['user_id'])){  
                              echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;
                        ?>
                     <?php } } } ?>
                     <span id="errors-<?= $row['name'] ?>"></span>
                  </div>
               </div>
            </div>
            <?php }else if($row['description'] == 'bamboo_lookup_multiple') { ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <?php if(!empty($bambooNfcUsers)){
                        foreach ($bambooNfcUsers as $select){ 
                        if(in_array($select['user_type'].'_'.$select['user_id'],$row['value'])){
                        echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'].',';
                        
                        } } } ?>    
                     <span id="errors-<?= $row['name'] ?>"></span>
                  </div>
               </div>
            </div>
            <?php }else{ ?>
            <div class="col-sm-12" id="div_<?= $row['name'] ?>">
               <div class="panel panel-default tile tile-profile">
                  <div class="panel-body">
                     <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                     <?php if(count($row['values']) > 0) {
                        foreach ($row['values'] as $select) {
                            if(!empty($select['label'])) {
                         ?>
                     <?php if(isset($row['value']) && ($row['value'] == $select['value'])){ ?>
                     <?=!empty($select['label'])?$select['label']:''?>
                     <?php } ?>
                     <?php } } } //select loop ?>
                     <span id="errors-<?= $row['name'] ?>"></span>
                  </div>
               </div>
            </div>
            <?php } ?>
            <?php } else if ($row['type'] == 'hidden' || $row['type'] == 'button') { ?>
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
                     <?php
                        $fileViewArray = array(
                            'fileArray' => (isset($row['value']) && !empty($row['value']))? $row['value'] : '',
                            'filePathMain' => $this->config->item('aai_img_base_url') . $ypId,
                            'filePathThumb' => $this->config->item('aai_img_base_url_small') . $ypId,
                            'deleteFileHidden' => 'hidden_'.$row['name']
                        );
                        echo getFileViewPrint($fileViewArray); ?>
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