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
                <div class="col-md-10">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body form-margin">                                                                    
                              
                            <div class="col-md-4 col-sm-12 p-l-r-0 m-b-15"> Was physical intervention used? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
                                <div class="form-group">
                                    <?php
                                    if($is_pi == 1){
                                        echo '<label class="radio-inline"><span class="label label-success">Yes</span></label>';
                                    }else{
                                        echo '<label class="radio-inline"><span class="label label-danger">No</span></label>';
                                    }
                                    ?>
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 p-l-r-0 m-b-15"> Did YP go missing? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
                                <div class="form-group">
                                    <?php
                                    if($is_yp_missing == 1){
                                        echo '<label class="radio-inline"><span class="label label-success">Yes</span></label>';
                                    }else{
                                     echo '<label class="radio-inline"><span class="label label-danger">No</span></label>';
                                    }
                                    ?>
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 p-l-r-0 m-b-15"> Was the YP injured? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
                                <div class="form-group">
                                    <?php
                                    if($is_yp_injured == 1){
                                        echo '<label class="radio-inline"><span class="label label-success">Yes</span></label>';
                                    }else{
                                        echo '<label class="radio-inline"><span class="label label-danger">No</span></label>';
                                    }
                                    ?>
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 p-l-r-0 m-b-15"> Has a complaint been made either by the YP or on behalf of the YP? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
                                <div class="form-group">
                                    <?php
                                    if($is_yp_complaint == 1){
                                       echo '<label class="radio-inline"><span class="label label-success">Yes</span></label>';
                                    }else{
                                       echo '<label class="radio-inline"><span class="label label-danger">No</span></label>';
                                    }
                                    ?>
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 p-l-r-0 m-b-15"> Is any part of this incident a safeguarding concern? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
                                <div class="form-group">
                                    <?php
                                    if($is_yp_safeguarding == 1){
                                        echo '<label class="radio-inline"><span class="label label-success">Yes</span></label>';
                                    }else{
                                        echo '<label class="radio-inline"><span class="label label-danger">No</span></label>';
                                    }
                                    ?>
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 p-l-r-0 m-b-15"> Was a staff member injured? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
                                <div class="form-group">
                                    <?php
                                    if($is_staff_injured == 1){
                                        echo '<label class="radio-inline"><span class="label label-success">Yes</span></label>';
                                    }else{
                                        echo '<label class="radio-inline"><span class="label label-danger">No</span></label>';
                                    }
                                    ?>
                                </div> 
                            </div>    
                            <div class="col-md-4 col-sm-12 p-l-r-0 m-b-15"> Was anyone else injured? </div>
                            <div class="col-md-2 col-sm-12 m-b-15">
                                <div class="form-group">
                                    <?php
                                    if($is_other_injured == 1){
                                        echo '<label class="radio-inline"><span class="label label-success">Yes</span></label>';
                                    }else{
                                        echo '<label class="radio-inline"><span class="label label-danger">No</span></label>';
                                    }
                                    ?>
                                </div> 
                            </div>    
                            
                        </div>
                    </div>
                </div>
            </div>                      
        </div>
    </div>
</div>