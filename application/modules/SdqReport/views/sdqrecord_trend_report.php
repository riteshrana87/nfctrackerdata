<script>
    var baseurl = '<?php echo base_url(); ?>';
</script>

<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            SDQ RECORD TREND REPORT  
            <div class="pull-right  pull-left-sm-xs for-tab">
              <div class="btn-group">
			  <?php 
				/*ghelani nikunj
					5/10/2018
					if in care to care archive then no need to show button
					*/
				if($is_archive_page==0){?>
                <a href="<?= base_url('SdqReport/SdqRecordReport/' . $yp_id); ?>" class="btn btn-default">
                           <i class="fa fa-mail-reply"></i> Back</a>
                <a href="<?php echo base_url('SdqReport/index/' . $yp_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> SDQ Record Sheet List</a>
				<?php } else {?>
				
				<a href="<?= base_url('SdqReport/SdqRecordReport/' . $yp_id.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                           <i class="fa fa-mail-reply"></i> Back</a>
                <a href="<?php echo base_url('SdqReport/index/' . $yp_id.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> SDQ Record Sheet List</a>
				
				<?php }?>
              </div>               
            </div>
        <div class="clearfix"></div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?php echo isset($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?php echo isset($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>Gender:</small>  <?php if(!empty($YP_details[0]['gender'])){ if($YP_details[0]['gender'] == 'M'){ ?> Male<?php } else{ ?>Female <?php }} else{ ?> N/A <?php } ?> 
        </h1>
    </div>
        <input type="hidden" value="<?php echo $yp_id; ?>" name="yp_id" id="yp_id">
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
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="page-title pull-left">Total Difficulties </h1>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="pie_container" style="width: 100%;">

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
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="pie_emotional_symp" style="width: 100%;">

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
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="pie_con_scale" style="width: 100%;">

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
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-30">
                        <div id="pie_hyperactivity" style="width: 100%;">

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
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body m-b-xs-30 m-b-sm-30">
                        <div id="pie_peer_problem" style="width: 100%;">

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
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-body">
                        <div id="pie_pro_behav" style="width: 100%;">

                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
</div>


