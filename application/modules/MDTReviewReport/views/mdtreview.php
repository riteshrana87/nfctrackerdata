
                    

<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" margin: 0; border-collapse: collapse;font-family:arial;">
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>REPORT START DATE:</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff(configDateTime($prev_edit_data[0]['report_start_date']), configDateTime($edit_data[0]['report_start_date']));
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?=(!empty($edit_data[0]['report_start_date']) && $edit_data[0]['report_start_date'] !='0000-00-00')?configDateTime($edit_data[0]['report_start_date']):''?>
                             <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>REPORT END DATE:</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                            $diff = new HtmlDiff(configDateTime($prev_edit_data[0]['report_end_date']), configDateTime($edit_data[0]['report_end_date']));
                            $diff->build();
                            echo $diff->getDifference();
                            ?>
                         <?php } else { ?>
                                <?=(!empty($edit_data[0]['report_end_date']) && $edit_data[0]['report_end_date'] !='0000-00-00')?configDateTime($edit_data[0]['report_end_date']):''?>
                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>SOCIAL WORKER NAME:</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                            $diff = new HtmlDiff($prev_edit_data[0]['social_worker'], $edit_data[0]['social_worker']);
                            $diff->build();
                            echo $diff->getDifference();
                            ?>
                         <?php } else { ?>
                                <?= !empty($edit_data[0]['social_worker']) ? $edit_data[0]['social_worker'] : $YP_details[0]['social_worker_firstname'].' '. $YP_details[0]['social_worker_surname'] ?>
                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>PLACING AUTHORITY:</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                    $diff = new HtmlDiff($prev_edit_data[0]['placing_authority'], $edit_data[0]['placing_authority']);
                                    $diff->build();
                                    echo $diff->getDifference();
                                    ?>
                                 <?php } else { ?>
                                        <?= !empty($edit_data[0]['placing_authority']) ? $edit_data[0]['placing_authority'] : $YP_details[0]['authority'] ?>
                                 <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>CASE MANAGER:</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['case_manager_name'], $edit_data[0]['case_manager_name']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['case_manager_name']) ? $edit_data[0]['case_manager_name'] : '' ?>
                             <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						 
                 
                     
                    
