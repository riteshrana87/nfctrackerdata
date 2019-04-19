
<div id="page-wrapper">
    <div class="main-page">
        <!--<h1 class="page-title">CRISIS TEAM New <small>Forest Care</small></h1>-->
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }
        ?>
         <div class="sticky-heading hidden-md" id="sticky-heading">
        </div>
        <span class="information-msg">Archived YP Info for <?php echo $care_home_data[0]['care_home_name'];?>.</span>
        <div class="row">
            <div class="col-md-12">
                <div class="profile widget-shadow profile-page new_profile_page">
                    <div class="profile-top">
                        <div class="row dashboard_main">
                          <div class="col-md-3 profile_side">
                             <div class="absolute remove-line">

                            <?php if (empty($editRecord[0]['profile_img'])) { ?>
                                <?php /* <img src="<?= base_url() ?>uploads/assets/front/images/img1.png" /> */ ?>
                                <div class="img-con">
                                    <img src="<?php echo $this->config->item('yp_profile_no_image_base_url'); ?>" />
                                </div>
                                <?php
                            } else {
                                if (!empty($editRecord) && file_exists(FCPATH . $this->config->item('yp_img_upload_path') . $editRecord[0]['profile_img'])) {
                                    ?>
                                    <div class="img-con">
                                        <img src="<?= base_url() . $this->config->item('yp_img_upload_path') . $editRecord[0]['profile_img'] ?>" />
                                    </div>
                                <?php } else {
                                    ?>
                                    <?php /* <img src="<?= base_url() ?>uploads/assets/front/images/img1.png" /> */ ?>
                                    <div class="img-con">
                                        <img src="<?php echo $this->config->item('yp_profile_no_image_base_url'); ?>" />
                                    </div>
                                    <?php
                                }
                            }
                            ?>


                            <h4><?= !empty($editRecord[0]['yp_fname']) ? $editRecord[0]['yp_fname'] : '' ?>
                                <?= !empty($editRecord[0]['yp_lname']) ? $editRecord[0]['yp_lname'] : '' ?>
                                <?php if (checkPermission('Filemanager', 'view') && !empty($editRecord)) { ?>  
                                    <a class="btn-gallery" href="<?php echo base_url('Filemanager/index/' . $editRecord[0]['yp_id'].'/'.$care_home_id.'/'.$past_care_home_id); ?>" title="View Photo Gallery">
                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                    </a>
                                <?php } ?>
                            </h4>
                            <h5>Young Person Profile</h5>

                        </div>
                      </div>
                      <div class="col-md-9 modules_div">
                            <?php if (checkPermission('DailyObservation', 'view')) { ?>
                                  <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Daily Observations" href="<?php echo base_url('DailyObservation/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-folder-open"></i>DO</a>
                                  </div>
                                <?php } ?>
                          
                                 <?php if (checkPermission('KeySession', 'view')) { ?>
                                  <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Key Sessions" href="<?php echo base_url('KeySession/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>KS</a>
                                  </div>
                                <?php } ?>
                                
                                <?php if (checkPermission('Concerns', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="YP Concerns" href="<?php echo base_url('Concerns/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>YPC</a>
                          </div>
                          <?php } ?>
                           <?php if (checkPermission('ArchiveIndividualStrategies', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Individual Strategies" href="<?php echo base_url('ArchiveIndividualStrategies/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>IS</a>
                          </div>
                                <?php } ?> 
                                
                                <?php if (checkPermission('RiskAssesment', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Risk Assesment" href="<?php echo base_url('ArchiveRiskAssesment/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-envelope"></i>RA</a>
                          </div>
                                <?php } ?>
                            
                            
                                <?php if (checkPermission('Ibp', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Individual Behaviour Plan" href="<?php echo base_url('ArchiveIbp/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>IBP</a>
                          </div>
                                <?php } ?>
                            
                            
                                <?php if (checkPermission('PlacementPlan', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                            <a title="Placement Plan" href="<?php echo base_url('ArchivePlacementPlan/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>PP</a>
                          </div>
                                <?php } ?>
                            
                            
                                <?php if (checkPermission('Communication', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Communication Log" href="<?php echo base_url('Communication/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-phone-square"></i>COM</a>
                          </div>
                                <?php } ?>
                            <?php if (checkPermission('Medical', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Medical Information" href="<?php echo base_url('Medical/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-medkit"></i>MEDS</a>
                          </div>
                                <?php } ?>
                          
                            
                                <?php if (checkPermission('Documents', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Documents" href="<?php echo base_url('Documents/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-edit"></i>DOCS</a>
                          </div>
                                <?php } ?>
                            
                            
                                <?php if (checkPermission('CseReport', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                <a title="Child Sexual Exploitation" href="<?php echo base_url('CseReport/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>CSE
                                </a>
                          </div>
                                <?php } ?>
                            
                          
                            <?php  if (checkPermission('WeeklyReport', 'view')) { ?>
                          <div class="col-sm-2 text-center w-20-per m-b-30">
                                <a title="Weekly Report to Social Worker" href="<?php echo base_url('WeeklyReport/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>WR
                                </a>
                          </div>
                                <?php }  ?>
                                <?php if (checkPermission('MDTReviewReport', 'view')) { ?>
                          <div class="col-sm-2  col-md-offset-0 text-center w-20-per m-b-30">
                                    <a title="MDT Review Report" href="<?php echo base_url('MDTReviewReport/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>MDT</a>
                          </div>
                                <?php } ?>
                            
                          <div class="col-sm-2  col-md-offset-0 text-center w-20-per m-b-30">
                                <?php if (checkPermission('SdqReport', 'view')) { ?>
                                    <a title="Strengths & Difficulties Questionnaires" href="<?php echo base_url('SdqReport/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>SDQ</a>
                                <?php } ?>
                            </div>
                            
                                <?php if (checkPermission('YPFinance', 'view')) { ?>
                          <div class="col-sm-2  col-md-offset-0 text-center w-20-per m-b-30">
                                    <a title="YP Finance" href="<?php echo base_url('YPFinance/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>YPF</a>
                                    </div>
                                <?php } ?>
                            
                            <?php if (checkPermission('LocationRegister', 'view')) { ?>
                                  <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Location Register" href="<?php echo base_url('LocationRegister/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>LR</a>
                                  </div>
                                <?php } ?>
                             <?php if (checkPermission('RiskManagementPlan', 'view')) { ?>
                                  <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Risk Management Plan" href="<?php echo base_url('RiskManagementPlan/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>RMP</a>
                                  </div>
                                <?php } ?>
                                <?php if (checkPermission('Appointments', 'view')) { ?>
                                  <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Appointment / Event" href="<?php echo base_url('Appointments/index/' . $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>Appointment / Event</a>
                                  </div>
                                <?php } ?>
                                <?php if (checkPermission('Planner', 'view')) { ?>
                                  <div class="col-sm-2 text-center w-20-per m-b-30">
                                    <a title="Planner" href="<?php echo base_url('Planner/index/'. $id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>Planner</a>
                                  </div>
                                <?php } ?>
                                <?php  if (checkPermission('ParentsCarerDetails', 'view')) { ?>
                                  <div class="col-sm-2 text-center w-20-per m-b-30">
                        <a title="Planner" href="<?php echo base_url('ParentsCarerDetails/index/'.$id .'/'. $care_home_id .'/'. $past_care_home_id); ?>"><i class="fa fa-file-text"></i>Parents & Carers</a>
                                  </div>
                                <?php } ?>


                                <?php /* if (checkPermission('Mail', 'view')) { ?>
                                  <div class="col-sm-3 text-center w-20-per m-b-30">
                                      <a class="profile-badge-link" title="Mail" href="<?php echo base_url('ArchiveMail/index/'.$id .'/INBOX/'. $care_home_id .'/'. $past_care_home_id); ?>">
                                          <i class="fa fa-file-text"></i>Mail <span class="badge profile-badge" style=""><?= (intval($countMails)>0)? $countMails:'' ?></span></a>
                                  </div>
                                <?php } */ ?>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <!-- <h1 class="page-title">Personal Information</h1> -->
                <div class="panel panel-default tile tile-profile main_dash_icon">
                    <div class="panel-body min-h-330">

                        <h2>Personal Info</h2>
                         <div class="body_content">
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Initials</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['yp_initials'])) ? $editRecord[0]['yp_initials']: lang('NA') ?></p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>D.O.B.</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['date_of_birth']) && $editRecord[0]['date_of_birth'] != '0000-00-00') ? configDateTime($editRecord[0]['date_of_birth']) : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Age</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['age']) ? $editRecord[0]['age'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Place of Birth</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['place_of_birth']) && $editRecord[0]['place_of_birth'] != '0000-00-00') ? $editRecord[0]['place_of_birth'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Gender</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p>
                                    <?php
                                    if (isset($editRecord[0]['gender'])) {
                                        echo (($editRecord[0]['gender'] == 'M') ? 'Male' : (($editRecord[0]['gender'] == 'F') ? 'Female' : lang('NA')));
                                    } else {
                                        echo lang('NA');
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Date of Placement</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['date_of_placement']) && $editRecord[0]['date_of_placement'] != '0000-00-00') ? configDateTime($editRecord[0]['date_of_placement']) : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>End of Placement</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['end_of_placement']) && $editRecord[0]['end_of_placement'] != '0000-00-00') ? configDateTime($editRecord[0]['end_of_placement']) : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Legal Status</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p>
                                    <?php
                                    if ($editRecord[0]['legal_status'] == 1) {
                                        echo "Section 31 (Full Care Order)";
                                    } else if ($editRecord[0]['legal_status'] == 2) {
                                        echo "Section 20 (Accommodated)";
                                    } else if ($editRecord[0]['legal_status'] == 3) {
                                        echo "Interim Care Order (ICO)";
                                    } else {
                                        echo lang('NA');
                                    }
                                    ?>



                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Staffing Ratio</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['staffing_ratio']) ? $editRecord[0]['staffing_ratio'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Assessment Date start</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['assessment_date_start']) && $editRecord[0]['assessment_date_start'] != '0000-00-00') ? configDateTime($editRecord[0]['assessment_date_start']) : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Assessment Date End</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['assessment_date_end']) && $editRecord[0]['assessment_date_end'] != '0000-00-00') ? configDateTime($editRecord[0]['assessment_date_end']) : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Assessment review Date</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['assessment_review_date']) && $editRecord[0]['assessment_review_date'] != '0000-00-00') ? configDateTime($editRecord[0]['assessment_review_date']) : lang('NA') ?></p>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <!-- <h1 class="page-title">Authority Information</h1> -->
                <div class="panel panel-default tile tile-profile main_dash_icon">
                    <div class="panel-body min-h-330">
                        <h2>Authority Information</h2>
                        <h3 class="care_sub_title">Placing Authority</h3> 
                        <div class="body_content">
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Authority</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['authority']) ? $editRecord[0]['authority'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Address</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['address_1']) ? $editRecord[0]['address_1'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Town</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['town']) ? $editRecord[0]['town'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>County</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['county']) ? $editRecord[0]['county'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Postcode</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['postcode']) ? $editRecord[0]['postcode'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>EDT / Out Of Hours</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['out_of_hours']) ? $editRecord[0]['out_of_hours'] : lang('NA') ?></p>
                            </div>
                        </div>

                    </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <!-- <h1 class="page-title">Authority Information</h1> -->
                <div class="panel panel-default tile tile-profile main_dash_icon">
                    <div class="panel-body min-h-330">
                        <h2>Authority Information</h2>
                        <h3 class="care_sub_title">Social Worker Details</h3>
                        <div class="body_content">
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Social Worker</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['social_worker_firstname']) ? $editRecord[0]['social_worker_firstname'] : '' ?> <?= !empty($editRecord[0]['social_worker_surname']) ? $editRecord[0]['social_worker_surname'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Mobile</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['mobile']) ? $editRecord[0]['mobile'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Landline</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['landline']) ? $editRecord[0]['landline'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Other</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['other']) ? $editRecord[0]['other'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Email</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p>
                                    <?php if(!empty($editRecord[0]['email'])){?>
                                    <a href="<?= !empty($editRecord[0]['email']) ? 'mailto:' . $editRecord[0]['email'] : '#' ?>"><?= !empty($editRecord[0]['email']) ? $editRecord[0]['email'] : '' ?></a>
                                    <?php }else{
                                        echo lang('NA');
                                    }
                                        ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Senior SW</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($editRecord[0]['senior_social_worker_firstname']) ? $editRecord[0]['senior_social_worker_firstname'] : '' ?> <?= !empty($editRecord[0]['senior_social_worker_surname']) ? $editRecord[0]['senior_social_worker_surname'] : lang('NA') ?> </p>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-sm-6">
                <!-- <h1 class="page-title">Overview of Young Person</h1> -->
                <div class="panel panel-default tile tile-profile main_dash_icon">
                    <div class="panel-body min-h-170 " >
                        <h2>Overview of Young Person</h2>
                        <h3 class="care_sub_title">Pen Portrait & Risk Oversight</h3>
                        <div class="body_content">
                        <div class ="slimScroll-245">
                            <?= !empty($editRecord[0]['pen_portrait']) ? htmlspecialchars_decode(stripslashes($editRecord[0]['pen_portrait']), ENT_QUOTES) : lang('NA') ?>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
          <div class="col-xs-12">
            <!-- <h1 class="page-title">Care Home Placing Information</h1> -->
          </div>
          <div class="clearfix"></div>
            <div class="col-lg-3 col-sm-6 ">
                <div class="panel panel-default tile tile-profile  main_dash_icon">
                    <div class="panel-body">
                        <h2>Care Home Placing Information</h2>
                        <h3 class="care_sub_title">Pen Portrait & Risk Oversight</h3>
                        <div class="body_content">
                        <?php
                        $currentCareHome = $this->YP_model->get_carehome($editRecord[0]['care_home']);
                        ?>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Current Care Home :</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($currentCareHome[0]['care_home_name']) ? $currentCareHome[0]['care_home_name'] : lang('NA') ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class=" "><small>Admission/Placement Date :</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= (!empty($editRecord[0]['care_home_admission_date'])&& $editRecord[0]['care_home_admission_date'] != '0000-00-00') ? configDateTime($editRecord[0]['care_home_admission_date']) : lang('NA') ?></p>
                            </div>
                        </div>
                      </div>
                    </div>

                </div>
            </div>
            <?php
            if (!empty($care_homeRecord)) {
                foreach ($care_homeRecord as $row) {
                    $pastCareHome = $this->YP_model->get_carehome($row['past_carehome']);

                    ?>
                    <div class="col-lg-3 col-sm-6 ">
                        <!-- <h1 class="page-title"> &nbsp;</h1> -->
                        <div class="panel panel-default tile tile-profile main_dash_icon">
                            <div class="panel-body">
                                <h2>Previous Care Home Info
                                   
                                </h2>
                                <div class="body_content">
                                <div class="row">
                                    <div class="col-xs-4 col-sm-5 padding-r-0">
                                        <p class=" "><small>Past Care Home :</small></p>
                                    </div>
                                    <div class="col-xs-8 col-sm-7">
                                        <p><?php echo $pastCareHome[0]['care_home_name']; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4 col-sm-5 padding-r-0">
                                        <p class=" "><small>Duration :</small></p>
                                    </div>
                                    <div class="col-xs-8 col-sm-7">
                                        <p>From <?= (!empty($row['enter_date'])&& $row['enter_date'] != '0000-00-00') ? configDateTime($row['enter_date']) : lang('NA') ?>  To <?= (!empty($row['move_date'])&& $row['move_date'] != '0000-00-00') ? configDateTime($row['move_date']) : lang('NA') ?></p>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
        </div>
        <!--        <div class="row">
                    <div class="col-xs-12">
                        <a class="btn btn-default" href="#">Archive</a>
                        <a class="btn btn-default" href="#">Upload File</a>
                    </div>
                </div>-->
    </div>
</div>
<script>
    $(function(){
    $('#inner_text').slimScroll({
        height: '100px'
    });
});
</script>