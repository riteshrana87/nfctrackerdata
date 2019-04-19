<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Daily Observations <small>New Forest Care</small>
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
                        <a href="<?= base_url('DailyObservation/view/' . $do_id . '/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> Return To Current DO
                        </a>
                    <?php } ?>
                    <a href="<?= base_url('ArchiveDailyObservation/index/' . $do_id . '/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> Return To Archive
                    </a>
                    <?php }else{ ?>
                    <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i>  ARCHIVE CAREHOME YP LIST</a>

                     <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                    <?php if (checkPermission('DailyObservation', 'view')) { ?>
                        <a href="<?=base_url('DailyObservation/view/'.$do_id.'/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> Return To Current DO
                    </a>
                    <?php } ?>
                    <a href="<?= base_url('ArchiveDailyObservation/index/' . $do_id . '/' . $ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> Return To Archive
                    </a>
                    <?php } ?>
                </div>
            </div>
        </h1>
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
                        <?php
                        /* $diff = new Diffe;
                          $difference = new stdClass;
                          $difference->mode = 'w';
                          $difference->patch = true;
                          $after_patch = new stdClass; */
                        ?>
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
                                    $staffolddata = array();
                                    if (!empty($do_staff_old_data)) {
                                        foreach ($do_staff_old_data as $staffold) {
                                            $staffolddata[] = $staffold['staff_name'];
                                        }
                                    }
                                    
                                    foreach ($do_staff_data as $staff) {
                                        ?>
                                        <p>
                                            <?php
                                            if (!empty($staff)) {
                                                if(!in_array($staff['staff_name'], $staffolddata))
                                                {
                                                    $diff = new HtmlDiff('', $staff['staff_name']);
                                                    $diff->build();
                                                    echo $diff->getDifference();
                                                }
                                                else
                                                {
                                                    echo $staff['staff_name'];
                                                }
                                                
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
                            /* $diff = new Diffe;
                              $difference = new stdClass;
                              $difference->mode = 'c';
                              $difference->patch = true;
                              $after_patch = new stdClass; */
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

                                                    <?=isset($row['value'])?nl2br(htmlentities($row['value'])):(isset($row['value'])?$row['value']:'')?>
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
                                                            <?=isset($row['value'])?nl2br(timeformat($row['value'])):(isset($row['value'])?timeformat($row['value']):'')?>
                                                        <?php }else if($row['type'] == 'date'){ ?>
                                                                <?=isset($row['value'])?nl2br(configDateTime($row['value'])):(isset($row['value'])?configDateTime($row['value']):'')?>
                                                        <?php }else{ ?>
                                                            <?=isset($row['value'])?nl2br(htmlentities($row['value'])):(isset($row['value'])?$row['value']:'')?>
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
                                                    if (isset($food_data[0][$row['name']])) {
                                                        echo isset($food_data[0][$row['name']]) ? nl2br(htmlentities($food_data[0][$row['name']])) : '';
                                                    } else {
                                                        if(isset($row['value']))
                                                        {
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

                                                <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {
                                                 ?>

                                                    <?php
                                                    if (!empty($row['value']) && is_json($row['value'])) {
                                                        if (!empty($summary_form_old_data[$n]['name']) && ($row['name'] == $summary_form_old_data[$n]['name'])) {
                                                            $jolddata = json_decode($summary_form_old_data[$n]['value']);
                                                        }
                                                        $jdata = json_decode($row['value']);

                                                        if (!empty($jdata)) {
                                                            $p = 0;
                                                            foreach ($jdata as $row) {
                                                              
                                                                
                                                                 $diff = new HtmlDiff(!empty($jolddata[$p]->content) ? $jolddata[$p]->content : '', $row->content);
                                                                $diff->build();
                                                                echo $diff->getDifference();

                                                                
                                                                ?>
                                                                <p class="date"><small><?= !empty($dodata[0]['create_name']) ? $dodata[0]['create_name'] . ' : ' : '' ?> <?= !empty($row->date) ? configDateTimeFormat($row->date) : '' ?> </small></p>
                                                                <?php
                                                                $p++;
                                                            }
                                                        }
                                                    } else {
                                                        ?>

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
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?>
<!--                                        <form action="<?= base_url('DailyObservation/add_summary') ?>" method="post">
                                            <input type="hidden" name="doid" value="<?= $do_id ?>">
                                            <input type="hidden" name="summary_field" value='<?php echo json_encode($row) ?>'>
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
                    $n++;
                } //foreach
            }
            ?>
            <?php if (checkPermission('ArchiveDailyObservation', 'signoff')) { ?>
                <div class="col-sm-12">
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
                <?php } ?>

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
                    </div>
                </div>
            </div>

            
        </div>

    </div>
</div>
