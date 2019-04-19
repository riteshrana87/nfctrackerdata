<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?=!empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:$ypid?>';
    var mdt_report_id = '<?=!empty($edit_data[0]['mdt_report_id'])?$edit_data[0]['mdt_report_id']:''?>';
    var care_plan_target = '<?=!empty($care_plan_target)?'1':''?>';   
    var hobbies_data = '<?=!empty($hobbies_data)?'1':''?>';   
    var physical_exercise_data = '<?=!empty($physical_exercise_data)?'1':''?>';   
    var incident_data = '<?=!empty($incident_data)?'1':''?>';   
    var sanction_data = '<?=!empty($sanction_data)?'1':''?>';   
    var life_skills_data = '<?=!empty($life_skills_data)?'1':''?>';
    var care_plan_target_week = '<?=!empty($care_plan_target_week)?'1':''?>';  
    var care_plan_target_previous = '<?=!empty($care_plan_target_previous)?'1':''?>';   
    
</script>

<div id="page-wrapper">
<form action="<?=base_url($this->viewname.'/insert')?>" method="post" id="mdtform" name="mdtform" data-parsley-validate enctype="multipart/form-data">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            MDT <small> New Forest Care</small>
                <div class="pull-right for-tab">
                    <div class="btn-group">
                <button type="submit" class="btn btn-default"  onclick="needToConfirm = false;" name="submit_mdtform" id="submit_mdtform" value="submit" style="default"><?=!empty($edit_data[0]['yp_id'])?'UPDATE':'ADD'?></button>
                      <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="btn btn-default"  style="default">Back To YP Info</a>
                      <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">CARE HOME YP LIST</a>
                <?php if(!empty($edit_data)) { ?> 
                <?php if(checkPermission('MDTReviewReport','view') && $edit_data[0]['draft'] == 0){ ?>
                    <a href="<?=base_url('MDTReviewReport/view/'.$edit_data[0]['mdt_report_id'].'/'.$edit_data[0]['yp_id']); ?>" class="btn btn-default width_a">
                            <i class="fa fa-mail-reply"></i> Return To Current MDT
                        </a>
                    <?php } ?>
                    <?php if($edit_data[0]['draft'] == 1){ ?>
                    <a href="<?=base_url('MDTReviewReport/index/'.$ypid)?>" class="btn btn-default"  style="default"><i class="fa fa-mail-reply"></i> Back To MDT</a>
                    <?php } ?>
                    <?php } else { ?> 
                    <a href="<?=base_url('MDTReviewReport/index/'.$ypid)?>" class="btn btn-default"  style="default"><i class="fa fa-mail-reply"></i> Back To MDT</a>
                    <?php } ?>
                    
                </div>
                </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            
                
                     <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Report Start Date <span class="astrick">*</span></h2>
                                <div class="form-group">
                                    <div class="input-group input-append date add_start_date_mdt col-sm-4">
                                               
                                                <input type="text" required="true" name="report_start_date" id="report_start_date" class="form-control" value="<?=(!empty($edit_data[0]['report_start_date']) && $edit_data[0]['report_start_date'] !='0000-00-00')?configDateTime($edit_data[0]['report_start_date']):''?>" autocomplete="true" readonly="" data-parsley-trigger="change" data-parsley-required-message="Please enter date." data-parsley-errors-container="#error_report_start_date" data-parsley-report_start_date>

                                                <div class="input-group-addon add-on">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <span id="error_report_start_date"></span>
                                    
                                </div>
                            </div>
                         </div> <!-- panel body over -->            
                    </div><!-- Social Worker Name over -->      
                     <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Report End Date <span class="astrick">*</span></h2>
                                <div class="form-group">
                                    <div class="input-group input-append date add_end_date_mdt col-sm-4">
                                                
                                                <input type="text" required="true" name="report_end_date" id="report_end_date" class="form-control" value="<?=(!empty($edit_data[0]['report_end_date']) && $edit_data[0]['report_end_date'] !='0000-00-00')?configDateTime($edit_data[0]['report_end_date']):''?>" autocomplete="true" readonly="" data-parsley-trigger="change" data-parsley-required-message="Please enter date." data-parsley-errors-container="#error_report_end_date" data-parsley-report_end_date>

                                                <div class="input-group-addon add-on">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <span id="error_report_end_date"></span>
                                    
                                </div>
                            </div>
                         </div> <!-- panel body over -->            
                    </div><!-- Social Worker Name over -->      
                     <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Social Worker Name <span class="astrick">*</span></h2>
                                <div class="form-group">
                                    <input type="text" class="form-control" required="true" name="social_worker" placeholder=""
                                        value="<?= !empty($edit_data[0]['social_worker']) ? $edit_data[0]['social_worker'] : $YP_details[0]['social_worker_firstname'].' '. $YP_details[0]['social_worker_surname'] ?>">
                                    
                                </div>
                            </div>
                         </div> <!-- panel body over -->            
                    </div><!-- Social Worker Name over -->                
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Placing Authority <span class="astrick">*</span></h2>
                                <div class="form-group">
                                    <input type="text" class="form-control" required="true" name="placing_authority" placeholder=""
                                        value="<?= !empty($edit_data[0]['placing_authority']) ? $edit_data[0]['placing_authority'] : $YP_details[0]['authority'] ?>">
                                    
                                </div>
                            </div>
                         </div> <!-- panel body over -->            
                    </div><!-- Placing Authority over --> 
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Case Manager <span class="astrick">*</span></h2>
                                <div class="form-group">
                                    <select class="chosen-select form-control" name="case_manager" id="type" required="true" data-parsley-errors-container="#error_case_manager" >
                                         <option value="">Select</option>
                                         <?php if(!empty($case_managers)){
                                            foreach ($case_managers as $row) { ?>
                                         <option <?=(!empty($edit_data[0]['case_manager']) && $edit_data[0]['case_manager'] == $row['login_id'])?'selected' : ''?> value="<?=$row['login_id']?>"><?=!empty($row['name'])?$row['name']:''?></option>
                                         <?php } } ?>
                                    </select>
                                    <span id="error_case_manager"></span>
                                </div>
                            </div>
                         </div> <!-- panel body over -->            
                    </div><!-- Placing Authority over --> 
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>CARE PLAN TARGETS IDENITIFIED FROM LAC/CLA REVIEW </h2>
                              <div class="row">
                                    <div class="form-group">
                                    <div class="col-lg-4 col-md-4 col-sm-3 add_items_field mt30 ">
                                         <strong>Care plan target</strong>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 add_items_field mt30 ">
                                        <strong>Achieved / Ongoing / Outstanding</strong>
                                      
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 add_items_field mt30 ">
                                        <strong>Evidence of how this has been achieved / reasons why it has not been achieved</strong>
                                    </div>
                                      
                                    
                                    </div>
                                </div>
                                   <div class="section_three">
                                    <div class="form-group row" id="add_cpt_review">
                                            <?php if (!empty($care_plan_target)) {
                                                            foreach ($care_plan_target as $row) {

                                                        ?> 
                                                <div class="delet_bottom form-group newrow" id="cpt_review_edit_<?= $row['cpt_id'] ?>">
                                                    <div class="col-lg-4 col-md-4 col-sm-3 form-group add_items_field mt30 ">
                                                      
                                                        <textarea class="form-control" name="cpt_title_<?= $row['cpt_id'] ?>" placeholder=""><?= !empty($row['care_plan_target_title']) ? $row['care_plan_target_title']: '' ?></textarea>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 form-group add_items_field mt30 ">
                                                        <?php 
                                                        $cpt_select = !empty($row['care_plan_target_select'])?explode(',',$row['care_plan_target_select']):'';
                                                        
                                                        ?>
                                                      
                                                        <select class="chosen-select form-control" name="cpt_select_<?= $row['cpt_id'] ?>" id="type" required="true">
                                                             <option value="">Select</option>
                                                             <option <?=(!empty($cpt_select) &&  in_array('Achieved',$cpt_select))?'selected' : ''?> value="Achieved">Achieved</option>
                                                             <option <?=(!empty($cpt_select) &&  in_array('Ongoing',$cpt_select))?'selected' : ''?>  value="Ongoing">Ongoing</option>
                                                             <option <?=(!empty($cpt_select) &&  in_array('Outstanding',$cpt_select))?'selected' : ''?> value="Outstanding">Outstanding</option>
                                                             
                                                              
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 form-group add_items_field mt30 ">
                                                      
                                                        <textarea class="form-control" name="cpt_reason_<?= $row['cpt_id'] ?>" placeholder=""><?= !empty($row['care_plan_target_reason']) ? $row['care_plan_target_reason'] : '' ?></textarea>
                                                    </div>
                                                      
                                                    <div class="col-md-1 col-sm-2 add_items_field mb44">
                                                        <a class="btn btn-default btn_border">
                                                            <span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row('cpt_review_edit_<?= $row['cpt_id'] ?>');"></span>
                                                        </a>
                                                    </div>
                                                    </div>
                                               
                                            
                                            <?php }} ?>
                                        </div>
                                    </div>
                            <div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_cpt_review_id" name="delete_cpt_review_id" value="">
                                <a id="add_new_cpt_review" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More CPT Review
                                </a>
                            </div>

                         </div> <!-- panel body over -->
                          
                                
                            </div>
                    </div>   

                                        <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                            <h2>CARE PLAN TARGET FROM PREVIOUS MDT REVIEW</h2>
                          <div class="row">
                                <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-3 add_items_field mt30 ">
                                     <strong>Care plan target</strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 add_items_field mt30 ">
                                    <strong>Achieved / Ongoing / Outstanding</strong>
                                  
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 add_items_field mt30 ">
                                    <strong>Evidence of how this has been achieved / reasons why it has not been achieved</strong>
                                </div>
                                  
                                
                                </div>
                            </div>
                               <div class="section_three">
                                <div class="form-group row" id="add_cpt_previous">
                                        <?php if (!empty($care_plan_target_previous)) {
                                                        foreach ($care_plan_target_previous as $row) {

                                                    ?> 
                                            <div class="delet_bottom form-group newrow" id="cpt_previous_edit_<?= $row['cpt_previous_id'] ?>">
                                                <div class="col-lg-4 col-md-4 col-sm-3 form-group add_items_field mt30 ">
                                                    <textarea class="form-control" name="cpt_previous_title_<?= $row['cpt_previous_id'] ?>" placeholder=""><?= !empty($row['care_plan_target_title']) ? $row['care_plan_target_title']: '' ?></textarea>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 form-group add_items_field mt30 ">
                                                    <?php 
                                                    $cpt_previous_select = !empty($row['care_plan_target_select'])?explode(',',$row['care_plan_target_select']):'';
                                                    
                                                    ?>
                                                    <select class="chosen-select form-control" name="cpt_previous_select_<?= $row['cpt_previous_id'] ?>" id="type" required="true">
                                                         <option value="">Select</option>
                                                         <option <?=(!empty($cpt_previous_select) &&  in_array('Achieved',$cpt_previous_select))?'selected' : ''?> value="Achieved">Achieved</option>
                                                         <option <?=(!empty($cpt_previous_select) &&  in_array('Ongoing',$cpt_previous_select))?'selected' : ''?>  value="Ongoing">Ongoing</option>
                                                         <option <?=(!empty($cpt_previous_select) &&  in_array('Outstanding',$cpt_previous_select))?'selected' : ''?> value="Outstanding">Outstanding</option>
                                                         
                                                          
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 form-group add_items_field mt30 ">
                                                    <textarea class="form-control" name="cpt_previous_reason_<?= $row['cpt_previous_id'] ?>" placeholder=""><?= !empty($row['care_plan_target_reason']) ? $row['care_plan_target_reason'] : '' ?></textarea>
                                                </div>
                                                  
                                                <div class="col-md-1 col-sm-2 add_items_field mb44">
                                                    <a class="btn btn-default btn_border">
                                                        <span class="glyphicon glyphicon-trash" onclick="delete_cpt_previous_row('cpt_previous_edit_<?= $row['cpt_previous_id'] ?>');"></span>
                                                    </a>
                                                </div>
                                                </div>
                                           
                                        
                                        <?php }} ?>
                                    </div>
                                </div>
                        <div class=" section_four text-center mb30">
                            <input type="hidden" id="delete_cpt_previous_id" name="delete_cpt_previous_id" value="">
                            <a id="add_new_cpt_previous" class="btn btn-default" href="javascript:;">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More CPT
                            </a>
                        </div>

                     </div> <!-- panel body over -->
                      
                            
                        </div>
                    </div><!-- CPT WEEK over -->  
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Health </h2>
                                    <div class="form-group">
                                      <div class="row">
                                    <div class="col-sm-4 add_items_field mt30 ">
                                         <strong>Health Appointments Attended</strong>
                                    </div>
                                    <div class="col-sm-4 add_items_field mt30 ">
                                        <strong>Date</strong>
                                      
                                    </div>
                                    <div class="col-sm-4 add_items_field mt30 ">
                                        <strong>Outcome/ Actions</strong>
                                    </div>
                                      </div>
                                    
                                    </div>
                            <?php if (!empty($appointments)) {
                                        foreach ($appointments as $row) {
                                    ?>

                                         <div class="delet_bottom form-group newrow">
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
                              
                              <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>DIET </h2>
                                    <div class="form-group">
                                <h3></h3>
                                      
                                <div class="form-group">
                                    <label>Average of ‘<?=$diet_avg?> a day’ consumed </label>
                                    <textarea class="tinyeditor" name="average_days_consumed" placeholder="">
                                        <?= !empty($edit_data[0]['average_days_consumed']) ? $edit_data[0]['average_days_consumed'] : '' ?>
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label>Comments/ Points For Consideration:  </label>
                                    <textarea class="tinyeditor" name="comments_points" placeholder="">
                                        <?= !empty($edit_data[0]['comments_points']) ? $edit_data[0]['comments_points'] : '' ?>
                                    </textarea>
                                </div>
                                      <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-5">
                                    <label>Regular Hobbies / Clubs Attended</label>
                                    </div>
                                    <div class="col-md-5 col-sm-5">
                                    <label>Duration per week (hours/minutes)</label>
                                    </div>
                                    
                                    </div>
                                </div>
                                <div id="add_hobbies">
                                <?php if (!empty($hobbies_data)) {
                                        foreach ($hobbies_data as $row) {
                                    ?> 
                                  <div class="row">
                                    <div class="form-group" id="hobbies_edit_<?= $row['regular_hobby_id'] ?>">
                                        <div class="col-md-6 col-sm-5">
                                        <input type="text" class="form-control col-sm-4" name="regular_hobbies_<?= $row['regular_hobby_id'] ?>" placeholder="" value="<?= !empty($row['regular_hobbies']) ? $row['regular_hobbies'] : '' ?>">
                                        </div>
                                        <div class="col-md-5 col-sm-5 mb30">
                                        <input type="text" class="form-control col-sm-4" name="regular_hobbies_duration_<?= $row['regular_hobby_id'] ?>" placeholder="" value="<?= !empty($row['regular_hobbies_duration']) ? $row['regular_hobbies_duration'] : '' ?>">
                                        </div>
                                         <div class="col-md-1 col-sm-2 add_items_field mb44">
                                            <a class="btn btn-default btn_border">
                                                <span class="glyphicon glyphicon-trash" onclick="delete_hobbies_row('hobbies_edit_<?= $row['regular_hobby_id'] ?>');"></span>
                                            </a>
                                        </div>
                                      </div>
                                    </div>
                                    <?php } } ?>
                                </div>
                               <div class="form-group">
                                <div class="col-sm-12 text-center mb30">
                                    <input type="hidden" id="delete_hobbies_id" name="delete_hobbies_id" value="">
                                    <a id="add_new_hobbies" class="btn btn-default">
                                        <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More
                                    </a>
                                </div>
                               </div>
                            </div>
                            <!-- Start Physical Exercise -->
                                  <div class="row">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-5">
                                <label>Physical Exercise Completed</label>
                                </div>
                                <div class="col-md-5 col-sm-5">
                                <label>Duration per week (hours/minutes)</label>
                                </div>
                                </div>
                                
                            </div>
                            <div id="add_physical_exercise">
                            <?php if (!empty($physical_exercise_data)) {
                                    foreach ($physical_exercise_data as $row) {
                                ?> 
                              <div class="row">
                                <div class="form-group" id="physical_exercise_edit_<?= $row['physical_exercise_id'] ?>">
                                    <div class="col-md-6 col-sm-5">
                                    <input type="text" class="form-control col-sm-4" name="physical_exercise_<?= $row['physical_exercise_id'] ?>" placeholder="" value="<?= !empty($row['physical_exercise']) ? $row['physical_exercise'] : '' ?>">
                                    </div>
                                    <div class="col-md-5 col-sm-5 mb30">
                                    <input type="text" class="form-control col-sm-4" name="physical_exercise_duration_<?= $row['physical_exercise_id'] ?>" placeholder="" value="<?= !empty($row['physical_exercise_duration']) ? $row['physical_exercise_duration'] : '' ?>">
                                    </div>
                                     <div class="col-sm-2 col-md-1 add_items_field mb44">
                                        <a class="btn btn-default btn_border">
                                            <span class="glyphicon glyphicon-trash" onclick="delete_physical_exercise_row('physical_exercise_edit_<?= $row['physical_exercise_id'] ?>');"></span>
                                        </a>
                                    </div>
                                  </div>
                                </div>
                                <?php } } ?>
                            </div>
                            <div class="form-group">
                            <div class="col-sm-12 text-center mb30">
                                <input type="hidden" id="delete_physical_exercise_id" name="delete_physical_exercise_id" value="">
                                <a id="add_new_physical_exercise" class="btn btn-default">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More
                                </a>
                            </div>
                            </div>
                                </div>
                              </div>
                            <!-- End Physical Exercise -->
                            </div>
                         </div> <!-- panel body over -->
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Emotional and Behavioural Development </h2>
                                    
                                <div class="row">
                                   
                                    <div class="form-group">
                                        <div class="col-sm-3 col-sm-5">
                                        <label>Incident summary (Include the date) </label>
                                        </div>                                        
                                    </div>
                                     <div class="form-group">
                                              <div class="level1 col-sm-12">There <?=(!empty($incident_level[0]['level1']) && $incident_level[0]['level1'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level1'])?$incident_level[0]['level1']:'0'?></span> incident of level1. </div>
                                              <div class="level2 col-sm-12">There <?=(!empty($incident_level[0]['level2']) && $incident_level[0]['level2'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level2'])?$incident_level[0]['level2']:'0'?></span> incident of level2. </div>
                                              <div class="level3 col-sm-12">There <?=(!empty($incident_level[0]['level3']) && $incident_level[0]['level3'] >1)?'are':'is'?> <span><?=!empty($incident_level[0]['level3'])?$incident_level[0]['level3']:'0'?></span> incident of level3. </div>
                                              <div class="level4 col-sm-12">There <?=(!empty($incident_level[0]['level4']) && $incident_level[0]['level4'] >1)?'are':'is'?>  <span><?=!empty($incident_level[0]['level4'])?$incident_level[0]['level4']:'0'?></span> incident of level4. </div>
                                          </div>
                                  </div>
                                    <div class="clearfix"></div>
                                    <div class="row" id="add_incident">
                                    <?php if (!empty($incident_data)) {
                                            foreach ($incident_data as $row) {
                                        ?> 
                                        <div class="form-group" id="incident_edit_<?= $row['incident_id'] ?>">
                                            <div class="col-md-3 col-sm-5 mb30">
                                            <textarea rows="4" class="form-control" name="incident_summary_<?= $row['incident_id'] ?>" placeholder=""><?= !empty($row['incident_summary']) ? $row['incident_summary'] : '' ?></textarea> 
                                            </div>
                                          <div class="col-md-8 col-sm-5">
                                         
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                  <lable class="radio-inline">
                                                    <input class="levelradio" type="radio" name="level_<?= $row['incident_id'] ?>" <?= (isset($row['level']) && $row['level'] == 1) ? 'checked' : '' ?> value="1">
                                                    Level 1(incident requiring no physical intervention)
                                                  </lable>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                  <lable class="radio-inline">
                                                      <input class="levelradio" type="radio" name="level_<?= $row['incident_id'] ?>" <?= (isset($row['level']) && $row['level'] == 2) ? 'checked' : '' ?> value="2">
                                                      Level 2(incident requiring physical intervention up to and including seated holds)
                                                  </lable>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                  <lable class="radio-inline">
                                                      <input class="levelradio" type="radio" name="level_<?= $row['incident_id'] ?>" <?= (isset($row['level']) && $row['level'] == 3) ? 'checked' : '' ?> value="3">
                                                      Level 3(incident requiring physical intervention including ground holds)
                                                  </lable>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                  <lable class="radio-inline">
                                                    <input class="levelradio" type="radio" name="level_<?= $row['incident_id'] ?>" <?= (isset($row['level']) && $row['level'] == 4) ? 'checked' : '' ?> value="4">
                                                    Level 4(Missing from care / absent without authority)
                                                  </lable>
                                                </div>
                                              </div>
                                            </div>
                                             <div class="col-md-1 col-sm-2 add_items_field mb44">
                                                <a class="btn btn-default btn_border">
                                                    <span class="glyphicon glyphicon-trash" onclick="delete_incident_row('incident_edit_<?= $row['incident_id'] ?>');"></span>
                                                </a>
                                            </div>
                                          
                                        </div>
                                        <div class="clearfix"></div>
                                        <?php } } ?>
                                    </div>
                                   <div class="form-group">
                                    <div class="col-sm-12 col-md-12 text-center mb30 m-t-10">
                                        <input type="hidden" id="delete_incident_id" name="delete_incident_id" value="">
                                        <a href="javascript:;" id="add_new_incident" class="btn btn-default">
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More
                                        </a>
                                    </div>
                                   </div>
                              <div class="clearfix"></div>
                                <div class="panel panel-default tile tile-profile">
                                  <div class="panel-body">
                                    <h2>Sanctions</h2>
                                    <div class="row">
                                  <div class="form-group">
                                        <div class="col-md-4 col-sm-3">
                                        <label>Reason for Sanction</label>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                        <label>Date</label>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                        <label>Sanction Imposed</label>
                                        </div>
                                        </div>
                                        
                                    </div>
                                    <div id="add_sanction">
                                    <?php if (!empty($sanction_data)) {
                                            foreach ($sanction_data as $row) {
                                        ?> 
                                      <div class="row">
                                        <div class="form-group" id="sanction_edit_<?= $row['sanction_id'] ?>">
                                            <div class="col-md-4 col-sm-3">
                                            <input type="text" class="form-control col-sm-4" name="reason_sanction_<?= $row['sanction_id'] ?>" placeholder="" value="<?= !empty($row['reason_sanction']) ? $row['reason_sanction'] : '' ?>">
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                               <div class="input-group input-append date checkdate">
                                                  <input type="text" class="form-control checkdate " name="date_sanction_<?= $row['sanction_id'] ?>" placeholder="" value="<?= !empty($row['date_sanction']) ?date('d/m/Y',strtotime($row['date_sanction'])) : '' ?>" readonly="" >
                                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 mb30">
                                            <input type="text" class="form-control col-sm-4" name="imposed_sanction_<?= $row['sanction_id'] ?>" placeholder="" value="<?= !empty($row['imposed_sanction']) ? $row['imposed_sanction'] : '' ?>">
                                            </div>
                                             <div class="col-md-1 col-sm-2 add_items_field mb44">
                                                <a class="btn btn-default btn_border">
                                                    <span class="glyphicon glyphicon-trash" onclick="delete_sanction_row('sanction_edit_<?= $row['sanction_id'] ?>');"></span>
                                                </a>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <?php } } ?>
                                    </div>
                                    <div class="form-group">
                                    <div class="col-sm-12 text-center mb30">
                                        <input type="hidden" id="delete_sanction_id" name="delete_sanction_id" value="">
                                        <a id="add_new_sanction" class="btn btn-default">
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More
                                        </a>
                                    </div>
                                   </div>
                                <div class="form-group">
                                    <label>Safeguarding Concerns</label>
                                    <textarea class="tinyeditor" name="safeguarding" placeholder="">
                                        <?= !empty($edit_data[0]['safeguarding']) ? $edit_data[0]['safeguarding'] : '' ?>
                                    </textarea>
                            </div>
                            <div class="form-group">
                                    <label>General behaviour</label>
                                    <textarea class="tinyeditor" name="general_behaviour" placeholder="">
                                        <?= !empty($edit_data[0]['general_behaviour']) ? $edit_data[0]['general_behaviour'] : '' ?>
                                    </textarea>
                            </div>
                            <div class="form-group">
                                    <label>Concerns</label>
                                    <textarea class="tinyeditor" name="concerns" placeholder="">
                                        <?= !empty($edit_data[0]['concerns']) ? $edit_data[0]['concerns'] : '' ?>
                                    </textarea>
                            </div>
                            <div class="form-group">
                                    <label>Bullying Issues/ Concerns</label>
                                    <textarea class="tinyeditor" name="bullying_issues" placeholder="">
                                        <?= !empty($edit_data[0]['bullying_issues']) ? $edit_data[0]['bullying_issues'] : '' ?>
                                    </textarea>
                            </div>
                            <div class="form-group">
                                    <label>Significant events</label>
                                    <textarea class="tinyeditor" name="significant_events" placeholder="">
                                        <?= !empty($edit_data[0]['significant_events']) ? $edit_data[0]['significant_events'] : '' ?>
                                    </textarea>
                            </div>  
                                
                                  </div>
                                </div>                            
                            </div>
                         </div> <!-- panel body over -->            
                    </div><!-- STAY SAFE over -->     
                     <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Education, Achievements and Social Skills</h2>  
                                 <div class="form-group">
                                   <div class="panel panel-default tile tile-profile">
                                      <div class="panel-body">
                                          <h2>Educational Attendance</h2>
                                      <div class="row">
                                        <div class="col-sm-4">
                                          <div class="form-group">
                                            <label>Percentage of Attendance</label>
                                          <input type="text" class="form-control col-sm-4" name="per_of_attendance" placeholder="" maxlength="4" onkeypress="return isNumberPerKey(event)" value="<?= !empty($edit_data[0]['per_of_attendance']) ? $edit_data[0]['per_of_attendance'] : '' ?>">
                                          </div>
                                        </div>
                                      <div class="col-sm-4">
                                          <div class="form-group">
                                            <label>Number of Referrals (Pink and Blue)</label>
                                          <input type="text" class="form-control col-sm-4" name="number_of_referrals" placeholder="" maxlength="300" value="<?=!empty($edit_data[0]['number_of_referrals']) ? $edit_data[0]['number_of_referrals'] : '' ?>">
                                          </div>
                                        </div>
                                          <div class="col-sm-4">
                                          <div class="form-group">
                                            <label>Achievements Student of the Week</label>
                                          <input type="text" class="form-control col-sm-4" name="achievements" placeholder="" maxlength="300" value="<?= !empty($edit_data[0]['achievements']) ? $edit_data[0]['achievements'] : '' ?>">
                                          </div>
                                        </div>
                                      </div>
                                      </div>
                                   </div>
                                 <div class="panel panel-default tile tile-profile">
                                      <div class="panel-body">
                                          <h2>Banding System</h2>
                                      <div class="row">
                                        <div class="col-sm-12">
                                          <div class="form-group">
                                            <label>Average Pocket Money Achieved</label>
                                          <input type="text" class="form-control col-sm-8" name="average_pocket" placeholder="" maxlength="300" value="<?= !empty($edit_data[0]['average_pocket']) ? $edit_data[0]['average_pocket'] : '' ?>">
                                          </div>
                                        </div>
                                      </div>
                                      </div>
                                   </div>
                                 </div>
                                 <div class="clearfix"></div>
                                <div class="form-group">
                                    <label>Emotional / Social Development</label>
                                        <textarea class="tinyeditor" name="emotional" placeholder="">
                                            <?= !empty($edit_data[0]['emotional']) ? $edit_data[0]['emotional'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Evidence of Positive Relationships</label>
                                        <textarea class="tinyeditor" name="positive_relationships" placeholder="">
                                            <?= !empty($edit_data[0]['positive_relationships']) ? $edit_data[0]['positive_relationships'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Contact</label>
                                        <textarea class="tinyeditor" name="contact" placeholder="">
                                            <?= !empty($edit_data[0]['contact']) ? $edit_data[0]['contact'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Peer relationships</label>
                                        <textarea class="tinyeditor" name="peer_relationships" placeholder="">
                                            <?= !empty($edit_data[0]['peer_relationships']) ? $edit_data[0]['peer_relationships'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Cultural Needs</label>
                                        <textarea class="tinyeditor" name="cultural_needs" placeholder="">
                                            <?= !empty($edit_data[0]['cultural_needs']) ? $edit_data[0]['cultural_needs'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Evidence of Positive Decision Making</label>
                                        <textarea class="tinyeditor" name="positive_decision" placeholder="">
                                            <?= !empty($edit_data[0]['positive_decision']) ? $edit_data[0]['positive_decision'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>After School Clubs</label>
                                        <textarea class="tinyeditor" name="school_clubs" placeholder="">
                                            <?= !empty($edit_data[0]['school_clubs']) ? $edit_data[0]['school_clubs'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Evidencing the 24hour Curriculum</label>
                                        <textarea class="tinyeditor" name="evidencing_curriculum" placeholder="">
                                            <?= !empty($edit_data[0]['evidencing_curriculum']) ? $edit_data[0]['evidencing_curriculum'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Part-time / Voluntary Work</label>
                                        <textarea class="tinyeditor" name="voluntary_work" placeholder="">
                                            <?= !empty($edit_data[0]['voluntary_work']) ? $edit_data[0]['voluntary_work'] : '' ?>
                                        </textarea>
                                </div>
                            </div>
                        </div> <!-- panel body over -->            
                    </div><!-- ENJOY AND ACHIEVE over -->  
                  
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>ACHIEVE ECONOMIC WELLBEING </h2>
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2>Life Skills Development</h2>
                                      <div class="row">
                                        <div class="form-group">
                                        <div class="col-sm-5">
                                        <label>Area of Development </label>
                                        </div>
                                        <div class="col-sm-5 col-md-6">
                                        <label>Progress achieved/ Action Required </label>
                                        </div>
                                        <div class="col-sm-2 col-md-1">
                                        <label></label>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="row" id="add_life_skills">
                                    <?php if (!empty($life_skills_data)) {
                                            foreach ($life_skills_data as $row) {
                                        ?> 
                                        <div class="form-group" id="life_skills_edit_<?= $row['life_skills_id'] ?>">
                                            <div class="col-sm-5 mb30">
                                            <textarea class="form-control col-sm-5" name="area_of_development_<?= $row['life_skills_id'] ?>" placeholder=""><?= !empty($row['area_of_development']) ? $row['area_of_development'] : '' ?></textarea> 
                                            </div>
                                            <div class="col-sm-5  col-md-6 mb30">
                                            <textarea class="form-control col-sm-5" name="progress_achieved_<?= $row['life_skills_id'] ?>" placeholder=""><?= !empty($row['progress_achieved']) ? $row['progress_achieved'] : '' ?></textarea> 
                                            </div>
                                             <div class="col-sm-1 col-md-1 add_items_field mb44">
                                                <a class="btn btn-default btn_border">
                                                    <span class="glyphicon glyphicon-trash" onclick="delete_life_skills_row('life_skills_edit_<?= $row['life_skills_id'] ?>');"></span>
                                                </a>
                                            </div>
                                          
                                        </div>
                                        <?php } } ?>
                                    </div>
                                    <div class="form-group">
                                    <div class="col-sm-12 text-center mb30">
                                        <input type="hidden" id="delete_life_skills_id" name="delete_life_skills_id" value="">
                                        <a id="add_new_life_skills" class="btn btn-default">
                                            <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More
                                        </a>
                                    </div>
                                   </div>
                                    </div>
                                </div>
                         </div> <!-- panel body over -->            
                        </div>
                    </div><!-- ACHIEVE ECONOMIC WELLBEING over --> 
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>CARE SUMMARY </h2>
                                <div class="form-group">
                                    <textarea class="tinyeditor" name="care_summary" placeholder="">
                                        <?= !empty($edit_data[0]['care_summary']) ? $edit_data[0]['care_summary'] : '' ?>
                                    </textarea>
                                </div>
                            </div>
                         </div> <!-- panel body over -->            
                    </div><!-- CARE SUMMARY over --> 
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>THERAPY</h2>  
                                <div class="form-group">
                                        <label>Attendance</label>
                                        <textarea class="tinyeditor" name="attendance" placeholder="">
                                            <?= !empty($edit_data[0]['attendance']) ? $edit_data[0]['attendance'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Engagement</label>
                                        <textarea class="tinyeditor" name="engagement" placeholder="">
                                            <?= !empty($edit_data[0]['engagement']) ? $edit_data[0]['engagement'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Areas of focus</label>
                                        <textarea class="tinyeditor" name="areas_of_focus" placeholder="">
                                            <?= !empty($edit_data[0]['areas_of_focus']) ? $edit_data[0]['areas_of_focus'] : '' ?>
                                        </textarea>
                                </div>
                                <div class="form-group">
                                        <label>Progress</label>
                                        <textarea class="tinyeditor" name="progress" placeholder="">
                                            <?= !empty($edit_data[0]['progress']) ? $edit_data[0]['progress'] : '' ?>
                                        </textarea>
                                </div>
                            </div>
                        </div> <!-- panel body over -->            
                    </div><!-- THERAPY over --> 
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                            <h2>CARE PLAN TARGETS IDENTIFIED FOR THE NEXT 12WEEKS</h2>
                          <div class="row">
                                <div class="form-group">
                                <div class="col-lg-4 col-md-4 col-sm-3 add_items_field mt30 ">
                                     <strong>Care plan target</strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 add_items_field mt30 ">
                                    <strong>Achieved / Ongoing / Outstanding</strong>
                                  
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 add_items_field mt30 ">
                                    <strong>Evidence of how this has been achieved / reasons why it has not been achieved</strong>
                                </div>
                                  
                                
                                </div>
                            </div>
                               <div class="section_three">
                                <div class="form-group row" id="add_cpt_weeks">
                                        <?php if (!empty($care_plan_target_week)) {
                                                        foreach ($care_plan_target_week as $row) {

                                                    ?> 
                                            <div class="delet_bottom form-group newrow" id="cpt_weeks_edit_<?= $row['cpt_week_id'] ?>">
                                                <div class="col-lg-4 col-md-4 col-sm-3 form-group add_items_field mt30 ">
                                                    <textarea class="form-control" name="cpt_week_title_<?= $row['cpt_week_id'] ?>" placeholder=""><?= !empty($row['care_plan_target_title']) ? $row['care_plan_target_title']: '' ?></textarea>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 form-group add_items_field mt30 ">
                                                    <?php 
                                                    $cpt_week_select = !empty($row['care_plan_target_select'])?explode(',',$row['care_plan_target_select']):'';
                                                    
                                                    ?>
                                                    <select class="chosen-select form-control" name="cpt_week_select_<?= $row['cpt_week_id'] ?>" id="type" required="true">
                                                         <option value="">Select</option>
                                                         <option <?=(!empty($cpt_week_select) &&  in_array('Achieved',$cpt_week_select))?'selected' : ''?> value="Achieved">Achieved</option>
                                                         <option <?=(!empty($cpt_week_select) &&  in_array('Ongoing',$cpt_week_select))?'selected' : ''?>  value="Ongoing">Ongoing</option>
                                                         <option <?=(!empty($cpt_week_select) &&  in_array('Outstanding',$cpt_week_select))?'selected' : ''?> value="Outstanding">Outstanding</option>
                                                         
                                                          
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 form-group add_items_field mt30 ">
                                                    <textarea class="form-control" name="cpt_week_reason_<?= $row['cpt_week_id'] ?>" placeholder=""><?= !empty($row['care_plan_target_reason']) ? $row['care_plan_target_reason'] : '' ?></textarea>
                                                </div>
                                                  
                                                <div class="col-md-1 col-sm-2 add_items_field mb44">
                                                    <a class="btn btn-default btn_border">
                                                        <span class="glyphicon glyphicon-trash" onclick="delete_cpt_weeks_row('cpt_weeks_edit_<?= $row['cpt_week_id'] ?>');"></span>
                                                    </a>
                                                </div>
                                                </div>
                                           
                                        
                                        <?php }} ?>
                                    </div>
                                </div>
                        <div class=" section_four text-center mb30">
                            <input type="hidden" id="delete_cpt_weeks_id" name="delete_cpt_weeks_id" value="">
                            <a id="add_new_cpt_weeks" class="btn btn-default" href="javascript:;">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More CPT
                            </a>
                        </div>

                     </div> <!-- panel body over -->
                      
                            
                        </div>
                    </div><!-- CPT WEEK over --> 
                      <?php
                // /pr($form_data);
                if(!empty($form_data))
                {
                    foreach ($form_data as $row) {
                      

                    if($row['type'] == 'textarea') {
                        ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        
                                         <textarea 
                                         class="<?=!empty($row['className'])?$row['className']:''?> <?=($row['subtype'] == 'tinymce')?'tinyeditor':''?>" 
                                         <?=!empty($row['required'])?'required=true':''?>
                                         name="<?=!empty($row['name'])?$row['name']:''?>" 
                                         placeholder="<?=!empty($row['placeholder'])?$row['placeholder']:''?>"
                                         <?php if($row['subtype'] != 'tinymce') { ?>
                                                 <?=!empty($row['maxlength'])?'maxlength="'.$row['maxlength'].'"':''?>
                                                 <?=!empty($row['rows'])?'rows="'.$row['rows'].'"':''?>
                                                 <?php } ?>
                                         id="<?=!empty($row['name'])?$row['name']:''?>" ><?=!empty($edit_data[0][$row['name']])?html_entity_decode($edit_data[0][$row['name']]):(isset($row['value'])?$row['value']:'')?></textarea>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        else if($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date')
                        { 
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                      <?php if((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date'){ ?>
                                        <div class="row">
                                            <div class="col-sm-4">
                                        <?php } ?>
                                         <div class="<?=(!empty($row['subtype']) && $row['subtype'] == 'time')?'input-group input-append bootstrap-timepicker':''?><?=(!empty($row['type']) && $row['type'] == 'date')?(!empty($row['description']) && $row['description'] == 'dob')?'input-group input-append date dob':'input-group input-append date adddate':''?>">
                                              <input type="<?=(!empty($row['type']) && $row['type']=='number')?'number':((!empty($row['subtype']) && $row['subtype'] !='time')?$row['subtype']:'text')?>" 
                                               class="<?=!empty($row['className'])?$row['className']:''?> <?=($row['type'] == 'date')?'adddate':''?> <?=(!empty($row['subtype']) && $row['subtype'] == 'time')?'addtime':''?>" 
                                                <?=!empty($row['required'])?'required=true':''?>
                                                <?=!empty($row['required'])?'required=true':''?>
                                                name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" 
                                                <?=!empty($row['maxlength'])?'maxlength="'.$row['maxlength'].'"':''?>
                                                <?=!empty($row['min'])?'min="'.$row['min'].'"':''?>
                                                <?=!empty($row['max'])?'max="'.$row['max'].'"':''?>
                                                <?=!empty($row['step'])?'step="'.$row['step'].'"':''?>
                                                placeholder="<?=!empty($row['placeholder'])?$row['placeholder']:''?>"
                                                <?php if($row['subtype'] == 'time'){ ?>
                                                value="<?=!empty($edit_data[0][$row['name']])?timeformat($edit_data[0][$row['name']]):(isset($row['value'])?timeformat($row['value']):'')?>"
                                                <?php }elseif($row['type'] == 'date'){?>
                                                value="<?=!empty($edit_data[0][$row['name']])?configDateTime($edit_data[0][$row['name']]):(isset($row['value'])?configDateTime($row['value']):'')?>"
                                                <?php }else{ ?> 
                                                value="<?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?>"
                                                <?php } ?>
                                                

                                                 <?=($row['type'] =='date')?'readonly':''?> data-parsley-errors-container="#errors-container<?=$row['name']?>" />
                                                  <?php if(!empty($row['subtype']) && $row['subtype'] == 'time') {?>
                                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                                    <?php } ?>
                                                  
                                                <?php if(!empty($row['type']) && $row['type'] == 'date') {?>
                                                <span class="input-group-addon add-on" ><i class="fa fa-calendar"></i></span>
                                                    <?php } ?>
                                            </div>
                                            <span id="errors-container<?=$row['name']?>"></span>
                                <?php if((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) { ?>
                                    </div>
                                    </div>
                                <?php } ?>
                                    </div>
                                </div>
                            </div>
                           
                        <?php
                        }
                        else if($row['type'] == 'radio-group')
                        {
                        ?>
                        <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        
                                         <div class="radio-group">
                                         <?php if(count($row['values']) > 0)  {
                                         foreach ($row['values'] as $radio) {
                                             if(!empty($radio['label'])) {

                                          ?>
                                         <div class="<?=!empty($row['inline'])?'radio-inline':'radio'?>">
                                             <label ><input  name="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?> 
                                                 class="<?=!empty($row['className'])?$row['className']:''?>" 
                                                 value="<?=!empty($radio['value'])?$radio['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $radio['value'])?'checked="checked"':isset($radio['selected'])?'checked="checked"':''?>  type="radio">
                                             <?=!empty($radio['label'])?$radio['label']:''?></label>
                                         </div>
                                        <?php } } } //radio loop ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        else if($row['type'] == 'checkbox-group')
                        {
                        ?>
                        <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                        
                                         <div class="checkbox-group">
                                         <?php if(count($row['values']) > 0) {
                                            $checkedValues =array();
                                            if(!empty($edit_data[0][$row['name']]))
                                            {
                                            $checkedValues = explode(',',$edit_data[0][$row['name']]);
                                            }
                                         foreach ($row['values'] as $checkbox) {
                                             if(!empty($checkbox['label'])) {
                                          ?>
                                         <div class="<?=!empty($row['inline'])?'checkbox-inline':'checkbox'?>">
                                             <label class="<?=!empty($row['toggle'])?'kc-toggle':''?>"><input 
                                                class="<?=!empty($row['className'])?$row['className']:''?> <?=!empty($row['toggle'])?'kc-toggle':''?>"
                                               name="<?=!empty($row['name'])?$row['name'].'[]':''?>" value="<?=!empty($checkbox['value'])?$checkbox['value']:''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues))?'checked="checked"':!empty($checkbox['selected'])?'checked="checked"':''?>  
                                                    <?=!empty($row['required'])?'required=true':''?>
                                                    type="checkbox">
                                             <?=!empty($checkbox['label'])?$checkbox['label']:''?></label>
                                         </div>
                                        <?php } } } //radio loop ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        else if($row['type'] == 'select')
                        {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                     
                                         <select class="chosen-select <?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?>>
                                         <option value="">Select</option>
                                         <?php if(count($row['values']) > 0) {
                                         foreach ($row['values'] as $select) {
                                             if(!empty($select['label'])) {
                                          ?>
                                          <option value="<?=!empty($select['value'])?$select['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $select['value'])?'selected="true"':!empty($select['selected'])?'selected="true"':''?> ><?=!empty($select['label'])?$select['label']:''?></option>
                                        <?php } } } //select loop ?>
                                        
                                         </select>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        else if($row['type'] == 'hidden' || $row['type'] == 'button')
                        {
                            ?>
                             <?php if($row['type'] == 'button'){ ?>
                             <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <div class="fb-button form-group">
                                       
                                            <button name="<?=!empty($row['name'])?$row['name']:''?>" value="" type="<?=!empty($row['type'])?$row['type']:''?>" class="<?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" style="<?=!empty($row['style'])?$row['style']:''?>"><?=!empty($row['label'])?$row['label']:''?></button>
                                        
                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                             <?php if($row['type'] == 'hidden'){ ?>
                             <div class="col-sm-12">
                                <input type="hidden" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" value="" />
                                </div>
                            <?php } ?>
                        <?php
                        }
                        else if($row['type'] == 'header')
                        {
                            ?>
                            <div class="col-sm-12">
                                <div class="">
                                    <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                                </div>
                            </div>
                        <?php } else if($row['type'] == 'file')
                        {?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                    <h2 class="page-title"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                    <input type="file" name="<?=!empty($row['name'])?$row['name'].'[]':''?>" id="<?=!empty($row['name'])?$row['name']:''?>"  class="<?=!empty($row['className'])?$row['className']:''?>" 
                                        <?=!empty($row['multiple'])?'multiple="true"':''?> <?= !empty($row['required']) ? 'required=true' : '' ?>>
                                        <h2></h2>
                                        <?php 
                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                            $fileViewArray = array(
                                                'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                                                'filePathMain' => $this->config->item('mdt_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('mdt_img_base_url_small') . $ypid,
                                                'deleteFileHidden' => 'hidden_'.$row['name']
                                            );
                                            echo getFileView($fileViewArray);
                                            ?>
                                            <input type="hidden" name="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" id="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" value="">
                                    </div>

                                </div>
                            </div>
                        <?php
                        }
                    } //foreach
                }
                    ?>
                         <div class="col-sm-12">
                            <div class="capsBtn">
                            <input type="hidden" name="mdt_report_id" id="mdt_report_id" value="<?=!empty($edit_data[0]['mdt_report_id'])?$edit_data[0]['mdt_report_id']:''?>">
							<input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                            <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:$ypid?>">
                                <input type="hidden" name="draft_data" id="draft_data" value="0">
                                <button type="submit" onclick="needToConfirm = false;" class="btn btn-default hidden-xs" name="submit_mdtform" id="submit_mdtform" value="submit" style="default"><?=!empty($edit_data[0]['yp_id'])?'UPDATE':'ADD'?></button>
								
                               <?php if(!empty($edit_data)) { ?> 
                                <?php if(checkPermission('MDTReviewReport','view') && $edit_data[0]['draft'] == 0){ ?>
								
								
										<a href="<?=base_url('MDTReviewReport/view/'.$edit_data[0]['mdt_report_id'].'/'.$edit_data[0]['yp_id']); ?>" class="btn btn-default pull-right width_a">
												<i class="fa fa-mail-reply"></i> RETURN TO CURRENT MDT
											</a>
										<?php } ?>
										<?php if($edit_data[0]['draft'] == 1){ ?>
										<a href="<?=base_url('MDTReviewReport/index/'.$ypid)?>" class=" btn btn-default updat_bn" style="default"><i class="fa fa-mail-reply"></i> BACK TO MDT</a>
										<?php } ?>
										<?php } else { ?> 
										<div class="pull-right btn-section">
									<div class="btn-group">
								 
										<a href="<?=base_url('MDTReviewReport/index/'.$ypid)?>" class=" btn btn-default" style="default"><i class="fa fa-mail-reply"></i> BACK TO MDT</a>
										<?php } ?>
										<a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="btn btn-default"  style="default">Back To YP Info</a>
										<a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"> CARE HOME YP LIST</a>
									</div>
								</div>
                            </div>
                        </div>

                    
            </form>        
      
        
    </div>
</div>
      
</div>
        