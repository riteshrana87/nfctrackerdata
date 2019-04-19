                     <table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>       
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="3" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">CARE PLAN TARGETS IDENITIFIED FROM LAC/CLA REVIEW</td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Care plan target</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Achieved/Ongoing/Outstanding</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Evidence of how this has been achieved / reasons why it has not been achieved</td>
                        </tr>
                        <?php if (!empty($care_plan_target)) {
                                  foreach ($care_plan_target as $row) {
                              ?>

                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_title']) ? $row['care_plan_target_title'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_select']) ? $row['care_plan_target_select'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_reason']) ? $row['care_plan_target_reason'] : '' ?></td>
                        </tr>
                         <?php }
                        }
                        ?>   
                    </table>
<table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="3" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">CARE PLAN TARGET FROM PREVIOUS MDT REVIEW</td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Care plan target</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Achieved/Ongoing/Outstanding</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Evidence of how this has been achieved / reasons why it has not been achieved</td>
                        </tr>
                        <?php if (!empty($care_plan_target_previous)) {
                                  foreach ($care_plan_target_previous as $row) {
                              ?>

                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_title']) ? $row['care_plan_target_title'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_select']) ? $row['care_plan_target_select'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_reason']) ? $row['care_plan_target_reason'] : '' ?></td>
                        </tr>
                         <?php }
                        }
                        ?>   
                    </table>

                     <table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>    
                      <!-- Start BE HEALTHY -->
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="3" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">Health</td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Health Appointments Attended</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Date</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Outcome/ Actions</td>
                        </tr>
                        <?php if (!empty($appointments)) {
                                  foreach ($appointments as $row) {
                              ?>

                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['mp_name']) ? $row['mp_name'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?=(!empty($row['appointment_date']) && $row['appointment_date'] !='0000-00-00')?configDateTime($row['appointment_date']):''?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= (!empty($row['comments'])) ? $row['comments']:'' ?></td>
                        </tr>
                         <?php }
                        }
                        ?>   
                        <tr style="background-color: #cccccc;">
                        <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Diet</td>
                        </tr>
                        <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                          <p style="font-size: 15px;font-weight: bold;">Average of ‘<?=$diet_avg?> a day’ consumed </p>
                           <?= !empty($edit_data[0]['average_days_consumed']) ? $edit_data[0]['average_days_consumed'] : '' ?>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                          <p style="font-size: 15px;font-weight: bold;">Comments/ Points For Consideration:</p>
                            <?= !empty($edit_data[0]['comments_points']) ? $edit_data[0]['comments_points'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Regular Hobbies / Clubs Attended</td>
                          <td colspan="2" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Duration per week (hours/minutes)</td>
                        </tr>
                        <?php if (!empty($hobbies_data)) {
                                  foreach ($hobbies_data as $row) {
                              ?>

                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['regular_hobbies']) ? $row['regular_hobbies'] : '' ?></td>
                          <td colspan="2" style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['regular_hobbies_duration']) ? $row['regular_hobbies_duration'] : '' ?></td>
                          
                        </tr>
                         <?php }
                        }
                        ?>   
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Physical Exercise Completed</td>
                          <td colspan="2" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Duration per week (hours/minutes)</td>
                        </tr>
                        <?php if (!empty($physical_exercise_data)) {
                                  foreach ($physical_exercise_data as $row) {
                              ?>

                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['physical_exercise']) ? $row['physical_exercise'] : '' ?></td>
                          <td colspan="2" style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['physical_exercise_duration']) ? $row['physical_exercise_duration'] : '' ?></td>
                          
                        </tr>
                         <?php }
                        }
                        ?>   
                    </table>
                    <!-- End BE HEALTHY -->
                    <table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>
                     <!-- Start STAY SAFE -->
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="5" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">Emotional and Behavioural Development</td>
                        </tr>
                        <tr style=""> 
                          <td colspan="5" style="font-size: 15px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">There <?=(!empty($incident_level[0]['level1']) && $incident_level[0]['level1'] >1)?'are':'is'?> <?=!empty($incident_level[0]['level1'])?$incident_level[0]['level1']:'0'?> incident of level1. </td>
                        </tr>
                        <tr style=""> 
                          <td colspan="5" style="font-size: 15px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">There <?=(!empty($incident_level[0]['level2']) && $incident_level[0]['level2'] >1)?'are':'is'?> <?=!empty($incident_level[0]['level2'])?$incident_level[0]['level2']:0?> incident of level2. </td>
                        </tr>
                        <tr style=""> 
                          <td colspan="5" style="font-size: 15px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">There <?=(!empty($incident_level[0]['level3']) && $incident_level[0]['level3'] >1)?'are':'is'?> <?=!empty($incident_level[0]['level3'])?$incident_level[0]['level3']:'0'?> incident of level3. </td>
                        </tr>
                        <tr style=""> 
                          <td colspan="5" style="font-size: 15px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">There <?=(!empty($incident_level[0]['level4']) && $incident_level[0]['level4'] >1)?'are':'is'?> <?=!empty($incident_level[0]['level4'])?$incident_level[0]['level4']:'0'?> incident of level4. </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Incident summary (Include the date)</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Level 1(incident requiring no physical intervention)</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Level 2(incident requiring physical intervention up to and including seated holds)</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Level 3(incident requiring physical intervention including ground holds)</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Level 4(Missing from care / absent without authority)</td>
                        </tr>
                        <?php if (!empty($incident_data)) {
                                  foreach ($incident_data as $row) { 
                              ?>

                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['incident_summary']) ? $row['incident_summary'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= (!empty($row['level']) && $row['level'] == 1) ? 'X' : ''?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= (!empty($row['level']) && $row['level'] == 2) ? 'X' : ''?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= (!empty($row['level']) && $row['level'] == 3) ? 'X' : ''?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= (!empty($row['level']) && $row['level'] == 4) ? 'X' : ''?></td>
                        </tr>
                         <?php }
                        }
                        ?>   
                        <tr style="background-color: #cccccc;">
                        <td colspan="5" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Sanctions</td>
                        </tr>
                        
                        <tr style="background-color: #cccccc;">
                          <td colspan="2" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Reason for Sanction</td>
                          <td colspan="1" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Date</td>
                          <td colspan="2" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Sanction Imposed</td>
                        </tr>
                        <?php if (!empty($sanction_data)) {
                                  foreach ($sanction_data as $row) {
                              ?>

                        <tr>
                          <td colspan="2" style="font-size: 15px;padding: 5px 10px;"> <?= !empty($row['reason_sanction']) ? $row['reason_sanction'] : '' ?></td>
                          <td colspan="1" style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['date_sanction']) ?date('d/m/Y',strtotime($row['date_sanction'])) : '' ?></td>
                          <td colspan="2" style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['imposed_sanction']) ? $row['imposed_sanction'] : '' ?></td>
                          
                        </tr>
                         <?php }
                        }
                        ?>   
                        <tr style="background-color: #cccccc;">
                          <td colspan="5" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Safeguarding Concerns</td>
                        </tr>
                       <tr>
                          <td colspan="5" style="font-size: 15px;padding: 5px 10px;"> <?= !empty($edit_data[0]['safeguarding']) ? $edit_data[0]['safeguarding'] : '' ?></td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="5" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">General behaviour</td>
                        </tr>
                       <tr>
                          <td colspan="5" style="font-size: 15px;padding: 5px 10px;"><?= !empty($edit_data[0]['general_behaviour']) ? $edit_data[0]['general_behaviour'] : '' ?></td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="5" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Concerns</td>
                        </tr>
                       <tr>
                          <td colspan="5" style="font-size: 15px;padding: 5px 10px;"><?= !empty($edit_data[0]['concerns']) ? $edit_data[0]['concerns'] : '' ?></td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="5" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Bullying Issues/ Concerns</td>
                        </tr>
                       <tr>
                          <td colspan="5" style="font-size: 15px;padding: 5px 10px;"><?= !empty($edit_data[0]['bullying_issues']) ? $edit_data[0]['bullying_issues'] : '' ?></td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="5" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Significant events</td>
                        </tr>
                       <tr>
                          <td colspan="5" style="font-size: 15px;padding: 5px 10px;">  <?= !empty($edit_data[0]['significant_events']) ? $edit_data[0]['significant_events'] : '' ?></td>
                        </tr>
                    </table>
                    <!-- End STAY SAFE-->
                     <table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>
                     <!-- Start ENJOY AND ACHIEVE -->
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="3" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">Education, Achievements and Social Skills</td>
                        </tr>
                       
                        <tr style="background-color: #cccccc;">
                        <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Educational Attendance</td>
                        </tr>
                        
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Percentage of Attendance</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Number of Referrals (Pink and Blue)</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">AchievementsStudent of the Week</td>
                        </tr>
                      
                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"> <?= !empty($edit_data[0]['per_of_attendance']) ? $edit_data[0]['per_of_attendance'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"> <?=!empty($edit_data[0]['number_of_referrals']) ? $edit_data[0]['number_of_referrals'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"> <?= !empty($edit_data[0]['achievements']) ? $edit_data[0]['achievements'] : '' ?></td>
                        </tr>
                          <tr style="background-color: #cccccc;">
                        <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Banding System</td>
                        </tr>
                        
                        <tr>
                          <td  style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Average Pocket Money Achieved</td>
                          <td colspan="2" style="font-size: 15px;font-weight: bold;padding: 5px 10px;"><?= !empty($edit_data[0]['average_pocket']) ? $edit_data[0]['average_pocket'] : '' ?></td>
                        </tr>
                      
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">  Emotional / Social Development</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;"> 
                          <?= !empty($edit_data[0]['emotional']) ? $edit_data[0]['emotional'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Evidence of Positive Relationships</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['positive_relationships']) ? $edit_data[0]['positive_relationships'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">  Contact</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['contact']) ? $edit_data[0]['contact'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Peer relationships</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['peer_relationships']) ? $edit_data[0]['peer_relationships'] : '' ?> 
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Cultural Needs</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['cultural_needs']) ? $edit_data[0]['cultural_needs'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">  Evidence of Positive Decision Making</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;"> 
                          <?= !empty($edit_data[0]['positive_decision']) ? $edit_data[0]['positive_decision'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">After School Clubs</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['school_clubs']) ? $edit_data[0]['school_clubs'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">  Evidencing the 24hour Curriculum</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['evidencing_curriculum']) ? $edit_data[0]['evidencing_curriculum'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Part-time / Voluntary Work</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['voluntary_work']) ? $edit_data[0]['voluntary_work'] : '' ?>
                          </td>
                        </tr>
                    </table>
                    <!-- End ENJOY AND ACHIEVE-->
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
                     <!-- Start ACHIEVE ECONOMIC WELLBEING -->
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="2" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">ACHIEVE ECONOMIC WELLBEING</td>
                        </tr>
                       
                        <tr style="background-color: #cccccc;">
                        <td colspan="2" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Life Skills Development</td>
                        </tr>
                        
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Area of Development</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Progress achieved/ Action Required</td>
                         
                        </tr>
                      <?php if (!empty($life_skills_data)) {
                                            foreach ($life_skills_data as $row) {
                                        ?> 
                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"> <?= !empty($row['area_of_development']) ? $row['area_of_development'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"> <?= !empty($row['progress_achieved']) ? $row['progress_achieved'] : '' ?></td>
                          
                        </tr>
                        <?php } } ?>
                          
                    </table>
                    <!-- End ACHIEVE ECONOMIC WELLBEING-->
                    <table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>
                     <!-- Start CARE SUMMARY -->
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="3" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">CARE SUMMARY</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;"> 
                           <?= !empty($edit_data[0]['care_summary']) ? $edit_data[0]['care_summary'] : '' ?>
                          </td>
                        </tr>
                    </table>
                    <!-- End CARE SUMMARY-->
                    <table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>
                     <!-- Start THERAPY -->
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="3" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">THERAPY</td>
                        </tr>
                       
                        
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">  Attendance</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;"> 
                          <?= !empty($edit_data[0]['attendance']) ? $edit_data[0]['attendance'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">After Engagement</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['engagement']) ? $edit_data[0]['engagement'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">  Areas of focus</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['areas_of_focus']) ? $edit_data[0]['areas_of_focus'] : '' ?>
                          </td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td colspan="3" style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Progress</td>
                        </tr>
                       <tr>
                          <td colspan="3" style="font-size: 15px;padding: 5px 10px;">
                            <?= !empty($edit_data[0]['progress']) ? $edit_data[0]['progress'] : '' ?>
                          </td>
                        </tr>
                       
                    </table>
                    <!-- End THERAPY-->
                    <!-- End ACHIEVE ECONOMIC WELLBEING-->
                    <table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>
                     <!-- Start CARE PLAN TARGETS IDENTIFIED FOR THE NEXT 12WEEKS -->
                    <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
                        <tr style="background-color: #4e6031;color: #ffffff;"> 
                          <td colspan="3" style="font-size: 20px; margin:0;text-align:left;line-height: 15px;height: auto;font-weight: bold;padding: 5px 10px;">CARE PLAN TARGETS IDENTIFIED FOR THE NEXT 12WEEKS</td>
                        </tr>
                        <tr style="background-color: #cccccc;">
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Care plan target</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Achieved/Ongoing/Outstanding</td>
                          <td style="font-size: 15px;font-weight: bold;padding: 5px 10px;">Evidence of how this has been achieved / reasons why it has not been achieved</td>
                        </tr>
                        <?php if (!empty($care_plan_target_week)) {
                                  foreach ($care_plan_target_week as $row) {
                              ?>

                        <tr>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_title']) ? $row['care_plan_target_title'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_select']) ? $row['care_plan_target_select'] : '' ?></td>
                          <td style="font-size: 15px;padding: 5px 10px;"><?= !empty($row['care_plan_target_reason']) ? $row['care_plan_target_reason'] : '' ?></td>
                        </tr>
                         <?php }
                        }
                        ?>   
                    </table>
                    <!-- End CARE PLAN TARGETS IDENTIFIED FOR THE NEXT 12WEEKS-->
                     <table width="100%">
                      <tr>
                      <td>
                      <p style="text-align: center;"><em>&nbsp;</em></p>
                      </td>
                      </tr>
                      </table>
                      <?php  if (!empty($ra_form_data) && $ra_form_data[0]['type'] != 'hidden') { ?>
                      <table width="100%" style=" margin: 0; border-collapse: collapse;">
                        <thead>
                           <tr style="background-color: #4e6031;">
                              <th width="15%" style="font-size: 13px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">Date</th>
                              <th width="70%" style="font-size: 13px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">Details</th>
                              <th width="15%" style="font-size: 13px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">Staff Initials</th>
                           </tr>
                        </thead>
                     </table>

                    <?php
           
                foreach ($ra_form_data as $row) { 

                  if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {

                     if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 

                                      $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                      ?>
                               

                  
                     <table width="100%" style=" margin: 0; border-collapse: collapse;">
                            <tbody>
                           <tr>
                              <td width="15%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                 <p>
                                    
                                    <?= !empty($data_textarea) ? configDateTime($edit_data[0]['modified_date']) : '' ?>
                                 </p>
                              </td>
                              <td width="70%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%">
                                  <tr>
                                    <td align="center">
                                      <b><?= !empty($row['label']) ? $row['label'] : '' ?>:</b>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td>
                                      <?php if($row['subtype'] == 'time'){ ?>
                                         <?= !empty($data_textarea) ? nl2br(timeformat($data_textarea)) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                      <?php }elseif($row['type'] == 'date'){?>
                                         <?= !empty($data_textarea) ? nl2br(configDateTime($data_textarea)) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                      <?php }else{ ?>
                                         <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>
                                      <?php } ?>
                                      
                                      
                                    </td>
                                  </tr>
                                </table>
                              </td>
                              <td width="15%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                 <p>
                                     <?= !empty($data_textarea) ? $edit_data[0]['create_name'] : '' ?>
                                 </p>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                     <?php
                    }  
                } //foreach
            }
          }
            ?>
<table width="100%">
<tr>
<td>
<p style="text-align: center;"><em>&nbsp;</em></p>
</td>
</tr>
</table>                                              
<table width="100%" style=" margin: 0; border-collapse: collapse;">
    <tbody>
        <tr>
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                <p>
                    Created By:
                </p>
            </td>
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                <p><?= !empty($edit_data[0]['create_name']) ? $edit_data[0]['create_name'] : '' ?> </p>
            </td>
        </tr>
         
        
    </tbody>
</table>   
