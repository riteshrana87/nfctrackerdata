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
			<div class="pull-right">
                <div class="btn-group">
                   <a href="<?= base_url('DailyObservation/DownloadPdf/' . $ypid . '/' . $signoff_id); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Generate PDF </a>
                </div>
            </div>
        </h1>
        <div class="clearfix"></div>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="clearfix"></div>
        <div class="row m-t-10">
            <div class="col-md-6 col-sm-6">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-360">
                        <h2>Overview
                        </h2>
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
                                    if (!empty($oldformsdata[0]['awake_time'])) {
                                        $diff = new HtmlDiff(timeformat($oldformsdata[0]['awake_time']), !empty($dodata[0]['awake_time']) ? timeformat($dodata[0]['awake_time']) : '' );
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
                                    if (!empty($oldformsdata[0]['bed_time'])) {
                                        $diff = new HtmlDiff(timeformat($oldformsdata[0]['bed_time']), !empty($dodata[0]['bed_time']) ? timeformat($dodata[0]['bed_time']) : '' );
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
                                    if (!empty($oldformsdata[0]['contact'])) {
                                        $diff = new HtmlDiff($oldformsdata[0]['contact'], !empty($dodata[0]['contact']) ? $dodata[0]['contact'] : '' );
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else {
                                        ?><?= (!empty($dodata[0]['contact']) && $dodata[0]['contact'] != '00:00:00') ? $dodata[0]['contact'] : '' ?>
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
                                    $s = 0;
                                    foreach ($do_staff_data as $staff) {
                                        ?>
                                        <p>
                                            <?php
                                            if (!empty($staff)) {
                                                $diff = new HtmlDiff(!empty($do_staff_old_data[$s]['staff_name']) ? $do_staff_old_data[$s]['staff_name'] : '', $staff['staff_name']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                            } else {
                                                ?>
                                                <?= !empty($staff['staff_name']) ? $staff['staff_name'] : '' ?>
                                            <?php } ?>
                                        </p>
                                        <?php
                                        $s++;
                                    }
                                }
                                ?>

                                <p>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-360">
                        <h2>Food Consumed

                        </h2>
                        <?php
                        if (!empty($food_form_data)) {
                            $n = 0;
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
                                                if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {
                                                    if (!empty($food_form_old_data[$n]['name']) && ($row['name'] == $food_form_old_data[$n]['name'])) {
                                                        
                                                        if(!empty($row['value']) && is_json($row['value'])){ 
                                                        $jdata = json_decode($row['value']);
                                                        $prevjdata = json_decode($food_form_old_data[$n]['value']);

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
                                                    } else {?>

                                                    <?=!empty($row['value'])?nl2br(htmlentities($row['value'])):(isset($row['value'])?$row['value']:'')?>
                                                    <?php } ?>
                                                    
                                                    <?php } else { 
                                                        if(!empty($row['value']) && is_json($row['value'])){ 
                                                        $jdata = json_decode($row['value']);
                                                        
                                                        if(!empty($jdata))
                                                        {

                                                            for($i=0;$i<count($jdata);$i++)
                                                            {
                                                                echo htmlentities($jdata[$i]->content);
                                                                

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
                                                            <?=!empty($row['value'])?nl2br(timeformat($row['value'])):(isset($row['value'])?timeformat($row['value']):'')?>
                                                        <?php }else if($row['type'] == 'date'){ ?>
                                                                <?=!empty($row['value'])?nl2br(configDateTime($row['value'])):(isset($row['value'])?configDateTime($row['value']):'')?>
                                                                
                                                        <?php }else{ ?>
                                                            <?=!empty($row['value'])?nl2br(htmlentities($row['value'])):(isset($row['value'])?$row['value']:'')?>
                                                        <?php } ?>


                                                    <?php } } ?>
                                                    <?php
                                                } else if ($row['type'] == 'checkbox-group') {
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
                                                    if (!empty($food_data[0][$row['name']])) {
                                                        echo!empty($food_data[0][$row['name']]) ? nl2br(htmlentities($food_data[0][$row['name']])) : '';
                                                    } else {
                                                         if (count($row['value']) > 0) {

                                                                echo $row['value'];
                                                            
                                                        }
                                                        else
                                                        {
                                                        if (count($row['values']) > 0) {

                                                            foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected']) ? $chked['value'] : '';
                                                            }
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
                                <?php
                                $n++;
                            }//end foreach
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
                                                    } else { ?>

                                                    <?=!empty($lastDayData[0]['handover_next_day'])?nl2br(htmlentities($lastDayData[0]['handover_next_day'])):(lang('NA'))?>
                                                    <?php } ?>
                                </div>
                            </div>
                        </div>

            <?php
            if (!empty($summary_form_data)) {
                $n = 0;
                $diff = new Diffe;
                $difference = new stdClass;
                $difference->mode = 'c';
                $difference->patch = true;
                $after_patch = new stdClass;
                foreach ($summary_form_data as $row) {

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

                                                <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { ?>

                                                    <?php
                                                    if (!empty($row['value']) && is_json($row['value'])) {
                                                        if (!empty($summary_form_old_data[$n]['name']) && ($row['name'] == $summary_form_old_data[$n]['name'])) {
                                                            $jolddata = json_decode($summary_form_old_data[$n]['value']);
                                                        }
                                                        $jdata = json_decode($row['value']);

                                                        if (!empty($jdata)) {
                                                            $p = 0;
                                                            foreach ($jdata as $row) {
                                                               echo $row->content;

                                                                
                                                                ?>
                                                                <p class="date"><small><?= !empty($dodata[0]['create_name']) ? $dodata[0]['create_name'] . ' : ' : '' ?> <?= !empty($row->date) ? configDateTimeFormat($row->date) : '' ?> </small></p>
                                                                <?php
                                                                $p++;
                                                            }
                                                        }
                                                    } else { ?>

                                                        <?php if ($row['subtype'] == 'time'){ ?>
                                                            <?= !empty($dodata[0][$row['name']]) ? timeformat($dodata[0][$row['name']]) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                                        <?php }else if($row['type'] == 'date'){ ?>
                                                                <?= !empty($dodata[0][$row['name']]) ? configDateTime($dodata[0][$row['name']]) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                                        <?php }else{ ?>
                                                            <?= !empty($dodata[0][$row['name']]) ? htmlentities($dodata[0][$row['name']]) : (isset($row['value']) ? $row['value'] : '') ?>
                                                        <?php } ?>
                                                        <?php
                                                    }
                                                } else if ($row['type'] == 'checkbox-group') {
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
                    $n++;
                } //foreach
            }
            ?>
            <div class="clearfix"></div>
            
            <div class="col-lg-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>sign off</h2>
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
                                            <p class="date"><small><?php echo $comments_data['create_name'] ?>:  
                                                <?php echo configDateTime($comments_data['created_date']); ?>    
                                                </small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php }
                        }
                        ?>   

                    </div>
                </div>
            </div>
 <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">

                        <div class="panel-body">
                            <h2>User sign off</h2>
                                    <input type="hidden" id="email_data" value="<?php echo $key_data; ?>" name="email_data">
                                    <input type="checkbox" onclick="signoff_request(<?php echo $ypid . ',' . $signoff_id; ?>);" name="signoff_data" class="" value="active">
                            
                        </div>

                    </div>
                </div>
        </div>
    </div>


</div>
