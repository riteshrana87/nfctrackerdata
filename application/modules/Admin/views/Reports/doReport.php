<?php
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <?php $this->load->view(ADMIN_SITE . '/assets/reporttype'); ?>
        <h1>
            Reports
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">DO Reports</h3>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap">
                            <?php
                            $attributes = array("name" => "generate_report_form", "id" => "generate_report_form", "class" => "form-horizontal", 'novalidate' => '');
                            echo form_open_multipart($form_action_path, $attributes);
                            ?>
                            <div class="row">                                
                                    <div id="example1_filter" class="dataTables_filter m-b-xs-10 m-b-lg-10">
                                    <div class="col-lg-2 col-md-4 col-sm-6 text-left m-b-md-10">
                                        
                                            <select class='form-control chosen-select' id='yp_name' name='yp_name'>
                                                    <option value=''> Select YP </option>
                                                    <?php foreach ($ypList as $ypData) { ?>
                                                        <option value="<?= $ypData['yp_id'] ?>" ><?= $ypData['ypName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            
                                        
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-4 col-sm-6 m-b-md-10">
                                        
                                            
                                                <div class="input-group" id='admin_report_start_date'>
                                                    <input type="text" class="form-control" placeholder="Start Date" name='admin_report_start_date' value=''>
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            
                                        
                                        </div>  
                                        <div class="col-lg-2 col-md-4 col-sm-6 m-b-md-10">
                                        
                                            
                                                <div class="input-group" id='admin_report_end_date'>
                                                    <input type="text" class="form-control" placeholder="End Date" name='admin_report_end_date' value=''>
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            
                                        
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 text-left m-b-md-10">
                                        
                                            
                                                <select class='form-control chosen-select' id='created_by' name='created_by'>
                                                    <option value=''> Created By </option>
                                                    <?php foreach ($loginList as $loginData) { ?>
                                                        <option value="<?= $loginData['login_id'] ?>" ><?= $loginData['created_by']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            
                                        
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 text-left m-b-md-10">
                                        
                                            
                                                <select class='form-control chosen-select' id='staff' name='staff'>
                                                    <option value=''>Select staff </option>
                                                     <?php if(!empty($loginList)){ ?>
                                                        <?php foreach ($loginList as $loginData) { ?>
                                                        <option value="<?= $loginData['login_id'] ?>" ><?= $loginData['created_by']; ?></option>
                                                    
                                                        <?php } } ?>
                                                </select>
                                            
                                        
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 text-right m-b-md-10">
                                        
                                            
                                                <input type="hidden" name='reportType' id='reportType' value='<?= $reportType ?>' />
                                                <input type="hidden" id="sortfield" name="sortfield" value="" />
                                                <input type="hidden" id="sortby" name="sortby" value="" />
                                                <input type='submit' id='show_report' name='show_report' class="btn btn-primary howler"  title="Search" value='Search' >
                                                <button class="btn btn-primary howler flt" title="Reset" onclick="reset_report_data()" title="Reset">Reset</button>
                                           
                                        
                                         </div>
                                      <div class="clearfix"></div>
                                    </div>
                                
                            </div>
                            <?php echo form_close(); ?>

                            <div class="clearfix"></div>
                            <div class="whitebox" id="common_div">
                            </div>
                        </div><!-- /.box-body -->
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->