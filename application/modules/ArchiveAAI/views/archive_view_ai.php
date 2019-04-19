<script>    
    var CREATE_MODE = <?= (isset($createMode))? $createMode:0 ?>;
    var EDIT_MODE = <?= (isset($editMode))? $editMode:0 ?>;
    var bambooNfcUsers =  <?php echo json_encode($bambooNfcUsers); ?>; 
   
</script>

<?php

 if($ypId>0){ ?>
<div id="page-wrapper">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }
        ?>
        <div class="sticky-heading" id="sticky-heading">
            <h1 class="page-title">
                Accident And Incident Record <small>New Forest Care</small>
                <div class="pull-right for-tab">
                    <div class="btn-group">
                        <a href="<?= base_url('YoungPerson/index/' . $YP_details[0]['care_home']) ?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?= base_url('YoungPerson/view/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> YP INFO </a> 
                        <a href="<?= base_url('AAI/index/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> accident & Incidents</a> 
                        <a href="<?= base_url('ArchiveAAI/index/' .$incidentId .'/'. $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i>Archive accident & Incidents</a> 
                    </div>
                </div>
            </h1>
            <h1 class="page-title">
                <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
            </h1>
        </div> 
        <br/>


        <div class="col-md-12">
            <!-- <div class="panel-group tool-tips" id="accordion" role="tablist" aria-multiselectable="true"> -->
                <div class="multi_step_form">
                     <div id="msform">
                     <ul id="progressbar">
                        <li id="mef" class="aimainformupdate active"><a href="#"><span>Main Entry Form</span></a></li>  
                      <li id="toi" class="aiplaceformupdate"><a href="#"><span>Type of incident</span></a></li> 
                      <li id="tl1" class="ail1formupdate"><a href="#"><span>Level 1</span></a></li>
                      <li id="tl23" class="ail2nl3formupdate"><a href="#"><span>L2 & L3</span></a></li>
                      <li id="tl4" class="ail4formupdate"><a href="#"><span>Level 4</span></a></li>
                      <li id="tl5" class="ail5formupdate"><a href="#"><span>Level 5</span></a></li>
                      <li id="tl6" class="ail6formupdate"><a href="#"><span>Level 6</span></a></li>
                      <li id="tl7" class="ail7formupdate"><a href="#"><span>Level 7</span></a></li>
                      <li id="tl8" class="ail8formupdate"><a href="#"><span>Level 8</span></a></li>
                      <li id="tl9" class="ail9formupdate"><a href="#"><span>Level 9</span></a></li>
                  </ul>
                <?php 
                if (isset($createMode) && $createMode == 1) { ?>
                    <form action="<?= base_url('AAI/updateMainForm') ?>" method="post" id="aiforminsert" name="aiforminsert" data-parsley-validate enctype="multipart/form-data">
                    <?php include_once 'main_entry_form.php'; ?>                        
                    </form>
                <?php }elseif(isset($editMode) && $editMode == 2){ ?>
                    <form action="<?= base_url('AAI/updateMainForm/'.$incidentId) ?>" method="post" id="aimainformupdate" name="aimainformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_main_entry_form.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>
                
                    <form action="<?= base_url('AAI/updateTypeForm/'.$incidentId) ?>" method="post" id="aiplaceformupdate" name="aiplaceformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_type_of_incident.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>
                    
                <?php }else{ ?>     
                
                    <form action="<?= base_url('AAI/updateMainForm/'.$incidentId) ?>" method="post" id="aimainformupdate" name="aimainformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_main_entry_form.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>
                
                    <form action="<?= base_url('AAI/updateTypeForm/'.$incidentId) ?>" method="post" id="aiplaceformupdate" name="aiplaceformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_type_of_incident.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>
                
                    <?php if($is_pi == 0){ ?>                
                    <form action="<?= base_url('AAI/updateL1Form/'.$incidentId) ?>" method="post" id="ail1formupdate" name="ail1formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_incident_l1_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>                        
                    <?php }else{ ?>                
                    <form action="<?= base_url('AAI/updateL2nL3Form/'.$incidentId) ?>" method="post" id="ail2nl3formupdate" name="ail2nl3formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_incident_l2_and_l3_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>                        
                    <?php } ?>
                
                    <?php if($is_yp_missing == 1){ ?>                
                    <form action="<?= base_url('AAI/updateL4Form/'.$incidentId) ?>" method="post" id="ail4formupdate" name="ail4formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_incident_l4_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>                        
                    <?php } ?>
					<?php if($is_yp_injured == 1){ ?>                
                    <form action="<?= base_url('AAI/updateL5Form/'.$incidentId) ?>" method="post" id="ail5formupdate" name="ail5formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_incident_l5_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>                        
                    <?php } ?>
                
                    <?php if($is_yp_complaint == 1){ ?>                
                    <form action="<?= base_url('AAI/updateL6Form/'.$incidentId) ?>" method="post" id="ail6formupdate" name="ail6formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_incident_l6_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>                
                    <?php } ?>
                
                    <?php if($is_yp_safeguarding == 1){ ?>                
                    <form action="#" method="post" id="ail7formupdate" name="ail7formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_incident_l7_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>                
                    <?php } ?>

                     <?php if($review_status == 1){ ?>                
                    <form action="#" method="post" id="ailreviewformupdate" name="ailreviewformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_review_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>                
                    <?php } ?>

                    <?php if($review_status == 1){ 
                            if($manager_review_status == 1){ 
                        ?>                
                    <form action="#" method="post" id="managerreviewformupdate" name="managerreviewformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'view_manager_review_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>                
                    <?php } } ?>
                <?php }?>

                <div class="clearfix"> </div>

                <?php if (checkPermission('AAI', 'signoff')) { 
                    if (!empty($signoff_data)) {
                ?> 
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                            <h2>sign off</h2>
                    <?php
                       
                                foreach ($signoff_data as $sign_name) { ?>
                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php }
                        ?> 
                        </div>
                        
                    </div>
                </div>
                <?php }} ?>
                    </div>
            </div>
        </div>
        <div class="clearfix"> </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group">
                        <a href="<?= base_url('YoungPerson/index/' . $YP_details[0]['care_home']) ?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?= base_url('YoungPerson/view/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> YP INFO </a> 
                        <a href="<?= base_url('AAI/index/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> accident & Incidents</a>
                        <button type="button" class="btn btn-default action-button previous previous_button">Back</button>
                         <button type="button" class="btn btn-default next action-button">Continue</button> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }else{ ?>
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading"> </div>
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Select YP </h4>            
        </div>        
        <div class="modal-body">
            <div class="form-group">
                <div class="form-group">
                    <select class='form-control chosen-select' id='yp_name' name='yp_name' onchange="change_incident_link();">
                        <option value=''> Select YP </option>
                        <?php if(!empty($yp_name)) {
                            foreach ($yp_name as $select) {  ?>
                                <option value="<?php echo $select['yp_id'];?>"> <?php echo $select['yp_name'];?></option>
                        <?php } } ?>
                    </select>
                    <ul id="errormsg" style="display:none" class="parsley-errors-list filled"><li class="parsley-required">Please select YP</li></ul>
                </div>
                
            </div>
        </div>
        <div class="modal-footer"> 
            <input type="hidden" name="isCareIncident" id="isCareIncident" value="<?= $isCareIncident ?>">
            <a id="createIncidentLink" href="javascript:void(0);" onclick="change_incident_link();" class="btn btn-default">Submit</a>
            <a href="<?= $backButtonLink; ?>" class="btn btn-default">Back</a>
        </div>
    </div>
</div>
    </div>
</div>
<?php } ?>