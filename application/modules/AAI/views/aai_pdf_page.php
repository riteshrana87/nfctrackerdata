

                <?php 
                if (isset($createMode) && $createMode == 1) { ?>
                     <?php include_once 'pdf_main_entry_form.php'; ?>                        
                    
                <?php }elseif(isset($editMode) && $editMode == 2){ ?>
                        <?php include_once 'pdf_main_entry_form.php'; ?>
                        <?php include_once 'pdf_type_of_incident.php'; ?>
                <?php }else{ ?>     
                        <?php include_once 'pdf_main_entry_form.php'; ?>

                        <?php include_once 'pdf_type_of_incident.php'; ?>
                        
                    <?php if($is_pi == 0){ ?>                
                    
                        <?php include_once 'pdf_incident_l1_process.php'; ?>
                    
                    <?php }else{ ?>                
                        <?php include_once 'pdf_incident_l2_and_l3_process.php'; ?>
                    <?php } ?>
                    
                    <?php if($is_yp_missing == 1){ ?>                
                        <?php include_once 'pdf_incident_l4_process.php'; ?>
                    <?php } ?>
					<?php if($is_yp_injured == 1){ ?>                
                        <?php include_once 'pdf_incident_l5_process.php'; ?>
                    <?php } ?>
                
                    <?php if($is_yp_injured == 1){ ?>                
                        <?php include_once 'pdf_incident_l5_process.php'; ?>
                        <input type="hidden" name="incident_id" id="incident_id" value="<?= $incidentId ?>"> 
                    <?php } ?>
                
                    <?php if($is_yp_complaint == 1){ ?>                
                        <?php include_once 'pdf_incident_l6_process.php'; ?>
                    <?php } ?>
                
                    <?php if($is_yp_safeguarding == 1){ ?>                
                        <?php include_once 'pdf_incident_l7_process.php'; ?>
                    <?php } ?>
					
					<?php if($is_staff_injured == 1){ ?>                
                        <?php include_once 'pdf_incident_l8_process.php'; ?>
                    <?php } ?>
					
					<?php if($is_other_injured == 1){ ?>                
                        <?php include_once 'pdf_incident_l9_process.php'; ?>
                    <?php } ?>

                    <?php if($review_status == 1){ ?>                
                        <?php include_once 'pdf_review_process.php'; ?>
                    <?php } ?>

                    <?php if($review_status == 1){ 
                            if($manager_review_status == 1){ 
                        ?>                
                        <?php include_once 'pdf_manager_review_process.php'; ?>
                    <?php } } ?>
                <?php } ?>
    
