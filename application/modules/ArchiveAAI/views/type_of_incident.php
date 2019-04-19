<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
        <h4 class="panel-title">
            <a id="incident_form_link" class="collapsed accordion-toggle text-uppercase" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                TYPE OF INCIDENT
            </a>
        </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
        <div class="panel-body">
            <div class="row aai-module">                                
                <div class="col-md-12 ">
                    <div class="panel panel-default tile tile-profile aai-border-box">
                        <div class="panel-body form-margin">                                                                    
                              
                            <div class="col-md-4 col-sm-12 m-b-15"> Was physical intervention used? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
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
                                    <label><input type="radio" name="is_pi" value="1" <?= $checkIsPI1 ?>> YES</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="is_pi" value="0" <?= $checkIsPI2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 m-b-15"> Did YP go missing? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
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
                                    <label><input type="radio" name="is_yp_missing" value="1" <?= $checkIsYPMiss1 ?>> YES</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="is_yp_missing" value="0" <?= $checkIsYPMiss2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 m-b-15"> Was the YP injured? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
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
                                    <label><input type="radio" name="is_yp_injured" value="1" <?= $checkIsYPInjured1 ?>> YES</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="is_yp_injured" value="0" <?= $checkIsYPInjured2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 m-b-15"> Has a complaint been made either by the YP or on behalf of the YP? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
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
                                    <label><input type="radio" name="is_yp_complaint" value="1" <?= $checkIsYPComplaint1 ?>> YES</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="is_yp_complaint" value="0" <?= $checkIsYPComplaint2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 m-b-15"> Is any part of this incident a safeguarding concern? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
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
                                    <label><input type="radio" name="is_yp_safeguarding" value="1" <?= $checkIsYPSafeguarding1 ?>> YES</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="is_yp_safeguarding" value="0" <?= $checkIsYPSafeguarding2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 m-b-15"> Was a staff member injured? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
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
                                    <label><input type="radio" name="is_staff_injured" value="1" <?= $checkIsStaffInjured1 ?>> YES</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="is_staff_injured" value="0" <?= $checkIsStaffInjured2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 m-b-15"> Was anyone else injured? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
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
                                    <label><input type="radio" name="is_other_injured" value="1" <?= $checkIsOtherInjured1 ?>> YES</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="is_other_injured" value="0" <?= $checkIsOtherInjured2 ?>> NO</label>&nbsp;&nbsp;
                                </div> 
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
</div>