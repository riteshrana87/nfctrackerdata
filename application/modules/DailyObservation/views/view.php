<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var Doid = '<?php echo $do_id ?>';
    var YPId = '<?php echo $ypid ?>';
	
</script>
<div id="page-wrapper">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }
        ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Daily Observations <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <?php
                    /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
                    ?>
                     <?php if($past_care_id == 0){ ?>   
                     <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                     <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>

                     <?php if (checkPermission('DailyObservation', 'view')) { ?>
                        <a href="<?= base_url('DailyObservation/index/' . $ypid); ?>" class="btn btn-default"><i class="fa fa-mail-reply"></i> DO
                        </a>
                    <?php } ?>

                    <?php if (checkPermission('DailyObservation', 'edit')) { ?>
                        <a href="<?= base_url('DailyObservation/edit_do/' . $do_id . '/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-edit" aria-hidden="true"></i> Edit DO
                        </a>
                    <?php } ?>

                    <?php if (checkPermission('ArchiveDailyObservation', 'view')) { ?>
                        <a href="<?= base_url('ArchiveDailyObservation/index/' . $do_id . '/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-search" aria-hidden="true"></i> VIEW ARCHIVE
                        </a>
                    <?php } ?>    

                     <?php }else{ ?>

                     <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                     <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?= base_url('DailyObservation/index/' . $ypid .'/'. $care_home_id .'/'.$past_care_id); ?>" class="btn btn-default"><i class="fa fa-mail-reply"></i> DO
                        </a>
                        <?php if (checkPermission('ArchiveDailyObservation', 'view')) { ?>
                    <a href="<?= base_url('ArchiveDailyObservation/index/' . $do_id . '/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                            <i class="fa fa-search" aria-hidden="true"></i> VIEW ARCHIVE
                        </a>
                    <?php } ?>

                     <?php } ?>
                     <a href="<?= base_url('DailyObservation/DownloadPrint/' . $dodata[0]['do_id'] . '/' . $ypid. '/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                    
                    <a href="<?=base_url('Mail/ComposeMailDO/'.$dodata[0]['do_id'].'/'.$ypid); ?>" class="btn btn-default">
                                 <i class="fa fa-edit"></i> Email
                            </a>
                    
                </div>
            </div>
        </h1>
        <div class="clearfix"></div>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div>
            <small>Created Date:</small>  <?= (!empty($dodata[0]['daily_observation_date']) && $dodata[0]['daily_observation_date'] != '0000-00-00') ? configDateTime($dodata[0]['daily_observation_date']) : '' ?>
            
        </h1>
    </div>
        <div class="clearfix"></div>
        <div class="row m-t-10">
            <div class="col-md-6 col-sm-6">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-250">
                        <h2>Overview
                            <?php
                            if($past_care_id == 0){
                             if (checkPermission('DailyObservation', 'edit')) { ?>
                                <a class="pull-right" href="<?= base_url('DailyObservation/add_overview/' . $do_id . '/' . $ypid); ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <?php } }
                            ?></h2>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Young Person</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($dodata[0]['yp_name']) ? $dodata[0]['yp_name'] : '' ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Staff</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?= !empty($dodata[0]['create_name']) ? $dodata[0]['create_name'] : '' ?></p>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Awake</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p>
                                    <?php
                                    if (!empty($do_prev_data[0]['awake_time'])) {
                                        $diff = new HtmlDiff(timeformat($do_prev_data[0]['awake_time']), !empty($dodata[0]['awake_time']) ? timeformat($dodata[0]['awake_time']) : '' );
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else {
                                        ?><?= (!empty($dodata[0]['awake_time']) && $dodata[0]['awake_time'] != '00:00:00') ? timeformat($dodata[0]['awake_time']) : '' ?>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Bedtime</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p>
                                    <?php
                                    if (!empty($do_prev_data[0]['bed_time'])) {
                                        $diff = new HtmlDiff(timeformat($do_prev_data[0]['bed_time']), !empty($dodata[0]['bed_time']) ? timeformat($dodata[0]['bed_time']) : '' );
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else {
                                        ?><?= (!empty($dodata[0]['bed_time']) && $dodata[0]['bed_time'] != '00:00:00') ? timeformat($dodata[0]['bed_time']) : '' ?>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Contact</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p>
                                    <?php
                                    if (!empty($do_prev_data[0]['contact'])) {
                                        $diff = new HtmlDiff($do_prev_data[0]['contact'], !empty($dodata[0]['contact']) ? $dodata[0]['contact'] : '' );
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else {
                                        ?><?= (!empty($dodata[0]['contact']) && $dodata[0]['contact'] != '') ? $dodata[0]['contact'] : '' ?>
                                    <?php } ?>
                                </p>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Staffing</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <?php
                                if (!empty($do_staff_data)) { 
                                    foreach ($do_staff_data as $staff) {
                                        echo '<p>';
                                        if (!empty($staff['is_new'])) {
                                            $diff = new HtmlDiff('', $staff['staff_name']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                        } else {
                                            ?>
                                            <?= !empty($staff['staff_name']) ? $staff['staff_name'] : '' ?>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if($past_care_id == 0){
                                         if (checkPermission('DailyObservation', 'delete')) { ?>
                                        <button onclick="deleteStaff('<?=$staff['do_staff_id']?>','<?=$staff['do_id']?>','<?=$ypid?>');" aria-hidden="true" data-refresh="true" title="" class="btn btn-link p-t-b-0"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        <?php } }
                                        echo '</p>';
                                    }
                                }
                                ?>

                                <p>
                                    <?php 
                                    if($past_care_id == 0){
                                    if (checkPermission('DailyObservation', 'edit')) { ?>
                                        <a href="<?= base_url('DailyObservation/add_staff/' . $do_id . '/' . $ypid); ?>" class="btn btn-default btn-sm">Add Staff</a>
                                    <?php }} ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-250">
                        <h2>Food Consumed</h2>
                        <?php
                        if (!empty($food_form_data)) {
                            foreach ($food_form_data as $row) {
                                if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') { 
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-5 padding-r-0">
                                            <p class="text-right"><small><?= !empty($row['label']) ? $row['label'] : '' ?></small></p>
                                        </div>
                                        <div class="col-xs-8 col-sm-7">
                                            <p>
                                                <?php
                                                if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'select') {
                                                    if(!empty($food_data[0][$row['name']]) && is_json($food_data[0][$row['name']])){ 
                                                        $jdata = json_decode($food_data[0][$row['name']]);
                                                        $prevjdata =array();
                                                        $prevjdata = !empty($food_previous_data)?json_decode($food_previous_data[0][$row['name']]):'';

                                                        if(!empty($jdata))
                                                        {

                                                            for($i=0;$i<count($jdata);$i++)
                                                            {
                                                                $diff = new HtmlDiff(!empty($prevjdata[$i]->content)?$prevjdata[$i]->content:'', $jdata[$i]->content);
                                                                $diff->build();
                                                                echo $diff->getDifference();

                                                                $match = array("login_id" => $jdata[$i]->user_id);
                                                                $fields = array("CONCAT(`firstname`,' ', `lastname`) as create_name");
                                                                $logindetail = $this->common_model->get_records(LOGIN, $fields, '', '', $match);
                                                                ?>
                                                                <p class="date"><small><?=!empty($logindetail[0]['create_name'])?$logindetail[0]['create_name'].' : ':''?> <?=!empty($jdata[$i]->date)?configDateTimeFormat($jdata[$i]->date):''?> </small></p>
                                                                <?php
                                                            }
                                                        }
                                                    } else { ?>
                                                        <?php if ($row['subtype'] == 'time'){ ?>
                                                            <?=isset($food_data[0][$row['name']])?nl2br(timeformat($food_data[0][$row['name']])):(isset($row['value'])?timeformat($row['value']):'')?>
                                                        <?php }else if($row['type'] == 'date'){ ?>
                                                                <?=isset($food_data[0][$row['name']])?nl2br(configDateTime($food_data[0][$row['name']])):(isset($row['value'])?configDateTime($row['value']):'')?>
                                                                
                                                        <?php }else{ ?>
                                                            <?=isset($food_data[0][$row['name']])?nl2br(htmlentities($food_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                        <?php } ?>
                                                    <?php } ?>


                                            <?php } else if ($row['type'] == 'checkbox-group') {
                                                    if (!empty($food_data[0][$row['name']])) {
                                                        $chk = explode(',', $food_data[0][$row['name']]);
                                                        foreach ($chk as $chk) {
                                                            echo $chk . "\n";
                                                        }
                                                    } else {

                                                        if (count($row['values']) > 0) {

                                                            foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected']) ? '<li>' . $chked['value'] . "\n" : '';
                                                            }
                                                        }
                                                    }
                                                    ?>

                                                    <?php
                                                } else if ($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                    if (isset($food_data[0][$row['name']])) { 
                                                        echo isset($food_data[0][$row['name']]) ? nl2br(htmlentities($food_data[0][$row['name']])) : '';
                                                    } else {
                                                        if (count($row['values']) > 0) {

                                                            foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected']) ? $chked['value'] : '';
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                       
                                    </div>
                                    <?php
                                } else if ($row['type'] == 'header') {
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-5 padding-r-0">
                                            <div class="text-right">
                                                <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                                <?php echo '<' . $head . ' class="page-title">'; ?>
                                                <?= !empty($row['label']) ? $row['label'] : '' ?>

                                                <?php echo '</' . $head . '>'; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else if ($row['type'] == 'file') {
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-5 padding-r-0">
                                            <p class="text-right"><?= !empty($row['label']) ? $row['label'] : '' ?></p>    
                                        </div>
                                        <div class="col-xs-8 col-sm-7 padding-r-0">
                                            <?php
                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                            $fileViewArray = array(
                                                'fileArray' => (isset($food_data[0][$row['name']]) && !empty($food_data[0][$row['name']]))? $food_data[0][$row['name']] : $row['value'],
                                                'filePathMain' => $this->config->item('do_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('do_img_base_url_small') . $ypid
                                            );
                                            echo getFileView($fileViewArray);
                                            ?>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            <?php }
                        }
                        ?>

                    </div>
                </div>
            </div>
            <?php if(!empty($do_professionals_data)){?>
             <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>MEDICAL APPOINTMENTS
                                    </h2>
                                    <ul class="media-list media-xs">
                                        <li class="media">
                                            <div class="media-body">
                                              <?php foreach ($do_professionals_data as $value) { ?>
                                                <p class ="small">Professionals: <?= !empty($value['mp_name']) ? $value['mp_name'] : '' ?></p>
                                                <p class ="small">Date of Appointment: <?=(!empty($value['appointment_date']) && $value['appointment_date'] !='0000-00-00')?configDateTime($value['appointment_date']):''?> 
                                                </p>
                                                <p class ="small">Time: <?=(!empty($value['appointment_time']) && $value['appointment_time'] !='0000-00-00')?timeformat($value['appointment_time']):''?>
                                                 </p>
                                                <p class ="small">Comments: <?= !empty($value['comments']) ? $value['comments'] : '' ?>  </p>
												
												<p class ="small">Comments By:   </p>
												 <?php if(!empty($appointment_view_comments)){ 
															foreach ($appointment_view_comments as $comments_data) {
																 if($value['appointment_id']==$comments_data['md_appoint_id']){
																	?>
																	<ul class="media-list media-xs">
															<li class="media">
																<div class="media-body">
																	<p class="small">
																<?php echo $comments_data['md_comment']?>
																	</p>
																	<p class="date"><small><?php echo $comments_data['create_name'] ?>:   <?php echo configDateTime($comments_data['created_date']); ?></small></p>
																</div>
															</li>
														</ul>
																<?php } ?>

														
														<?php } }?>   
                                                <hr></hr>
                                              <?php } ?>      
                                                
                                                
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
            <?php if(!empty($do_planner_data)){?>
             <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>APPOINTMENTS /EVENTS
                                    </h2>
                                    <ul class="media-list media-xs">
                                        <li class="media">
                                            <div class="media-body">
                                              <?php foreach ($do_planner_data as $value) { ?>
                                                
                                                <p class ="small">Date of Appointment /Event: <?=(!empty($value['appointment_date']) && $value['appointment_date'] !='0000-00-00')?configDateTime($value['appointment_date']):''?> 
                                                </p>
                                                <p class ="small">Time: <?=(!empty($value['appointment_time']) && $value['appointment_time'] !='0000-00-00')?timeformat($value['appointment_time']):''?>
                                                 </p>
                                                 <p class ="small">Type Of Appointment / Event: <?= !empty($value['appointment_type']) ? $value['appointment_type'] : '' ?>  </p>
                                                <p class ="small">Comments: <?= !empty($value['comments']) ? $value['comments'] : '' ?>  </p>
                                                <hr></hr>
                                              <?php } ?>      
                                                
                                                
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>Morning Handover from previous day
                                    </h2>
                                    <?php
                                    if(!empty($lastDayData[0]['handover_next_day']) && is_json($lastDayData[0]['handover_next_day'])){ 
                                                        $jdata = json_decode($lastDayData[0]['handover_next_day']);
                                                        if(!empty($jdata))
                                                        { 

                                                            for($i=0;$i<count($jdata);$i++)
                                                            {
                                                                echo $jdata[$i]->content;
                                                                $match = array("login_id" => $jdata[$i]->user_id);
                                                                $fields = array("CONCAT(`firstname`,' ', `lastname`) as create_name");
                                                                $logindetail = $this->common_model->get_records(LOGIN, $fields, '', '', $match);
                                                                ?>
                                                                <p class="date"><small><?=!empty($logindetail[0]['create_name'])?$logindetail[0]['create_name'].' : ':''?> <?=!empty($jdata[$i]->date)?configDateTimeFormat($jdata[$i]->date):''?> </small></p>
                                                                <?php
                                                            }
                                                        }
                                                    } else {?>

                                                    <?=!empty($lastDayData[0]['handover_next_day'])?nl2br(htmlentities($lastDayData[0]['handover_next_day'])):(isset($row['value'])?$row['value']:lang('NA'))?>
                                                    <?php } ?>
                                </div>
                            </div>
                        </div>
            <!-- Start  administer medication-->
            <?php if(!empty($administer_medication)){?>
             <div class="clearfix"></div>
            <div class="col-lg-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>ADMINISTRATION HISTORY</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <?php
                                        if (!empty($form_data)) {
                                            foreach ($form_data as $row) {
                                                if (!empty($row['displayonlist'])) {
                                                    ?>
                                                    <th>  <?= !empty($row['label']) ? $row['label'] : '' ?></th>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            
                                                <th>Quantity Remaining</th>
                                           

                                </tr>
                                </thead>
                                <?php if (isset($administer_medication) && count($administer_medication) > 0) { ?>
                                    <?php foreach ($administer_medication as $data) { ?>
                                        <tbody>
                                            <tr>
                                                <?php
                                                if (!empty($form_data)) {
                                                    foreach ($form_data as $row) {
                                                        if (!empty($row['displayonlist'])) {
                                                            ?>
                                                            <td> 
                                    <?php
                                    if ($row['type'] == 'select') {
                                        if (!empty($data[$row['name']])) {
                                            if(!empty($row['description']) && $row['description'] == 'get_user'){
                                            
                                            $get_data = $this->common_model->get_single_user($data[$row['name']]);
                                            echo!empty($get_data[0]['username']) ? $get_data[0]['username'] : '';
                                        }else if(!empty($row['description']) && $row['description'] == 'get_medication'){
                         
                                      $get_medication_data = $this->common_model->get_single_medication($data[$row['name']]);
                                      echo!empty($get_medication_data[0]['medication_name']) ? $get_medication_data[0]['medication_name'] : ''; 
                                                } ?>

                                                            <?php }} else {
                                                                ?>
                                                                <?= (!empty($data[$row['name']]) && $data[$row['name']] != '0000-00-00') ? nl2br(html_entity_decode($data[$row['name']])) : (isset($row['value']) ? $row['value'] : '') ?>
                                                            <?php } ?>
                                                        </td>

                                                        <?php

                                                    }
                                                }
                                            }
                                            ?>
                                            <td>
                                            <?php 
                                                if(isset($data['available_stock'])){
                                                    echo $data['available_stock'];
                                                 } ?>
                                            </td>
                                                      </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="<?= !empty($mp_form_data) ? count($mp_form_data) : '10' ?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <!-- end  administer medication-->
            <div class="clearfix"></div>
            <?php
            if (!empty($summary_form_data)) {


                foreach ($summary_form_data as $row) {

                    $jdata = array();
                    if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?>
                                    </h2>
                                    <ul class="media-list media-xs">
                                        <li class="media">
                                            <div class="media-body">
                                                <p class ="small">
                                                    
                                                    <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 
                                                        if(!empty($dodata[0][$row['name']]) && is_json($dodata[0][$row['name']])){ 
                                                        $jdata = json_decode($dodata[0][$row['name']]);

                                                        $prevjdata = json_decode($do_prev_data[0][$row['name']]);
                                                       
                                                        if(!empty($jdata))
                                                        { 

                                                            for($i=0;$i<count($jdata);$i++)
                                                            {
                                                                $diff = new HtmlDiff(!empty($prevjdata[$i]->content)?$prevjdata[$i]->content:'', $jdata[$i]->content);
                                                                $diff->build();
                                                                echo $diff->getDifference();
                                                                

                                                                $match = array("login_id" => $jdata[$i]->user_id);
                                                                $fields = array("CONCAT(`firstname`,' ', `lastname`) as create_name");
                                                                $logindetail = $this->common_model->get_records(LOGIN, $fields, '', '', $match);
                                                                ?>
                                                                <p class="date"><small><?=!empty($logindetail[0]['create_name'])?$logindetail[0]['create_name'].' : ':''?> <?=!empty($jdata[$i]->date)?configDateTimeFormat($jdata[$i]->date):''?> </small></p>
                                                                <?php
                                                            }
                                                        }
                                                    } else { ?>

                                                        <?php if ($row['subtype'] == 'time'){ ?>
                                                            <?=!empty($dodata[0][$row['name']])?nl2br(timeformat($dodata[0][$row['name']])):(isset($row['value'])?timeformat($row['value']):'')?>
                                                        <?php }else if($row['type'] == 'date'){ ?>
                                                                <?=!empty($dodata[0][$row['name']])?nl2br(configDateTime($dodata[0][$row['name']])):(isset($row['value'])?configDateTime($row['value']):'')?>
                                                        <?php }else{ ?>
                                                            <?=!empty($dodata[0][$row['name']])?nl2br(htmlentities($dodata[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                            <?php } ?>
                                                        <?php } ?>
                                            
                                   
                                            <?php } else if ($row['type'] == 'checkbox-group') {
                                                    if (!empty($dodata[0][$row['name']])) {
                                                        $chk = explode(',', $dodata[0][$row['name']]);
                                                        foreach ($chk as $chk) {
                                                            echo $chk . "\n";
                                                        }
                                                    } else {

                                                        if (count($row['values']) > 0) {

                                                            foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected']) ? '<li>' . $chked['value'] . "\n" : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                <?php
                                                
                                                } else if ($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                    if (!empty($dodata[0][$row['name']])) {
                                                        echo!empty($dodata[0][$row['name']]) ? nl2br(htmlentities($dodata[0][$row['name']])) : '';
                                                    } else {
                                                        if (count($row['values']) > 0) {

                                                            foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected']) ? $chked['value'] : '';
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                                </p>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'header') {
                        ?>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <h1 class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></h1>
                            
                        </div>
                        <?php
                    } else if ($row['type'] == 'file') {
                        ?>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <ul class="media-list media-xs">
                                        <li class="media">
                                            <div class="media-body">
                                                <p class ="small">
                                                    <?php
                                                    /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                                    $fileViewArray = array(
                                                        'fileArray' => (isset($dodata[0][$row['name']]) && !empty($dodata[0][$row['name']]))? $dodata[0][$row['name']] : $row['value'],
                                                        'filePathMain' => $this->config->item('do_img_base_url') . $ypid,
                                                        'filePathThumb' => $this->config->item('do_img_base_url_small') . $ypid
                                                    );
                                                    echo getFileView($fileViewArray); 
                                                ?>  
                                                </p>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } //foreach
            }
            ?>
            <div class="clearfix"></div>
            <?php 
            if (!empty($dodata) || !empty($food_data)) { 
if (checkPermission('DailyObservation', 'signoff')) {
                ?>
            <div class="col-lg-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>sign off</h2>
                        <?php 
                        if($past_care_id == 0){
                        if(empty($check_do_signoff_data)){
                        
                         ?>
                            <input type="checkbox" name="do_signoff" onclick="manager_request(<?php echo $ypid; ?>,<?php echo $dodata[0]['do_id']; ?>);" class="" value="1" >
                        <?php 
                            $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
                            echo getUserName($login_user_id);
                        } }
                        ?> 


<?php
                       if (!empty($do_signoff_data)) {
                                foreach ($do_signoff_data as $sign_name) {
                                ?>

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
            <?php }} ?>
            <div class="col-lg-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>Comments Box</h2>
                        <?php
                        if (!empty($comments)) {
                            foreach ($comments as $comments_data) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
        <?php echo $comments_data['overview_comments'] ?>
                                            </p>
                                            <p class="date"><small><?php echo $comments_data['create_name'] ?>:   <?php echo configDateTime($comments_data['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php }
                        }
                        ?>   

<?php
 if($past_care_id == 0){ 
 if (checkPermission('DailyObservation', 'comment')) { ?>
                        <form data-parsley-validate="true" action="<?= base_url('DailyObservation/add_commnts') ?>" method="post">
                            <input type="hidden" name="doid" value="<?= $do_id ?>">
                            <input type="hidden" name="yp_id" value="<?= $ypid ?>">
                            <input type="hidden" name="care_home_id" id="care_home_id" value="<?=!empty($care_home_id)?$care_home_id:''?>">
                                <div class="col-sm-12">
                                    <div class="panel panel-default tile tile-profile">
                                        <div class="panel-body">
                                            <h2>Comments</h2>
                                            <textarea class="form-control" name="overview_comments" placeholder="add comments" id="ob_comments" required=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            <button type="submit" class="btn btn-default">
                                Submit
                            </button>
                        </form>
                        <?php }} ?>  
                    </div>
                </div>
            </div>

<?php 

if (!empty($dodata)) { 
if (checkPermission('DailyObservation', 'document_signoff')) { 
    ?>
            <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <?php if($past_care_id == 0){ ?>
                                     <a href="javascript:;" data-href="<?php echo base_url() . 'DailyObservation/signoff/'.$ypid.'/'.$do_id; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning"><?php echo lang('external_approval'); ?></span></a>

                                     <?php if(!empty($check_external_signoff_data)){ ?>
                                 <a href="<?php echo base_url() . 'DailyObservation/external_approval_list/'.$ypid.'/'.$do_id; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                    </a>
                                <?php } ?>
                                     <?php }else{ ?>

                                     <?php if(!empty($check_external_signoff_data)){ ?>
                                 <a href="<?php echo base_url() . 'DailyObservation/external_approval_list/'.$ypid.'/'.$do_id.'/'.$care_home_id.'/'.$past_care_id; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                    </a>
                                <?php }} ?>
                                </div>
                            </div>
                        </div> 
<?php } }  ?>
            
        </div>
        

<div class="row">
            <div class="col-sm-12">
				 <?php if(!empty($NextAndPreDodata['pre'])){ ?>
                      <a href="<?= base_url('DailyObservation/view/' . $NextAndPreDodata['pre'] . '/' . $ypid); ?>" class="btn btn-default updat_bn"> Previous </a>       
                      <?php } ?>
                      <?php if(!empty($NextAndPreDodata['next'])){ ?> 
                      <a href="<?= base_url('DailyObservation/view/' . $NextAndPreDodata['next'] . '/' . $ypid); ?>"  class="btn btn-default updat_bn"> Next </a>
                      <?php } ?>
                <div class="pull-right">
                    <div class="btn-group">
                    <?php
                    /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
                    ?>
                     <?php if($past_care_id == 0){ ?>   
                     <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                     <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>

                     <?php if (checkPermission('DailyObservation', 'view')) { ?>
                        <a href="<?= base_url('DailyObservation/index/' . $ypid); ?>" class="btn btn-default"><i class="fa fa-mail-reply"></i> DO
                        </a>
                    <?php } ?>

                    <?php if (checkPermission('DailyObservation', 'edit')) { ?>
                        <a href="<?= base_url('DailyObservation/edit_do/' . $do_id . '/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-edit" aria-hidden="true"></i> Edit DO
                        </a>
                    <?php } ?>

                    <?php if (checkPermission('ArchiveDailyObservation', 'view')) { ?>
                        <a href="<?= base_url('ArchiveDailyObservation/index/' . $do_id . '/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-search" aria-hidden="true"></i> VIEW ARCHIVE
                        </a>
                    <?php } ?>    

                     <?php }else{ ?>

                     <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                     <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?= base_url('DailyObservation/index/' . $ypid .'/'. $care_home_id .'/'.$past_care_id); ?>" class="btn btn-default"><i class="fa fa-mail-reply"></i> DO
                        </a>
                        <?php if (checkPermission('ArchiveDailyObservation', 'view')) { ?>
                    <a href="<?= base_url('ArchiveDailyObservation/index/' . $do_id . '/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                            <i class="fa fa-search" aria-hidden="true"></i> VIEW ARCHIVE
                        </a>
                    <?php } ?>

                     <?php } ?>
                     <a href="<?= base_url('DailyObservation/DownloadPrint/' . $dodata[0]['do_id'] . '/' . $ypid. '/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PRINT </a>
                    
                    <a href="<?=base_url('Mail/ComposeMailDO/'.$dodata[0]['do_id'].'/'.$ypid); ?>" class="btn btn-default">
                                 <i class="fa fa-edit"></i> EMAIL
                            </a>
                    
                </div>

                </div>
               
            </div>
        </div>
    </div>
</div>
