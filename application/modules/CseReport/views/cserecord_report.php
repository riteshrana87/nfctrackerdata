<script>
    var baseurl = '<?php echo base_url(); ?>';

</script>
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
            <h1 class="page-title">
                CSE RECORD REPORT  
                <div class="pull-right pull-left-sm-xs for-tab">
                    <form id="savecsereport" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?= base_url('CseReport/generatePDF/' . $yp_id); ?>" >
                        <div class="btn-group">
                            <?php if($past_care_id == 0){ ?>
                            <a href="<?php echo base_url('CseReport/index/' . $yp_id); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> CSE Record Sheet List</a>
                             <?php }else{?>
                                <a href="<?php echo base_url('CseReport/index/' . $yp_id .'/'. $care_home_id .'/'. $past_care_id); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> CSE Record Sheet List</a>
                             <?php } ?>   
                            <button type="submit" class="btn btn-default" name="submit" id="submit">
                                <i class="fa fa-cloud-download" aria-hidden="true"></i> Export PDF CSE Record Report
                            </button>
                        </div>
                        <input type="hidden" name="yp_id" value="<?php echo $yp_id; ?>">
                        <input type="hidden" name="past_care_id" value="<?php echo $past_care_id; ?>">
                        <input type="hidden" name="movedate" value="<?php echo $movedate; ?>">
                        <input type="hidden" name="created_date" value="<?php echo $created_date; ?>">

                        <input type="hidden" name="year" id="get_Year" value="<?php echo $year; ?>">
                        <input type="hidden" name="month" id="get_Month" value="<?php echo $month; ?>">

                    </form>
                </div>
                <div class="clearfix"></div>
            </h1>
            <h1 class="page-title">
                <small>Name: </small><?php echo isset($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?php echo isset($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>Gender:</small>  <?php
                if (!empty($YP_details[0]['gender'])) {
                    if ($YP_details[0]['gender'] == 'M') {
                        ?> Male<?php } else { ?>Female <?php
                        }
                    } else {
                        ?> N/A <?php } ?> 
                <div class="pull-right">
                    <div class="form-group">
                        <div class="input-group input-append date time-input1" id='cse_year_record' style='max-width: 120px;'>
                            <input type="text" id="cse_year" class="form-control input-sm" placeholder="Select Year" name='cse_year' value='<?php //echo $year;   ?>'>
                            <div class="input-group-addon">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pull-right">
                    <div class="form-group">
                        <div class="input-group input-append date time-input1" id='cse_month_record' style='max-width: 130px;'>
                            <input type="text" id="cse_month" class="form-control input-sm" placeholder="Select Month" name='cse_month' value='<?php //echo $month;   ?>'>
                            <div class="input-group-addon">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </h1>
        </div>
        <div class="row">

        </div>
        <input type="hidden" value="<?php echo $yp_id; ?>" name="yp_id" id="yp_id">
        <?php 
        /*
            @Author : Ritesh rana
            @Desc   : Get past care home data As per create date and move date
            @Input  : create date and pmove data
            @Output : CSE report data
            @Date   : 25/09/2018
        */
        ?>
        <input type="hidden" name="past_care_id" id="past_care_id" value="<?php echo $past_care_id; ?>">
        <input type="hidden" name="movedate" id="movedate" value="<?php echo $movedate; ?>">
        <input type="hidden" name="created_date" id="CreatedDate" value="<?php echo $created_date; ?>">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left"></h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="line_container" style="width: 100%;">
                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>

        <div class="row">
            <!-- total pie -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Total Indicators</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="total_container" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>

        <div class="row">
            <!-- healt pie -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Health</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="health_container" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>

            <!-- Behaviour pie -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Behaviour </h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="behaviour_container" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <!-- Grooming pie -->
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Grooming</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="groom_container" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <!-- Looked After Children pie -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Looked After Children</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="child_container" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <!-- Family and Social pie -->

        </div>
        <div class="row">
            <!-- E safety -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Family and Social</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30 m-b-xs-30 m-b-sm-30">
                        <div id="soc_container" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">E safety</h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30 m-b-xs-30 m-b-sm-30">
                        <div id="safety_container" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>

        <div class="row" id="comment_section">
            <?php $this->load->view('comment_section'); ?>
        </div>
        <!-- box start -->
        <div class="row">
            <div class="col-md-12">
                <div class="row div-1-i">
                    <div class="colo4 col-md-12">
                        <div class="col-md-6 green-1">
                            <div class="text-green">
                                0. No Known risk
                            </div>
                        </div>
                        <div class="col-md-6 green-1">
                            No history or evidence at present to indicate likelihood of risk from behaviour.
                            No current indication of risk.
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="colo3 col-md-12">
                        <div class="col-md-6 green-2">
                            <div class="text-green">
                                1. Low apparent risk
                            </div>
                        </div>
                        <div class="col-md-6 green-2">
                            No current indication of risk but young person’s history
                            indicates possible risk from identified behaviour.
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="colo2 col-md-12">
                        <div class="col-md-6 green-3">
                            <div class="text-green">
                                2. Medium apparent risk
                            </div>
                        </div>
                        <div class="col-md-6 green-3">
                            Young person’s history and current behaviour indicates
                            the presence of risk but action has already been identified to moderate risk.
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="colo col-md-12">
                        <div class="col-md-6 green-4">
                            <div class="text-green">
                                3. High apparent risk
                            </div>
                        </div>
                        <div class="col-md-6 green-4">
                            The young person’s circumstances indicate that the behaviour may result in a risk of serious harm without
                            intervention from one or more agency.
                            The young person will commit the behaviour as soon as they are able and the risk of significant harm is considered imminent.
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="graph-text col-md-3">
                        <p>No known risk = 0</p>
                        <p>Low risk = 1</p>
                        <p>Med Risk = 2</p>
                        <p>High Risk = 3</p>
                    </div>
                    <div class="graph-text col-md-3">
                        <p>0-85 = Low</p>
                        <p>86-170 = Medium</p>
                        <p>171-255 = High</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- box over -->
    </div>
</div>

