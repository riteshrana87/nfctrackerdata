<?php
$admin_session = $this->session->userdata('nfc_admin_session');
?>

<script>
    var baseurl = '<?php echo base_url(); ?>';
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

  <?php echo $this->session->flashdata('msg'); ?>  
    <!-- Main content -->
    <section class="content">
        <!-- /.row -->
        <div class="row">
            <!-- /.col -->
            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">YP Data</h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id='admin_report_data'>
                                    <input type="text" id="admin_report" class="form-control input-sm" placeholder="Start Year" name='admin_report' value='<?php echo date('Y'); ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">Daily Observation</h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id='do_report_data'>
                                    <input type="text" id="do_report" class="form-control input-sm" placeholder="Start Year" name='do_report' value='<?php echo date('Y'); ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="do-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">Key Sessions</h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id='ks_report_data'>
                                    <input type="text" id="ks_report" class="form-control input-sm" placeholder="Start Year" name='ks_report' value='<?php echo date('Y'); ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="ks-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            
            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">Placement Plan</h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id='pp_report_data'>
                                    <input type="text" id="pp_report" class="form-control input-sm" placeholder="Start Year" name='pp_report' value='<?php echo date('Y'); ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="pp-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            
            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">Individual Behaviour Plan</h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id='ibp_report_data'>
                                    <input type="text" id="ibp_report" class="form-control input-sm" placeholder="Start Year" name='ibp_report' value='<?php echo date('Y'); ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="ibp-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            
            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">Risk Assessment</h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id='ra_report_data'>
                                    <input type="text" id="ra_report" class="form-control input-sm" placeholder="Start Year" name='ra_report' value='<?php echo date('Y'); ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="ra-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">Documents</h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id='docs_report_data'>
                                    <input type="text" id="docs_report" class="form-control input-sm" placeholder="Start Year" name='docs_report' value='<?php echo date('Y'); ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="docs-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            
            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">Medical Authorisations & Consents </h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id='meds_report_data'>
                                    <input type="text" id="meds_report" class="form-control input-sm" placeholder="Start Year" name='meds_report' value='<?php echo date('Y'); ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="meds-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            
            
            <div class="col-md-6">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title m-b-xs-40">Communication</h3>
                        <div class="box-tools pull-right">
                            <div class="col-lg-3 p-r-0 col-lg-offset-9 col-md-4 col-md-offset-8 col-sm-3 col-sm-offset-9 col-xs-12 m-t-xs-30" >
                                <div class="input-group dateadd" id="coms_report_data">
                                    <input type="text" id="coms_report" class="form-control input-sm" placeholder="Start Year" name="coms_report" value="<?php echo date('Y'); ?>">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="coms-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">

    function save_data()
    {
        $('div .error').html('');
    }
</script>