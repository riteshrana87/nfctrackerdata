<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypId; ?>';
</script>


        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');                    
                } ?>
                <div class="sticky-heading row for_center_ver" id="sticky-heading">
                <h1 class="page-title col-md-6 col-xs-12">
                    Accident And Incident Reports <small>New Forest Care</small>
                    
                   
                </h1>
                <div class="pull-right for-tab col-md-6 text-right">
                        <div class="btn-group">
                                <a href="<?=base_url('AAIReport/Dashboard')?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"> Dashboard Reports</a>
                                
                                <a href="<?=base_url('AAIReport/Management')?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"> Management Reports</a>
                            
                        </div>
                    </div>
                 </div>
                 <div class="clearfix"></div>
                <div class="row chart_de">
                <!-- filter options -->
                
              
                    <div class=" filter_form" id="searchForm">
                          <div class="col-md-2 col-lg-2 col-sm-6 tite_report_dashboard">Dashboard Reports</div>
                           <div class="col-lg-2 col-md-2 col-sm-6 text-left aai_input_select">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home" id="care_home">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-6 text-left aai_input_select">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="location" id="location">
                                    <option value="0">Select location</option>
                                    <?php foreach($location as $lc_data){?>
                                    <option value="<?php echo $lc_data['option_id']?>"><?php echo $lc_data['title']?></option>
                                    <?php }?>
                            </select>
                            </div>
                             </div>
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-6 text-left aai_input_select">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="yp_id" id="yp_id">
                                    <option value="0">Select YP</option>
                                    <?php foreach($all_yp as $yp_data){?>
                                    <option value="<?php echo $yp_data['yp_id']?>"><?php echo $yp_data['ypName']?></option>
                                    <?php }?>
                            </select>
                            </div>
                             </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 text-left aai_input_select">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date">
                            <input class="form-control" name="from_date" id="from_date" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 text-left aai_input_select last_chld">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date">
                            <input class="form-control" name="to_date" id="to_date" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home" id="care_home" value="" />
                        <input type="hidden" name="location" id="location" value="" />
                        <input type="hidden" name="yp_id" id="yp_id" value="" />
                        <input type="hidden" name="from_date" id="from_date" value="" />
                        <input type="hidden" name="to_date" id="to_date" value="" />
                        
         
            </div>
                    <div class="clearfix"></div>
            <div class="col-md-12 p-l-r-0">
                        <div class="col-md-6 for_box_De_re">
                         <div class="box-header with-border">
                        <h1 class="page-title pull-left ">Number of Incidents by Status</h1>
                    </div>
                           
                                    <div class="clearfix"></div>
                                        <div class="box-body m-b-30">
                                        <!--report -->
                                            <div id="container_status" style="width: 100%;">

                                            </div>
                                        
                            </div>
                        </div><div class="col-md-6 for_box_De_re">
                         <div class="box-header with-border">
                        <h1 class="page-title pull-left ">Number of Incidents by Type</h1>
                    </div>
                           
                                    <div class="clearfix"></div>
                                        <div class="box-body m-b-30">
                                        <!--report -->
                                            <div id="container" style="width: 100%;">

                                          
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
            <div class="col-md-12 p-l-r-0">
                 <div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left ">Number of Incidents by Care Home</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="count_incident" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Total lenth of time of PI excluding laying</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="related_to_l2_form" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>
            <div class="clearfix"></div>
            <div class="col-md-12 p-l-r-0">
            <div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Total incident duration (PI)</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="related_to_l2AndL3_form" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Total laying duration (PI)</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="related_to_L3_form" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
</div>
<div class="clearfix"></div>
            <div class="col-md-12 p-l-r-0">
            <div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Total time without PI (in release)
</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="related_to_L1_form" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

<div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Number of Incidents requiring Police involvement</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="related_to_police_involvement" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
</div>
<div class="clearfix"></div>
            <div class="col-md-12 p-l-r-0">
            <div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Number of Incidents requiring REG40 Notification</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="related_to_REG40_form" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Related To L7 LADO </h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="related_to_l7lado" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
			</div>
            <div class="clearfix"></div>
            <div class="col-md-12 p-l-r-0">
			<div class="col-md-6 for_box_De_re">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Number Of Incidents requiring review </h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="related_to_status_panding_for_each_incident" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

</div>
            
                </div>
               
            </div>
        </div>
         <?= $this->load->view('/Common/common', '', true); ?>
