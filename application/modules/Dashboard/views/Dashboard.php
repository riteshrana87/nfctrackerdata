<?php //pr($staff_no_data);                                                              ?>
<script>
    var baseurl = '<?php echo base_url(); ?>';
</script>
<div id="page-wrapper">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }else{
            echo $this->session->flashdata('error_msg');
        }
        ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">NEW FOREST CARE CASE FILES</h1>
    </div>
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="profile widget-shadow">
                    <div class="profile-top">
                        <?php if (empty($logged_user[0]['profile_img'])) { ?>
                            <img src="<?= base_url() ?>uploads/assets/front/images/default-user.jpg" alt="">
                            <?php
                        } else {
                            if (!empty($logged_user) && @getimagesize($logged_user[0]['profile_img'])) {
                                ?>
                                <img src="<?= $logged_user[0]['profile_img'] ?>" alt=""/>
                            <?php } else { ?>
                                <img src="<?= base_url() ?>uploads/assets/front/images/default-user.jpg" alt="">    
                                <?php
                            }
                        }
                        ?>

                        <h4><?= !empty($logged_user[0]['name']) ? $logged_user[0]['name'] : '' ?></h4>
                        <h5><?= !empty($logged_user[0]['role_name']) ? $logged_user[0]['role_name'] : '' ?> </h5>
                    </div>
                    <div class="profile-btm">
                        <ul>
                            <li>
                                <a target="_blank" href="https://portal.office.com">
                                    <h4>NFC Email</h4>
                                </a>
                            </li>
                            <li>
                                <a data-href="<?php echo base_url('Help/add'); ?>" data-toggle="ajaxModal" class="" aria-hidden="true" data-refresh="true" href="javascript:;">
                                    <h4>Help/Feedback</h4>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-8">
                <div class="panel-group tool-tips" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    STAFF NOTICES
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <div class="row">

                                    <?php
                                    $i = 1;
                                    $count = count($staff_no_data);
                                    foreach ($staff_no_data as $key => $value) {
                                        ?>

                                        <div class="col-md-6">
                                            <div class="panel panel-default tile">
                                                <div class="panel-body padd_zero">
                                                    <div class="dashboard_content">
                                                    <h2><span class="title"><?php echo $value['title']; ?></span>
                                                      
                                                    </h2>
                                                   <div class="clearfix"></div>
                                                    <p class="slimScroll"><?php echo $value['notice'] ?></p>
                                                </div>
                                                    <h6 class="bottom_attachment"><div class="attachments">
                                                        <?php
                                                        $staff_id = $value['staff_notices_id'];
                                                        $upload_data = getStaffUploadData($staff_id);
                                                        if(!empty($upload_data)){ ?>
                                                            <a href="<?php echo base_url('Dashboard/download/' . $staff_id); ?>" title="" class="attachment"><i class="fa fa-paperclip fa-flip-horizontal" aria-hidden="true"></i></a>
                                                        <?php } ?>
                                                        <?php
                                                        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                                                        $created_by = $value['created_by'];
                                                        if ($created_by == $login_user_id) {
                                                            ?>
                                                            <a href="javascript:;" data-href="<?php echo base_url('Dashboard/editStaff/' . $staff_id); ?>" class="attachment" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title=""><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                                                            <a href="javascript:;" onclick="deleteStaffData(<?php echo $staff_id; ?>);" class="attachment" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                        <?php } ?>
                                                      </div><span><b><?php echo $value['firstname'] . ' ' . $value['lastname']; ?>: </b><?= (!empty($value['created_date']) && $value['created_date'] != '0000-00-00') ? configDateTime($value['created_date']) : '' ?></span></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($i % 2 == 0 && $i > 0) { ?>
                                            <div class="clearfix"></div>
                                        <?php } ?>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    <div class="col-md-12">
                                        <a data-href="<?php echo base_url() . 'Dashboard/staffNotices'; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-default pull-right" >Add New Notice</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <a class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    SCHOOL HANDOVER FOR CARE
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <div class="row">

                                    <?php
                                    $i = 1;
                                    $count = count($school_hand_data);
                                    foreach ($school_hand_data as $key => $value) {
                                        ?>

                                        <div class="col-md-6">
                                            <div class="panel panel-default tile">
                                                <div class="panel-body padd_zero">
                                                     <div class="dashboard_content">
                                                    <h2>
                                                      <span class="title"><?php echo $value['title']; ?> </span>
                                                      
                                                    </h2>
                                                  <div class="clearfix"></div>
                                                    <p class="slimScroll"><?php echo $value['notice'] ?></p>
                                                </div>
                                                    <h6 class="bottom_attachment"><div class="attachments">
                                                        <?php
                                                        $school_hand_id = $value['school_handover_id'];
                                                        $upload_data = getSchoolHandUploadData($school_hand_id);
                                                        if(!empty($upload_data)){
                                                            ?>
                                                            <a href="<?php echo base_url('Dashboard/HandoverFiledownload/' . $school_hand_id); ?>" title="" class="attachment"><i class="fa fa-paperclip fa-flip-horizontal" aria-hidden="true"></i></a>

                                                        <?php } ?>
                                                        </div><span><b><?php echo $value['firstname'] . ' ' . $value['lastname']; ?>: </b><?= (!empty($value['created_date']) && $value['created_date'] != '0000-00-00') ? configDateTime($value['created_date']) : '' ?></span></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($i % 2 == 0 && $i > 0) { ?>
                                            <div class="clearfix"></div>
                                        <?php } ?>
                                        <?php
                                        $i++;
                                    }
                                    ?>

                                    <div class="col-md-12">
                                        <a data-href="<?php echo base_url() . 'Dashboard/schollHandover'; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-default pull-right" >Add School Handover</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTherre">
                            <h4 class="panel-title">
                                <a class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTherre" aria-expanded="false" aria-controls="collapseTherre">
                                   CARE HANDOVER FOR SCHOOL 
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTherre" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTherre">
                            <div class="panel-body">
                                <div class="row">

                                    <?php
                                    $i = 1;
                                    $count = count($crisis_hand_data);
                                    foreach ($crisis_hand_data as $key => $value) {
                                        ?>

                                        <div class="col-md-6">
                                            <div class="panel panel-default tile">
                                                <div class="panel-body padd_zero">
                                                     <div class="dashboard_content">
                                                    <h2>
                                                      <span class="titile"><?php echo $value['title']; ?> </span>
                                                      
                                                    </h2>
                                                  <div class="clearfix"></div>
                                                    <p class="slimScroll"><?php echo $value['notice'] ?></p>
                                                </div>
                                                    <h6 class="bottom_attachment"><div class="attachments">
                                                        <?php
                                                        $crisis_hand_id = $value['crisis_handover_id'];
                                                        $upload_data = getCrisisHandUploadData($crisis_hand_id);
                                                        if(!empty($upload_data)){
                                                        ?>
                                                            <a href="<?php echo base_url('Dashboard/CrisisHandoverFiledownload/' . $crisis_hand_id); ?>" title="" class="attachment"><i class="fa fa-paperclip fa-flip-horizontal" aria-hidden="true"></i></a>

                                                        <?php } ?>
                                                        </div><span><b><?php echo $value['firstname'] . ' ' . $value['lastname']; ?>: </b><?= (!empty($value['created_date']) && $value['created_date'] != '0000-00-00') ? configDateTime($value['created_date']) : '' ?></span></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($i % 2 == 0 && $i > 0) { ?>
                                            <div class="clearfix"></div>
                                        <?php } ?>
                                        <?php
                                        $i++;
                                    }
                                    ?>

                                    <div class="col-md-12">
                                        <a data-href="<?php echo base_url() . 'Dashboard/CrisisHandover'; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-default pull-right" >Add Care Handover</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
   $('.panel-heading a').click(function() {
    $('.panel-heading').removeClass('header_bg_collaps');
    if(!$(this).closest('.panel').find('.panel-collapse').hasClass('in'))
    $(this).parents('.panel-heading').addClass('header_bg_collaps');
});
});

</script>