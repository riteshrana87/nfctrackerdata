<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            MEDICAL INFORMATION <small>New Forest Care</small>
          
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
      </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    
                    <p>
                       You have added a new Administer Medication:
                    </p>
                    <?php 
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])){
                                    $edit_data[0][$row['name']] = timeformat($edit_data[0][$row['name']]);
                                }
                                if($row['type'] == 'date' && isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])){
                                    $edit_data[0][$row['name']] = configDateTime($edit_data[0][$row['name']]);
                                }
                                ?>
                                <p>
                                <label><?=!empty($row['label'])?$row['label']:''?> :</label>
                                <?php if($row['type'] == 'select' && !empty($row['description']) && $row['description'] == 'get_user') {
                                    if(!empty($edit_data[0][$row['name']])){
                                     $get_data = $this->common_model->get_single_user($edit_data[0][$row['name']]);
                                     echo !empty($get_data[0]['username'])?$get_data[0]['username']:'';}
                                    ?>

                                    <?php
                                    }else if($row['type'] == 'select' && !empty($row['description']) && $row['description'] == 'get_medication') {
                                    if(!empty($edit_data[0][$row['name']])){
                                     $get_medication_data = $this->common_model->get_single_medication($edit_data[0][$row['name']]);
                                     echo !empty($get_medication_data[0]['medication_name'])?$get_medication_data[0]['medication_name']:'';}
                                    ?>

                                    <?php
                                    }else{?>
                                <?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?>
                                </p>
                                <?php
                            }
                        }
                        }
                    ?>
                    <p>
                    <?php if($redirect_flag == 1){
                        ?>
                        <a class="" href="<?php echo base_url('Medical/log_administer_medication/'.$edit_data[0]['yp_id']); ?>">
                        <?php 
                        }else{
                            ?>
                        
                    <a class="" href="<?php echo base_url('Medical/index/'.$edit_data[0]['yp_id']); ?>">
                            <?php

                            } ?>
                        
                        <button class="btn btn-primary" type="button">Back To MEDS</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>