<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var MedicationId = '<?= !empty($edit_data[0]['medication_id']) ? $edit_data[0]['medication_id'] : '' ?>';
</script>
<?php $this->method = $this->router->fetch_method(); ?>
<div id="page-wrapper">
    <div class="main-page">
        <!-- <h1 class="page-title">Medical Information <small>New Forest Care</small></h1> -->
        <div class="row">
            <div class="col-xs-12">
                <div class="sticky-heading" id="sticky-heading">
                    <h1 class="page-title">
                        MEDICAL INFORMATION <small>New Forest Care</small>
                        <div class="pull-right for-tab">
                            <div class="btn-group">
                                <a href="<?=base_url('Medical/index/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> MEDS
                                </a>
                                <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                                <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>
                                <a href="<?= base_url('Medical/DownloadMedicaltion/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                            </div>
                        </div>
                    </h1>
                    <h1 class="page-title">
                        <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                    </h1>
                </div>
                <?php if(($this->session->flashdata('msg'))){ ?>
                    <div class="col-sm-12 text-center" id="div_msg">
                        <?php echo $this->session->flashdata('msg');?>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                <?php if(checkPermission('Medical','add')){ ?>
                    <div class="panel panel-default tile tile-profile m-t-10">
                        <div class="panel-body min-h-310">
                            <h2>Add New Medication</h2>
                            <form action="<?=base_url('Medical/insert_medication')?>" method="post" id="docsform" name ="docsform" data-parsley-validate enctype="multipart/form-data">

                                <?php
                                if(!empty($form_data))
                                    {   $n=0;
                                        foreach ($form_data as $row) {

                                            if($n == 2)
                                            {
                                                echo '<div class="clearfix"></div>';
                                                $n = 0;
                                            }
                                            if($row['type'] == 'textarea') {$n =0;
                                                ?>
                                                <div class="col-sm-12">
                                                    <div class="panel panel-default tile tile-profile">
                                                        <div class="panel-body">
                                                            <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>

                                                            <textarea 
                                                            class="<?=!empty($row['className'])?$row['className']:''?> <?=($row['subtype'] == 'tinymce')?'tinyeditor':''?>" 
                                                            <?=!empty($row['required'])?'required=true':''?>
                                                            name="<?=!empty($row['name'])?$row['name']:''?>" 
                                                            placeholder="<?=!empty($row['placeholder'])?$row['placeholder']:''?>"
                                                            <?php if($row['subtype'] != 'tinymce') { ?>
                                                                <?=!empty($row['maxlength'])?'maxlength="'.$row['maxlength'].'"':''?>
                                                                <?=!empty($row['rows'])?'rows="'.$row['rows'].'"':''?>
                                                            <?php } ?>
                                                            id="<?=!empty($row['name'])?$row['name']:''?>" ><?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?></textarea>

                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            else if($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date')
                                            { 
                                                if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])){
                                                    $edit_data[0][$row['name']] = timeformat($edit_data[0][$row['name']]);
                                                }
                                                if($row['type'] == 'date' && isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])){
                                                    $edit_data[0][$row['name']] = configDateTime($edit_data[0][$row['name']]);
                                                }
                                                $n++;
                                                ?>
                                                <div class="col-sm-6">
                                                    <div class="panel panel-default tile tile-profile">
                                                        <div class="panel-body">
                                                            <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                            <?php if((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date'){ ?>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                    <?php } ?>
                                                                    <div class="<?=(!empty($row['subtype']) && $row['subtype'] == 'time')?'input-group input-append bootstrap-timepicker':''?><?=(!empty($row['type']) && $row['type'] == 'date')?'input-group input-append date medi_adddate_prescribed':''?>">
                                                                       <input type="<?=(!empty($row['type']) && $row['type']=='number')?'number':((!empty($row['subtype']) && $row['subtype'] !='time')?$row['subtype']:'text')?>"
                                                                       class="<?=!empty($row['className'])?$row['className']:''?> <?=($row['type'] == 'date')?'medi_adddate_prescribed':''?> <?=(!empty($row['subtype']) && $row['subtype'] == 'time')?'addtime':''?>" 
                                                                       <?=!empty($row['required'])?'required=true':''?>
                                                                       name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" 
                                                                       <?=!empty($row['maxlength'])?'maxlength="'.$row['maxlength'].'"':''?>
                                                                       <?=!empty($row['min'])?'min="'.$row['min'].'"':''?>
                                                                       <?=!empty($row['max'])?'max="'.$row['max'].'"':''?>
                                                                       <?=!empty($row['step'])?'step="'.$row['step'].'"':''?>
                                                                       placeholder="<?=!empty($row['placeholder'])?$row['placeholder']:''?>"
                                                                       value="<?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?>" <?=($row['type'] =='date')?'readonly':''?> data-parsley-errors-container="#errors-container<?=$row['name']?>" <?=(!empty($row['type']) && $row['type']=='number')?'data-parsley-type="digits"':''?> />
                                                                       <?php if(!empty($row['subtype']) && $row['subtype'] == 'time') {?>
                                                                           <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                                                       <?php } ?>
                                                                       <?php if(!empty($row['type']) && $row['type'] == 'date') {?>
                                                                           <span class="input-group-addon add-on" ><i class="fa fa-calendar"></i></span>
                                                                       <?php } ?>
                                                                   </div>
                                                                   <span id="errors-container<?=$row['name']?>"></span>
                                                                   <?php if((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) { ?>
                                                                   </div>
                                                               </div>
                                                           <?php } ?>
                                                       </div>
                                                   </div>
                                               </div>

                                               <?php
                                           }
                                           else if($row['type'] == 'radio-group')
                                            {$n++;
                                                ?>
                                                <div class="col-sm-6">
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
                                                                         value="<?=!empty($radio['value'])?$radio['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $radio['value'])?'checked="checked"':isset($radio['selected'])?'checked="checked"':''?>  type="radio">
                                                                         <?=!empty($radio['label'])?$radio['label']:''?></label>
                                                                     </div>
                                                                 <?php } } } //radio loop ?>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <?php
                                             }
                                             else if($row['type'] == 'checkbox-group')
                                                {$n++;
                                                    ?>
                                                    <div class="col-sm-6">
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
                                                }
                                                else if($row['type'] == 'select')
                                                    {$n++;
                                                        ?>
                                                        <div class="col-sm-6">
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
                                                      }
                                                      else if($row['type'] == 'hidden' || $row['type'] == 'button')
                                                        { $n =0;
                                                            ?>
                                                            <?php if($row['type'] == 'button'){ ?>
                                                                <div class="col-sm-12">
                                                                    <div class="panel panel-default tile tile-profile">
                                                                        <div class="panel-body">
                                                                            <div class="fb-button form-group">

                                                                                <button name="<?=!empty($row['name'])?$row['name']:''?>" value="" type="<?=!empty($row['type'])?$row['type']:''?>" class="<?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" style="<?=!empty($row['style'])?$row['style']:''?>"><?=!empty($row['label'])?$row['label']:''?></button>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($row['type'] == 'hidden'){ ?>
                                                                <div class="col-sm-12">
                                                                    <input type="hidden" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" value="" />
                                                                </div>
                                                            <?php } ?>
                                                            <?php
                                                        }
                                                        else if($row['type'] == 'header')
                                                            { $n =0;
                                                                ?>
                                                                <div class="col-sm-12">
                                                                    <div class="">
                                                                        <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                                                                    </div>
                                                                </div>
                                                            <?php } else if($row['type'] == 'file')
                                                            {$n =0;?>
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
                                                                                'filePathMain' => $this->config->item('medical_img_base_url') . $ypid,
                                                                                'filePathThumb' => $this->config->item('medical_img_base_url_small') . $ypid,
                                                                                'deleteFileHidden' => 'hidden_'.$row['name']
                                                                            );
                                                                            echo getFileView($fileViewArray);
                                                                            ?>
                                                                            <input type="hidden" name="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" id="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" value="">
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <?php
                                                            }
} //foreach
?>

