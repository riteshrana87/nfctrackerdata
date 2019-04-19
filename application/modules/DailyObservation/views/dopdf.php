<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                NFC Tracker
                <small class="pull-right">Last Modified Date: <?= (!empty($dodata[0]['modified_date'])) ? configDateTime($dodata[0]['modified_date']) : '' ?></small>
            </h2>
        </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-xs-12">
            <p class="lead" style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">Daily Observation</p>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-3 invoice-col">
            <strong>YP Name</strong>
            <address>
                <?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?>
                <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?>
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            <strong>Care Home</strong>
            <address>
                <?= !empty($YP_details[0]['care_home_name']) ? $YP_details[0]['care_home_name'] : '' ?>
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            <strong>Email</strong>
            <address>
                <?= !empty($YP_details[0]['email']) ? $YP_details[0]['email'] : '' ?>
            </address>
        </div>
    </div><!-- /.row -->
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-6">
            <p class="lead">Overview</p>
            <div class="table-responsive">
                <table class="table border-0">
                    <tr>
                        <th class="text-right" width="30%">Young Person:</th>
                        <td class="text-left"><?= !empty($dodata[0]['yp_name']) ? $dodata[0]['yp_name'] : '' ?></td>
                    </tr>
                    <tr>
                        <th class="text-right" width="30%">Staff:</th>
                        <td class="text-left"><?= !empty($dodata[0]['create_name']) ? $dodata[0]['create_name'] : '' ?></td>
                    </tr>
                    <tr>
                        <th class="text-right" width="30%">Awake:</th>
                        <td class="text-left"><?= (!empty($dodata[0]['awake_time']) && $dodata[0]['awake_time'] != '00:00:00') ? timeformat($dodata[0]['awake_time']) : '' ?></td>
                    </tr>
                    <tr>
                        <th class="text-right" width="30%">Bedtime:</th>
                        <td class="text-left"><?= (!empty($dodata[0]['bed_time']) && $dodata[0]['bed_time'] != '00:00:00') ? timeformat($dodata[0]['bed_time']) : '' ?></td>
                    </tr>
                    <tr>
                        <th class="text-right" width="30%">Contact:</th>
                        <td class="text-left"><?= !empty($dodata[0]['contact']) ? strip_tags($dodata[0]['contact']) : '' ?></td>
                    </tr>
                    <tr>
                        <th class="text-right" width="30%">Staffing:</th>
                        <td class="text-left">
                            <?php
                            if (!empty($do_staff_data)) {
                                foreach ($do_staff_data as $staff) {
                                    ?>
                                    <p><?= !empty($staff['staff_name']) ? $staff['staff_name'] : '' ?></p>
                                    <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>

                </table>
            </div>
        </div>

        <div class="col-xs-6">
            <p class="lead">Food Consumed</p>
            <?php
            if (!empty($food_form_data)) {
                foreach ($food_form_data as $row) {
                    ?>
                        <table class="table border-0" style="margin-bottom: 0;">
                            <?php if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') { ?>

                                <tr>
                                    <th class="text-right" width="30%"><?= !empty($row['label']) ? $row['label'] : '' ?></th>
                                    <td class="text-left">
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
                                                            <?=!empty($food_data[0][$row['name']])?nl2br(timeformat($food_data[0][$row['name']])):(isset($row['value'])?v($row['value']):'')?>
                                                        <?php }else if($row['type'] == 'date'){ ?>
                                                                <?=!empty($food_data[0][$row['name']])?nl2br(configDateTime($food_data[0][$row['name']])):(isset($row['value'])?configDateTime($row['value']):'')?>
                                                                
                                                        <?php }else{ ?>
                                                           <?=!empty($food_data[0][$row['name']])?nl2br(htmlentities($food_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                        <?php } ?>

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
                                    </td>
                                </tr>
                            </table>
                        


                        <?php
                    } else if ($row['type'] == 'header') {
                        ?>
                        <div class="col-xs-12">
                            <p class="lead">
                                <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                <?php echo '<' . $head . ' class="page-title">'; ?>
                                <?= !empty($row['label']) ? $row['label'] : '' ?>

                                <?php echo '</' . $head . '>'; ?>
                            </p>
                        </div>
                        <?php
                    } else if ($row['type'] == 'file') {
                        ?>
                        <div class="col-xs-12">
                            <div class="panel-body">
                                <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                <div class="">   
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
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
        <?php if(!empty($do_professionals_data)){?>
             <div class="clearfix"></div>
                        <div class="col-lg-12">
                            
                                    
                                     <p class="lead"><b>MEDICAL APPOINTMENTS</b></p> 
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
                        <?php } ?>
            <?php if(!empty($do_planner_data)){?>
             <div class="clearfix"></div>
                        <div class="col-lg-12">
                            
                                     <p class="lead"><b>APPOINTMENTS /EVENTS</b></p> 
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
                        <?php } ?>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">

                                    <p class="lead"><b>Morning Handover from previous day</b></p> 
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
                        <!-- Start  administer medication-->
                        <?php if(!empty($administer_medication)){?>
                         <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>ADMINISTRATION HISTORY</h2>
                                    <div class="">
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
        if (!empty($do_form_data)) {
            foreach ($do_form_data as $row) {

                if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                    ?>
                    <div class="clearfix"></div>
                    <div class="col-xs-12 dont-break paddi-z">
                        <p class="lead"><b><?= !empty($row['label']) ? $row['label'] : '' ?></b></p>   
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {
                                ?>

                                <?php
                                if (!empty($dodata[0][$row['name']]) && is_json($dodata[0][$row['name']])) {
                                    $jdata = json_decode($dodata[0][$row['name']]);
                                    if (!empty($jdata)) {

                                        foreach ($jdata as $row) {
                                            $data_content = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $row->content);
                                            echo nl2br(html_entity_decode($data_content));
                                            echo '<p></p>';
                                            ?>
                                            <?= !empty($dodata[0]['create_name']) ? $dodata[0]['create_name'] . ' : ' : '' ?> <?= !empty($row->date) ? configDateTimeFormat($row->date) : '' ?>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>


                                    <?php
										/* Start - Mantis issue # 7298 note 0010712 :Ritesh Rana , Dt : 6th Dec 2017 */
										$data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $dodata[0][$row['name']]);
										/* End - Mantis issue # 7298 note 0010712 : Ritesh Rana , Dt : 6th Dec 2017 */
                                    ?>
                                    <?php if ($row['subtype'] == 'time'){ ?>
                                                            <?= !empty($data_textarea) ? nl2br(timeformat($data_textarea)) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                                        <?php }else if($row['type'] == 'date'){ ?>
                                                            <?= !empty($data_textarea) ? nl2br(configDateTime($data_textarea)) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                                                
                                                        <?php }else{ ?>
                                                            <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>

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
                        </p>


                    </div>
                     <?php
            } else if ($row['type'] == 'header') {
                ?>
                <div class="col-xs-12">
                    <p class="lead">
                        <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                        <?php echo '<' . $head . ' class="page-title">'; ?>
                        <?= !empty($row['label']) ? $row['label'] : '' ?>

                        <?php echo '</' . $head . '>'; ?>
                    </p>
                </div>
                <?php
            } else if ($row['type'] == 'file') {
                ?>
                <div class="col-xs-12">
                    <div class="panel-body">
                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                        <div class="">   
                            <?php
                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                            $fileViewArray = array(
                                'fileArray' => (isset($dodata[0][$row['name']]) && !empty($dodata[0][$row['name']]))? $dodata[0][$row['name']] : $row['value'],
                                'filePathMain' => $this->config->item('do_img_base_url') . $ypid,
                                'filePathThumb' => $this->config->item('do_img_base_url_small') . $ypid
                            );
                            echo getFileView($fileViewArray); 
                            ?>                               
                        </div>
                    </div>
                </div>
                <?php
            }
            } //foreach
        }
        ?>
    </div>
</section><!-- /.content -->