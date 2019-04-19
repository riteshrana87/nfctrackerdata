<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypId; ?>';
</script>


        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');                    
                } ?>
                <div class="sticky-heading for_center_ver row" id="sticky-heading">
                <h1 class="page-title col-md-6 col-xs-12">
                    Accident And Incident Reports <small>New Forest Care</small>
					<br><small>Management Reports</small>
                 </h1>
                    <div class="pull-right for-tab col-md-6 text-right">
                        <div class="btn-group">
                                <a href="<?=base_url('AAIReport/Dashboard/')?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Dashboard Reports</a> 
								
								<a href="<?=base_url('AAIReport/Management/')?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"> Management Reports</a>
                         
                        </div>
                    </div>
               
               
            </div>
                <div class="row">
				<!-- filter options -->
				
                    <div class="col-md-12 p-l-r-0">
					
					
						<div class="col-md-6 for_box_De_re ">
						
						<h1 class="page-title pull-left">Comparison of number of incident between care homes</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select  ">
                             <div class="form-group">
                            <div class="mul_sel">

                            <select class="form-control for_multiselect" multiple name="care_home_1[]" id="care_home_1">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                    
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_1">
                            <input class="form-control" name="from_date_1" id="from_date_1" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_1">
                            <input class="form-control" name="to_date_1" id="to_date_1" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_1" id="care_home_1" value="" />
                        <input type="hidden" name="from_date_1" id="from_date_1" value="" />
                        <input type="hidden" name="to_date_1" id="to_date_1" value="" />
                        
         
            </div>
							
									<div class="clearfix"></div>
										<div class="box-body m-b-30">
										<!--report -->
											<div id="comparison_of_number_of_incident_between_care_homes" style="width: 100%;">

										
								</div>
							</div>
						</div>
						<div class="col-md-6 for_box_De_re">
						
			<h1 class="page-title pull-left">Time elapsed between first stage</h1>
            <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home_2" id="care_home_2">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                        
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="yp_id_2" id="yp_id_2">
                                    <option value="0">Select YP</option>
                                    <?php foreach($all_yp as $yp_data){?>
                                    <option value="<?php echo $yp_data['yp_id']?>"><?php echo $yp_data['ypName']?></option>
                                    <?php }?>
                            </select>
                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_2">
                            <input class="form-control" name="from_date_2" id="from_date_2" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_2">
                            <input class="form-control" name="to_date_2" id="to_date_2" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_2" id="care_home_2" value="" />
                        <input type="hidden" name="yp_id_2" id="yp_id_2" value="" />
                        <input type="hidden" name="from_date_2" id="from_date_2" value="" />
                        <input type="hidden" name="to_date_2" id="to_date_2" value="" />
                        
         
            </div>
									<div class="clearfix"></div>
										<div class="box-body m-b-30">
										<!--report -->
											<div id="time_elapsed_between_first_stage" style="width: 100%;">

											</div>
										</div>
								
						</div>
                    </div>
                    <div class="clearfix"></div>
              <div class="col-md-12 p-l-r-0">
                 <div class="col-md-6 for_box_De_re">
				 
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Number and type of incident by YP Over time</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home_3" id="care_home_3">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="yp_id_3" id="yp_id_3">
                                    <option value="0">Select YP</option>
                                    <?php foreach($all_yp as $yp_data){?>
                                    <option value="<?php echo $yp_data['yp_id']?>"><?php echo $yp_data['ypName']?></option>
                                    <?php }?>
                            </select>
                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_3">
                            <input class="form-control" name="from_date_3" id="from_date_3" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_3">
                            <input class="form-control" name="to_date_3" id="to_date_3" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_3" id="care_home_3" value="" />
                        <input type="hidden" name="yp_id_3" id="yp_id_3" value="" />
                        <input type="hidden" name="from_date_3" id="from_date_3" value="" />
                        <input type="hidden" name="to_date_3" id="to_date_3" value="" />
                        
         
            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="number_of_type_of_incident_by_yp_over" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>

                <!-- /.box -->
            </div>

            <div class="col-md-6 for_box_De_re">
			
			
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Number and level of incidents  by staff member over time</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="staff_name_4" id="staff_name_4">
                                    <option value="0">Select Staff</option>
                                    <?php foreach($all_staff_data as $staff_data){?>
                                    <option value="<?php echo $staff_data['user_id']?>"><?php echo $staff_data['first_name'].' '.$staff_data['last_name'];?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                    
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_4">
                            <input class="form-control" name="from_date_4" id="from_date_4" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_4">
                            <input class="form-control" name="to_date_4" id="to_date_4" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="staff_name_4" id="staff_name_4" value="" />
                        <input type="hidden" name="from_date_4" id="from_date_4" value="" />
                        <input type="hidden" name="to_date_4" id="to_date_4" value="" />
                        
         
            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="numner_and_level_of_incidents_by_staff_member_over_time" style="width: 100%;">

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
                        <h1 class="page-title pull-left">Number of Sactions(Per YP and per care home)</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home_5" id="care_home_5">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                    
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="yp_id_5" id="yp_id_5">
                                    <option value="0">Select YP</option>
                                    <?php foreach($all_yp as $yp_data){?>
                                    <option value="<?php echo $yp_data['yp_id']?>"><?php echo $yp_data['ypName']?></option>
                                    <?php }?>
                            </select>
                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_5">
                            <input class="form-control" name="from_date_5" id="from_date_5" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_5">
                            <input class="form-control" name="to_date_5" id="to_date_5" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_5" id="care_home_5" value="" />
                        <input type="hidden" name="yp_id_5" id="yp_id_5" value="" />
                        <input type="hidden" name="from_date_5" id="from_date_5" value="" />
                        <input type="hidden" name="to_date_5" id="to_date_5" value="" />
                        
         
            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="number_of_sactions" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
			
			<div class="col-md-6 for_box_De_re">
			
			
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Banding-ticks gained and grade in comparison to incident by yp and carehome</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home_6" id="care_home_6">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_6">
                            <input class="form-control" name="from_date_6" id="from_date_6" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_6">
                            <input class="form-control" name="to_date_6" id="to_date_6" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_6" id="care_home_6" value="" />
                        <input type="hidden" name="from_date_6" id="from_date_6" value="" />
                        <input type="hidden" name="to_date_6" id="to_date_6" value="" />
                        
         
            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="bandingticks_gained_and_grande_in_coparison_to_incident_by_yp_and_carehome" style="width: 100%;">

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
                        <h1 class="page-title pull-left">Banding-number of ticks gained against total of possible gained</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home_7" id="care_home_7">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                    
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_7">
                            <input class="form-control" name="from_date_7" id="from_date_7" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_7">
                            <input class="form-control" name="to_date_7" id="to_date_7" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_7" id="care_home_7" value="" />
                        <input type="hidden" name="from_date_7" id="from_date_7" value="" />
                        <input type="hidden" name="to_date_7" id="to_date_7" value="" />
                        
         
            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="banding_number_of_ticks_gained_against_total_of_possible_gained" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
<div class="col-md-6 for_box_De_re">
			
			
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Number of Complaints by YP</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home_8" id="care_home_8">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                        
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="yp_id_8" id="yp_id_8">
                                    <option value="0">Select YP</option>
                                    <?php foreach($all_yp as $yp_data){?>
                                    <option value="<?php echo $yp_data['yp_id']?>"><?php echo $yp_data['ypName']?></option>
                                    <?php }?>
                            </select>
                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_8">
                            <input class="form-control" name="from_date_8" id="from_date_8" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_8">
                            <input class="form-control" name="to_date_8" id="to_date_8" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_8" id="care_home_8" value="" />
                        <input type="hidden" name="yp_id_8" id="yp_id_8" value="" />
                        <input type="hidden" name="from_date_8" id="from_date_8" value="" />
                        <input type="hidden" name="to_date_8" id="to_date_8" value="" />
                        
         
            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="number_of_complaints_by_yp" style="width: 100%;">

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
                        <h1 class="page-title pull-left">Number of Complaints by CareHome</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home_9" id="care_home_9">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_9">
                            <input class="form-control" name="from_date_9" id="from_date_9" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_9">
                            <input class="form-control" name="to_date_9" id="to_date_9" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_9" id="care_home_9" value="" />
                        <input type="hidden" name="from_date_9" id="from_date_9" value="" />
                        <input type="hidden" name="to_date_9" id="to_date_9" value="" />
                        
         
            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="number_of_complaints_by_carehome" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
	<div class="col-md-6 for_box_De_re">
			
			
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Number Of Safeguarding occurences by YP and CareHome</h1>
                        <div class="col-md-12 seprate_filter" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="care_home_10" id="care_home_10">
                                    <option value="0">Select CareHome</option>
                                    <?php foreach($care_home_data as $care_data){?>
                                    <option value="<?php echo $care_data['care_home_id']?>"><?php echo $care_data['care_home_name']?></option>
                                    <?php }?>
                                    
                            </select>
                            </div>
                             </div>
                        </div>
                        
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="">
                            <select class="form-control" name="yp_id_10" id="yp_id_10">
                                    <option value="0">Select YP</option>
                                    <?php foreach($all_yp as $yp_data){?>
                                    <option value="<?php echo $yp_data['yp_id']?>"><?php echo $yp_data['ypName']?></option>
                                    <?php }?>
                            </select>
                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_from_date_10">
                            <input class="form-control" name="from_date_10" id="from_date_10" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left aai_input_select ">
                             <div class="form-group">
                            <div class="input-group input-append date " id="aai_report_to_date_10">
                            <input class="form-control" name="to_date_10" id="to_date_10" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                            

                            </div>
                             </div>
                        </div>
                        <input type="hidden" name="search" id="search" value="" />
                        <input type="hidden" name="professional_name" id="professional_name_value" value="" />
                        <input type="hidden" name="care_home_10" id="care_home_10" value="" />
                        <input type="hidden" name="yp_id_10" id="yp_id_10" value="" />
                        <input type="hidden" name="from_date_10" id="from_date_10" value="" />
                        <input type="hidden" name="to_date_10" id="to_date_10" value="" />
                        
         
            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="numer_of_safeguarding_occurences_by_yp_and_carehome" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

            </div>
            <div class="clearfix"></div>
                </div>
               
            </div>
        </div>
         <?= $this->load->view('/Common/common', '', true); ?>


<!-- Script for multi select dropdown By ARKITA -->       
<script>


    $(document).ready(function () {
    $('.for_multiselect').multiselect({
        buttonWidth: '160px',
        includeSelectAllOption: true,
        nonSelectedText: 'Select an Option' });

});

function getSelectedValues() {
    var selectedVal = $(".for_multiselect").val();
    for (var i = 0; i < selectedVal.length; i++) {var
        innerFunc = function innerFunc(i) {
            setTimeout(function () {
                location.href = selectedVal[i];
            }, i * 2000);
        };
        innerFunc(i);
    }
}
</script>
<!-- Script for multi select dropdown By ARKITA over-->