<div class="col-sm-12">
    <div class="">
        <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:$ypid?>">
        <input type="hidden" name="care_home_id" id="care_home_id" value="<?=!empty($YP_details[0]['care_home'])?$YP_details[0]['care_home']:''?>">
        <input type="hidden" name="summary_field" value='<?=!empty($summary_field)?$summary_field:''?>'>
    </div>
</div>
<?php
}
?>



<div class="col-sm-12">

   <div class="pull-right btn-section">
    <div class="btn-group">   
     <input type="submit" class="btn btn-default" name="submit_medform" id="submit_medform" value="Add New Medication" />
     <a href="<?=base_url('Medical/index/'.$ypid)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To Meds</a>
 </div>
</div>

</div>


</form>      

</div>
</div>
<?php } ?>
<div class="row">
    <div class="col-sm-12">
     <div class="panel panel-default tile tile-profile">
        <div class="panel-body">
            <h2>Current Medication</h2>
            <div class="refresh-btn">
                <button class="btn btn-default btn-sm" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                </button>
            </div>

            <div class="clearfix"></div>
            <div class="whitebox" id="common_div">
                <?php $this->load->view('medication_ajaxlist'); ?>
            </div>

        </div>
    </div>
</div></div>
</div>
<div class="row">
    <h1 class="page-title">
        <div class="col-xs-12">
            <div class="pull-right btn-section">
                <div class="btn-group">
                    <a href="<?=base_url('Medical/index/'.$ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> MEDS
                    </a>
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?= base_url('Medical/DownloadMedicaltion/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PRINT </a>
                </div>
            </div>
        </div>
    </h1>
</div>
</div>
</div>

<?= $this->load->view('/Common/common', '', true); ?>