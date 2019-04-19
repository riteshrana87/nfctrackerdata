<div class="panel panel-default incident_process">
    <div class="panel-heading aai-page-header" role="tab" id="headingSix">
        <h4 class="panel-title">
                INCIDENT L6 PROCESS (COMPLAINTS)
        </h4>
    </div>
        <div class="panel-body form-horizontal">
            <div class="row aai-module clear-div-flex">
                <div class="col-md-4 col-sm-6  ">
                        <div class="form-group">
                            <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                                <div class="col-md-12">
                                    <input type="text" disabled="true" class="form-control" value="<?= (isset($l6_report_compiler))? $l6_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>">                                
                                    <input type="hidden" name="l6_report_compiler" class="form-control" value="<?= (isset($l6_report_compiler))? $l6_report_compiler:$loggedInUser['FIRSTNAME'].' '.$loggedInUser['LASTNAME'] ?>">
                                </div> 
                        </div>
                </div>                
                <?php 
                if (!empty($l6_form_data)) {
                    foreach ($l6_form_data as $row) {
                        $dd=$row['name'];
                        if ($row['type'] == 'textarea') { ?>
                            <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                    <div class="form-group">
                                        <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                        <div class="col-md-12">
                                        <?php if (!empty($preveditl6Data)) {
                                            $diff = new HtmlDiff(html_entity_decode($preveditl6Data["$dd"]['value']), html_entity_decode($row['value']));
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
                            <?php if($row['name'] == 'is_other_injured'){ if($is_other_injured == 1){ ?>
                                <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                        <div class="form-group">
                                            <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                            <div class="col-md-12">
                                            <?php if (!empty($preveditl6Data)) {
                                                $diff = new HtmlDiff(html_entity_decode($preveditl6Data["$dd"]['value']), html_entity_decode($row['value']));
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
                                <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                        <div class="form-group">
                                            <h2 class="aai-header-title col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                            <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                                                if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])){
                                                    $row['value'] = timeformat($row['value']);
                                                }
                                                if($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])){
                                                    $row['value'] = configDateTime($row['value']);
                                                }
                                                ?>
                                                    <div class="col-md-12">
                                                    <?php } ?>
                                                    <div class=" col-md-12 <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append date dob' : 'input-group input-append date' : '' ?> ">

                                                        <?php if (!empty($preveditl6Data)) {
                                                            $diff = new HtmlDiff(html_entity_decode($preveditl6Data["$dd"]['value']), html_entity_decode($row['value']));
                                                            $diff->build();
                                                            echo $diff->getDifference();
                                                            ?>
                                                        <?php } else { ?>
                                                            <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) { ?>
                                                    </div>
                                            <?php } ?>
                                        </div>
                                </div>
                            <?php } ?>
                            <?php
                        } else if($row['type'] == 'radio-group') { ?>
                            <div class="col-md-4 col-sm-12  " id="div_<?= $row['name'] ?>">
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

                                <?php if ($row['name']=='l6_sanction_required'){ ?>
                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <h2 class="aai-header-title col-md-12 aai-form-ttle">Sequence of events</h2>
                                            </div>
                                    </div> 


                                    <div class="col-sm-12 clear-div-flex">
                                        <?php if(!empty($l6sequence_data)){ 
                                            $ilsx=1;
                                            foreach ($l6sequence_data as $key=>$value) { 

                                                ?>
                                                <div class="col-md-6 col-md-12 dynamic-div" id="item_new_sequence_six_<?php echo $ilsx; ?>">
                                                    <div class="form-group">
                                                       
                                                                <div class="col-md-6"><label>Sequence Number</label></div>
                                                                <div class="col-md-6">
                                                                    <?php echo 'S'.$ilsx;?>
                                                                </div>
                                                    </div>
                                                    <div class="form-group">
                                                                <div class="col-md-6"><label>Who raised Complaint</label></div>
                                                                <div class="col-md-6">
                                                                    <?php if(!empty($bambooNfcUsers)) {
                                                                        foreach ($bambooNfcUsers as $select) { 
                                                                            if(isset($value['l6who_raised_complaint']) && ($value['l6who_raised_complaint'] == $select['user_type'].'_'.$select['user_id'])){ ?>

                                                                                <?php if (!empty($preveditl6Data) && $l6sequence_data_prev[$key]['l6who_raised_complaint'] != $value['l6who_raised_complaint']) {
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
                                                                        <div class="col-md-6"><label>What happened / what was done (include Senior Cover instructions)</label></div>
                                                                        <div class="col-md-6">
                                                                            <?php if (!empty($l6sequence_data_prev)) {
                                                                                $diff = new HtmlDiff($l6sequence_data_prev[$key]['l6what_happened'],$value['l6what_happened']);
                                                                                $diff->build();
                                                                                echo $diff->getDifference();
                                                                                ?>
                                                                            <?php } else { ?>
                                                                                <?= !empty($value['l6what_happened']) ? $value['l6what_happened'] : '' ?>
                                                                            <?php } ?>
                                                                </div> 
                                                            </div> 

                                                            <div class="form-group">
                                                                <div class="col-md-6"><label>Date</label></div>
                                                                    <div class="col-md-6">
                                                                       <?php if (!empty($l6sequence_data_prev)) {
                                                                        $diff = new HtmlDiff($l6sequence_data_prev[$key]['l6sequence_date'],$value['l6sequence_date']);
                                                                        $diff->build();
                                                                        echo $diff->getDifference();
                                                                        ?>
                                                                    <?php } else { ?>
                                                                        <?= !empty($value['l6sequence_date']) ? $value['l6sequence_date'] : '' ?>
                                                                    <?php } ?>
                                                                 </div>
                                                          </div>
                                                    <div class="form-group">
                                                            <div class="col-md-6"><label>Time</label></div>
                                                            <div class="col-md-12">
                                                            <div class="input-group input-append bootstrap-timepicker">
                                                               <?php if (!empty($l6sequence_data_prev)) {
                                                                $diff = new HtmlDiff($l6sequence_data_prev[$key]['l6time_sequence'],$value['l6time_sequence']);
                                                                $diff->build();
                                                                echo $diff->getDifference();
                                                                ?>
                                                            <?php } else { ?>
                                                                <?= !empty($value['l6time_sequence']) ? $value['l6time_sequence'] : '' ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>

                                            <?php  $ilsx++; }  ?>
                                            <script>
                                                var xsix = "<?php echo $ilsx;?>";
                                            </script>
                                        <?php } ?>
                                    </div>
                                <?php }?>

                            <?php } else if($row['type'] == 'checkbox-group') { ?>
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
                                    <div class="col-md-4 col-sm-6  " id="div_<?= $row['name'] ?>">
                                            <div class="form-group">
                                                <h2 class="aai-header-title col-md-12"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                <div class="col-md-12">
                                                <?php if(!empty($bambooNfcUsers)) {
                                                    foreach ($bambooNfcUsers as $select) {  
                                                        if(isset($row['value']) && ($row['value'] == $select['user_type'].'_'.$select['user_id'])){ ?>

                                                            <?php if (!empty($preveditl7Data) && $preveditl6Data["$dd"]['value'] != $row['value']) {
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
                                                                    if(!empty($preveditl6Data)){
                                                                        $staff_present = array_diff($row['value'],$preveditl6Data["$dd"]['value']);
                                                                        if(!empty($staff_present)){
                                                                         if(in_array($select['user_type'].'_'.$select['user_id'],$staff_present)){

                                                                            $diff = new HtmlDiff('',$select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'].',');
                                                                            $diff->build();
                                                                            echo $diff->getDifference();

                                                                        }else{ 
                                                                            if(in_array($select['user_type'].'_'.$select['user_id'],$row['value'])){
                                                                                echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'].',';
                                                                            }
                                                                        } 
                                                                    } } }} ?> 
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
                                                                                    $staff_present_data = array_diff($row['value'],$preveditl6Data["$dd"]['value']);
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
                                                                                           <?php if (!empty($preveditl6Data) && $preveditl6Data["$dd"]['value'] != $row['value']) {
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
                                                                                <h2 class="aai-form-ttle col-md-12"><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
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