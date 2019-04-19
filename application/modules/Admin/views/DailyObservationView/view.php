<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Daily Observations <small>New Forest Care</small>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="<?=base_url('Admin/Reports/DOS'); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> DO Reports
                        </a>
                </div>
            </div>
        </h1>
        <div class="clearfix"></div>
        <div class="row m-t-10">
            <div class="col-md-6 col-sm-6">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-280">
                        <h2>Overview
                        </h2>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Young Person</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=!empty($dodata[0]['yp_name'])?$dodata[0]['yp_name']:''?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Staff</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=!empty($dodata[0]['create_name'])?$dodata[0]['create_name']:''?></p>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Awake</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=(!empty($dodata[0]['awake_time']) && $dodata[0]['awake_time'] != '00:00:00')? timeformat($dodata[0]['awake_time']):''?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Bedtime</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=(!empty($dodata[0]['bed_time']) && $dodata[0]['bed_time'] != '00:00:00')? timeformat($dodata[0]['bed_time']):''?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Contact</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <p><?=!empty($dodata[0]['contact'])?$dodata[0]['contact']:''?></p>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 padding-r-0">
                                <p class="text-right"><small>Staffing</small></p>
                            </div>
                            <div class="col-xs-8 col-sm-7">
                                <?php if(!empty($do_staff_data)) {
                                    foreach ($do_staff_data as $staff) {
                                        ?>
                                            <p><?=!empty($staff['staff_name'])?$staff['staff_name']:''?></p>
                                        <?php
                                    }
                                    } ?>
                                
                                <p>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body min-h-280">
                        <h2>Food Consumed
                        </h2>
                        <?php   if(!empty($food_form_data))
                                {
                                    foreach ($food_form_data as $row) {
                                    if($row['type'] == 'textarea' || $row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {?>
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-5 padding-r-0">
                                            <p class="text-right"><small><?=!empty($row['label'])?$row['label']:''?></small></p>
                                        </div>
                                        <div class="col-xs-8 col-sm-7">
                                            <p class="small">
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
                                                    } else {?>

                                                    <?=!empty($food_data[0][$row['name']])?nl2br(htmlentities($food_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                    <?php } ?>
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
                                    }
                                    else if ($row['type'] == 'header') {
                                       ?>
                                       <div class="row">
                                       <div class="col-xs-4 col-sm-5 padding-r-0">
                                                    <div class="text-right">
                                                        <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                                        <?php echo '<'.$head.' class="page-title">'; ?>
                                                        <?=!empty($row['label'])?$row['label']:''?>
                                                            
                                                        <?php echo '</'.$head.'>'; ?>
                                                    </div>
                                                </div>
                                        </div>
                                       <?php
                                    }else if ($row['type'] == 'file') {
                                       ?>
                                       <div class="row">
                                       <div class="col-xs-4 col-sm-5 padding-r-0">
                                                <p class="text-right"><?=!empty($row['label'])?$row['label']:''?></p>    
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
                                       <?php
                                    } ?>
                        <?php } }?>
                       
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
                                                <p class ="small">Professionals: <?= !empty($value['professionals']) ? $value['professionals'] : '' ?></p>
                                                <p class ="small">Date of Appointment: <?=(!empty($value['appointment_date']) && $value['appointment_date'] !='0000-00-00')?configDateTime($value['appointment_date']):''?> 
                                                </p>
                                                <p class ="small">Time: <?=(!empty($value['appointment_time']) && $value['appointment_time'] !='0000-00-00')?timeformat($value['appointment_time']):''?>
                                                 </p>
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

                                                        //$prevjdata = json_decode($do_prev_data[0][$row['name']]);
                                                       
                                                        if(!empty($jdata))
                                                        { 

                                                            for($i=0;$i<count($jdata);$i++)
                                                            {
                                                                /*$diff = new HtmlDiff(!empty($prevjdata[$i]->content)?$prevjdata[$i]->content:'', $jdata[$i]->content);
                                                                $diff->build();
                                                                echo $diff->getDifference();*/
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
                                                if(isset($data['stock'])){
                                                    echo $data['stock'];
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
            if(!empty($summary_form_data))
            {
            foreach ($summary_form_data as $row) {

             if($row['type'] == 'textarea' || $row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                ?>
          <div class="clearfix"></div>
                <div class="col-lg-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                        <h2><?=!empty($row['label'])?$row['label']:''?>
                        
                        </h2>
                        <ul class="media-list media-xs">
                            <li class="media">
                                <div class="media-body">
                                    <p class ="small">
                                        <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 
                                                        if(!empty($dodata[0][$row['name']]) && is_json($dodata[0][$row['name']])){ 
                                                        $jdata = json_decode($dodata[0][$row['name']]);
                                                        if(!empty($do_prev_data))
                                                        {
                                                            $prevjdata = json_decode($do_prev_data[0][$row['name']]);
                                                           
                                                        }
                                                        
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

                                                    <?=!empty($dodata[0][$row['name']])?nl2br(htmlentities($dodata[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
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
                }
                else if ($row['type'] == 'header') {
                   ?>
                    <div class="clearfix"></div>
                   <div class="col-lg-12">
                        <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                       
                    </div>
             <?php
                }
                else if ($row['type'] == 'file') { ?>
                    <div class="clearfix"></div>
                    <div class="col-lg-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                            <h2><?=!empty($row['label'])?$row['label']:''?>
<!--                                <form action="<?=base_url('DailyObservation/add_summary')?>" method="post">
                                <input type="hidden" name="doid" value="<?=$do_id?>">
                                <input type="hidden" name="summary_field" value='<?php echo json_encode($row)?>'>
                                <button type="submit" class="pull-right">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </form>-->
                            </h2>
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
        </div>
        
        </div>
    </div>
