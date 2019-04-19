<script>
    var baseurl = '<?php echo base_url(); ?>';
    var check_Do_url = "<?php echo base_url('DailyObservation/isDuplicateDo'); ?>";
</script>
<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Daily Observation <small>New Forest Care</small>
          
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            <?php
            if (($this->session->flashdata('msg'))) {
                echo $this->session->flashdata('msg');
            }
            ?>
            <form action="<?=base_url('DailyObservation/checkDo')?>" method="post" id="doform" name="doform" data-parsley-validate enctype="multipart/form-data">
                <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($ypid)?$ypid:''?>">
                        <input type="hidden" name="care_home_id" id="care_home_id" value="<?=!empty($care_home_id)?$care_home_id:''?>">
                    <div class="col-sm-12">
                        <div class="">
                            <h1 class="page-title">Select Date</h1>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>Daily Observation Date</h2>
                                    <div class="col-sm-6">
           <div class="form-group">
                <div class="input-group input-append date do_adddate">
                    <input type="text" required="true" name="create_date" id="create_date" class="form-control" autocomplete="true" readonly="" data-parsley-trigger="change" data-parsley-required-message="Please enter date." data-parsley-errors-container="#DO_error" data-parsley-create_date>

                    <div class="input-group-addon add-on">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                <span id="DO_error"></span>
            </div>
                                    </div>
                            </div>
                        </div>
                    </div>
					<div class="pull-right btn-section" >
						<div class="btn-group" >
							 
                        
                        
                            <button type="submit" class="btn btn-default" name="submit_doform" id="submit_doform" value="submit" style="default">Check For Existing Observation</button>
                            <?php if(checkPermission('DailyObservation','view')){ ?>
                    <a href="<?=base_url('DailyObservation/index/'.$ypid); ?>" class="btn btn-default"><i class="fa fa-mail-reply"></i> DO</a>
                    <?php } ?>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> YP INFO</a>
                             <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        
							
						</div>
					</div>
            </form>        
        </div>
        
    </div>
</div>
