<script>
    var baseurl = '<?php echo base_url(); ?>';
</script>

<div id="page-wrapper" class="sdqReport">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            SDQ RECORD REPORT  
            <div class="pull-right pull-left-sm-xs for-tab">
              <form id="savesdqreport" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?= base_url('SdqReport/generatePDF/' . $yp_id); ?>" >
              <div class="btn-group">
			  <?php 
				/*ghelani nikunj
					5/10/2018
					if in care to care archive then no need to show button
					*/
				if($is_archive_page==0){?>
                    <a href="<?= base_url('SdqReport/index/' . $yp_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> SDQ Record Sheet List</a>
                    <a href="<?= base_url('SdqReport/SdqTrendReport/' . $yp_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-forward"></i> SDQ Record Trend Report</a>
                <input type="hidden" name="yp_id" value="<?php echo $yp_id; ?>">
                <input type="hidden" name="year" id="get_Year" value="<?php echo $year; ?>">
                <button type="submit" class="btn btn-default" name="submit" id="submit">
                    <i class="fa fa-cloud-download" aria-hidden="true"></i> Export PDF SDQ Record Report
                  </button>
				<?php } else {?>
				<a href="<?= base_url('SdqReport/index/' . $yp_id.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> SDQ Record Sheet List</a>
                    <a href="<?= base_url('SdqReport/SdqTrendReport/' . $yp_id.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                        <i class="fa fa-mail-forward"></i> SDQ Record Trend Report</a>
                <input type="hidden" name="yp_id" value="<?php echo $yp_id; ?>">
                <input type="hidden" name="year" id="get_Year" value="<?php echo $year; ?>">
                <button type="submit" class="btn btn-default" name="submit" id="submit">
                    <i class="fa fa-cloud-download" aria-hidden="true"></i> Export PDF SDQ Record Report
                  </button>
				
				<?php }?>
                </div>
                </form>
              
            </div>
          <div class="clearfix"></div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?php echo isset($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?php echo isset($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>Gender:</small>  <?php if(!empty($YP_details[0]['gender'])){ if($YP_details[0]['gender'] == 'M'){ ?> Male<?php } else{ ?>Female <?php }} else{ ?> N/A <?php } ?> 
        </h1>
    </div>
        <input type="hidden" value="<?php echo $yp_id; ?>" name="yp_id" id="yp_id">
  
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Total Difficulties </h1>
                        <div class="pull-right">
                            <div class="form-group">
                                <div class="input-group input-append date time-input1" id='total_diff_year' style='max-width: 120px;'>
                                    <input type="text" id="total_dif_report" class="form-control input-sm" placeholder="Start Year" name='total_dif_report' value='<?php echo $year; ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="container" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Emotional Symptoms Scale </h1>
                        <div class="pull-right">
                            <div class="form-group">
                                <div class="input-group input-append date time-input1" id='emo_sympt_year' style='max-width: 120px;'>
                                    <input type="text" id="emo_sympt_report" class="form-control input-sm" placeholder="Start Year" name='emo_sympt_report' value='<?php echo $year; ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="emotional_symp" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Conduct Problem Scale </h1>
                        <div class="pull-right">
                            <div class="form-group">
                                <div class="input-group input-append date time-input1" id='con_scale_year' style='max-width: 120px;'>
                                    <input type="text" id="con_scale_report" class="form-control input-sm" placeholder="Start Year" name='con_scale_report' value='<?php echo $year; ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="con_scale" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Hyperactivity Scale</h1>
                            <div class="pull-right">
                                <div class="form-group">
                                <div class="input-group input-append date time-input1" id='hyp_scale_year' style='max-width: 120px;'>
                                    <input type="text" id="hyp_scale_report" class="form-control input-sm" placeholder="Start Year" name='hyp_scale_report' value='<?php echo $year; ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="hyperactivity" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Peer Problems Scale</h1>
                        <div class="pull-right">
                            <div class="form-group">
                                <div class="input-group input-append date time-input1" id='peer_scale_year' style='max-width: 120px;'>
                                    <input type="text" id="peer_scale_report" class="form-control input-sm" placeholder="Start Year" name='peer_scale_report' value='<?php echo $year; ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-xs-30 m-b-sm-30">
                        <div id="peer_problem" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Prosocial Scale</h1>
                        <div class="pull-right">
                            <div class="form-group">
                                <div class="input-group input-append date time-input1" id='pro_scale_year' style='max-width: 120px;'>
                                    <input type="text" id="pro_scale_report" class="form-control input-sm" placeholder="Start Year" name='pro_scale_report' value='<?php echo $year; ?>'>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body">
                        <div id="pro_behav" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
</div>
