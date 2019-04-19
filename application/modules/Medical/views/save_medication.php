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
                       You have added a new Medication:
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
                                <?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?>
                                </p>
                                <?php
                        }
                        }
                    ?>
                    <p>
                    <?php if($redirect_flag == 1){
                        ?>
                        
                        <a class="" href="<?php echo base_url('Medical/medication_list/'.$edit_data[0]['yp_id']); ?>">
                        <button class="btn btn-primary" type="button">Back To Medication</button></a>
                        <?php 
                        }else{
                            ?>
                        <a class="" href="<?php echo base_url('Medical/medication/'.$edit_data[0]['yp_id']); ?>">
                        <button class="btn btn-primary" type="button">Back To Medication</button></a>
                            <?php

                            } ?>
                        
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>