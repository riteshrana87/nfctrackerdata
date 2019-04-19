	   <style>
                    td  *{
                      word-break: break-all;
                      word-wrap: break-word;
                      white-space: pre;           /* CSS 2.0 */
                      white-space: pre-wrap;      /* CSS 2.1 */
                      white-space: pre-line;      /* CSS 3.0 */
                      white-space: -pre-wrap;     /* Opera 4-6 */
                      white-space: -o-pre-wrap;   /* Opera 7 */
                      white-space: -moz-pre-wrap; /* Mozilla */
                      white-space: -hp-pre-wrap;  /* HP Printers */
                      word-wrap: break-word;
                    }
                    .diffmod
                    {
                      text-decoration: none !important;
                    }
                  </style>
				  <table width="100%" style="margin-bottom: 20px; border-collapse: collapse;">
				  <tr>
					<td align="center" style="border:1px solid;">
						<span style="margin-bottom: 10px;border-bottom: 1px solid;width:100%;float: left;">OVERVIEW</span>
						<table width="100%" style=" margin: 0; border-collapse: collapse;">
						  <tr>
							<td><b>YOUNG PERSON</b></td>
							<td><p><?= !empty($dodata[0]['yp_name']) ? $dodata[0]['yp_name'] : '' ?></p></td>
						  </tr>
						  <tr>
							<td><b>STAFF</b></td>
							<td> <p><?= !empty($dodata[0]['create_name']) ? $dodata[0]['create_name'] : '' ?></p></td>
						  </tr>
						  <tr>
							<td><b>AWAKE</b></td>
							<td><p>
                                    <?php
                                    if (!empty($oldformsdata[0]['awake_time'])) {
                                        $diff = new HtmlDiff(timeformat($oldformsdata[0]['awake_time']), !empty($dodata[0]['awake_time']) ? timeformat($dodata[0]['awake_time']) : '' );
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else {
                                        ?><?= (!empty($dodata[0]['awake_time']) && $dodata[0]['awake_time'] != '00:00:00') ? timeformat($dodata[0]['awake_time']) : '' ?>
                                    <?php } ?>
                                </p></td>
						  </tr>
						  <tr>
							<td><b>BEDTIME</b></td>
							<td><p>
                                    <?php
                                    if (!empty($oldformsdata[0]['bed_time'])) {
                                        $diff = new HtmlDiff(timeformat($oldformsdata[0]['bed_time']), !empty($dodata[0]['bed_time']) ? timeformat($dodata[0]['bed_time']) : '' );
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else {
                                        ?><?= (!empty($dodata[0]['bed_time']) && $dodata[0]['bed_time'] != '00:00:00') ? timeformat($dodata[0]['bed_time']) : '' ?>
                                    <?php } ?>
                                </p></td>
						  </tr>
						  <tr>
							<td><b>CONTACT</b></td>
							<td><p>
                                    <?php
                                    if (!empty($oldformsdata[0]['contact'])) {
                                        $diff = new HtmlDiff(timeformat($oldformsdata[0]['contact']), !empty($dodata[0]['contact']) ? timeformat($dodata[0]['contact']) : '' );
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else {
                                        ?><?= (!empty($dodata[0]['contact']) && $dodata[0]['contact'] != '00:00:00') ? timeformat($dodata[0]['contact']) : '' ?>
                                    <?php } ?>

                                </p></td>
						  </tr>
						  <tr>
							<td><b>STAFFING</b></td>
							<td><?php
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
                                </p></td>
						  </tr>
				  
				  </table>
					</td>
					
				  </tr>
				  </table>
				  
				  <table width="100%" style="margin-bottom: 20px; border-collapse: collapse;">
				  <tr>
					<td align="center" style="text-transform: uppercase;border:1px solid;">
						<span style="margin-bottom: 10px;border-bottom: 1px solid;width:100%;float: left;">FOOD CONSUMED</span>
						<table width="100%" style=" margin: 0; border-collapse: collapse;">
						  
						  <?php

            if (!empty($food_form_data)) {
                foreach ($food_form_data as $row) { 
				
				

                  if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {

                                      ?>
									  <tr >
							<th style="width:  16%;text-align: left;"><b><?= !empty($row['label']) ? $row['label'] : '' ?></b></th>
							<td>
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

                                                    <?=isset($food_data[0][$row['name']])?nl2br(htmlentities($food_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
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
                                                    if (isset($food_data[0][$row['name']])) { 
                                                        echo isset($food_data[0][$row['name']]) ? nl2br(htmlentities($food_data[0][$row['name']])) : '';
                                                    } else {
                                                        if (count($row['values']) > 0) {

                                                            foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected']) ? $chked['value'] : '';
                                                            }
                                                        }
                                                    }
                                                } else if ($row['type'] == 'header') { ?>   
							<td>
                 <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                                <?php echo '<' . $head . ' class="page-title">'; ?>
                                                <?= !empty($row['label']) ? $row['label'] : '' ?>

                                                <?php echo '</' . $head . '>'; ?>
                  
              </td>
							<?php } else if ($row['type'] == 'file') {?> 
              <td>
                                            <?php
                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                            $fileViewArray = array(
                                                'fileArray' => (isset($food_data[0][$row['name']]) && !empty($food_data[0][$row['name']]))? $food_data[0][$row['name']] : $row['value'],
                                                'filePathMain' => $this->config->item('do_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('do_img_base_url_small') . $ypid
                                            );
                                            echo getFileView($fileViewArray);
                                            ?>
                  
                </td>
                <?php } ?>
							</td>
							</tr>
					<?php }}} ?>
						  
						 
				  </table>
					</td>
					
				  </tr>
				  </table>
				 
<?php if(!empty($do_professionals_data)){?>
          <table width="100%" style=" margin: 0; border-collapse: collapse;">
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>MEDICAL APPOINTMENTS</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
                              <?php foreach ($do_professionals_data as $value) { ?>
                                                <p class ="small">Professionals: <?= !empty($value['professionals']) ? $value['professionals'] : '' ?></p>
                                                <p class ="small">Date of Appointment: <?=(!empty($value['appointment_date']) && $value['appointment_date'] !='0000-00-00')?configDateTime($value['appointment_date']):''?> 
                                                </p>
                                                <p class ="small">Time: <?=(!empty($value['appointment_time']) && $value['appointment_time'] !='0000-00-00')?timeformat($value['appointment_time']):''?>
                                                 </p>
                                                <p class ="small">Comments: <?= !empty($value['comments']) ? $value['comments'] : '' ?>  </p>
                                          <?php } ?> 
                                    </td>
                  
                                  </tr>
                  
                                </table>
                              </td>
                             
                           </tr>
               <tr><td style="padding-bottom:10px;"></td></tr>
                   
                   
</table>
 <?php } ?>

 <?php if(!empty($do_planner_data)){?>
          <table width="100%" style=" margin: 0; border-collapse: collapse;">
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>APPOINTMENTS /EVENTS</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
                              <?php foreach ($do_planner_data as $value) { ?>
                                                
                                                <p class ="small">Date of Appointment /Event: <?=(!empty($value['appointment_date']) && $value['appointment_date'] !='0000-00-00')?configDateTime($value['appointment_date']):''?> 
                                                </p>
                                                <p class ="small">Time: <?=(!empty($value['appointment_time']) && $value['appointment_time'] !='0000-00-00')?timeformat($value['appointment_time']):''?>
                                                 </p>
                                                 <p class ="small">Type Of Appointment / Event: <?= !empty($value['appointment_type']) ? $value['appointment_type'] : '' ?>  </p>
                                                <p class ="small">Comments: <?= !empty($value['comments']) ? $value['comments'] : '' ?>  </p>
                                              <?php } ?>   
                                    </td>
                  
                                  </tr>
                  
                                </table>
                              </td>
                             
                           </tr>
               <tr><td style="padding-bottom:10px;"></td></tr>
                   
                   
</table>
 <?php } ?>



          <table width="100%" style=" margin: 0; border-collapse: collapse;">
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Morning Handover from previous day</b>
                                    </td>
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
                              <?php
                                    if(!empty($lastDayData[0]['handover_next_day']) && is_json($lastDayData[0]['handover_next_day'])){ 
                                                        $jdata = json_decode($lastDayData[0]['handover_next_day']);

                                                        //$prevjdata = json_decode($do_prev_data[0][$row['name']]);
                                                       
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
                                    </td>
                  
                                  </tr>
                  
                                </table>
                              </td>
                             
                           </tr>
               <tr><td style="padding-bottom:10px;"></td></tr>
                   
                   
</table>
 

                    

<table width="100%" style=" margin: 0; border-collapse: collapse;">
          <?php
            if (!empty($summary_form_data)) {
                foreach ($summary_form_data as $row) { 
				
                  if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {

                                      ?>
						
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                      <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { ?>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; text-decoration: none !important; border: 0px !important;">
									                 <?php if(!empty($dodata[0][$row['name']]) && is_json($dodata[0][$row['name']])){ 
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
                                                ?>
                                    </td>
                                    <?php } else if ($row['type'] == 'header') {?>
                                    <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
                                    </td>
									               <?php } else if ($row['type'] == 'file') { ?>
                                   <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
                                      <p>
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


                                    </td>
                                 <?php }?>
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
                 
                     
                     <?php
                } //foreach
            }
          }
            ?>
</table>

<table width="100%">
  <tr>
    <td>
      <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>COMMENTS</b>
                                    </td>
                             
                           </tr>
						   <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  <?php foreach($comments as $cm){?>
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php echo $cm['overview_comments'];?>
   </p>                                
          <p class="date">
            <small>
              <?php echo $cm['create_name'] ?>:   <?php echo configDateTime($cm['created_date']); ?>
              </small>
            </p>   
                                    </td>
									
                                  </tr>
								  <?php }?>
								  
                                </table>
                              </td>
                             
                           </tr>
    </td>
  </tr>
</table>
    
<table width="100%">
  <tr>
    <td>
      <p style="text-align: center;"><em>&nbsp;</em></p>
    </td>
  </tr>
</table>


<table width="100%">
  <tr>
    <td>
      <p style="text-align: center;"><em>&nbsp;</em></p>
    </td>
  </tr>
</table>


<table width="100%">
<tr>
<td>
<p style="text-align: center;"><em>&nbsp;</em></p>
</td>
</tr>
</table>                                              

<table width="100%">
    <tr>
      <td>
        <p style="text-align: center;"><em>&nbsp;</em></p>
      </td>
    </tr>
</table> 

<table width="100%" style="font-size: 16px; margin: 0; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #4e6031;">
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">NAME</th>
            
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 0px 10px;">DATE</th>
        </tr>
    </thead>
    <tbody>
      <?php if (!empty($do_signoff_data)) {
      foreach ($do_signoff_data as $sign_name) { ?>
      <tr>
            <td width="50%" style="font-size: 14px; margin:0;color:black;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">
                <p>
                  <?php
					$strname=$sign_name['name'];
				  echo $strname;
						?>
                </p>
            </td>
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:center;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                <p><?= (!empty($sign_name['created_date']) && $sign_name['created_date'] != '0000-00-00') ? configDateTime($sign_name['created_date']) : '' ?></p>
            </td>
        </tr>
        <?php }
        }
      ?>
    </tbody>
</table>