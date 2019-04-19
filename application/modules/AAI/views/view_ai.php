
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
        slidesToShow: 3,
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
                            <a href="<?= base_url('AAI/index/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> ACCIDENT & INCIDENTS</a> 
                        </div>
                    </div>
                </h1>
                <h1 class="page-title">
                    <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
            </div> 
            <br/>


            <div class="col-md-12 p-l-r-0">
                <div class="multi_step_form">
                    <div id="msform">
                        <div class="progressbar_loaing"></div>
                        <ul id="progressbar">
                            <?php if (!empty($entry_form_data)) { ?>
                                <li id="mef" class="aimainformupdate active"><a href="#"><span>Main Entry Form</span></a></li>  
                                <li id="toi" class="aiplaceformupdate"><a href="#"><span>Type of incident</span></a></li>
                            <?php } ?>
                            <?php if($is_l1 == 1 && !empty($l1_form_data)){ ?>
                                <li id="tl1" class="ail1formupdate"><a href="#"><span>Level 1</span></a></li>
                            <?php } ?>     

                            <?php if($is_pi == 1 && !empty($l2_form_data)){ ?>
                                <li id="tl23" class="ail2nl3formupdate"><a href="#"><span>L2 & L3</span></a></li>
                            <?php } ?>  

                            <?php if($is_yp_missing == 1 && !empty($l4_form_data)){ ?>
                                <li id="tl4" class="ail4formupdate"><a href="#"><span>Level 4</span></a></li>
                            <?php } ?>       

                            <?php if($is_yp_injured == 1 && !empty($l5_form_data)){ ?>
                                <li id="tl5" class="ail5formupdate"><a href="#"><span>Level 5</span></a></li>
                            <?php } ?>

                            <?php if($is_yp_complaint == 1 && !empty($l6_form_data)){ ?>
                                 <li id="tl6" class="ail6formupdate"><a href="#"><span>Level 6</span></a></li>
                            <?php } ?> 

                            <?php if($is_yp_safeguarding == 1 && !empty($l7_form_data)){ ?>
                                <li id="tl7" class="ail7formupdate"><a href="#"><span>Level 7</span></a></li>
                            <?php }?> 

                            <?php if(($is_staff_injured == 1 || $is_other_injured == 1) && !empty($l8_form_data)){ ?>
                                <li id="tl8" class="ail8formupdate"><a href="#"><span>Level 8</span></a></li>
                            <?php } ?>    

                            <?php if(!empty($l9_form_data) && (($is_yp_injured==1) ||($is_staff_injured==1) ||($is_yp_safeguarding==1) ||($is_yp_complaint==1) || ($is_other_injured == 1))){ ?>
                                <li id="tl9" class="ail9formupdate"><a href="#"><span>Level 9</span></a></li>
                            <?php } ?>
                        </ul>
                        <?php 
                        if (isset($createMode) && $createMode == 'main') { ?>
                            <form action="#" method="post" id="aiforminsert" name="aiforminsert" data-parsley-validate enctype="multipart/form-data">
                                <?php include_once 'view_main_entry_form.php'; ?>                        
                            </form>
                        <?php }elseif(isset($editMode) && $editMode == 'main'){ ?>
                            <form action="#" method="post" id="aimainformupdate" name="aimainformupdate" data-parsley-validate enctype="multipart/form-data">
                                <?php include_once 'view_main_entry_form.php'; ?>
                                <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                            </form>

                            <form action="#" method="post" id="aiplaceformupdate" name="aiplaceformupdate" data-parsley-validate enctype="multipart/form-data">
                                <?php include_once 'view_type_of_incident.php'; ?>
                                <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                            </form>

                        <?php }else{ ?>     

                            <form action="#" method="post" id="aimainformupdate" name="aimainformupdate" data-parsley-validate enctype="multipart/form-data">
                                <?php include_once 'view_main_entry_form.php'; ?>
                                <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                            </form>

                            <form action="#" method="post" id="aiplaceformupdate" name="aiplaceformupdate" data-parsley-validate enctype="multipart/form-data">
                                <?php include_once 'view_type_of_incident.php'; ?>
                                <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                            </form>

                            <?php if($is_l1 == 1 && !empty($l1_form_data)){ ?>                  
                                <form action="#" method="post" id="ail1formupdate" name="ail1formupdate" data-parsley-validate enctype="multipart/form-data">
                                    <?php include_once 'view_incident_l1_process.php'; ?>
                                    <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                </form>                        
                            <?php } ?>                
                            <?php if($is_pi == 1 && !empty($l2_form_data)){ ?>  
                                <form action="#" method="post" id="ail2nl3formupdate" name="ail2nl3formupdate" data-parsley-validate enctype="multipart/form-data">
                                    <?php include_once 'view_incident_l2_and_l3_process.php'; ?>
                                    <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                </form>                        
                            <?php } ?>

                            <?php if($is_yp_missing == 1 && !empty($l4_form_data)){ ?>                
                                <form action="#" method="post" id="ail4formupdate" name="ail4formupdate" data-parsley-validate enctype="multipart/form-data">
                                    <?php include_once 'view_incident_l4_process.php'; ?>
                                    <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                </form>                        
                            <?php } ?>
                            <?php if($is_yp_injured == 1 && !empty($l5_form_data)){ ?>                
                                <form action="#" method="post" id="ail5formupdate" name="ail5formupdate" data-parsley-validate enctype="multipart/form-data">
                                    <?php include_once 'view_incident_l5_process.php'; ?>
                                    <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                </form>                        
                            <?php } ?>

                            <?php if($is_yp_complaint == 1 && !empty($l6_form_data)){ ?>                
                                <form action="#" method="post" id="ail6formupdate" name="ail6formupdate" data-parsley-validate enctype="multipart/form-data">
                                    <?php include_once 'view_incident_l6_process.php'; ?>
                                    <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                </form>                
                            <?php } ?>

                            <?php if($is_yp_safeguarding == 1 && !empty($l7_form_data)){ 
                                /*if (isset($access_data_val) && !empty($access_data_val) && in_array($loggedInUser['ID'],$access_data_val)){*/
                                    ?>                
                                    <form action="#" method="post" id="ail7formupdate" name="ail7formupdate" data-parsley-validate enctype="multipart/form-data">
                                        <?php include_once 'view_incident_l7_process.php'; ?>
                                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                    </form>                
                                <?php /*}*/ } ?>

                                <?php if($is_staff_injured == 1 && !empty($l8_form_data)){ ?>                
                                    <form action="#" method="post" id="ail8formupdate" name="ail8formupdate" data-parsley-validate enctype="multipart/form-data">
                                        <?php include_once 'view_incident_l8_process.php'; ?>
                                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                    </form>                
                                <?php } ?>

                                <?php if(!empty($l9_form_data) && (($is_yp_injured==1) ||($is_staff_injured==1) ||($is_yp_safeguarding==1) ||($is_yp_complaint==1) || ($is_other_injured == 1))){ ?>                
                                    <form action="#" method="post" id="ail9formupdate" name="ail9formupdate" data-parsley-validate enctype="multipart/form-data">
                                        <?php include_once 'view_incident_l9_process.php'; ?>
                                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                    </form>                
                                <?php } ?>

                                <?php if($review_status == 1){ ?>                
                                    <form action="#" method="post" id="ailreviewformupdate" name="ailreviewformupdate" data-parsley-validate enctype="multipart/form-data">
                                        <?php include_once 'view_review_process.php'; ?>
                                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                    </form>                
                                <?php }else{ ?>
                                    <form action="<?= base_url('AAI/updateReviewForm/'.$incidentId) ?>" method="post" id="ailreviewformupdate" name="ailreviewformupdate" data-parsley-validate enctype="multipart/form-data">
                                        <?php include_once 'review_process.php'; ?>
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
                                    <?php }else{ ?> 
                                        <form action="<?= base_url('AAI/updateManagerReviewForm/'.$incidentId) ?>" method="post" id="managerreviewformupdate" name="managerreviewformupdate" data-parsley-validate enctype="multipart/form-data">
                                            <?php include_once 'manager_review_process.php'; ?>
                                            <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                                        </form>                    
                                    <?php } } ?>
                                <?php } ?>
                                <div class="clearfix"> 
                                </div>
                            </div>
                        </div>
                        <?php if (checkPermission('AAI', 'signoff')) { ?> 

                            <div class="col-sm-12 p-l-r-0 m-sign-top">
                                <div class="panel panel-default tile tile-profile ">
                                    <div class="panel-body">
                                        <h2>sign off</h2>
                                        <?php if(empty($check_aai_signoff_data)){ ?>
                                            <input type="checkbox" onclick="manager_request(<?php echo $ypId . ',' . $incidentId; ?>);" name="aai_signoff" required="true" class="aai_signoff" value="1">
                                            <?php
                                            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                                            echo getUserName($login_user_id);
                                        } ?>
                                        <?php
                                        if (!empty($signoff_data)) {
                                            foreach ($signoff_data as $sign_name) { ?>
                                                <ul class="media-list media-xs">
                                                    <li class="media">
                                                        <div class="media-body">
                                                            <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                                        </div>
                                                    </li>
                                                </ul>
                                            <?php }
                                        }
                                        ?> 
                                    </div>

                                </div>
                            </div>
                        <?php } ?>




                        <?php if (!empty($incidentId)) { 
                           if (checkPermission('AAI', 'document_signoff')) {
                            ?>
                            <div class="col-sm-12 p-l-r-0">
                                <div class="panel panel-default tile tile-profile m-b-50">
                                    <div class="panel-body">
                                       <a href="javascript:;" data-href="<?php echo base_url() . 'AAI/signoff/'.$ypId.'/'.$incidentId; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning"><?php echo lang('external_approval'); ?></span></a>
                                       <?php if(!empty($check_external_signoff_data)){ ?>
                                           <a href="<?php echo base_url() . 'AAI/external_approval_list/'.$ypId.'/'.$incidentId; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                           </a>
                                       <?php } ?>
                                   </div>
                               </div>
                           </div> 

                       <?php }} ?>
                       <div class="row">
                        <div class="col-xs-12 sticky-next-pre">
                            <div class="pull-right btn-section ">
                                <div class="btn-group ">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row capsBtn">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-default action-button previous previous_button updat_bn">Previous</button>
                        <button type="button" class="btn btn-default next action-button updat_bn">Next</button> 
                        <div class="pull-right for-tab">
                            <div class="btn-group">
                                <a href="<?= base_url('YoungPerson/index/' . $YP_details[0]['care_home']) ?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                                <a href="<?= base_url('YoungPerson/view/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> YP INFO </a> 
                                <a href="<?= base_url('AAI/index/' . $ypId); ?>" class="btn btn-default"> <i class="fa fa-mail-reply"></i> ACCIDENT & INCIDENTS</a> 
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    <?php } ?>