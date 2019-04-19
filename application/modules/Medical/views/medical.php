<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var Miid = '<?= !empty($mi_details[0]['mi_id']) ? $mi_details[0]['mi_id'] : '' ?>';
</script>
<?php  $this->method = 'mp_ajax';?>
<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                    Medical Information <small>New Forest Care</small>
                    <div class="pull-right for-tab">
					<?php if($is_archive_page==0){	?>
                        <div class="btn-group">
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            
                            <a href="<?= base_url('Medical/DownloadPrint/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                        </div>
					<?php } else { ?>
					<div class="btn-group">
                           
                             <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                            
                           
                        </div>
					<?php }?>
                    </div>
                </h1>
                <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
                   </div>                 
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="panel panel-primary tile tile-profile">
                            <div class="panel-heading">
                                 <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> YOUNG PERSON DETAILS
                                <?php
								if($is_archive_page==0){
                                 if(checkPermission('Medical','edit')){ ?>
                    <?php if(isset($mi_details) && !empty($mi_details)) { ?>
                                <a class="pull-right btn-white" href="<?php echo base_url('Medical/editMi/'.$ypid.'/'.$mi_details[0]['mi_id']);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                                 <?php }else{ ?>
                                <a class="pull-right" href="<?php echo base_url('Medical/create/'.$ypid);?>"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                    <?php } ?>
								<?php } }?>
                                </h3>
                            </div>
                            <div class="panel-body min-h-205 p-b-0">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <label class="value large"><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?></label>
                                        </div>
                                  </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>DOB</label>
                                            <label class="value large">
                                            <?=(!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00')?configDateTime($YP_details[0]['date_of_birth']):''?>
                                            </label>
                                        </div>
                                </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>MED CARD No / NHS No</label>
                                            <label class="value large">
                                    <?= !empty($mi_details[0]['medical_number']) ? $mi_details[0]['medical_number'] : '' ?>
                                            </label>
                                        </div>
                                </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date Received</label>
                                            <label class="value large">
                                             <?php
                                              if(!empty($mi_prevous_details)) {
                                                    $diff = new HtmlDiff( configDateTime($mi_prevous_details[0]['date_received']),configDateTime($mi_details[0]['date_received']));
                                                    $diff->build();
                                                    echo $diff->getDifference();
                                                    ?>
                                                <?php } else { ?>

                                             <?=(!empty($mi_details[0]['date_received']) && $mi_details[0]['date_received'] != '0000-00-00')? configDateTime($mi_details[0]['date_received']):''?>

                                        

                                               <?php  } ?>
                                           
                                   
                                            </label>
                                        </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($is_archive_page==0){?>
                    <div class="col-sm-6 col-md-4">
                        <div class="panel panel-primary tile tile-profile">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> RECORD INFO</h3>
                            </div>
                            <div class="panel-body min-h-205">
                                <div class="row">
                                    <div class="col-lg-12 med_round">
                                    <?php if(checkPermission('Medical','add')){ ?>
                                        <a href="<?php echo base_url('Medical/add_mc/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Medical Communication and Summary</a>
                                    <?php } ?>
                                    </div>
                                    <div class="col-lg-6 med_round">
                                    <?php if(checkPermission('Medical','add')){ ?>
                                        <a href="<?php echo base_url('Medical/medication/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add New Medication</a>
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="col-lg-6 med_round">
                                    <?php if(checkPermission('Medical','add')){ ?>
                                        <a href="<?php echo base_url('Medical/healthAssessment/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add Health Assessment</a>
                                    <?php } ?>
                                    </div>
                                    <div class="col-lg-12 med_round">
                                    <?php if(checkPermission('Medical','add')){ ?>
                                        <a href="<?php echo base_url($crnt_view.'/add_appointment_data/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add Medical Appointment</a>
                                    <?php } ?>
                                    </div>
                                    <div class="col-lg-12 med_round">
                                     <?php if(checkPermission('Medical','add')){ ?>
                                        <a href="<?php echo base_url('Medical/administer_medication/'.$ypid);?>" class="btn btn-default btn-block bright-green"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Record Administered Medication</a>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php }?>
                    <div class="col-sm-6 col-md-4">
                        <div class="panel panel-primary tile tile-profile">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> VIEW LOGS</h3>
                            </div>
                            <div class="panel-body min-h-205">
                                <div class="row">
                                    <div class="col-lg-6 med_round">
									<?php if($is_archive_page==0){?>
                                        <a href="<?php echo base_url('Medical/view_mc/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Coms & Appointment Log</a>
									<?php } else { ?>
									<a href="<?php echo base_url('Medical/view_mc/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Coms & Appointment Log</a>
									<?php }?>
                                    </div>
                                    <div class="col-lg-6 med_round">
									<?php if($is_archive_page==0){?>
                                        <a href="<?php echo base_url('Medical/medication_list/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Medication List</a>
									<?php } else {?>
									<a href="<?php echo base_url('Medical/medication_list/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Medication List</a>
									<?php }?>
                                    </div>
                                    <div class="col-lg-6 med_round">
									<?php if($is_archive_page==0){?>
                                        <a href="<?php echo base_url('Medical/log_administer_medication/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Administration Log</a>
									<?php } else {?>
									<a href="<?php echo base_url('Medical/log_administer_medication/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Administration Log</a>
									<?php }?>
                                    </div>
                                    <div class="col-lg-6 med_round">
									<?php if($is_archive_page==0){?>
                                <a href="<?php echo base_url('Medical/log_health_assessment/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Health Assessment</a>
									<?php } else {?>
									<a href="<?php echo base_url('Medical/log_health_assessment/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Health Assessment</a>
									<?php }?>
                                    </div>
                                    <div class="col-lg-12 med_round">
									<?php if($is_archive_page==0){?>
                                        <a href="<?php echo base_url('Medical/appointment/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> View Appointments</a>
									<?php } else {?>
									<a href="<?php echo base_url('Medical/appointment/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> View Appointments</a>
									<?php }?>
                                    </div>
                                    
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="panel panel-danger tile tile-profile">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i>  Allergies & Meds not to be Used
                                <?php if($is_archive_page==0){	if(!empty($mi_details)){ ?>        
                                <a class="pull-right btn-white" href="<?php echo base_url('Medical/add_edit_allergies/'.$ypid.'/'.$mi_details[0]['mi_id']);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <?php } }?>
                                </h3>
                                
                            </div>
                            <div class="panel-body min-h-155">
                                <p class="slimScroll-110">
                                    <?php
                                  if(!empty($mi_prevous_details)) {
                          
                                        $diff = new HtmlDiff( $mi_prevous_details[0]['allergies_and_meds_not_to_be_used'],$mi_details[0]['allergies_and_meds_not_to_be_used']);
                                        $diff->build();
                                        echo $diff->getDifference()
                                        ?>
                                    <?php } else {?>
                                <?= !empty($mi_details[0]['allergies_and_meds_not_to_be_used']) ? html_entity_decode($mi_details[0]['allergies_and_meds_not_to_be_used']) : '' ?>
                                   <?php  } ?>
                                    
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Medical Professionals
                                <div class="pull-right xs-block-btns">
								<?php if($is_archive_page==0){	?>
                                  <a class="btn btn-info btn-xs btn-white-font" href="<?=base_url('Medical/add_mp/'.$ypid); ?>">
                                    <i class="fa fa-plus-circle"></i> Add Professional
                                  </a>
                                  <a class="btn btn-info btn-xs btn-white-font" href="<?php echo base_url($crnt_view.'/appointment/'.$ypid);?>">
                                    View Appointments
                                  </a>
                                  <?php if(checkPermission('Medical','add')){ ?>
								<?php } } ?>
                                </div>                                
                                </h2>
                                <div class="">
                                    <div class="whitebox" id="common_div">
                                        <?php $this->load->view('mp_ajaxlist'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Other Medical Information
                                 <?php
								if($is_archive_page==0){								 
                                 if(checkPermission('Medical','edit')){ ?>
                                <a class="pull-right" href="<?=base_url('Medical/add_omi/'.$ypid); ?>" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <?php } }?>
                                </h2>
                                <?php
                                if(!empty($omi_form_data))
                                {
                                    foreach ($omi_form_data as $row) {
                                        if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                                        ?>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                        <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                         <label class="value large">
                                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {
                                                             if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($omi_details[0][$row['name']]) && !empty($omi_details[0][$row['name']])){
                                                                $omi_details[0][$row['name']] = timeformat($omi_details[0][$row['name']]);
                                                            }
                                                            if($row['type'] == 'date' && isset($omi_details[0][$row['name']]) && !empty($omi_details[0][$row['name']])){
                                                                $omi_details[0][$row['name']] = configDateTime($omi_details[0][$row['name']]);
                                                            }
                                            ?>
                                                            <?php
                                                      if(!empty($omi_previous_details)) {
                                              
                                                            $diff = new HtmlDiff( $omi_previous_details[0][$row['name']],$omi_details[0][$row['name']] );
                                                            $diff->build();
                                                            echo $diff->getDifference()
                                                            ?>
                                                        <?php } else {?>
                                                     <?=!empty($omi_details[0][$row['name']])?nl2br(html_entity_decode($omi_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                       <?php  } ?>
                                                           
                                                           <?php  }
                                                            else if($row['type'] == 'checkbox-group') {
                                                            if(!empty($omi_details[0][$row['name']])) {
                                                                $chk = explode(',',$omi_details[0][$row['name']]);
                                                                        foreach ($chk as $chk) {
                                                                            echo $chk."\n";
                                                                        }
                                                                     
                                                                
                                                            }else{

                                                                    if(count($row['values']) > 0) {
                                                                       
                                                                     foreach ($row['values'] as $chked) {
                                                                        echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                                         }
                                                                       
                                                                    }
                                                                }?>

                                                           <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                             if(!empty($omi_details[0][$row['name']])) {
                                                                echo !empty($omi_details[0][$row['name']])?nl2br(htmlentities($omi_details[0][$row['name']])):'';
                                                             }
                                                             else
                                                             {
                                                                 if(count($row['values']) > 0) {
                                                                        
                                                                     foreach ($row['values'] as $chked) {
                                                                        echo isset($chked['selected'])?$chked['value']:'';
                                                                         }
                                                                        
                                                                    }
                                                             }
                                                            } ?>
                                                        </label>
                                                    
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        else if ($row['type'] == 'textarea') { ?>
                                            <h4><?=!empty($row['label'])?$row['label']:''?></h4>
                                            <p>
                                    <?php                                    
                                        if (!empty($omi_previous_details)) {
                                            $diff = new HtmlDiff($omi_previous_details[0][$row['name']], $omi_details[0][$row['name']]);
                                            $diff->build();
                                            echo $diff->getDifference();
                                        } else {
                                            echo (!empty($omi_details[0][$row['name']]) && $omi_details[0][$row['name']] != '0000-00-00') ? html_entity_decode($omi_details[0][$row['name']]) : (isset($row['value']) ? $row['value'] : '');
                                        }
                                    ?>
                                    </p>
                                    <hr class="hr-10" />
                                        <?php
                                        }
                                        else if ($row['type'] == 'header') {
                                           ?>
                                           <div class="col-sm-12">
                                                        <div class="">
                                                            <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                                            <?php echo '<'.$head.' class="page-title">'; ?>
                                                            <?=!empty($row['label'])?$row['label']:''?>
                                                                
                                                            <?php echo '</'.$head.'>'; ?>
                                                            <hr class="hr-10">
                                                        </div>
                                                    </div>
                                           <?php
                                        }
                                        else if ($row['type'] == 'file') { ?>
                                            <div class="col-sm-12">
                                                <div class="panel panel-default tile tile-profile">
                                                    <div class="panel-body">
                                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                                        <div class="">   
                                                            <?php 
                                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                                            $fileViewArray = array(
                                                                'fileArray' => (isset($omi_details[0][$row['name']]) && !empty($omi_details[0][$row['name']]))? $omi_details[0][$row['name']] : '',
                                                                'filePathMain' => $this->config->item('medical_img_base_url') . $ypid,
                                                                'filePathThumb' => $this->config->item('medical_img_base_url_small') . $ypid
                                                            );
                                                            echo getFileView($fileViewArray); 
                                                            ?>                               
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        } 
                                        
                                        
                                    } 
                                
                                } ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>
                                    Inoculations
                                    <a target="_blank" class="pull-right m-l-10 btn-nhs-info" href="http://www.nhs.uk/Planners/vaccinations/Pages/Vaccinationchecklist.aspx" title="NHS Info"><i class="fa fa-info-circle" aria-hidden="true"></i> NHS INFO</a>
                                    
                                    <?php 
				if($is_archive_page==0){	
                                    if(checkPermission('Medical','edit')){ ?>
                                    <a class="pull-right" href="<?=base_url('Medical/add_mi/'.$ypid); ?>" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <?php } }?>
                                </h2>
                                 <?php
                                if(!empty($mi_form_data))
                                {
                                    foreach ($mi_form_data as $row) {

                                   if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                                        ?>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                        <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                         <label class="value large">
                                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {
                                                            if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($miform_details[0][$row['name']]) && !empty($miform_details[0][$row['name']])){
                                                                $miform_details[0][$row['name']] = timeformat($miform_details[0][$row['name']]);
                                                            }
                                                            if($row['type'] == 'date' && isset($miform_details[0][$row['name']]) && !empty($miform_details[0][$row['name']])){
                                                                $miform_details[0][$row['name']] = configDateTime($miform_details[0][$row['name']]);
                                                            }
                                                      if(!empty($miform_prevous_details)) {
                                              
                                                            $diff = new HtmlDiff( $miform_prevous_details[0][$row['name']],$miform_details[0][$row['name']] );
                                                            $diff->build();
                                                            echo $diff->getDifference()
                                                            ?>
                                                        <?php } else {?>
                                                     <?=!empty($miform_details[0][$row['name']])?nl2br(html_entity_decode($miform_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                       <?php  } ?>
                                                           
                                                           <?php  }
                                                            else if($row['type'] == 'checkbox-group') {
                                                            if(!empty($miform_details[0][$row['name']])) {
                                                                $chk = explode(',',$miform_details[0][$row['name']]);
                                                                        foreach ($chk as $chk) {
                                                                            echo $chk."\n";
                                                                        }
                                                                     
                                                                
                                                            }else{

                                                                    if(count($row['values']) > 0) {
                                                                       
                                                                     foreach ($row['values'] as $chked) {
                                                                        echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                                         }
                                                                       
                                                                    }
                                                                }?>

                                                           <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                             if(!empty($miform_details[0][$row['name']])) {
                                                                echo !empty($miform_details[0][$row['name']])?nl2br(htmlentities($miform_details[0][$row['name']])):'';
                                                             }
                                                             else
                                                             {
                                                                 if(count($row['values']) > 0) {
                                                                        
                                                                     foreach ($row['values'] as $chked) {
                                                                        echo isset($chked['selected'])?$chked['value']:'';
                                                                         }
                                                                        
                                                                    }
                                                             }
                                                            } ?>
                                                        </label>
                                                    
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        else if ($row['type'] == 'textarea') { ?>
                                            <div class="col-sm-6">
                                                 <div class="form-group">
                                                        <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                         <label class="value large">
                                                        <?php
                                                          if(!empty($miform_prevous_details)) {
                                                  
                                                                $diff = new HtmlDiff( $miform_prevous_details[0][$row['name']],$miform_details[0][$row['name']] );
                                                                $diff->build();
                                                                echo $diff->getDifference()
                                                                ?>
                                                            <?php } else {?>
                                                         <?=!empty($miform_details[0][$row['name']])?nl2br(html_entity_decode($miform_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                           <?php  } ?>
                                                        </label>
                                                   
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        else if ($row['type'] == 'header') {
                                           ?>
                                           <div class="col-sm-12">
                                                        <div class="">
                                                            <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                                            <?php echo '<'.$head.' class="page-title">'; ?>
                                                            <?=!empty($row['label'])?$row['label']:''?>
                                                                
                                                            <?php echo '</'.$head.'>'; ?>
                                                            <hr class="hr-10">
                                                        </div>
                                                    </div>
                                           <?php
                                        }
                                        else if ($row['type'] == 'file') { ?>
                                            <div class="col-sm-12">
                                                <div class="panel panel-default tile tile-profile">
                                                    <div class="panel-body">
                                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                                        <div class="">   
                                                            <?php 
                                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                                            $fileViewArray = array(
                                                                'fileArray' => (isset($miform_details[0][$row['name']]) && !empty($miform_details[0][$row['name']]))? $miform_details[0][$row['name']] : '',
                                                                'filePathMain' => $this->config->item('medical_img_base_url') . $ypid,
                                                                'filePathThumb' => $this->config->item('medical_img_base_url_small') . $ypid
                                                            );
                                                            echo getFileView($fileViewArray); 
                                                            ?>                               
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        } ?>

                                        <?php
                                    } //foreach
                                }
                                 ?>
                              
                            </div>
                        </div>
                        <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>
                                    Medical Authorisations & Consents

                                    <?php 
									if($is_archive_page==0){	
                                    if(checkPermission('Medical','edit')){ ?>
                                   <a class="pull-right" href="<?=base_url('Medical/add_mac/'.$ypid); ?>" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<?php } } ?>
                                </h2>
                                 <?php
                                if(!empty($mac_form_data))
                                {
                                    foreach ($mac_form_data as $row) {

                                   if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                                        ?>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                        <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                         <label class="value large">
                                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) { 
                                                            if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($mac_details[0][$row['name']]) && !empty($mac_details[0][$row['name']])){
                                                                $mac_details[0][$row['name']] = timeformat($mac_details[0][$row['name']]);
                                                            }
                                                            if($row['type'] == 'date' && isset($mac_details[0][$row['name']]) && !empty($mac_details[0][$row['name']])){
                                                                $mac_details[0][$row['name']] = configDateTime($mac_details[0][$row['name']]);
                                                            }
                                                             if(!empty($mac_prevous_details)) {
                                                  
                                                                $diff = new HtmlDiff( $mac_prevous_details[0][$row['name']],$mac_details[0][$row['name']] );
                                                                $diff->build();
                                                                echo $diff->getDifference()
                                                                ?>
                                                            <?php } else {?>
                                                           <?=!empty($mac_details[0][$row['name']])?nl2br(html_entity_decode($mac_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                           <?php  } ?>

                                                           <?php  }
                                                            else if($row['type'] == 'checkbox-group') {
                                                            if(!empty($mac_details[0][$row['name']])) {
                                                                $chk = explode(',',$mac_details[0][$row['name']]);
                                                                        foreach ($chk as $chk) {
                                                                            echo $chk."\n";
                                                                        }
                                                                     
                                                                
                                                            }else{

                                                                    if(count($row['values']) > 0) {
                                                                       
                                                                     foreach ($row['values'] as $chked) {
                                                                        echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                                         }
                                                                       
                                                                    }
                                                                }?>

                                                           <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                             if(!empty($mac_details[0][$row['name']])) {
                                                                echo !empty($mac_details[0][$row['name']])?nl2br(htmlentities($mac_details[0][$row['name']])):'';
                                                             }
                                                             else
                                                             {
                                                                 if(count($row['values']) > 0) {
                                                                        
                                                                     foreach ($row['values'] as $chked) {
                                                                        echo isset($chked['selected'])?$chked['value']:'';
                                                                         }
                                                                        
                                                                    }
                                                             }
                                                            } ?>
                                                        </label>
                                                    
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        else if ($row['type'] == 'textarea') { ?>
                                            <div class="col-sm-9">
                                                 <div class="form-group">
                                                        <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                         <label class="value large">
                                                         <?php
                                                          if(!empty($mac_prevous_details)) {
                                                  
                                                                $diff = new HtmlDiff( $mac_prevous_details[0][$row['name']],$mac_details[0][$row['name']] );
                                                                $diff->build();
                                                                echo $diff->getDifference()
                                                                ?>
                                                            <?php } else {?>
                                                          <?=!empty($mac_details[0][$row['name']])?nl2br(html_entity_decode($mac_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                           <?php  } ?>
                                                        
                                                        </label>
                                                   
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        else if ($row['type'] == 'header') {
                                           ?>
                                           <div class="col-sm-12">
                                                        <div class="">
                                                            <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                                            <?php echo '<'.$head.' class="page-title">'; ?>
                                                            <?=!empty($row['label'])?$row['label']:''?>
                                                                
                                                            <?php echo '</'.$head.'>'; ?>
                                                            <hr class="hr-10">
                                                        </div>
                                                    </div>
                                           <?php
                                        }
                                        else if ($row['type'] == 'file') { ?>
                                            <div class="col-sm-12">
                                                <div class="panel panel-default tile tile-profile">
                                                    <div class="panel-body">
                                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                                        <div class="">   
                                                            <?php
                                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                                            $fileViewArray = array(
                                                                'fileArray' => (isset($mac_details[0][$row['name']]) && !empty($mac_details[0][$row['name']]))? $mac_details[0][$row['name']] : '',
                                                                'filePathMain' => $this->config->item('medical_img_base_url') . $ypid,
                                                                'filePathThumb' => $this->config->item('medical_img_base_url_small') . $ypid
                                                            );
                                                            echo getFileView($fileViewArray);  
                                                            ?>                               
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        } ?>

                                        <?php
                                    } //foreach
                                }
                                 ?>
                               
                                
                            </div>
                        </div>
                    </div>
                </div>
                       
                    </div>
                     <div class="clearfix"></div>
                   

                </div>

                <div class="row">
        <h1 class="page-title">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
				<?php if($is_archive_page==0){	?>
                    <div class="btn-group">
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>

                                 <a href="<?= base_url('Medical/DownloadPrint/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                    </div>
				<?php } else {?>
				 <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
				<?php }?>
                </div>
            </div>
        </h1>
        </div>
            </div>
        </div>
        <?= $this->load->view('/Common/common', '', true); ?>