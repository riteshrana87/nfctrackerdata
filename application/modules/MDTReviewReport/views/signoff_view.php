<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var mdt_report_id = '<?=!empty($edit_data[0]['mdt_report_id'])?$edit_data[0]['mdt_report_id']:''?>';   
    var edit_data = '<?=!empty($item_details)?$item_details:''?>';    
    var edit_protocols_details = '<?=!empty($protocols_details)?$protocols_details:''?>';    


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
		<div class="pull-right">
		<?php if($external_view!=1) {?>
                <div class="btn-group">
                   <a href="<?= base_url('MDTReviewReport/DownloadPDF_after_mail/' . $ypid . '/' . $signoff_id); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Generate PDF </a>
                </div>
		<?php }?>
            </div>
		
            MDT Review Report <small>New Forest Care</small>
            <?php if(!isset($key_data)) { ?>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="<?=base_url('MDTReviewReport/external_approve/'.$mdt_report_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>

                </div>
            </div>
            <?php } ?>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>Report Start Date </h2>
                        <div class="form-group box_sty">
                        <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff(configDateTime($prev_edit_data[0]['report_start_date']), configDateTime($edit_data[0]['report_start_date']));
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?=(!empty($edit_data[0]['report_start_date']) && $edit_data[0]['report_start_date'] !='0000-00-00')?configDateTime($edit_data[0]['report_start_date']):''?>
                             <?php } ?>
                           
                            
                        </div>
                    </div>
                 </div> <!-- panel body over -->            
            </div><!-- Report Start Date over --> 
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>Report End Date </h2>
                        <div class="form-group box_sty">
                        <?php if (!empty($prev_edit_data)) {

                            $diff = new HtmlDiff(configDateTime($prev_edit_data[0]['report_end_date']), configDateTime($edit_data[0]['report_end_date']));
                            $diff->build();
                            echo $diff->getDifference();
                            ?>
                         <?php } else { ?>
                                <?=(!empty($edit_data[0]['report_end_date']) && $edit_data[0]['report_end_date'] !='0000-00-00')?configDateTime($edit_data[0]['report_end_date']):''?>
                         <?php } ?>
                          
                            
                        </div>
                    </div>
                 </div> <!-- panel body over -->            
            </div><!-- Report End Date over  --> 
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>Social Worker Name </h2>
                        <div class="form-group box_sty">
                         <?php if (!empty($prev_edit_data)) {

                            $diff = new HtmlDiff($prev_edit_data[0]['social_worker'], $edit_data[0]['social_worker']);
                            $diff->build();
                            echo $diff->getDifference();
                            ?>
                         <?php } else { ?>
                                <?= !empty($edit_data[0]['social_worker']) ? $edit_data[0]['social_worker'] : $YP_details[0]['social_worker_firstname'].' '. $YP_details[0]['social_worker_surname'] ?>
                         <?php } ?>
                           
                            
                        </div>
                    </div>
                 </div> <!-- panel body over -->            
            </div><!-- Social Worker Name over --> 
            <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body1">
                                <h2>Placing Authority </h2>
                                <div class="form-group box_sty">
                                <?php if (!empty($prev_edit_data)) {

                                    $diff = new HtmlDiff($prev_edit_data[0]['placing_authority'], $edit_data[0]['placing_authority']);
                                    $diff->build();
                                    echo $diff->getDifference();
                                    ?>
                                 <?php } else { ?>
                                        <?= !empty($edit_data[0]['placing_authority']) ? $edit_data[0]['placing_authority'] : $YP_details[0]['authority'] ?>
                                 <?php } ?>
                                    
                                    
                                </div>
                            </div>
                         </div> <!-- panel body over -->            
             </div><!-- Placing authority over -->
             <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>Case Manager </h2>
                        <div class="form-group box_sty">
                            <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['case_manager_name'], $edit_data[0]['case_manager_name']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['case_manager_name']) ? $edit_data[0]['case_manager_name'] : '' ?>
                             <?php } ?>
                        </div>
                    </div>
                 </div> <!-- panel body over -->            
             </div><!-- Case Manager over --> 
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>CARE PLAN TARGETS IDENITIFIED FROM LAC/CLA REVIEW</h2>
                        <div class="mdt_report_idable_hd col-xs-12 mdt_table_hd">
                          <div class="row">
                            <div class="col-sm-4 add_items_field">
                              <strong>Care plan target</strong>
                            </div>
                            <div class="col-sm-4 add_items_field">
                              <strong>Achieved/Ongoing/Outstanding</strong>

                            </div>
                            <div class="col-sm-4 add_items_field">
                              <strong>Evidence of how this has been achieved / reasons why it has not been achieved</strong>
                            </div>
                          </div>
                          </div>
                        <?php if (!empty($care_plan_target)) {$n= 0;
                                                    foreach ($care_plan_target as $row) {
                                                ?>

                                 <div class="delet_bottom form-group col-xs-12 box_sty newrow" id="cpt_review_edit_<?= $row['cpt_id'] ?>">
                                 <div class="row">
                                   <div class="col-sm-4 add_items_field mt30 ">
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

                                   </div>
                                   <div class="col-sm-4 add_items_field mt30 ">
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
                                   </div>
                                   <div class="col-sm-4 add_items_field mt30 ">
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

                                   </div>
                                 </div>

                                 </div>
                            <?php $n++;}
                        }
                        ?>   
                        
                    </div>
                </div>
            </div>     
            <!-- CPT PREVIOUS start --> 
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>CARE PLAN TARGET FROM PREVIOUS MDT REVIEW</h2>
                        <div class="mdt_report_idable_hd col-xs-12 mdt_table_hd">
                          <div class="row">
                            <div class="col-sm-4 add_items_field">
                              <strong>Care plan target</strong>
                            </div>
                            <div class="col-sm-4 add_items_field">
                              <strong>Achieved/Ongoing/Outstanding</strong>

                            </div>
                            <div class="col-sm-4 add_items_field">
                              <strong>Evidence of how this has been achieved / reasons why it has not been achieved</strong>
                            </div>
                          </div>
                          </div>
                        <?php if (!empty($care_plan_target_previous)) {$n= 0;
                            foreach ($care_plan_target_previous as $row) {
                        ?>

                                 <div class="delet_bottom form-group col-xs-12 box_sty newrow" id="cpt_review_edit_<?= $row['cpt_previous_id'] ?>">
                                 <div class="row">
                                   <div class="col-sm-4 add_items_field mt30 ">
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

                                   </div>
                                   <div class="col-sm-4 add_items_field mt30 ">
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


                                   </div>
                                   <div class="col-sm-4 add_items_field mt30 ">
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

                                   </div>
                                 </div>

                                 </div>
                            <?php $n++;}
                        }
                        ?>   
                        
                    </div>
                </div>
            </div>
            <!-- CPT PREVIOUS over -->            
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>Health</h2>
                      <div class="form-group">
                            <div class="col-xs-12 mdt_table_hd">
                              <div class="row">
                                <div class="col-sm-4 add_items_field ">
                                  <strong>Health Appointments Attended</strong>
                                </div>
                                <div class="col-sm-4 add_items_field ">
                                  <strong>Date</strong>

                                </div>
                                <div class="col-sm-4 add_items_field ">
                                  <strong>Outcome/ Actions</strong>
                                </div>
                              </div>
                              </div>
                        <div class="clearfix"></div>
                             <?php if (!empty($appointments)) {
                                        foreach ($appointments as $row) {
                                    ?>

                                     <div class="delet_bottom col-xs-12 box_sty newrow">
                                       <div class="row">
                                         <div class="col-sm-4 add_items_field mt30 ">
                                           <?= !empty($row['mp_name']) ? $row['mp_name'] : '' ?>
                                         </div>
                                         <div class="col-sm-4 add_items_field mt30 ">
                                           <?=(!empty($row['appointment_date']) && $row['appointment_date'] !='0000-00-00')?configDateTime($row['appointment_date']):''?>

                                         </div>
                                         <div class="col-sm-4 add_items_field mt30 ">
                                           <?= (!empty($row['comments'])) ? ((strlen ($row['comments']) > 50) ? $substr = substr (trim(strip_tags($row['comments'])), 0, 50) . '...<a data-href="'.base_url('Medical'.'/readmore_appointment/'.$row['appointment_id']).'/comments" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($row['comments']))):'' ?>
                                         </div>
                                       </div>
                                       </div>
                                <?php }
                            }
                            ?>
                        <div class="clearfix"></div>
                      </div>
                           <div class="panel panel-default tile tile-profile">
                             <div class="panel-body1">
                                <h2>Diet</h2>
                                <div class="form-group">
                                    <label>Average of ‘<?=$diet_avg?> a day’ consumed </label>
                                    <div class ="form-group box_sty">
                                        <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['average_days_consumed'], $edit_data[0]['average_days_consumed']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['average_days_consumed']) ? $edit_data[0]['average_days_consumed'] : '' ?>
                                     <?php } ?>
                                       
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label>Comments/ Points For Consideration:  </label>
                                    <div class ="form-group box_sty">
                                    <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['comments_points'], $edit_data[0]['comments_points']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['comments_points']) ? $edit_data[0]['comments_points'] : '' ?>
                                     <?php } ?>
                                        
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="col-sm-6 p-l-0">
                                    <label>Regular Hobbies / Clubs Attended</label>
                                    </div>
                                    <div class="col-sm-6">
                                    <label>Duration per week (hours/minutes)</label>
                                    </div>
                                    
                                    
                                </div>
                                <?php if (!empty($hobbies_data)) { $n= 0;
                                        foreach ($hobbies_data as $row) {
                                    ?> 
                                    <div class="form-group ">
                                        <div class="col-sm-6 p-l-0">
                                        <div class ="form-group box_sty">
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
                                            
                                                
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                        <div class ="form-group box_sty">
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
                                        
                                            
                                        </div>
                                        </div>
                                        
                                    </div>
                                    <?php $n++;} } ?>
                                    <div class="form-group">
                                        <div class="col-sm-6 p-l-0">
                                        <label>Physical Exercise Completed</label>
                                        </div>
                                        <div class="col-sm-6">
                                        <label>Duration per week (hours/minutes)</label>
                                        </div>
                                        
                                        
                                    </div>
                                    <?php if (!empty($physical_exercise_data)) {$n= 0;
                                            foreach ($physical_exercise_data as $row) {
                                        ?> 
                                        <div class="form-group">
                                            <div class="col-sm-6 p-l-0">
                                            <div class ="form-group box_sty">
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
                                            
                                                
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class ="form-group box_sty">
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
                                            </div>
                                            </div>
                                            
                                        </div>
                                    <?php $n++;} } ?>
                            </div>
                           </div>
                            </div>
                     </div> <!-- panel body over -->
                  
                        
            </div><!-- end BE HEALTHY -->
            <!-- start stay safe -->
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>Emotional and Behavioural Development</h2>
                        <div class="form-group">
                          <div class="level1 col-sm-12">There <?=(!empty($incident_level[0]['level1']) && $incident_level[0]['level1'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level1'])?$incident_level[0]['level1']:'0'?></span> incident of level1. </div>
                          <div class="level2 col-sm-12">There <?=(!empty($incident_level[0]['level2']) && $incident_level[0]['level2'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level2'])?$incident_level[0]['level2']:'0'?></span> incident of level2. </div>
                          <div class="level3 col-sm-12">There <?=(!empty($incident_level[0]['level3']) && $incident_level[0]['level3'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level3'])?$incident_level[0]['level3']:'0'?></span> incident of level3. </div>
                          <div class="level4 col-sm-12">There <?=(!empty($incident_level[0]['level4']) && $incident_level[0]['level4'] >1)?'are':'is'?>  <span><?=!empty($incident_level[0]['level4'])?$incident_level[0]['level4']:'0'?></span> incident of level4. </div>
                      </div>    
                        <div class="form-group ">
                            <div class="mdt_table_hd col-xs-12">
                              <div class="row">
                                <div class="col-sm-3">
                                  <label>Incident summary (Include the date) </label>
                                </div>
                                <div class="col-sm-2">
                                  <label>Level 1(incident requiring no physical intervention)</label>
                                </div>
                                <div class="col-sm-2">
                                  <label>Level 2(incident requiring physical intervention up to and including seated holds)</label>
                                </div>
                                <div class="col-sm-2">
                                  <label>Level 3(incident requiring physical intervention including ground holds)</label>
                                </div>
                                <div class="col-sm-2">
                                  <label>Level 4(Missing from care / absent without authority)</label>
                                </div>
                                <div class="col-sm-1">
                                  <label></label>
                                </div>
                              </div>
                              </div>
                            <div class="clearfix"></div>
                            <div class="" id="">
                            <?php if (!empty($incident_data)) {$n= 0;
                                    foreach ($incident_data as $row) {
                                ?> 
                                <div class="box_sty col-xs-12" id="incident_edit_<?= $row['incident_id'] ?>">
                                <div class="row">
                                  <div class="col-sm-3">
                                    <div class ="">
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
                                    </div>
                                  </div>
                                  <div class="col-sm-2">
                                    <?php $lchange = (!empty($incident_item_archive[$n]['level']) && $incident_item_archive[$n]['incident_id'] == $row['incident_id'] && $incident_item_archive[$n]['level'] != $row['level'])?'1':'' ?>
                                    <div class ="">
                                      <?= (!empty($row['level']) && $row['level'] == 1) ? 'X' : ''?>
                                    </div>
                                  </div>
                                  <div class="col-sm-2">
                                    <div class ="">
                                      <?= (!empty($row['level']) && $row['level'] == 2) ? 'X' : ''?>
                                    </div>
                                  </div>
                                  <div class="col-sm-2">
                                    <div class ="">
                                      <?= (!empty($row['level']) && $row['level'] == 3) ? 'X' : ''?>
                                    </div>
                                  </div>
                                  <div class="col-sm-2">
                                    <div class ="">
                                      <?= (!empty($row['level']) && $row['level'] == 4) ? 'X' : ''?>
                                    </div>
                                  </div>
                                </div>
                                </div>
                                <div class="clearfix"></div>
                                <?php $n++;} } ?>
                            </div>
                          
                        </div>
                        <div class="panel panel-default tile tile-profile">
                          <div class="panel-body1">
                            <h3>Sanctions</h3>
                            <div class="mdt_table_hd col-xs-12">
                              <div class="row">
                                <div class="col-sm-4">
                                  <label>Reason for Sanction</label>
                                </div>
                                <div class="col-sm-2">
                                  <label>Date</label>
                                </div>
                                <div class="col-sm-4">
                                  <label>Sanction Imposed</label>
                                </div>
                              </div>
                              </div>
                            <div id="">
                            <?php if (!empty($sanction_data)) {$n= 0;
                                    foreach ($sanction_data as $row) {
                                ?> 
                                <div class="col-xs-12 box_sty" id="sanction_edit_<?= $row['sanction_id'] ?>">
                                <div class="row">
                                  <div class="col-sm-4">
                                    <div class ="form-group ">
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

                                    </div>
                                  </div>
                                  <div class="col-sm-2">
                                    <div class="">
                                      <div class ="form-group ">
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

                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                    <div class ="form-group ">
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

                                    </div>
                                  </div>
                                </div>
                                </div>
                                <?php $n++;} } ?>
                            </div>
                          </div>
                    </div>
                    <div class="clearfix"></div>
                      <div class="form-group">
                            <label>Safeguarding Concerns</label>
                      <div class="form-group box_sty">
                            <div class ="slimScroll-120">
                             <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['safeguarding'], $edit_data[0]['safeguarding']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['safeguarding']) ? $edit_data[0]['safeguarding'] : '' ?>
                             <?php } ?>
                            
                            </div>
                      </div>
                    </div>
                    <div class="form-group">
                            <label>General behaviour</label>
                      <div class="form-group box_sty">
                            <div class ="slimScroll-120">
                            <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['general_behaviour'], $edit_data[0]['general_behaviour']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['general_behaviour']) ? $edit_data[0]['general_behaviour'] : '' ?>
                             <?php } ?>
                            
                            </div>
                      </div>
                    </div>
                    <div class="form-group">
                            <label>Concerns</label>
                      <div class="form-group box_sty">
                            <div class ="slimScroll-120">
                            <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['concerns'], $edit_data[0]['concerns']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['concerns']) ? $edit_data[0]['concerns'] : '' ?>
                             <?php } ?>
                            
                            </div>
                      </div>
                    </div>
                    <div class="form-group">
                            <label>Bullying Issues/ Concerns</label>
                      <div class="form-group box_sty">
                            <div class ="slimScroll-120">
                            <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['bullying_issues'], $edit_data[0]['bullying_issues']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['bullying_issues']) ? $edit_data[0]['bullying_issues'] : '' ?>
                             <?php } ?>
                            </div>
                            </div>
                    </div>
                    <div class="form-group">
                            <label>Significant events</label>
                      <div class="form-group box_sty">
                            <div class ="slimScroll-120">
                            <?php if (!empty($prev_edit_data)) {

                                $diff = new HtmlDiff($prev_edit_data[0]['significant_events'], $edit_data[0]['significant_events']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                             <?php } else { ?>
                                    <?= !empty($edit_data[0]['significant_events']) ? $edit_data[0]['significant_events'] : '' ?>
                             <?php } ?>
                            </div>
                            </div>
                    </div>
                 </div>           
                </div><!-- panel body over -->  
            </div><!-- end stay safe -->
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>Education, Achievements and Social Skills</h2>  
                         <div class="panel panel-default tile tile-profile">
                           <div class="panel-body1">  
                                <h2>Educational Attendance</h2>
                                <div class="mdt_table_hd col-xs-12 ">
                                  <div class="row">
                                    <div class="col-sm-4">
                                      <label>Percentage of Attendance</label>
                                    </div>
                                    <div class="col-sm-4">
                                      <label>Number of Referrals (Pink and Blue)</label>
                                    </div>
                                    <div class="col-sm-4">
                                      <label>Achievements Student of the Week</label>
                                    </div>
                                  </div>
                                </div>
                                <div class="box_sty col-xs-12">
                                  <div class="row">
                                    <div class="col-sm-4">
                                        <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['per_of_attendance'], $edit_data[0]['per_of_attendance']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                        <?php } else { ?>
                                        <?= !empty($edit_data[0]['per_of_attendance']) ? $edit_data[0]['per_of_attendance'] : '' ?>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0]['number_of_referrals'], $edit_data[0]['number_of_referrals']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                        <?php } else { ?>
                                        <?=!empty($edit_data[0]['number_of_referrals']) ? $edit_data[0]['number_of_referrals'] : '' ?>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0]['achievements'], $edit_data[0]['achievements']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                        <?php } else { ?>
                                        <?= !empty($edit_data[0]['achievements']) ? $edit_data[0]['achievements'] : '' ?>
                                        <?php } ?>
                                    </div>
                                  </div>
                                  </div>
                         </div>
                         </div>
                         <div class="clearfix"></div>
                      <div class="panel panel-default tile tile-profile">
                        <div class="panel-body1">
                                <h2>Banding System</h3>
                                <div class="form-group">
                                  <label>Average Pocket Money Achieved</label>
                                  <div class="form-group box_sty">
                                            <?php if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0]['average_pocket'], $edit_data[0]['average_pocket']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                                    <?= !empty($edit_data[0]['average_pocket']) ? $edit_data[0]['average_pocket'] : '' ?>
                                             <?php } ?>
                                            </div>
                                    </div>
                                    
                                </div>
                               </div>
                        <div class="form-group">
                            <label>Emotional / Social Development</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['emotional'], $edit_data[0]['emotional']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['emotional']) ? $edit_data[0]['emotional'] : '' ?>
                                     <?php } ?>
                                    
                                </div>
                          </div>
                        </div>

                        <div class="form-group">
                                <label>Evidence of Positive Relationships</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {
                                        $diff = new HtmlDiff($prev_edit_data[0]['positive_relationships'], $edit_data[0]['positive_relationships']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['positive_relationships']) ? $edit_data[0]['positive_relationships'] : '' ?>
                                     <?php } ?>
                                    
                                </div>
                          </div>
                        </div>
                      
                        <div class="form-group">
                                <label>Contact</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['contact'], $edit_data[0]['contact']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['contact']) ? $edit_data[0]['contact'] : '' ?>
                                     <?php } ?>
                                    
                                </div>
                          </div>
                        </div>
                        <div class="form-group">
                                <label>Peer relationships</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['peer_relationships'], $edit_data[0]['peer_relationships']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['peer_relationships']) ? $edit_data[0]['peer_relationships'] : '' ?>
                                     <?php } ?>
                                    </div>
                                </div>
                        </div>
                        <div class="form-group">
                                <label>Cultural Needs</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                        $diff = new HtmlDiff($prev_edit_data[0]['cultural_needs'], $edit_data[0]['cultural_needs']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['cultural_needs']) ? $edit_data[0]['cultural_needs'] : '' ?>
                                     <?php } ?>
                                    </div>
                                </div>
                        </div>
                        <div class="form-group">
                                <label>Evidence of Positive Decision Making</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['positive_decision'], $edit_data[0]['positive_decision']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['positive_decision']) ? $edit_data[0]['positive_decision'] : '' ?>
                                         <?php } ?>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <label>After School Clubs</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['school_clubs'], $edit_data[0]['school_clubs']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['school_clubs']) ? $edit_data[0]['school_clubs'] : '' ?>
                                         <?php } ?>
                                </div>    
                                </div>
                        </div>
                        <div class="form-group">
                                <label>Evidencing the 24hour Curriculum</label>
                          <div class="form-group box_sty">
                          <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['evidencing_curriculum'], $edit_data[0]['evidencing_curriculum']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['evidencing_curriculum']) ? $edit_data[0]['evidencing_curriculum'] : '' ?>
                                         <?php } ?>
                                </div>    
                                </div>
                        </div>
                        <div class="form-group">
                                <label>Part-time / Voluntary Work</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['voluntary_work'], $edit_data[0]['voluntary_work']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['voluntary_work']) ? $edit_data[0]['voluntary_work'] : '' ?>
                                         <?php } ?>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div> <!-- panel body over -->            
            </div><!-- ENJOY AND ACHIEVE over -->
            
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>ACHIEVE ECONOMIC WELLBEING </h2>
                            
                        <div class="form-group ">
                           <h3>Life Skills Development</h3>
                            <div class="form-group mdt_table_hd col-xs-12">
                                <div class="col-sm-5 p-l-0">
                                <label>Area of Development </label>
                                </div>
                                <div class="col-sm-5">
                                <label>Progress achieved/ Action Required </label>
                                </div>
                                <div class="col-sm-2">
                                <label></label>
                                </div>
                                
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-xs-12 box_sty" id="">
                            <?php if (!empty($life_skills_data)) {$n= 0;
                                    foreach ($life_skills_data as $row) {
                                ?> 
                                <div class="form-group " id="life_skills_edit_<?= $row['life_skills_id'] ?>">
                                    <div class="col-sm-5">
                                    <div class ="form-group">
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
                                        
                                    </div> 
                                    </div>
                                    <div class="col-sm-5">
                                    <div class ="form-group ">
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
                                       
                                    </div> 
                                    </div>
                                    
                                  
                                </div>
                                <?php $n++;} } ?>
                            </div>
                           </div>
                    </div>
                 </div> <!-- panel body over -->            
            </div><!-- ACHIEVE ECONOMIC WELLBEING over --> 
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>CARE SUMMARY </h2>
                        <div class="form-group box_sty">
                            <div class ="slimScroll-120">
                                <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['care_summary'], $edit_data[0]['care_summary']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['care_summary']) ? $edit_data[0]['care_summary'] : '' ?>
                                         <?php } ?>
                                
                            </div>
                        </div>
                    </div>
                 </div> <!-- panel body over -->            
            </div><!-- CARE SUMMARY over --> 
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>THERAPY</h2>  
                        <div class="form-group">
                                <label>Attendance</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['attendance'], $edit_data[0]['attendance']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['attendance']) ? $edit_data[0]['attendance'] : '' ?>
                                         <?php } ?>
                                    </diV>
                                </div>
                        </div>
                        <div class="form-group">
                                <label>Engagement</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['engagement'], $edit_data[0]['engagement']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['engagement']) ? $edit_data[0]['engagement'] : '' ?>
                                         <?php } ?>
                                    </div>
                                </div>
                        </div>
                        <div class="form-group">
                                <label>Areas of focus</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['areas_of_focus'], $edit_data[0]['areas_of_focus']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                                <?= !empty($edit_data[0]['areas_of_focus']) ? $edit_data[0]['areas_of_focus'] : '' ?>
                                         <?php } ?>
                                    </div>
                                </div>
                        </div>
                        <div class="form-group">
                                <label>Progress</label>
                          <div class="form-group box_sty">
                                <div class ="slimScroll-120">
                                    <?php if (!empty($prev_edit_data)) {

                                            $diff = new HtmlDiff($prev_edit_data[0]['progress'], $edit_data[0]['progress']);
                                            $diff->build();
                                            echo $diff->getDifference();
                                            ?>
                                         <?php } else { ?>
                                               <?= !empty($edit_data[0]['progress']) ? $edit_data[0]['progress'] : '' ?>
                                         <?php } ?>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div> <!-- panel body over -->            
            </div><!-- THERAPY over --> 
            <!-- CPT WEEK start --> 
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body1">
                        <h2>CARE PLAN TARGETS IDENTIFIED FOR THE NEXT 12WEEKS</h2>
                        <div class="mdt_report_idable_hd col-xs-12 mdt_table_hd">
                          <div class="row">
                            <div class="col-sm-4 add_items_field">
                              <strong>Care plan target</strong>
                            </div>
                            <div class="col-sm-4 add_items_field">
                              <strong>Achieved/Ongoing/Outstanding</strong>

                            </div>
                            <div class="col-sm-4 add_items_field">
                              <strong>Evidence of how this has been achieved / reasons why it has not been achieved</strong>
                            </div>
                          </div>
                          </div>
                        <?php if (!empty($care_plan_target_week)) {$n= 0;
                            foreach ($care_plan_target_week as $row) {
                        ?>

                                 <div class="delet_bottom form-group col-xs-12 box_sty newrow" id="cpt_review_edit_<?= $row['cpt_week_id'] ?>">
                                 <div class="row">
                                   <div class="col-sm-4 add_items_field mt30 ">
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

                                   </div>
                                   <div class="col-sm-4 add_items_field mt30 ">
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


                                   </div>
                                   <div class="col-sm-4 add_items_field mt30 ">
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

                                   </div>
                                 </div>

                                 </div>
                            <?php $n++;}
                        }
                        ?>   
                        
                    </div>
                </div>
            </div>
            <!-- CPT WEEK over --> 
            
                    <?php
            if (!empty($form_data)) {
                foreach ($form_data as $row) {

                    if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
                        <div class="<?= ($row['type'] == 'header') ? 'col-sm-12' : 'col-sm-12' ?>">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body1">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class ="slimScroll-120">
                                        <?php
                                        if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { ?>
                                            <?php if($row['subtype'] == 'time'){ ?>
                                            <?php 
                                            if (!empty($prev_edit_data)) {
                                                $diff = new HtmlDiff(timeformat($prev_edit_data[0][$row['name']]), timeformat($edit_data[0][$row['name']]));
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? timeformat($edit_data[0][$row['name']]) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                            <?php } ?>
                                            <?php }elseif($row['type'] == 'date') { ?>
                                                <?php 
                                            if (!empty($prev_edit_data)) {
                                                $diff = new HtmlDiff(configDateTime($prev_edit_data[0][$row['name']]), configDateTime($edit_data[0][$row['name']]));
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? configDateTime($edit_data[0][$row['name']]) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                            <?php } ?>
                                            <?php }else{ ?>
                                                <?php 
                                            if (!empty($prev_edit_data)) {
                                                $diff = new HtmlDiff($prev_edit_data[0][$row['name']], $edit_data[0][$row['name']]);
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? html_entity_decode($edit_data[0][$row['name']]) : (isset($row['value']) ? $row['value'] : '') ?>
                                            <?php } ?>
                                            <?php } ?>

                                            

                                            <?php
                                        } else if ($row['type'] == 'checkbox-group') {
                                            if (!empty($edit_data[0][$row['name']])) {
                                                $chk = explode(',', $edit_data[0][$row['name']]);
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
                                        } else if ($row['type'] == 'select') {
                                            if (!empty($edit_data[0][$row['name']])) {
                                                echo!empty($edit_data[0][$row['name']]) ? nl2br(htmlentities($edit_data[0][$row['name']])) : '';
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

                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'radio-group') {
                        ?>
                        <div class="<?= ($row['type'] == 'header') ? 'col-sm-12' : 'col-sm-12' ?>">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body1">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <?php
                                    if (!empty($edit_data[0][$row['name']])) {
                                        if($edit_data[0][$row['name']] == 'high'){
                                            $class_name = "label-danger";
                                        }else if($edit_data[0][$row['name']] == 'low'){
                                            $class_name = "label-success";    
                                        }else if($edit_data[0][$row['name']] == 'Medium'){
                                                $class_name = "label-warning";  
                                        }

                                        echo ($edit_data[0][$row['name']] == 'high') ? '<span class="label label-danger">High</span>' : (($edit_data[0][$row['name']] == 'Medium') ? '<span class="label label-warning">Medium</span>' : (($edit_data[0][$row['name']] == 'low') ? '<span class="label label-success">Low</span>' : '<span class="label label-danger">' . $edit_data[0][$row['name']] . '</span>'));
                                    } else {
                                        if (count($row['values']) > 0) {

                                            foreach ($row['values'] as $chked) {
                                                echo isset($chked['selected']) ? ($chked['value'] == 'high') ? '<span class="label label-danger">High</span>' : (($chked['value'] == 'Medium') ? '<span class="label label-warning">Medium</span>' : '<span class="label label-success">Low</span>') : '';
                                            }
                                        }
                                    }
                                    ?>
                                    <!--code added for green edit-->
                                    <?php
                                    if (!empty($prev_edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])) {
                                        if ($prev_edit_data[0][$row['name']] != $edit_data[0][$row['name']]) {
                                            ?>
                                            <span class="text-success">&nbsp;<i class="fa fa-edit" aria-hidden="true"></i></span>
                                    <?php } } ?>
                                    <!--code added for green check ends here-->
                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'header') {
                        ?>
                        <div class="col-sm-12">
                            <div class="">
                                <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                <?php echo '<' . $head . ' class="page-title">'; ?>
                                <?= !empty($row['label']) ? $row['label'] : '' ?>
                                <?php echo '</' . $head . '>'; ?>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'file') {
                        ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body1">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class="">   
                                        <?php
                                        /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                        $fileViewArray = array(
                                            'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                                            'filePathMain' => $this->config->item('mdt_img_base_url') . $ypid,
                                            'filePathThumb' => $this->config->item('mdt_img_base_url_small') . $ypid
                                        );
                                        echo getFileView($fileViewArray); 
                                        ?>                               
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } //foreach
            }
            ?>
            </div>    
            <div class="row">
            <?php 
             if (checkPermission('MDTReviewReport', 'signoff')) {
            if (!empty($signoff_data)) { ?>
            <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">

                        <div class="panel-body">
                            <h2>sign off</h2>
                            <?php
                                foreach ($signoff_data as $sign_name) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php } ?> 
                        </div>

                    </div>
                </div>
            <?php } }?>
            <?php if(isset($key_data)) { ?>
            <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">

                        <div class="panel-body">
                            <h2>User sign off</h2>
                                    <input type="hidden" id="email_data" value="<?php echo $key_data; ?>" name="email_data">
                                    <input type="checkbox" onclick="signoff_request(<?php echo $ypid . ',' . $signoff_id; ?>);" name="signoff_data" class="" value="active">
                            
                        </div>

                    </div>
            </div>
            <?php } ?>
            <?php if(!isset($key_data)) { ?>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="<?=base_url('MDTReviewReport/external_approve/'.$mdt_report_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>

                </div>
            </div>
            <?php } ?>
        </div>
        </div>
       
    </div>
</div>