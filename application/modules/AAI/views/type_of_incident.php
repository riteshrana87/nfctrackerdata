<div class="panel panel-default">
    <div class="panel-heading aai-page-header" role="tab" id="headingTwo">
        <h4 class="panel-title">
                TYPE OF INCIDENT
        </h4>
    </div>
        <div class="panel-body form-horizontal">
            <div class="row aai-module clear-div-flex">                                
                <div class="col-md-12">
                        <div class="form-group form-margin typ-of-incident">                                                                    
                              
                            <div class="col-md-4 col-sm-12 "> Was physical intervention used? </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <?php
                                    if($is_pi == 1){
                                        $checkIsPI1 = 'checked';
                                        $checkIsPI2 = '';
                                    }else{
                                        $checkIsPI2 = 'checked';
                                        $checkIsPI1 = '';
                                    }
                                    ?>
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_pi" value="1" <?= $checkIsPI1 ?>> YES</label>&nbsp;&nbsp;
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()"  name="is_pi" value="0" <?= $checkIsPI2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12"> Did YP go missing? </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <?php
                                    if($is_yp_missing == 1){
                                        $checkIsYPMiss1 = 'checked';
                                        $checkIsYPMiss2 = '';
                                    }else{
                                        $checkIsYPMiss2 = 'checked';
                                        $checkIsYPMiss1 = '';
                                    }
                                    ?>
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_yp_missing" value="1" <?= $checkIsYPMiss1 ?>> YES</label>&nbsp;&nbsp;
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_yp_missing" value="0" <?= $checkIsYPMiss2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12"> Was the YP injured? </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <?php
                                    if($is_yp_injured == 1){
                                        $checkIsYPInjured1 = 'checked';
                                        $checkIsYPInjured2 = '';
                                    }else{
                                        $checkIsYPInjured2 = 'checked';
                                        $checkIsYPInjured1 = '';
                                    }
                                    ?>
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_yp_injured" value="1" <?= $checkIsYPInjured1 ?>> YES</label>&nbsp;&nbsp;
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_yp_injured" value="0" <?= $checkIsYPInjured2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12"> Has a complaint been made either by the YP or on behalf of the YP? </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <?php
                                    if($is_yp_complaint == 1){
                                        $checkIsYPComplaint1 = 'checked';
                                        $checkIsYPComplaint2 = '';
                                    }else{
                                        $checkIsYPComplaint2 = 'checked';
                                        $checkIsYPComplaint1 = '';
                                    }
                                    ?>
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_yp_complaint" value="1" <?= $checkIsYPComplaint1 ?>> YES</label>&nbsp;&nbsp;
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_yp_complaint" value="0" <?= $checkIsYPComplaint2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12"> Is any part of this incident a safeguarding concern? </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <?php
                                    if($is_yp_safeguarding == 1){
                                        $checkIsYPSafeguarding1 = 'checked';
                                        $checkIsYPSafeguarding2 = '';
                                    }else{
                                        $checkIsYPSafeguarding2 = 'checked';
                                        $checkIsYPSafeguarding1 = '';
                                    }
                                    ?>
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_yp_safeguarding" value="1" <?= $checkIsYPSafeguarding1 ?>> YES</label>&nbsp;&nbsp;
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_yp_safeguarding" value="0" <?= $checkIsYPSafeguarding2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12"> Was a staff member injured? </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <?php
                                    if($is_staff_injured == 1){
                                        $checkIsStaffInjured1 = 'checked';
                                        $checkIsStaffInjured2 = '';
                                    }else{
                                        $checkIsStaffInjured2 = 'checked';
                                        $checkIsStaffInjured1 = '';
                                    }
                                    ?>
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_staff_injured" value="1" <?= $checkIsStaffInjured1 ?>> YES</label>&nbsp;&nbsp;
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_staff_injured" value="0" <?= $checkIsStaffInjured2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12"> Was anyone else injured? </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <?php
                                    if($is_other_injured == 1){
                                        $checkIsOtherInjured1 = 'checked';
                                        $checkIsOtherInjured2 = '';
                                    }else{
                                        $checkIsOtherInjured2 = 'checked';
                                        $checkIsOtherInjured1 = '';
                                    }
                                    ?>
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_other_injured" value="1" <?= $checkIsOtherInjured1 ?>> YES</label>&nbsp;&nbsp;
                                    <label class="radio-inline"><input type="radio" onclick="change_incident_type()" name="is_other_injured" value="0" <?= $checkIsOtherInjured2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            
			<div class="col-md-4 col-sm-12 div_l1_creation"> You want to create L1 incident? </div>
                            <div class="col-md-2 col-sm-12 div_l1_creation">
                                <div class="form-group">
                                    <?php
                                    if($is_l1 == 1){
                                        $checkIsl11 = 'checked';
                                        $checkIsl12 = '';
                                    }else{
                                        $checkIsl12 = 'checked';
                                        $checkIsl11 = '';
                                    }
                                    ?>
                                    <label class="radio-inline"><input type="radio" name="is_l1" value="1" <?= $checkIsl11 ?>> YES</label>&nbsp;&nbsp;
                                    <label class="radio-inline"><input type="radio" name="is_l1" value="0" <?= $checkIsl12 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            
                        </div>
                </div>
                <div class="col-md-12 col-xs-12">
                    <div class="pull-right btn-section">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-default" name="submit" id="submit2" value="submit" style="default">Continue</button>                                                            
                        </div>
                    </div>
                </div>
            </div>                      
        </div>
    </div>