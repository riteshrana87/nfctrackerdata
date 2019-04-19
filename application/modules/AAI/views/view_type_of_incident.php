<div class="panel panel-default">
    <div class="panel-heading aai-page-header" role="tab" id="headingTwo">
        <h4 class="panel-title">
                TYPE OF INCIDENT
        </h4>
    </div>
        <div class="panel-body form-horizontal">
            <div class="row aai-module clear-div-flex">                                
                <div class="col-md-12">
                        <div class="form-group form-margin">                                                                    
                              
                            <div class="col-md-4 col-sm-12"> Was physical intervention used? </div>
                            <div class="col-md-2 col-sm-12">
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
                            <div class="col-md-4 col-sm-12 "> Did YP go missing? </div>
                            <div class="col-md-2 col-sm-12 ">
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
                            <div class="clearfix"></div>   
                            <div class="col-md-4 col-sm-12 "> Was the YP injured? </div>
                            <div class="col-md-2 col-sm-12 ">
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
                            <div class="col-md-4 col-sm-12"> Has a complaint been made either by the YP or on behalf of the YP? </div>
                            <div class="col-md-2 col-sm-12">
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
                             <div class="clearfix"></div> 
                            <div class="col-md-4 col-sm-12"> Is any part of this incident a safeguarding concern? </div>
                            <div class="col-md-2 col-sm-12 ">
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

                            <div class="col-md-4 col-sm-12"> Was a staff member injured? </div>
                            <div class="col-md-2 col-sm-12">
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
                             <div class="clearfix"></div>  
                            <div class="col-md-4 col-sm-12"> Was anyone else injured? </div>
                            <div class="col-md-2 col-sm-12">
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
