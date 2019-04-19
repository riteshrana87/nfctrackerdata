
<div class="panel panel-default">
    <div class="panel-heading aai-page-header" role="tab" id="headingl8">
        <h4 class="panel-title">
                INCIDENT L8 PROCESS (Staff Member or injury to a person that is not YP) 
        </h4>
    </div>
        <div class="panel-body form-horizontal">
            <div class="row aai-module clear-div-flex">
                <?php //pr($editl1Data);
				
                if (!empty($l8_form_data)) {
                    foreach ($l8_form_data as $row) {
						
                        if ($row['type'] == 'textarea') {							
                            ?>
                            <div class="col-md-4 col-sm-6" id="div_<?php echo $row['name'];?>">
                                    <div class="form-group">
                                        <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                        <div class="col-md-12">
                                       <?=!empty($l8_form_data[0][$row['name']])?nl2br(html_entity_decode($l8_form_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                   </div>
                                    </div>
                            </div>
						
                            
							<?php 
                        } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') { ?>
							
                        <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                        <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                        <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') { ?>
                                                <div class="col-sm-12">
                                                <?php } ?>
                                                <div class=" col-md-12 <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date adddate' : '' ?>">
                                                    <?php if($row['name']=='l8_date_of_incident'){ echo $l1date_of_incident;}elseif($row['name']!='l8_time_incident'){?><?=!empty($l8_form_data[0][$row['name']])?nl2br(html_entity_decode($l8_form_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?><?php } else { echo $l1time_of_incident;?><?php }?>
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
							
			<?php      }else if($row['type'] == 'radio-group') { ?>
							
                            <div class="col-md-4 col-sm-12" id="div_<?php echo $row['name'];?>">
                                    <div class="form-group">
                                        <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        <div class="col-md-12">
                                         <div class="radio-group">
                                         <?php if(count($row['values']) > 0) {
                                         foreach ($row['values'] as $radio) {
                                             if(!empty($radio['label'])) {
                                          ?>
                                         <div class="<?=!empty($row['inline'])?'radio-inline':'radio'?>">

                                             <label ><input hidden="true" name="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?> 
                                                    value="<?=!empty($radio['value'])?$radio['value']:''?>" <?= (isset($row['value']) && $row['value'] == $radio['value'])? 'checked="checked"' :(!isset($row['value']) && !empty($radio['selected']))?'checked="checked"':''?> type="radio">
                                                    
                                                    <?php 
                                                    if(isset($row['value']) && $row['value'] == $radio['value']){ ?>
                                                           <?=!empty($radio['label'])?$radio['label']:''?>                                                                        
                                                    <?php } ?></label>
                                        </div>
                                        <?php } } } //radio loop ?>
                                        </div>
                                    </div>
                                    </div>
                            </div>
							
					 
                                <?php
                        } else if($row['type'] == 'checkbox-group') { ?>
                            <div class="col-md-4 col-sm-6" id="div_<?php echo $row['name'];?>">
                                    <div class="form-group">
                                        <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        <div class="col-md-12">
                                        <div class="checkbox-group">
                                         <?php if(count($row['values']) > 0) {
                                            $checkedValues =array();
                                            if(!empty($l8_form_data[0][$row['name']])) {
                                                $checkedValues = explode(',',$l8_form_data[0][$row['name']]);
                                            }
                                        foreach ($row['values'] as $checkbox) {
                                             if(!empty($checkbox['label'])) { ?>
                                            
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
                            <div class="col-md-4 col-sm-6">
                                    <div class="panel-body">
                                        <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        <div class="col-md-12">
                                         <select readonly class="chosen-select <?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?>>
                                         <option value="">Select</option>
                                         <?php if(count($row['values']) > 0) {
                                         foreach ($row['values'] as $select) {
                                             if(!empty($select['label'])) {
                                          ?>
                                          <option value="<?=!empty($select['value'])?$select['value']:''?>" <?=(!empty($l8_form_data[0][$row['name']]) && $l8_form_data[0][$row['name']] == $select['value'])?'selected="true"':!empty($select['selected'])?'selected="true"':''?> ><?=!empty($select['label'])?$select['label']:''?></option>
                                        <?php } } } //select loop ?>

                                         </select>
                                     </div>
                                    </div> 
                            </div>
							
							<?php 
							if($row['name']=='l8_form_status'){?>
								
								 <div class="col-md-4 col-sm-6">
										<div class="form-group">
											<h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
												<div class="col-sm-12">
													<input type="text" disabled="true" class="form-control" value="<?= (isset($l8_report_compiler))? $l8_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>">                                
													<input type="hidden" name="l8_report_compiler" class="form-control" value="<?= (isset($l8_report_compiler))? $l8_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>">
												</div> 
										</div>
								</div>      	
							<?php }?>				
							
                                <?php
                        } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {  ?>                    
                            <?php if ($row['type'] == 'button') { ?>
                                <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="fb-button col-md-12">
                                                <button name="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" type="<?= !empty($row['type']) ? $row['type'] : '' ?>" class="<?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" style="<?= !empty($row['style']) ? $row['style'] : '' ?>"><?= !empty($row['label']) ? $row['label'] : '' ?></button>
                                            </div>
                                        </div>
                                </div>
                            <?php } 
                            if ($row['type'] == 'hidden') { ?>
                                <div class="col-sm-12">
                                    <input type="hidden" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" />
                                </div>
                            <?php } 
                        } else if ($row['type'] == 'header') { ?>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <h2 class="aai-form-ttle col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                </div>
								
                            </div>                                        
                        <?php } else if($row['type'] == 'file') { ?>
                        <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                    <input type="file" name="<?=!empty($row['name'])?$row['name'].'[]':''?>" id="<?=!empty($row['name'])?$row['name']:''?>"  class="<?=!empty($row['className'])?$row['className']:''?>" 
                                        <?=!empty($row['multiple'])?'multiple="true"':''?> <?= !empty($row['required']) ? 'required=true' : '' ?>>
                                    <h2></h2>
                                    <div class="col-md-12">
                                        <?php 
                                        /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                        $fileViewArray = array(
                                            'fileArray' => (isset($l8_form_data[0][$row['name']]) && !empty($l8_form_data[0][$row['name']]))? $l8_form_data[0][$row['name']] : $row['value'],
                                            'filePathMain' => $this->config->item('ks_img_base_url') . $ypid,
                                            'filePathThumb' => $this->config->item('ks_img_base_url_small') . $ypid,
                                            'deleteFileHidden' => 'hidden_'.$row['name']
                                        );
                                        echo getFileView($fileViewArray); 
                                    ?>
                                    <input type="hidden" name="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" id="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" value="">
                                </div>
                            </div>
                        </div>
                        <?php } 
                    } //foreach
                    ?>
					
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group">
				<input class="btn btn-default" type="submit" name="continue" value="Continue" />                                                                                                         
                            </div> 
                        </div>
                    </div> 
                     <?php } ?>
            </div> 
        </div>
</div>
