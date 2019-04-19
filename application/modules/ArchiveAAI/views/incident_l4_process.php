<div class="panel panel-default incident_process">
    <div class="panel-heading" role="tab" id="headingFive">
        <h4 class="panel-title">
            <a id="incident_l4_form_link" class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                INCIDENT L4 PROCESS (YP MISSING FROM CARE) 
            </a>
        </h4>
    </div>
    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
        <div class="panel-body">
            <div class="row aai-module">
                <div class="panel panel-default tile tile-profile">
                <?php
                if (!empty($l4_form_data)) {
                    foreach ($l4_form_data as $row) {
                        if ($row['type'] == 'textarea') {							
                            ?>
                            <div class="col-sm-12" id="div_<?php echo $row['name'];?>">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <textarea 
                                            class="abcd <?= !empty($row['className']) ? $row['className'] : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'tinymce') ? 'tinyeditor' : '' ?>" 
                                            <?= !empty($row['required']) ? 'required=true' : '' ?>
                                            name="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                            placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                            <?php if ($row['subtype'] != 'tinymce') { ?>
                                                <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                                <?= !empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : '' ?>
                                            <?php } ?>
                                            id="<?= !empty($row['name']) ? $row['name'] : '' ?>" ><?=!empty($editL4Data[0][$row['name']])?nl2br(html_entity_decode($editL4Data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php  if($row['label']=='Details'){
                                echo '<div class="col-xs-12">
					<div class="pull-left btn-section">
                                            <div class="btn-group">
                                                <input class="btn btn-default" id="send_worker_notification" type="button" name="sendworker" value="Send Missing YP Alert to missing team" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <input readonly placeholder="Notification sent date and time" type="text" class="form-control" name="calculate_notification_missing" id="calculate_notification_missing" value=""/>
                                            </div>
                                        </div>
                                    </div>';
                            }
                        } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') { ?>
							
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                        <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') { ?>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                <?php } ?>
                                                <div class="<?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date adddate' : '' ?>">
                                                    <input type="<?= (!empty($row['type']) && $row['type'] == 'number') ? 'number' : ((!empty($row['subtype']) && $row['subtype'] != 'time') ? $row['subtype'] : 'text') ?>"  
                                                           class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= ($row['type'] == 'date') ? 'adddate' : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'addtime' : '' ?> addtime_data"  
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
                                                <span id="errors-container<?= $row['name'] ?>"></span>
                                                <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) { ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
			<?php  if($row['label']=='Location Last Seen'){
                            echo '<div class="col-xs-12">
                                    <div class="pull-left btn-section">
                                        <div class="btn-group">
                                            <input class="btn btn-default" id="send_worker_notification" type="button" name="sendworker" value="Send Notification To S.worker" />
					</div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="panel panel-default tile tile-profile">
                                        <div class="panel-body">
                                            <input readonly placeholder="Notification sent date and time" type="text" class="form-control" name="calculate_notification_worker" id="calculate_notification_worker" value=""/>
                                        </div>
                                    </div>
                                </div>';
			}elseif($row['label']=='Time Informed'){
                            echo '<div class="col-sm-12">
                                    <div class="panel panel-default tile tile-profile">
                                        <div class="panel-body">
                                            <h2>Sequence Of Event</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" id="add_seqevent"></div>
                                <div class="clearfix"></div>
                                <div class=" section_four text-center mb30">
                                    <input type="hidden" id="delete_cpt_review_id" name="delete_cpt_review_id" value="">
                                    <a id="add_new_seq_event" class="btn btn-default" href="javascript:;">
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Sequence
                                    </a>
                                </div>';

			}elseif($row['label']=='Time YP returned'){
                            echo '<div class="col-sm-12">
                                    <div class="panel panel-default tile tile-profile">
                                        <div class="panel-body">
                                            <div class="col-sm-12 p-l-r-0">
												<h2>Total Time YP Was Missing (Hour:Minute)</h2>
                                            </div>  
                                            <div class="col-sm-4 p-l-r-0">
                                                <input type="text" class="form-control" name="" id="" />
                                            </div>
                                        </div>
                                    </div>
				</div>';
			} 
                       }else if($row['type'] == 'radio-group') { ?>
                            <div class="col-sm-12" id="div_<?php echo $row['name'];?>">
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
                                                 value="<?=!empty($radio['value'])?$radio['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $radio['value'])?'checked="checked"':!empty($radio['selected'])?'checked="checked"':''?>  type="radio">
                                             <?=!empty($radio['label'])?$radio['label']:''?></label>
                                        </div>
                                        <?php } } } //radio loop ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <?php
                        } else if($row['type'] == 'checkbox-group') { ?>
                            <div class="col-sm-12" id="div_<?php echo $row['name'];?>">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        <div class="checkbox-group">
                                         <?php if(count($row['values']) > 0) {
                                            $checkedValues =array();
                                            if(!empty($edit_data[0][$row['name']])) {
                                                $checkedValues = explode(',',$edit_data[0][$row['name']]);
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
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>

                                         <select class="chosen-select <?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?>>
                                         <option value="">Select</option>
                                         <?php if(count($row['values']) > 0) {
                                         foreach ($row['values'] as $select) {
                                             if(!empty($select['label'])) {
                                          ?>
                                          <option value="<?=!empty($select['value'])?$select['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $select['value'])?'selected="true"':!empty($select['selected'])?'selected="true"':''?> ><?=!empty($select['label'])?$select['label']:''?></option>
                                        <?php } } } //select loop ?>

                                         </select>
                                    </div> 
                                </div>
                            </div>
                                <?php
                        } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {  ?>                    
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
                            <?php } 
                            if ($row['type'] == 'hidden') { ?>
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
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2 class="page-title"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                    <input type="file" name="<?=!empty($row['name'])?$row['name'].'[]':''?>" id="<?=!empty($row['name'])?$row['name']:''?>"  class="<?=!empty($row['className'])?$row['className']:''?>" 
                                        <?=!empty($row['multiple'])?'multiple="true"':''?> <?= !empty($row['required']) ? 'required=true' : '' ?>>
                                    <h2></h2>
                                        <?php 
                                        /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                        $fileViewArray = array(
                                            'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
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
    </div>
</div>
