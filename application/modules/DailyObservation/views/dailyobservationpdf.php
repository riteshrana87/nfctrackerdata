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
                                        $diff = new HtmlDiff($oldformsdata[0]['contact'], !empty($dodata[0]['contact']) ? $dodata[0]['contact'] : '' );
                                        $diff->build();
                                        echo $diff->getDifference();
                                    } else {
                                        ?><?= (!empty($dodata[0]['contact']) && $dodata[0]['contact'] != '00:00:00') ? $dodata[0]['contact'] : '' ?>
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

                     if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text'|| $row['type'] == 'select') { 

                                      $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                      ?>
									  <tr >
							<th style="width:  16%;text-align: left;"><b><?= !empty($row['label']) ? $row['label'] : '' ?></b></th>
							<td>
							<?php if(is_json($row['value'])){
								$jdata = json_decode($row['value']);
								foreach($jdata as $food_data){ ?>
									<td style="text-align: left;"><?php echo $food_data->content; ?></td>
									
									<?php $match = array("login_id" => $food_data->user_id);
                                                                $fields = array("CONCAT(`firstname`,' ', `lastname`) as create_name");
                                                                $logindetail = $this->common_model->get_records(LOGIN, $fields, '', '', $match);
                                                                ?>
                                                               <td><small><?=!empty($logindetail[0]['create_name'])?$logindetail[0]['create_name'].' : ':''?> <?=!empty($food_data->date)?configDateTimeFormat($food_data->date):''?> </small></td>
								
							
							
								<?php  } } else {?>
							<td>

          
        <?php if ($row['subtype'] == 'time'){ ?>
          <?= !empty($data_textarea) ? nl2br(timeformat($data_textarea)) : (isset($row['value']) ? timeformat($row['value']) : '') ?>                                                            
        <?php }else if($row['type'] == 'date'){ ?>
          <?= !empty($data_textarea) ? nl2br(configDateTime($data_textarea)) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>                                
        <?php }else{ ?>
          <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>
          <?php } ?>
                  
                </td>
							<?php }?>
							</td>
							</tr>
					<?php }}}}?>
						  
						 
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

                     if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text'|| $row['type'] == 'select') { 

                                      $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
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
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
									<?php if(is_json($row['value'])){
										$summary_form_data_json_formate = json_decode($row['value']);
										foreach($summary_form_data_json_formate as $summary_form_data_json_formate_decode){
											 $match = array("login_id" => $summary_form_data_json_formate_decode->user_id);
                                                                $fields = array("CONCAT(`firstname`,' ', `lastname`) as create_name");
                                                                $logindetail = $this->common_model->get_records(LOGIN, $fields, '', '', $match);
                                                               echo $summary_form_data_json_formate_decode->content;
										?>
											<p class="date"><small><?=!empty($logindetail[0]['create_name'])?$logindetail[0]['create_name'].' : ':''?> <?=!empty($summary_form_data_json_formate_decode->date)?configDateTimeFormat($summary_form_data_json_formate_decode->date):''?> </small></p>
								
									<?php  } } else {?>
									<p style="word-wrap: break-word; word-break: break-all;">

                          <?php if ($row['subtype'] == 'time'){ ?>
          <?= !empty($data_textarea) ? nl2br(timeformat($data_textarea)) : (isset($row['value']) ? timeformat($row['value']) : '') ?>                                                            
        <?php }else if($row['type'] == 'date'){ ?>
          <?= !empty($data_textarea) ? nl2br(configDateTime($data_textarea)) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>                                
        <?php }else{ ?>
          <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>

          <?php } ?>

									</p> 
									<?php }?>
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
                 
                     
                     <?php
                    }  
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