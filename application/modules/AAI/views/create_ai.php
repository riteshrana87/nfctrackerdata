<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script>
    
    $( document ).ready(function() {

        $('#progressbar').slick({
          dots: false,
          infinite: false,
          speed: 300,
          slidesToShow: 10,
          slidesToScroll: 1,
          responsive: [
          {
              breakpoint: 1024,
              settings: {
                slidesToShow: 5,
                slidesToScroll: 1,
                arrows:true
            }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows:true
        }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows:true
    }
}
]
});

$('#progressbar').show();
//$('.progressbar_loaing').delay(10000).hide();

$('.progressbar_loaing').delay(300).fadeOut();


    });//ready

    
</script>
<script>    
    var CREATE_MODE = <?= (isset($createMode))? "'".$createMode."'":0 ?>;
    var EDIT_MODE = <?= (isset($editMode))? $editMode:0 ?>;
    var bambooNfcUsers =  <?php echo json_encode($bambooNfcUsers); ?>; 
    var type =  <?php echo json_encode($type); ?>; 
    var position_of_yp =  <?php echo json_encode($position_of_yp); ?>; 
    var pre_outside_agency =  <?php echo json_encode($pre_outside_agency); ?>; 
    var baseurl = '<?php echo base_url(); ?>';
    var persons_infromed =  <?php echo json_encode($persons_infromed); ?>; 
    var other_option =  <?php echo OTHER_OPTION; ?>; 
    var external_agency_other =  <?php echo EXTERNAL_AGENCY_OTHER_OPTION; ?>; 
    var YP_PLACEMENT_DATE =  '<?php echo $YP_details[0]['date_of_placement']; ?>'; 
    var LOADER = '<img src="' + '<?= base_url("uploads/images/ajax-loader.gif") ?>' + '">' + '<?php echo lang('please_wait'); ?>' + '';
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
                        <a href="<?= base_url('ArchiveAAI/index/' .$incidentId.'/'. $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> Archive AAI</a> 
                    </div>
                </div>
            </h1>
            <h1 class="page-title">
                <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
            </h1>
        </div> 
        <br/>


        <div class="col-md-12 p-l-r-0">
           <!-- <div class="panel-group tool-tips" id="accordion" role="tablist" aria-multiselectable="true"> -->
            <div class="multi_step_form">
            <div id="msform">  
                <div class="progressbar_loaing"></div>
                 <ul id="progressbar">
                <?php 
                if (isset($createMode) && $createMode == 'main'){ ?>
                     
                    <li id="mef" class="aimainformupdate active"><a href="#"><span>Main Entry Form</span></a></li>  
                <?php }elseif(isset($editMode) && $editMode == 'main'){ ?>
                    
                  <li id="mef" class="aimainformupdate active"><a href="#"><span>Main Entry Form</span></a></li>  
                  <li id="toi" class="aiplaceformupdate"><a href="#"><span>Type of incident</span></a></li> 
                    
                <?php }else{ ?>
                  
                  <li id="mef" class="aimainformupdate active"><a href="#"><span>Main Entry Form</span></a></li>  
                  <li id="toi" class="aiplaceformupdate"><a href="#"><span>Type of incident</span></a></li>
                  
                <?php if($is_l1 == 1){ ?>
                    <li id="tl1" class="ail1formupdate"><a href="#"><span>Level 1</span></a></li>
                <?php } ?>     

                <?php if($is_pi == 1){ ?>
                    <li id="tl23" class="ail2nl3formupdate"><a href="#"><span>L2 & L3</span></a></li>
                <?php } ?>  

                <?php if($is_yp_missing == 1){ ?>
                    <li id="tl4" class="ail4formupdate"><a href="#"><span>Level 4</span></a></li>
                <?php } ?>       

                <?php if($is_yp_injured == 1){ ?>
                    <li id="tl5" class="ail5formupdate"><a href="#"><span>Level 5</span></a></li>
                <?php } ?>

                <?php if($is_yp_complaint == 1){ ?>
                     <li id="tl6" class="ail6formupdate"><a href="#"><span>Level 6</span></a></li>
                <?php } ?> 
                  
                <?php if($is_yp_safeguarding == 1){ ?>
                    <li id="tl7" class="ail7formupdate"><a href="#"><span>Level 7</span></a></li>
                <?php }?> 
                
                <?php if($is_staff_injured == 1 || $is_other_injured == 1){ ?>
                    <li id="tl8" class="ail8formupdate"><a href="#"><span>Level 8</span></a></li>
                <?php } ?>    
                
                <?php if(($is_yp_injured==1) ||($is_staff_injured==1) ||($is_yp_safeguarding==1) ||($is_yp_complaint==1) || ($is_other_injured == 1)){ ?>
                    <li id="tl9" class="ail9formupdate"><a href="#"><span>Level 9</span></a></li>
                <?php } ?>
                <?php } ?>
                </ul>
                <?php 
                if (isset($createMode) && $createMode == 'main') { ?>
                     
                    <form action="<?= base_url('AAI/updateMainForm') ?>" method="post" id="aimainformupdate" name="aimainformupdate" data-parsley-validate enctype="multipart/form-data">
                    <?php include_once 'main_entry_form.php'; ?>                        
                    </form>
                   
               
                <?php }elseif(isset($editMode) && $editMode == 'main'){ ?>
                    
                    <form action="<?= base_url('AAI/updateMainForm/'.$incidentId) ?>" method="post" id="aimainformupdate" name="aimainformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'main_entry_form.php'; ?>
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    </form>
               
                
                    <form action="<?= base_url('AAI/updateTypeForm/'.$incidentId) ?>" method="post" id="aiplaceformupdate" name="aiplaceformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'type_of_incident.php'; ?>
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="incident_type_id" id="incident_type_id" value="<?= $incident_type_id ?>"> 
                    </form>
               
                    
                <?php }else{ ?>     
                    
                    <form action="<?= base_url('AAI/updateMainForm/'.$incidentId) ?>" method="post" id="aimainformupdate" name="aimainformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'main_entry_form.php'; ?>
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="entry_form_id" id="entry_form_id" value="<?= $entry_form_id ?>"> 
                    </form>
               
                
                    <form action="<?= base_url('AAI/updateTypeForm/'.$incidentId) ?>" method="post" id="aiplaceformupdate" name="aiplaceformupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'type_of_incident.php'; ?>
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="incident_type_id" id="incident_type_id" value="<?= $incident_type_id ?>"> 
                    </form>
               
                
                    <?php if($is_l1 == 1){ ?>     
                               
                    <form action="<?= base_url('AAI/updateL1Form/'.$incidentId) ?>" method="post" id="ail1formupdate" name="ail1formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'incident_l1_process.php'; ?>
                        <input type="hidden" name="l1_form_id" id="l1_form_id" value="<?= $l1_form_id ?>"> 
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="saveAsDraftL1" id="saveAsDraftL1" value="0" />
                    </form>  
               
					<?php }?>					
                     <?php if($is_pi == 1){ ?>  
                                  
                    <form action="<?= base_url('AAI/updateL2nL3Form/'.$incidentId) ?>" method="post" id="ail2nl3formupdate" name="ail2nl3formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'incident_l2_and_l3_process.php'; ?>
                        <input type="hidden" name="l2_form_id" id="l2_form_id" value="<?= $l2_form_id ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="saveAsDraftL2" id="saveAsDraftL2" value="0" />
                    </form>  
                                         
                    <?php } ?>
                
                    <?php if($is_yp_missing == 1){ ?>       
                             
                    <form action="<?= base_url('AAI/updateL4Form/'.$incidentId) ?>" method="post" id="ail4formupdate" name="ail4formupdate" data-parsley-validate  <?php /*data-parsley-ui-enabled="false" data-parsley-focus="first"*/?> enctype="multipart/form-data">
                        <?php include_once 'incident_l4_process.php'; ?>
                        <input type="hidden" name="l4_form_id" id="l4_form_id" value="<?= $l4_form_id ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="saveAsDraftL4" id="saveAsDraftL4" value="0" />
                    </form>         
                                  
                    <?php } ?>

					<?php if($is_yp_injured == 1){ ?> 
                                   
                    <form action="<?= base_url('AAI/updateL5Form/'.$incidentId) ?>" method="post" id="ail5formupdate" name="ail5formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'incident_l5_process.php'; ?>
                        <input type="hidden" name="l5_form_id" id="l5_form_id" value="<?= $l5_form_id ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="saveAsDraftL5" id="saveAsDraftL5" value="0" />
                    </form> 
                                          
                    <?php } ?>
                
                    <?php if($is_yp_injured == 1){ ?>                
                        
                    <form action="<?= base_url('AAI/updateL5Form/'.$incidentId) ?>" method="post" id="ail5formupdate" name="ail5formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'incident_l5_process.php'; ?>
                        <input type="hidden" name="l5_form_id" id="l5_form_id" value="<?= $l5_form_id ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="saveAsDraftL5" id="saveAsDraftL5" value="0" /> 
                    </form>          
                                 
                    <?php } ?>
                
                    <?php if($is_yp_complaint == 1){ ?> 
                                   
                    <form action="<?= base_url('AAI/updateL6Form/'.$incidentId) ?>" method="post" id="ail6formupdate" name="ail6formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'incident_l6_process.php'; ?>
                        <input type="hidden" name="l6_form_id" id="l6_form_id" value="<?= $l6_form_id ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="saveAsDraftL6" id="saveAsDraftL6" value="0" />
                    </form> 
                    <?php } ?>
                
                    <?php if($is_yp_safeguarding == 1){ 
                            /*if (!empty($access_data_val) && in_array($loggedInUser['ID'],$access_data_val)){*/
                        ?>  
                                      
                    <form action="<?= base_url('AAI/updateL7Form/'.$incidentId) ?>" method="post" id="ail7formupdate" name="ail7formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'incident_l7_process.php'; ?>
                        <input type="hidden" name="l7_form_id" id="l7_form_id" value="<?= $l7_form_id ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>">
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>">  
                        <input type="hidden" name="saveAsDraftL7" id="saveAsDraftL7" value="0" />
                    </form>  
                                 
                    <?php /*}*/ } ?>

					<?php if($is_staff_injured == 1){ ?>    
                                
                    <form action="<?= base_url('AAI/updateL8Form/'.$incidentId) ?>" method="post" id="ail8formupdate" name="ail8formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'incident_l8_process.php'; ?>
                        <input type="hidden" name="l8_form_id" id="l8_form_id" value="<?= $l8_form_id ?>"> 
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>"> 
                        <input type="hidden" name="saveAsDraftL8" id="saveAsDraftL8" value="0" /> 
                    </form>   
                                
                    <?php } ?>
					
					<?php if(($is_yp_injured==1) ||($is_staff_injured==1) ||($is_yp_safeguarding==1) ||($is_yp_complaint==1) || ($is_other_injured == 1)){ ?>      
                              
                    <form action="<?= base_url('AAI/updateL9Form/'.$incidentId) ?>" method="post" id="ail9formupdate" name="ail9formupdate" data-parsley-validate enctype="multipart/form-data">
                        <?php include_once 'incident_l9_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>">
                        <input type="hidden" name="yp_id" id="yp_id" value="<?= $ypId ?>">  
                        <input type="hidden" name="saveAsDraftL9" id="saveAsDraftL9" value="0" />
                    </form> 
                                  
                    <?php } ?>
                    
                <?php }?>
                
            </div>
        </div>
        </div>
        <div class="clearfix"> </div>
            <div class="row capsBtn">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default action-button previous previous_button updat_bn">Previous</button>
                        <button type="button" class="btn btn-default next action-button updat_bn">Next</button> 
                        <div class="pull-right for-tab">
                            <div class="btn-group">
                                <a href="<?= base_url('YoungPerson/index/' . $YP_details[0]['care_home']) ?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                                <a href="<?= base_url('YoungPerson/view/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> YP INFO </a> 
                                <a href="<?= base_url('AAI/index/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> accident & Incidents</a>
                                <a href="<?= base_url('ArchiveAAI/index/' .$incidentId.'/'. $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> Archive AAI</a> 
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
            <a id="createIncidentLink" href="javascript:void(0);" onclick="change_incident_link();" class="btn btn-default updat_bn">Submit</a>
            <a href="<?= $backButtonLink; ?>" class="btn btn-default updat_bn">Back</a>
        </div>
    </div>
</div>
    </div>
</div>
<?php } ?>
