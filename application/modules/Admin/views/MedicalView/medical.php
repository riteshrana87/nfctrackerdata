<?php  $this->method = 'mp_ajax';?>
<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <h1 class="page-title">
                    Medical Information <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                            <a href="<?=base_url('Admin/Reports/MEDS'); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> MEDS Reports
                            </a>
                        </div>
                    </div>
                </h1>
                                    
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-primary tile tile-profile">
                            <div class="panel-heading">
                                 <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> YOUNG PERSON DETAILS
                                </h3>
                            </div>
                            <div class="panel-body min-h-140 p-b-0">
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
                                            <?=(!empty($mi_details[0]['date_received']) && $mi_details[0]['date_received'] != '0000-00-00')?configDateTime($mi_details[0]['date_received']):''?>
                                   
                                            </label>
                                        </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                   
                    <div class="col-sm-6">
                        <div class="panel panel-primary tile tile-profile">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> VIEW LOGS</h3>
                            </div>
                            <div class="panel-body min-h-140">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Admin/MedicalView/view_mc/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Coms & Appointment Log</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Admin/MedicalView/medication/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Medication List</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="<?php echo base_url('Admin/MedicalView/log_administer_medication/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Administration Log</a>
                                    </div>
                                    <div class="col-lg-6">
                                <a href="<?php echo base_url('Admin/MedicalView/log_health_assessment/'.$ypid);?>" class="btn btn-default btn-block"><i class="fa fa-search" aria-hidden="true"></i> Health Assessment</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="panel panel-danger tile tile-profile">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i>  Allergies & Meds not to be Used</h3>
                            </div>
                            <div class="panel-body min-h-140">
                                <p class="slimScroll-110">
                                   
                                    <?= !empty($mi_details[0]['allergies_and_meds_not_to_be_used']) ? $mi_details[0]['allergies_and_meds_not_to_be_used'] : '' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                            <h2>
                                Medical Authorisations & Consents
                                
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
                                                    <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>

                                                       <?=!empty($mac_details[0][$row['name']])?nl2br(html_entity_decode($mac_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
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
                                                    <?=!empty($mac_details[0][$row['name']])?nl2br(html_entity_decode($mac_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
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
                                                            'fileArray' => (isset($mac_details[0][$row['name']]) && !empty($mac_details[0][$row['name']]))? $mac_details[0][$row['name']] : $row['value'],
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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Medical Professionals
                                <a class="pull-right pull-right btn btn-info btn-xs btn-white-font" href="<?php echo base_url('Admin/'.$crnt_view.'/appointment/'.$ypid);?>">
                                        View Appointments
                                    </a>
                                </h2>
                                <div class="table-responsive">
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
                                
                                </h2>
                                <?php
                                if(!empty($omi_form_data))
                                {
                                    foreach ($omi_form_data as $row) {
                                ?>
                                <h4><?=!empty($row['label'])?$row['label']:''?></h4>
                                <p><?=(!empty($omi_details[0][$row['name']]) && $omi_details[0][$row['name']] !='0000-00-00')?nl2br(html_entity_decode($omi_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?></p>
                                <hr class="hr-10" />
                                <?php } } ?>
                                
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
                                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>

                                                           <?=!empty($miform_details[0][$row['name']])?nl2br(html_entity_decode($miform_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
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
                                                        <?=!empty($miform_details[0][$row['name']])?nl2br(html_entity_decode($miform_details[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
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
                                                                'fileArray' => (isset($miform_details[0][$row['name']]) && !empty($miform_details[0][$row['name']]))? $miform_details[0][$row['name']] : $row['value'],
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
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group">
                                 <a href="<?=base_url('Admin/Reports/MEDS'); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> MEDS Reports
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->load->view('/Common/common', '', true); ?>