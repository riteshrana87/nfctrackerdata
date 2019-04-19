<?php //echo htmlspecialchars_decode($pp_forms[0]['form_data']);exit;               ?>
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
    var form_field_name = '<?php echo json_encode($form_field_data_name);?>';
    var ks_id = '<?=!empty($edit_data[0]['ks_id'])?$edit_data[0]['ks_id']:''?>';

</script>
<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <form action="<?= base_url('ClothingMoney/insert') ?>" method="post" id="ksform" name="ksform" data-parsley-validate enctype="multipart/form-data">
            <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Clothing Money <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group capsBtn">
                    <button type="submit" onclick="needToConfirm = false;"  class="btn btn-default" name="submit_cmform" id="submit_cmform" value="submit" style="default">Add</button>
                    <a href="<?=base_url($this->viewname .'/index/'.$ypid)?>" class="btn btn-default" style="default">Back To Clothing Money</a>   
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"> CARE HOME YP LIST</a>          
                    <a href="<?= base_url('YoungPerson/view/' . $ypid) ?>" class="btn btn-default" style="default">Back To YP Info</a>
                                
                </div>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <?php if (($this->session->flashdata('msg'))) { ?>
            <div class="col-sm-12 text-center" id="div_msg">
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        <?php } ?>
        <div class="row">            
                <?php
                if (!empty($ks_form_data)) {
                    foreach ($ks_form_data as $row) {

                        if ($row['type'] == 'textarea') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <textarea 
                                            class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'tinymce') ? 'tinyeditor' : '' ?>" 
                                            <?= !empty($row['required']) ? 'required=true' : '' ?>
                                            name="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                            placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                            <?php if ($row['subtype'] != 'tinymce') { ?>
                                                <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                                <?= !empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : '' ?>
                                            <?php } ?>
                                            id="<?= !empty($row['name']) ? $row['name'] : '' ?>" > <?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?></textarea>

                                    </div>
                                </div>
                            </div>
                            <?php
                        } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
                            ?>
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
                                                           <?= isset($row['min']) ? 'min="' . $row['min'] . '"' : '' ?>
                                                           <?php /*($row['name'] == 'money_out')?"max =".$total_balance."":'' */ ?>
                                                           <?= isset($row['step']) ? 'step="' . $row['step'] . '"' : '' ?>
                                                           placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                                           value="<?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>" 
                                                           <?= ($row['type'] == 'date') ? 'readonly' : '' ?> data-parsley-errors-container="#errors-container<?= $row['name'] ?>" <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'readonly' : '' ?> />
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

                            <?php
                                }
                                else if($row['type'] == 'radio-group')
                                {
                                ?>
                                <div class="col-sm-12">
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
                                } else if($row['type'] == 'checkbox-group')
                                {
                                ?>
                                <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                
                                                 <div class="checkbox-group">
                                                 <?php if(count($row['values']) > 0) {
                                                    $checkedValues =array();
                                                    if(!empty($edit_data[0][$row['name']]))
                                                    {
                                                    $checkedValues = explode(',',$edit_data[0][$row['name']]);
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
                                }else if($row['type'] == 'select')
                                {
                                    ?>
                                    <div class="col-sm-6">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                             
                                                 <select class="chosen-select <?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?>>
                                                 <option value="">Select</option>
                                                 
                                                 <?php if(!empty($row['description']) && $row['description'] == 'get_user'){
                                                 $get_data = $this->common_model->get_user(); 
                                                 foreach ($get_data as $rowdata) {?>
                                                  <option value="<?=!empty($rowdata['login_id'])?$rowdata['login_id']:''?>"><?=!empty($rowdata['username'])?$rowdata['username']:''?></option>
                                                <?php
                                    } }else if(!empty($row['description']) && $row['description'] == 'get_medication'){
                                        $get_medication_data = $this->common_model->get_medication($ypid);                      
                                                 foreach ($get_medication_data as $rowdata) {?>
                                                  <option value="<?=!empty($rowdata['medication_id'])?$rowdata['medication_id']:''?>"><?=!empty($rowdata['medication_name'])?$rowdata['medication_name']:''?></option>
                               
                                                 <?php }}else{?>
                                                 <?php if(count($row['values']) > 0) {
                                                 foreach ($row['values'] as $select) {
                                                     if(!empty($select['label'])) {
                                                  ?>
                                                  <option value="<?=!empty($select['value'])?$select['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $select['value'])?'selected="true"':!empty($select['selected'])?'selected="true"':''?> ><?=!empty($select['label'])?$select['label']:''?></option>
                                                <?php } } } }//select loop ?>
                                                
                                                 </select>
                                                
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {
                            ?>
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
                            <?php } ?>
                            <?php
                        } else if ($row['type'] == 'header') {
                            ?>
                            <div class="col-sm-12">
                                <div class="">
                                    <h1 class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></h1>
                                </div>
                            </div>
                        <?php } else if($row['type'] == 'file')
                                {?>
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
                                                            'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : '',
                                                            'filePathMain' => $this->config->item('cm_img_base_url') . $ypid,
                                                            'filePathThumb' => $this->config->item('cm_img_base_url_small') . $ypid
                                                        );
                                                        echo getFileView($fileViewArray);
                                                    ?>
                                            </div>

                                        </div>
                                    </div>
                                <?php } ?>
                        <?php
                    } //foreach
                    ?>
                
                        <div class="col-sm-12 capsBtn ">
                            <div class="">
                                <input type="hidden" name="draft_data" id="draft_data" value="0">
                                <input type="hidden" name="yp_id" id="yp_id" value="<?php echo $ypid; ?>">
                                <input type="hidden" name="ks_id" id="ks_id" value="<?=!empty($edit_data[0]['ks_id'])?$edit_data[0]['ks_id']:''?>">

                              <button type="submit" onclick="needToConfirm = false;"  class="btn btn-default updat_bn" name="submit_cmform" id="submit_cmform" value="submit" style="default">Add</button> 
                                  <div class="pull-right btn-section">
											<div class="btn-group">
										           
                                <a href="<?= base_url('YoungPerson/view/' . $ypid) ?>" class="btn btn-default" style="default">Back To YP Info</a>
                                <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="pull-right btn" name="submit_ppform" id="submit_ppform" value="submit" style="default"> CARE HOME YP LIST</a>
                                <a href="<?=base_url($this->viewname .'/index/'.$ypid)?>" class="btn btn-default " style="default">Back To Clothing Money</a>
                                        
                            </div>
                            </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

            </form>        
        </div>

    </div>
</div>
<script type="text/javascript">
    var total_balance = <?=!empty($total_balance)?$total_balance:0?>
</script>