<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?=!empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:$ypid?>';
    var incident_id = '<?=!empty($edit_data[0]['incident_id'])?$edit_data[0]['incident_id']:''?>';
</script>

<div id="page-wrapper">
<form action="" method="post" id="mdtform" name="mdtform" data-parsley-validate enctype="multipart/form-data">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Sanction for Incident and Accident Record <small> New Forest Care</small>
                <div class="pull-right for-tab">
                    <div class="btn-group">
                      <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="btn btn-default"  style="default">Back To YP Info</a>
                      <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">CARE HOME YP LIST</a>
                    
                    <a href="<?=base_url('Sanctions/index/'.$ypid); ?>" class="btn btn-default width_a">
                            <i class="fa fa-mail-reply"></i> Return To Sanctions
                        </a>
                    
                </div>
                </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
        <h1 class="page-title">Sanction Form for Incident reference number : <?php echo $edit_data[0]['reference_number']; ?></h1>
    </div>

    <div class="row" id="table-view">
    <div class="col-sm-12 m-t-10">    
       <div class="panel panel-default tile tile-profile">
                        <div class="panel-body form-margin"> 
        <div class="table-responsive aai_table_heights">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                   <th> Reference Number</th>
                                                   <th>Incident Status</th>
                                                   <th>Date Incident Started</th>
                                                   <th>Report Compiler</th>
                                                   <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>                     
                    <?php if (isset($information) && count($information) > 0) { ?>
                        <?php foreach ($information as $data) { ?>

                            <tr>
                                <td>
                                        <input disabled="" type="checkbox" name="sanction_incident[]" class="incidentCheckbox" value="<?php echo $data['list_main_incident_id']; ?>" <?= (in_array($data['list_main_incident_id'], $sanction_incident))? 'checked':'' ?>>
                                </td>
                                <td> 
                                    <?= !empty($data['reference_number']) ? $data['reference_number'] : lang('NA') ?>
                                </td>
                                <td> 
                                    <?= !empty($data['title']) ? $data['title'] : lang('NA') ?>
                                </td>
                                <td> 
                                    <?= !empty($data['date_of_incident']) ? configDateTime($data['date_of_incident']) : lang('NA') ?>
                                </td>
                                <td>
                                    <?php $aai_report_com = getUserDetailAAiList($data['list_main_incident_id']); ?>

                                   <?= !empty($aai_report_com) ? $aai_report_com : lang('NA') ?>
                                </td>
                                
                                <td>
                                    <?= (!empty($data['description']) && $data['description'] !='0000-00-00') ? ((strlen (strip_tags($data['description'])) > 50) ? substr (nl2br(strip_tags($data['description'])), 0, 50).'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['list_main_incident_id'].'/'.$data['reference_number'].'/description').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data['description']))) :lang('NA') ?>
                                </td>
                            </tr>
                    <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9"  class="text-center"><?= lang('common_no_record_found') ?></td>
                         </tr>
    <?php } ?>
                    </tbody>



                                        </table>
                                    </div>
                                </div>
                              <div class="clearfix"></div>                                
                              </div>
                            </div></div>
        <div class="row">
                      <?php
                // /pr($form_data);
                if(!empty($form_data))
                {
                    foreach ($form_data as $row) {
                      

                    if($row['type'] == 'textarea') {
                        ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                         <?=!empty($edit_data[0][$row['name']])?html_entity_decode($edit_data[0][$row['name']]):(isset($row['value'])?$row['value']:'')?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        else if($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date')
                        { 
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                      <?php if((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date'){ ?>
                                        <div class="row">
                                            <div class="col-sm-4">
                                        <?php } ?>
                                         <div class="input-group input-append">
                                                <?php if($row['subtype'] == 'time'){ ?>
                                                <?=!empty($edit_data[0][$row['name']])?timeformat($edit_data[0][$row['name']]):(isset($row['value'])?timeformat($row['value']):'')?>
                                                <?php }elseif($row['type'] == 'date'){?>
                                                <?=!empty($edit_data[0][$row['name']])?configDateTime($edit_data[0][$row['name']]):(isset($row['value'])?configDateTime($row['value']):'')?>
                                                <?php }else{ ?> 
                                                <?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?>
                                                <?php } ?>
                                            </div>
                                            
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
                        {
                        ?>
                        <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        
                                         <div class="radio-group">
                                         <?php if(count($row['values']) > 0)  {
                                         foreach ($row['values'] as $radio) {
                                             if(!empty($radio['label'])) {
                                          ?>
                                         <div class="<?=!empty($row['inline'])?'radio-inline':'radio'?>">
                                             <label ><input disabled=""  name="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?> 
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
                        }
                        else if($row['type'] == 'select')
                        {
                            ?>
                            <?php if($row['className'] == 'bamboo_lookup'){ ?>
                                        <div class="col-sm-12" id="div_<?= $row['name'] ?>">
                                            <div class="panel panel-default tile tile-profile">
                                                <div class="panel-body">
                                                    <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                        <?php if(!empty($bambooNfcUsers)) {
                                                            foreach ($bambooNfcUsers as $select) {  ?>
                                                                <?php if(isset($edit_data[0][$row['name']]) && ($edit_data[0][$row['name']] == $select['user_type'].'_'.$select['user_id'])){
                                                                  echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;
                                                                }?> 
                                                        <?php } } ?>
                                                  <span id="errors-<?= $row['name'] ?>"></span>
                                        </div>
                                    </div>
                                </div>
                                <?php }else if($row['className'] == 'bamboo_lookup_multiple') { ?>

                                        <div class="col-sm-12" id="div_<?= $row['name'] ?>">
                                            <div class="panel panel-default tile tile-profile">
                                                <div class="panel-body">
                                                    <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                        <?php if(!empty($bambooNfcUsers)) {
                                                            $userAr = array();
                                                            foreach ($bambooNfcUsers as $select) { 
                                                              if(in_array($select['user_type'].'_'.$select['user_id'], $edit_data[0][$row['name']])){
                                                                echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;
                                                              } } } ?>
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

                                                <?php if(!empty($row['name']) && !empty($row['className']) && $row['className'] == 'multiple'){ ?>
                                                        <?php if(count($row['values']) > 0) {
                                                            $userAr = array();
                                                            foreach ($row['values'] as $select) {     
                                                            if(in_array($select['value'],$edit_data[0][$row['name']])){ ?>
                                                              <?=!empty($select['label'])?$select['label']:''?>
                                                          <?php } } } ?>
                                                    
                                            <?php }else{ ?>
                                                 <?php if(count($row['values']) > 0) {
                                                 foreach ($row['values'] as $select) {
                                                     if(!empty($select['label'])) {
                                                      if(isset($row['value']) && ($edit_data[0][$row['name']] == $select['value'])){
                                                  ?>
                                                  <?=!empty($select['label'])?$select['label']:''?>
                                                 <?php }} } } } ?> 
                        </div>
                    </div>
                </div>
                        <?php } ?>
                        <?php
                        }
                        else if($row['type'] == 'hidden' || $row['type'] == 'button')
                        {
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
                        {
                            ?>
                            <div class="col-sm-12">
                                <div class="">
                                    <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                                </div>
                            </div>
                        <?php } else if($row['type'] == 'file'){ ?>
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
                                                'filePathMain' => $this->config->item('mdt_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('mdt_img_base_url_small') . $ypid,
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
                }
                    ?>
                         <div class="col-sm-12">
                            <div class="capsBtn">
                            <input type="hidden" name="incident_id" id="incident_id" value="<?=!empty($incident_id)?$incident_id:''?>">
              <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                            <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:$ypid?>">
                            <input type="hidden" name="sanctions_id" id="sanctions_id" value="<?=!empty($edit_data[0]['sanctions_id'])?$edit_data[0]['sanctions_id']:''?>">
                                <input type="hidden" name="draft_data" id="draft_data" value="0">
                                
                <div class="pull-right btn-section">
                  <div class="btn-group">
                  
                  
                      
                                            <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="btn btn-default"  style="default">Back To YP Info</a>
                      <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">CARE HOME YP LIST</a>
                    
                    <a href="<?=base_url('Sanctions/index/'.$ypid); ?>" class="btn btn-default width_a">
                            <i class="fa fa-mail-reply"></i> Return To Sanctions
                        </a>

                  </div>
                </div>
                            </div>
                        </div>

                    
            </form>        
      
        
    </div>
</div>
      
</div>
        