</table>

						<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="font-size: 14px;color:white;text-align:center;padding:10px;">
                                      <b>CARE PLAN TARGETS IDENITIFIED FROM LAC/CLA REVIEW</b>
                                    </td>
                             
                           </tr>
						   <tr>
                               <td align="left" valign="top">
									<table width="100%" style="overflow: wrap;border-collapse:collapse;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="word-wrap: break-word; word-break: break-all;">
												CARE PLAN TARGET
											</td>
											
											<td style="word-wrap: break-word; word-break: break-all;">
                                       ACHIEVED/ONGOING/OUTSTANDING</td>
											
											<td style="word-wrap: break-word; word-break: break-all;">
                                        EVIDENCE OF HOW THIS HAS BEEN ACHIEVED / REASONS WHY IT HAS NOT BEEN ACHIEVED
											</td>											
										</tr>
										</thead>
										<tbody>
										 <?php if (!empty($care_plan_target)) {$n= 0;
                                                    foreach ($care_plan_target as $row) {
                                                ?>
										<tr style="color:#000000;">
											<td>
											<?php if (!empty($cpt_item_archive[$n]['care_plan_target_title']) && $cpt_item_archive[$n]['cpt_id'] == $row['cpt_id']) {

                                        $diff = new HtmlDiff($cpt_item_archive[$n]['care_plan_target_title'], $row['care_plan_target_title']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_title']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_edit_data) && empty($cpt_item_archive))
                                                        {
                                                            $diff = new HtmlDiff('', $row['care_plan_target_title']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($row['care_plan_target_title']) ? nl2br($row['care_plan_target_title']) : '' ?>
                                                 <?php
                                                    }
                                        }
									} ?>
											</td>
											<td>
											<?php if (!empty($cpt_item_archive[$n]['care_plan_target_select']) && $cpt_item_archive[$n]['cpt_id'] == $row['cpt_id']) {

                                        $diff = new HtmlDiff($cpt_item_archive[$n]['care_plan_target_select'], $row['care_plan_target_select']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_select']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                        }else{
                                             if(!empty($prev_edit_data) && empty($cpt_item_archive))
                                                        {
                                                            $diff = new HtmlDiff('', $row['care_plan_target_select']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($row['care_plan_target_select']) ? nl2br($row['care_plan_target_select']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
											</td>
											<td>
											<?php if (!empty($cpt_item_archive[$n]['care_plan_target_reason']) && $cpt_item_archive[$n]['cpt_id'] == $row['cpt_id']) {

                                        $diff = new HtmlDiff($cpt_item_archive[$n]['care_plan_target_reason'], $row['care_plan_target_reason']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_reason']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_edit_data) && empty($cpt_item_archive))
                                                        {
                                                            $diff = new HtmlDiff('', $row['care_plan_target_reason']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($row['care_plan_target_reason']) ? nl2br($row['care_plan_target_reason']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
											</td>											
										</tr>
										<?php }}?>
										</tbody>
										
										
										
										
									</table>
									  
                                    </td>
                             
                           </tr>

							</table>
    

						<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="font-size: 14px;color:white;text-align:center;padding:10px;">
                                      <b>CARE PLAN TARGET FROM PREVIOUS MDT REVIEW</b>
                                    </td>
                             
                           </tr>
						   <tr>
                               <td align="left" valign="top">
									<table width="100%" style="overflow: wrap;border-collapse:collapse;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="word-wrap: break-word; word-break: break-all;">
												CARE PLAN TARGET
											</td>
											
											<td style="word-wrap: break-word; word-break: break-all;">
                                       ACHIEVED/ONGOING/OUTSTANDING</td>
											
											<td style="word-wrap: break-word; word-break: break-all;">
                                        EVIDENCE OF HOW THIS HAS BEEN ACHIEVED / REASONS WHY IT HAS NOT BEEN ACHIEVED
											</td>											
										</tr>
										</thead>
										<tbody>
										<?php if (!empty($care_plan_target_previous)) {$n= 0;
                            foreach ($care_plan_target_previous as $row) {
                        ?>
										<tr style="color:#000000;">
											<td>
											<?php if (!empty($cpt_item_previous_archive[$n]['care_plan_target_title']) && $cpt_item_previous_archive[$n]['cpt_previous_id'] == $row['cpt_previous_id']) {

                                        $diff = new HtmlDiff($cpt_item_previous_archive[$n]['care_plan_target_title'], $row['care_plan_target_title']);
                                        $diff->build();
                                       echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_previous_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_title']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                        if(!empty($prev_edit_data) && empty($cpt_item_previous_archive))
                                            {
                                                $diff = new HtmlDiff('', $row['care_plan_target_title']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                            }else{
                                            ?>
                                     <?= !empty($row['care_plan_target_title']) ? nl2br($row['care_plan_target_title']) : '' ?>
                                     <?php
                                        }}
                                      } ?>
											</td>
											<td>
											<?php if (!empty($cpt_item_previous_archive[$n]['care_plan_target_select']) && $cpt_item_previous_archive[$n]['cpt_previous_id'] == $row['cpt_previous_id']) {

                                        $diff = new HtmlDiff($cpt_item_previous_archive[$n]['care_plan_target_select'], $row['care_plan_target_select']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_previous_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_select']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                        }else{
                                        if(!empty($prev_edit_data) && empty($cpt_item_previous_archive))
                                            {
                                                $diff = new HtmlDiff('', $row['care_plan_target_select']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                            }else{
                                            ?>
                                     <?= !empty($row['care_plan_target_select']) ? nl2br($row['care_plan_target_select']) : '' ?>
                                     <?php
                                        }}
                                      } ?>
											</td>
											<td>
											<?php if (!empty($cpt_item_previous_archive[$n]['care_plan_target_reason']) && $cpt_item_previous_archive[$n]['cpt_previous_id'] == $row['cpt_previous_id']) {

                                        $diff = new HtmlDiff($cpt_item_previous_archive[$n]['care_plan_target_reason'], $row['care_plan_target_reason']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_previous_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_reason']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_edit_data) && empty($cpt_item_previous_archive))
                                            {
                                                $diff = new HtmlDiff('', $row['care_plan_target_reason']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                            }else{
                                            ?>
                                     <?= !empty($row['care_plan_target_reason']) ? nl2br($row['care_plan_target_reason']) : '' ?>
                                     <?php
                                        } }
                                      } ?>
											</td>											
										</tr>
										 <?php $n++;}
                        }
                        ?>   
										</tbody>
										
										
										
										
									</table>
									  
                                    </td>
                             
                           </tr>

							</table>



              <table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="font-size: 14px;color:white;text-align:center;padding:10px;">
                                      <b>HEALTH</b>
                                    </td>
                             
                           </tr>
               <tr>
                               <td align="left" valign="top">
                  <table width="100%" style="overflow: wrap;border-collapse:collapse;" cellspacing="0" cellpadding="10" border="1" >
                      <thead>
                    <tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
                      <td style="word-wrap: break-word; word-break: break-all;">HEALTH APPOINTMENTS ATTENDED</td>
                      <td style="word-wrap: break-word; word-break: break-all;">DATE</td>
                      <td style="word-wrap: break-word; word-break: break-all;">OUTCOME/ ACTIONS</td>                     
                    </tr>
                    </thead>
                    <tbody>
                  <?php if (!empty($appointments)) {
                                        foreach ($appointments as $row) {
                                    ?>
                    <tr style="color:#000000;">
                      <td><?= !empty($row['mp_name']) ? $row['mp_name'] : '' ?></td>
                      <td> <?=(!empty($row['appointment_date']) && $row['appointment_date'] !='0000-00-00')?configDateTime($row['appointment_date']):''?> </td>
                      <td><?= (!empty($row['comments'])) ? ((strlen ($row['comments']) > 50) ? $substr = substr (trim(strip_tags($row['comments'])), 0, 50) . '...<a data-href="'.base_url('Medical'.'/readmore_appointment/'.$row['appointment_id']).'/comments" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($row['comments']))):'' ?></td>                     
                    </tr>
                     <?php $n++;}
                        }
                        ?>   
                    </tbody>
                  </table>
                  </td>
                  </tr>

              </table>
    
<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px;color:#ffffff;text-align:center;padding:10px;">
                                      <b>Diet</b>
                                    </td>
                             
                           </tr>
						   <tr>
                               <td align="left" valign="top">
									<table width="100%" style="overflow: wrap;border-collapse:collapse;margin-bottom:10px;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;" colspan="2">
												Average of ‘<?=$diet_avg?> a day’ consumed
											</td>
											
																					
										</tr>
										</thead>
										<tbody>
										<tr style="color:#000000;text-align:left;line-height: auto;">
										 <td style="word-wrap: break-word; word-break: break-all;" colspan="2">
												<?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['average_days_consumed'], $edit_data[0]['average_days_consumed']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['average_days_consumed']) ? $edit_data[0]['average_days_consumed'] : '' ?>
                                     <?php } ?>
											</td>
											</tr>
										</tbody>
									</table>
									<table width="100%" style="overflow: wrap;border-collapse:collapse;margin-bottom:10px;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;" colspan="2">
												Comments/ Points For Consideration
											</td>
											
																					
										</tr>
										</thead>
										<tbody>
										<tr style="color:#000000;text-align:left;line-height: auto;">
										 <td style="word-wrap: break-word; word-break: break-all;" colspan="2">
											<?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['comments_points'], $edit_data[0]['comments_points']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['comments_points']) ? $edit_data[0]['comments_points'] : '' ?>
                                     <?php } ?>
                                        
											</td>
											</tr>
										</tbody>
									</table>  
									<table width="100%" style="overflow: wrap;border-collapse:collapse;margin-bottom:10px;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;" width="50%">
												Regular Hobbies / Clubs Attended
											</td>
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;" width="50%">
												Duration per week (hours/minutes)
											</td>
											
																					
										</tr>
										</thead>
										<tbody>
										  <?php if (!empty($hobbies_data)) { $n= 0;
                                        foreach ($hobbies_data as $row) {
                                    ?> 
										<tr style="color:#000000;text-align:left;line-height: auto;">
										 <td style="word-wrap: break-word; word-break: break-all;" width="50%">
											 <?php if (!empty($hobbies_item_archive[$n]['regular_hobbies']) && $hobbies_item_archive[$n]['regular_hobby_id'] == $row['regular_hobby_id']) {

                                                $diff = new HtmlDiff($hobbies_item_archive[$n]['regular_hobbies'], $row['regular_hobbies']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                             <?php if(!empty($hobbies_item_archive))
                                                {
                                                     $diff = new HtmlDiff('', $row['regular_hobbies']);
                                                        $diff->build();
                                                        echo $diff->getDifference();
                                                }else{
                                                    if(!empty($prev_edit_data) && empty($hobbies_item_archive))
                                                        {
                                                            $diff = new HtmlDiff('', $row['regular_hobbies']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($row['regular_hobbies']) ? nl2br($row['regular_hobbies']) : '' ?>
                                                 <?php
                                                    }
                                                }
                                              } ?>
                                            
											</td>
											 <td style="word-wrap: break-word; word-break: break-all;" width="50%">
											<?php if (!empty($hobbies_item_archive[$n]['regular_hobbies_duration']) && $hobbies_item_archive[$n]['regular_hobby_id'] == $row['regular_hobby_id']) {

                                        $diff = new HtmlDiff($hobbies_item_archive[$n]['regular_hobbies_duration'], $row['regular_hobbies_duration']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($hobbies_item_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['regular_hobbies_duration']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                        }else{
                                             if(!empty($prev_edit_data) && empty($hobbies_item_archive))
                                                        {
                                                            $diff = new HtmlDiff('', $row['regular_hobbies_duration']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($row['regular_hobbies_duration']) ? nl2br($row['regular_hobbies_duration']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
											</td>
											</tr>
											 <?php $n++;} } ?>
										</tbody>
									</table>  
									<table width="100%" style="overflow: wrap;border-collapse:collapse;margin-bottom:10px;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;" width="50%">
												Physical Exercise Completed
											</td>
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;" width="50%">
												Duration per week (hours/minutes)
											</td>
											
																					
										</tr>
										</thead>
										<tbody>
										  <?php if (!empty($physical_exercise_data)) {$n= 0;
                                            foreach ($physical_exercise_data as $row) {
                                        ?> 
										<tr style="color:#000000;text-align:left;line-height: auto;">
										 <td style="word-wrap: break-word; word-break: break-all;" width="50%">
											 <?php if (!empty($exercise_item_archive[$n]['physical_exercise']) && $exercise_item_archive[$n]['physical_exercise_id'] == $row['physical_exercise_id']) {

                                                $diff = new HtmlDiff($exercise_item_archive[$n]['physical_exercise'], $row['physical_exercise']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                             <?php if(!empty($exercise_item_archive))
                                                {
                                                     $diff = new HtmlDiff('', $row['physical_exercise']);
                                                        $diff->build();
                                                        echo $diff->getDifference();
                                                }else{
                                                     if(!empty($prev_edit_data) && empty($exercise_item_archive))
                                                        {
                                                            $diff = new HtmlDiff('', $row['physical_exercise']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($row['physical_exercise']) ? nl2br($row['physical_exercise']) : '' ?>
                                                 <?php
                                                    }
                                                }
                                              } ?>
                                            
											</td>
											 <td style="word-wrap: break-word; word-break: break-all;" width="50%">
											 <?php if (!empty($exercise_item_archive[$n]['physical_exercise_duration']) && $exercise_item_archive[$n]['physical_exercise_id'] == $row['physical_exercise_id']) {

                                                $diff = new HtmlDiff($exercise_item_archive[$n]['physical_exercise_duration'], $row['physical_exercise_duration']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                             <?php if(!empty($exercise_item_archive))
                                                {
                                                     $diff = new HtmlDiff('', $row['physical_exercise_duration']);
                                                        $diff->build();
                                                        echo $diff->getDifference();
                                                }else{ 
                                                    if(!empty($prev_edit_data) && empty($exercise_item_archive))
                                                        {
                                                            $diff = new HtmlDiff('', $row['physical_exercise_duration']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($row['physical_exercise_duration']) ? nl2br($row['physical_exercise_duration']) : '' ?>
                                                 <?php
                                                    }
                                                }
                                              } ?>
											</td>
											</tr>
											<?php $n++;} } ?>
										</tbody>
									</table>  
                                    </td>
                             
                           </tr>
</table>



<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px;color:#ffffff;text-align:center;padding:10px;">
                                      <b>Emotional and Behavioural Development</b>
                                    </td>
                             
                           </tr>
						   <tr>
							<td>There <?=(!empty($incident_level[0]['level1']) && $incident_level[0]['level1'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level1'])?$incident_level[0]['level1']:'0'?></span> incident of level1.</td>
							
						   </tr><tr>
							<td>There <?=(!empty($incident_level[0]['level2']) && $incident_level[0]['level2'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level2'])?$incident_level[0]['level2']:'0'?></span> incident of level2.</td>
							
						   </tr><tr>
							<td>There <?=(!empty($incident_level[0]['level3']) && $incident_level[0]['level3'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level3'])?$incident_level[0]['level3']:'0'?></span> incident of level3.</td>
							
						   </tr><tr>
							<td>There <?=(!empty($incident_level[0]['level4']) && $incident_level[0]['level4'] >1)?'are':'is'?>  <span><?=!empty($incident_level[0]['level4'])?$incident_level[0]['level4']:'0'?></span> incident of level4.</td>
							
						   </tr>
						   <tr>
                               <td align="left" valign="top">
									<table width="100%" style="overflow: wrap;border-collapse:collapse;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
												Incident summary (Include the date)
											</td>
											
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                       Level 1(incident requiring no physical intervention)</td>
											
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                        Level 2(incident requiring physical intervention up to and including seated holds)
											</td><td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                        Level 3(incident requiring physical intervention including ground holds)
											</td><td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                        Level 4(Missing from care / absent without authority)
											</td>											
										</tr>
										</thead>
										<tbody>
										<?php if (!empty($incident_data)) {$n= 0;
                                    foreach ($incident_data as $row) {
                                ?> 
										<tr style="color:#000000;">
											<td>
											<?php if (!empty($incident_item_archive[$n]['incident_summary']) && $incident_item_archive[$n]['incident_id'] == $row['incident_id']) {

                                        $diff = new HtmlDiff($incident_item_archive[$n]['incident_summary'], $row['incident_summary']);
                                        $diff->build();
                                       echo nl2br($diff->getDifference());
                                        ?>
                                      <?php } else { ?>
                                      <?php if(!empty($incident_item_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['incident_summary']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                           if(!empty($prev_edit_data) && empty($incident_item_archive))
                                                {
                                                    $diff = new HtmlDiff('', $row['incident_summary']);
                                                    $diff->build();
                                                    echo nl2br($diff->getDifference());
                                                }else{
                                                ?>
                                         <?= !empty($row['incident_summary']) ? nl2br($row['incident_summary']) : '' ?>
                                         <?php
                                            }
                                        }
                                      } ?>
											</td>
											<td>
											<?php $lchange = (!empty($incident_item_archive[$n]['level']) && $incident_item_archive[$n]['incident_id'] == $row['incident_id'] && $incident_item_archive[$n]['level'] != $row['level'])?'1':'' ?>
											<?= (!empty($row['level']) && $row['level'] == 1) ? 'X' : ''?>
											</td>
											<td>
											<?= (!empty($row['level']) && $row['level'] == 2) ? 'X' : ''?>
											</td><td>
											<?= (!empty($row['level']) && $row['level'] == 3) ? 'X' : ''?>
											</td><td>
											 <?= (!empty($row['level']) && $row['level'] == 4) ? 'X' : ''?>
											</td>											
										</tr>
										 <?php $n++;}
                        }
                        ?>   
										</tbody>
										
										
										
										
									</table>
									  
                                    </td>
                             
                           </tr>
</table>

<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px;color:#ffffff;text-align:center;padding:10px;">
                                      <b>Sanctions</b>
                                    </td>
                             
                           </tr>
						   
						   <tr>
                               <td align="left" valign="top">
									<table width="100%" style="overflow: wrap;border-collapse:collapse;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
												Reason for Sanction
											</td>
											
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                       Date</td>
											
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                       Sanction Imposed
											</td>									
										</tr>
										</thead>
										<tbody>
										<?php if (!empty($sanction_data)) {$n= 0;
                                    foreach ($sanction_data as $row) {
                                ?> 
										<tr style="color:#000000;">
											<td>
											<?php if (!empty($sanction_item_archive[$n]['reason_sanction']) && $sanction_item_archive[$n]['sanction_id'] == $row['sanction_id']) {

                                        $diff = new HtmlDiff($sanction_item_archive[$n]['reason_sanction'], $row['reason_sanction']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                      <?php } else { ?>
                                      <?php if(!empty($sanction_item_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['reason_sanction']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                        }else{
                                            if(!empty($prev_edit_data) && empty($sanction_item_archive))
                                                {
                                                    $diff = new HtmlDiff('', $row['reason_sanction']);
                                                    $diff->build();
                                                    echo nl2br($diff->getDifference());
                                                }else{
                                                ?>
                                         <?= !empty($row['reason_sanction']) ? nl2br($row['reason_sanction']) : '' ?>
                                         <?php
                                            }
                                        }
                                      } ?>
											</td>
											<td>
											<?php if (!empty($sanction_item_archive[$n]['date_sanction']) && $sanction_item_archive[$n]['sanction_id'] == $row['sanction_id']) {

                                            $diff = new HtmlDiff(configDateTime($sanction_item_archive[$n]['date_sanction']), configDateTime($row['date_sanction']));
                                            $diff->build();
                                            echo $diff->getDifference();
                                        ?>
                                        <?php } else { ?>
                                        <?php if(!empty($sanction_item_archive))
                                            {
                                                 $diff = new HtmlDiff('', configDateTime($row['date_sanction']));
                                                    $diff->build();
                                                    echo $diff->getDifference();
                                            }else{
                                                if(!empty($prev_edit_data) && empty($sanction_item_archive))
                                                {
                                                    $diff = new HtmlDiff('', $row['date_sanction']);
                                                    $diff->build();
                                                    echo nl2br($diff->getDifference());
                                                }else{
                                                ?>
                                         <?= !empty($row['date_sanction']) ? nl2br(configDateTime($row['date_sanction'])) : '' ?>
                                         <?php
                                            }
                                            }
                                          } ?>
											</td>
											<td>
											 <?php if (!empty($sanction_item_archive[$n]['imposed_sanction']) && $sanction_item_archive[$n]['sanction_id'] == $row['sanction_id']) {

                                        $diff = new HtmlDiff($sanction_item_archive[$n]['imposed_sanction'], $row['imposed_sanction']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                      <?php } else { ?>
                                      <?php if(!empty($sanction_item_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['imposed_sanction']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                        }else{
                                            if(!empty($prev_edit_data) && empty($sanction_item_archive))
                                                {
                                                    $diff = new HtmlDiff('', $row['imposed_sanction']);
                                                    $diff->build();
                                                    echo nl2br($diff->getDifference());
                                                }else{
                                                ?>
                                         <?= !empty($row['imposed_sanction']) ? nl2br($row['imposed_sanction']) : '' ?>
                                         <?php
                                            }
                                        }
                                      } ?>
											</td>											
										</tr>
										 <?php $n++;}
                        }
                        ?>   
										</tbody>
										
										
										
										
									</table>
									  
                                    </td>
                             
                           </tr>
</table>


<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Safeguarding Concerns</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['safeguarding'], $edit_data[0]['safeguarding']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['safeguarding']) ? $edit_data[0]['safeguarding'] : '' ?>
                             <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>General behaviour</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['general_behaviour'], $edit_data[0]['general_behaviour']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['general_behaviour']) ? $edit_data[0]['general_behaviour'] : '' ?>
                             <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Concerns</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['concerns'], $edit_data[0]['concerns']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['concerns']) ? $edit_data[0]['concerns'] : '' ?>
                             <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Bullying Issues/ Concerns</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['bullying_issues'], $edit_data[0]['bullying_issues']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['bullying_issues']) ? $edit_data[0]['bullying_issues'] : '' ?>
                             <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Significant events</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['significant_events'], $edit_data[0]['significant_events']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['significant_events']) ? $edit_data[0]['significant_events'] : '' ?>
                             <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						 
                 
                     
                    
</table>

<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px;color:#ffffff;text-align:center;padding:10px;">
                                      <b>Education, Achievements and Social Skills</b>
                                    </td>
                             
                           </tr>
						   <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px;color:#ffffff;text-align:center;padding:10px;">
                                      <b>Educational Attendance</b>
                                    </td>
                             
                           </tr>
						   
						   <tr>
                               <td align="left" valign="top">
									<table width="100%" style="overflow: wrap;border-collapse:collapse;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
												Percentage of Attendance
											</td>
											
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                       Number of Referrals (Pink and Blue)</td>
											
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                       Achievements Student of the Week
											</td>									
										</tr>
										</thead>
										<tbody>
										 
										<tr style="color:#000000;">
											<td>
											<?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['per_of_attendance'], $edit_data[0]['per_of_attendance']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                        <?php } else { ?>
                                        <?= !empty($edit_data[0]['per_of_attendance']) ? $edit_data[0]['per_of_attendance'] : '' ?>
                                        <?php } ?>
											</td>
											<td>
											<?php if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0]['number_of_referrals'], $edit_data[0]['number_of_referrals']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                        <?php } else { ?>
                                        <?=!empty($edit_data[0]['number_of_referrals']) ? $edit_data[0]['number_of_referrals'] : '' ?>
                                        <?php } ?>
											</td>
											<td>
											 <?php if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0]['achievements'], $edit_data[0]['achievements']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                        <?php } else { ?>
                                        <?= !empty($edit_data[0]['achievements']) ? $edit_data[0]['achievements'] : '' ?>
                                        <?php } ?>
											</td>											
										</tr>
										 
										</tbody>
										
										
										
										
									</table>
									  
                                    </td>
                             
                           </tr>
</table>


<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Banding System</b>
                                    </td>
                             
                           </tr><tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Average Pocket Money Achieved</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0]['average_pocket'], $edit_data[0]['average_pocket']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                                    <?= !empty($edit_data[0]['average_pocket']) ? $edit_data[0]['average_pocket'] : '' ?>
                                             <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Emotional / Social Development</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['emotional'], $edit_data[0]['emotional']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['emotional']) ? $edit_data[0]['emotional'] : '' ?>
                                     <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Evidence of Positive Relationships</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {
                                        $diff = new HtmlDiff($prev_edit_data[0]['positive_relationships'], $edit_data[0]['positive_relationships']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['positive_relationships']) ? $edit_data[0]['positive_relationships'] : '' ?>
                                     <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Contact</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['contact'], $edit_data[0]['contact']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['contact']) ? $edit_data[0]['contact'] : '' ?>
                                     <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Peer relationships</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['peer_relationships'], $edit_data[0]['peer_relationships']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['peer_relationships']) ? $edit_data[0]['peer_relationships'] : '' ?>
                                     <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Cultural Needs</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['cultural_needs'], $edit_data[0]['cultural_needs']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['cultural_needs']) ? $edit_data[0]['cultural_needs'] : '' ?>
                                     <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Evidence of Positive Decision Making</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                        <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['positive_decision'], $edit_data[0]['positive_decision']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['positive_decision']) ? $edit_data[0]['positive_decision'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>After School Clubs</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                         <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['school_clubs'], $edit_data[0]['school_clubs']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['school_clubs']) ? $edit_data[0]['school_clubs'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr><tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Evidencing the 24hour Curriculum</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                        <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['evidencing_curriculum'], $edit_data[0]['evidencing_curriculum']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['evidencing_curriculum']) ? $edit_data[0]['evidencing_curriculum'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr><tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Part-time / Voluntary Work</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                        <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['voluntary_work'], $edit_data[0]['voluntary_work']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['voluntary_work']) ? $edit_data[0]['voluntary_work'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						 
                 
                     
                    
</table>

<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px;color:#ffffff;text-align:center;padding:10px;">
                                      <b>ACHIEVE ECONOMIC WELLBEING</b>
                                    </td>
                             
                           </tr><tr style="background-color: #4e6031;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px;color:#ffffff;text-align:center;padding:10px;">
                                      <b>LIFE SKILLS DEVELOPMENT</b>
                                    </td>
                             
                           </tr>
						   
						   <tr>
                               <td align="left" valign="top">
									<table width="100%" style="overflow: wrap;border-collapse:collapse;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
												Area of Development
											</td>
											
											<td style="text-transform: uppercase;word-wrap: break-word; word-break: break-all;">
                                       Progress achieved/ Action Required</td>
											
																				
										</tr>
										</thead>
										<tbody>
										 <?php if (!empty($life_skills_data)) {$n= 0;
                                    foreach ($life_skills_data as $row) {
                                ?> 
										<tr style="color:#000000;">
											
											<td>
											<?php if (!empty($likeskills_item_archive[$n]['area_of_development']) && $likeskills_item_archive[$n]['life_skills_id'] == $row['life_skills_id']) {

                                        $diff = new HtmlDiff($likeskills_item_archive[$n]['area_of_development'], $row['area_of_development']);
                                        $diff->build();
                                       echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($likeskills_item_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['area_of_development']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_edit_data) && empty($likeskills_item_archive))
                                                {
                                                    $diff = new HtmlDiff('', $row['area_of_development']);
                                                    $diff->build();
                                                    echo nl2br($diff->getDifference());
                                                }else{
                                                ?>
                                         <?= !empty($row['area_of_development']) ? nl2br($row['area_of_development']) : '' ?>
                                         <?php
                                            }
                                        }
                                      } ?>
											</td>
											<td>
											 <?php if (!empty($likeskills_item_archive[$n]['progress_achieved']) && $likeskills_item_archive[$n]['life_skills_id'] == $row['life_skills_id']) {

                                            $diff = new HtmlDiff($likeskills_item_archive[$n]['progress_achieved'], $row['progress_achieved']);
                                            $diff->build();
                                            echo nl2br($diff->getDifference());
                                            ?>
                                         <?php } else { ?>
                                         <?php if(!empty($likeskills_item_archive))
                                            {
                                                 $diff = new HtmlDiff('', $row['progress_achieved']);
                                                    $diff->build();
                                                    echo nl2br($diff->getDifference());
                                            }else{
                                                 if(!empty($prev_edit_data) && empty($likeskills_item_archive))
                                                {
                                                    $diff = new HtmlDiff('', $row['progress_achieved']);
                                                    $diff->build();
                                                    echo nl2br($diff->getDifference());
                                                }else{
                                                ?>
                                         <?= !empty($row['progress_achieved']) ? nl2br($row['progress_achieved']) : '' ?>
                                         <?php
                                            }
                                            }
                                          } ?>
											</td>											
										</tr>
										 <?php $n++;}
                        }
                        ?>   
										</tbody>
										
										
										
										
									</table>
									  
                                    </td>
                             
                           </tr>
</table>


<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>CARE SUMMARY</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['care_summary'], $edit_data[0]['care_summary']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['care_summary']) ? $edit_data[0]['care_summary'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>THERAPY</b>
                                    </td>
                             
                           </tr><tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>ATTENDANCE</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['attendance'], $edit_data[0]['attendance']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['attendance']) ? $edit_data[0]['attendance'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Engagement</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['engagement'], $edit_data[0]['engagement']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['engagement']) ? $edit_data[0]['engagement'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Areas of focus</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['areas_of_focus'], $edit_data[0]['areas_of_focus']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['areas_of_focus']) ? $edit_data[0]['areas_of_focus'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
						   
						   
						   <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>Progress</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['progress'], $edit_data[0]['progress']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                               <?= !empty($edit_data[0]['progress']) ? $edit_data[0]['progress'] : '' ?>
                                         <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						 
                 
                     
                    
</table>

<table width="100%" style="font-size:14px;font-family:arial;overflow: wrap;border-collapse:collapse;margin-top:10px;" cellspacing="0" cellpadding="0" >
                           <tr style="background-color: #4e6031;">
                             
                               <td align="left" style="font-size: 14px;color:white;text-align:center;padding:10px;">
                                      <b>CARE PLAN TARGETS IDENTIFIED FOR THE NEXT 12WEEKS</b>
                                    </td>
                             
                           </tr>
						   <tr>
                               <td align="left" valign="top">
									<table width="100%" style="overflow: wrap;border-collapse:collapse;" cellspacing="0" cellpadding="10" border="1" >
									    <thead>
										<tr style="color:#FFFFFF;text-align:left;line-height: auto;height: auto;background-color: #4e6031;font-weight:bold">
											<td style="word-wrap: break-word; word-break: break-all;">
												CARE PLAN TARGET
											</td>
											
											<td style="word-wrap: break-word; word-break: break-all;">
                                       ACHIEVED/ONGOING/OUTSTANDING</td>
											
											<td style="word-wrap: break-word; word-break: break-all;">
                                        EVIDENCE OF HOW THIS HAS BEEN ACHIEVED / REASONS WHY IT HAS NOT BEEN ACHIEVED
											</td>											
										</tr>
										</thead>
										<tbody>
										<?php if (!empty($care_plan_target_week)) {$n= 0;
                            foreach ($care_plan_target_week as $row) {
                        ?>
										<tr style="color:#000000;">
											<td>
											<?php if (!empty($cpt_item_week_archive[$n]['care_plan_target_title']) && $cpt_item_week_archive[$n]['cpt_week_id'] == $row['cpt_week_id']) {

                                        $diff = new HtmlDiff($cpt_item_week_archive[$n]['care_plan_target_title'], $row['care_plan_target_title']);
                                        $diff->build();
                                       echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_week_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_title']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_edit_data) && empty($cpt_item_week_archive))
                                            {
                                                $diff = new HtmlDiff('', $row['care_plan_target_title']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                            }else{
                                            ?>
                                     <?= !empty($row['care_plan_target_title']) ? nl2br($row['care_plan_target_title']) : '' ?>
                                     <?php
                                        }
                                        }
                                      } ?>
											</td>
											<td>
											<?php if (!empty($cpt_item_week_archive[$n]['care_plan_target_select']) && $cpt_item_week_archive[$n]['cpt_week_id'] == $row['cpt_week_id']) {

                                        $diff = new HtmlDiff($cpt_item_week_archive[$n]['care_plan_target_select'], $row['care_plan_target_select']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_week_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_select']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                        }else{
                                            if(!empty($prev_edit_data) && empty($cpt_item_week_archive))
                                            {
                                                $diff = new HtmlDiff('', $row['care_plan_target_select']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                            }else{
                                            ?>
                                     <?= !empty($row['care_plan_target_select']) ? nl2br($row['care_plan_target_select']) : '' ?>
                                     <?php
                                        }
                                        }
                                      } ?>
											</td>
											<td>
											<?php if (!empty($cpt_item_week_archive[$n]['care_plan_target_reason']) && $cpt_item_week_archive[$n]['cpt_week_id'] == $row['cpt_week_id']) {

                                        $diff = new HtmlDiff($cpt_item_week_archive[$n]['care_plan_target_reason'], $row['care_plan_target_reason']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($cpt_item_week_archive))
                                        {
                                             $diff = new HtmlDiff('', $row['care_plan_target_reason']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_edit_data) && empty($cpt_item_week_archive))
                                            {
                                                $diff = new HtmlDiff('', $row['care_plan_target_reason']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                            }else{
                                            ?>
                                     <?= !empty($row['care_plan_target_reason']) ? nl2br($row['care_plan_target_reason']) : '' ?>
                                     <?php
                                        }
                                        }
                                      } ?>
											</td>											
										</tr>
										 <?php $n++;}
                        }
                        ?>   
										</tbody>
										
										
										
										
									</table>
									  
                                    </td>
                             
                           </tr>

							</table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
  <tr>
    <td>
      <p style="text-align: center;"><em>&nbsp;</em></p>
    </td>
  </tr>
</table>

              <table width="100%" cellpadding="5" cellspacing="0" border="1" style=" margin: 0; border-collapse: collapse;">
          
                        <?php

            if (!empty($form_data)) {
                foreach ($form_data as $row) { 
        

                  if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {

                     if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 

                                      $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                      ?>
            
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b><?= !empty($row['label']) ? $row['label'] : '' ?>:</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">

                                       <?php if($row['subtype'] == 'time'){ ?>
                                         <?= !empty($data_textarea) ? nl2br(timeformat($data_textarea)) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                      <?php }elseif($row['type'] == 'date'){?>
                                         <?= !empty($data_textarea) ? nl2br(configDateTime($data_textarea)) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                      <?php }else{ ?>
                                         <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>
                                      <?php } ?>
   </p>                                   
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
  <tr>
    <td>
      <p style="text-align: center;"><em>&nbsp;</em></p>
    </td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
<tr>
<td>
<p style="text-align: center;"><em>&nbsp;</em></p>
</td>
</tr>
</table>                                              
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
    <tr>
      <td>
        <p style="text-align: center;"><em>&nbsp;</em></p>
      </td>
    </tr>
</table> 

<!-- <table width="100%">
  <tr>
    <td style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
      <p style="text-align: center;"><em>&nbsp;</em></p>
    </td>
  </tr>
</table> -->
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="font-size: 16px;  border-collapse: collapse;font-family:arial;border:1px solid #000000;">
    <thead>
        <tr style="background-color: #4e6031;">
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: auto;height: auto;font-weight: bold;  padding:10px">NAME</th>
            
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: auto;height: auto;font-weight: bold; padding:10px">DATE</th>
        </tr>
    </thead>
    <tbody>
      <?php if (!empty($signoff_data)) {
      foreach ($signoff_data as $sign_name) { ?>
      <tr>
            <td width="50%" style="font-size: 14px;color:#000000;text-align:center;line-height: auto;font-weight: bold; padding:10px  ">
                  <?php /*  $strname.=!empty($sign_name['name']) ? $sign_name['name'] . ',' : '';
                        echo substr($strname, 0, -1); */ ?>
                        
                        <?= !empty($sign_name['name']) ? $sign_name['name'] : '' ?>
            </td>
             <td width="50%" style="font-size: 14px;color:#000000;text-align:center;line-height: auto;font-weight: bold;padding:10px  ">
          <?= (!empty($sign_name['created_date']) && $sign_name['created_date'] != '0000-00-00') ? configDateTime($sign_name['created_date']) : '' ?>
              
            </td>
        </tr>
        <?php }
        }
      ?>
    </tbody>
</table>