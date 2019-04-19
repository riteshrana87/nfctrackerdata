<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var Appointment_id = '<?= !empty($mp_data[0]['appointment_id']) ? $mp_data[0]['appointment_id'] : '' ?>';
    var check_Do_url = "<?php echo base_url('DailyObservation/isDuplicateDo'); ?>";
</script>
<?php
  if (isset($edit)) {
    $url = base_url('Appointments/update_appointment');
} else {
    $url = base_url('Appointments/insert_appointment');
}
?>
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
            <h1 class="page-title">
                Appointment / Event  <small>New Forest Care</small>
            </h1>
            <h1 class="page-title">
                <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
            </h1>
        </div>
        <div class="">
            <?php if(($this->session->flashdata('msg'))){ ?>
              <div class="col-sm-12 text-center" id="div_msg">
                  <?php echo $this->session->flashdata('msg');?>
              </div>
            <?php } ?>
            <form action="<?=$url?>" method="post" id="docsform" name="docsform" data-parsley-validate enctype="multipart/form-data">
                <div class="panel panel-default tile tile-profile m-t-10">
                    <div class="panel-body min-h-310">
                        <h2><?=!isset($edit)?'ADD':'EDIT'?> Appointment / Event</h2>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Repeat</label>
                                    <input type="checkbox" <?php if(empty($mp_data)) { ?> id="repeat_apt" <?php  } ?> name="repeat" <?=!empty($mp_data[0]['is_repeat'])?'checked':''?> value="1">
                                </div>
                            </div>
                        </div>
                        <div id="without_repeat">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date of Appointment / Event<span class="astrick">*</span></label>
                                        <div class="input-group input-append date" id="appointment_date">
                                            <input value="<?=!empty($mp_data[0]['appointment_date'])?configDateTime($mp_data[0]['appointment_date']):''?>" class="form-control" type="text" required="true" value="" name="appointment_date" id="appointment_date" readonly="" data-parsley-errors-container="#errors-container-date" >
                                            <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <span id="errors-container-date"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>TIME <span class="astrick">*</span></label>
                                        <div class="input-group input-append bootstrap-timepicker">
                                            <input value ="<?=!empty($mp_data[0]['appointment_time'])?timeformat($mp_data[0]['appointment_time']):''?>" class="form-control timepicker appointment_time_data" type="text" name="appointment_time" id="appointment_time" required="true" readonly="" value="" data-parsley-errors-container="#errors-container-time" >
                                            <span class="input-group-addon appointment add-on"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                        <span id="errors-container-time"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="with_repeat">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date of Start Appointment / Event<span class="astrick">*</span></label>
                                        <div class="input-group input-append date add_start_date">
                                            <input value="<?=!empty($mp_data[0]['appointment_start_date'])?configDateTime($mp_data[0]['appointment_start_date']):''?>" class="form-control" type="text" value="" name="appointment_start_date" id="appointment_start_date" readonly="" data-parsley-errors-container="#errors-container-startdate" >
                                            <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <span id="errors-container-startdate"></span>                                             
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date of End Appointment / Event<span class="astrick">*</span></label>
                                        <div class="input-group input-append date add_end_date">
                                            <input value="<?=!empty($mp_data[0]['appointment_end_date'])?configDateTime($mp_data[0]['appointment_end_date']):''?>" class="form-control" type="text" value="" name="appointment_end_date" id="appointment_end_date" readonly="" data-parsley-errors-container="#errors-container-enddate" >
                                            <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <span id="errors-container-enddate"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                               <div class="col-sm-6">
                                   <div class="form-group">
                                       <label>TIME <span class="astrick">*</span></label>
                                       <div class="input-group input-append bootstrap-timepicker">
                                            <input value ="<?=!empty($mp_data[0]['appointment_time'])?timeformat($mp_data[0]['appointment_time']):''?>" class="form-control timepicker appointment_time_data" type="text" name="repeat_appointment_time" id="repeat_appointment_time" readonly="" value="" data-parsley-errors-container="#errors-container-repeat-time" >
                                           <span class="input-group-addon repeatappointment add-on"><i class="fa fa-clock-o"></i></span>
                                       </div>
                                       <span id="errors-container-repeat-time"></span>
                                   </div>
                               </div>
                           </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Select Day </label>
                                        <div class="input-group input-append bootstrap-timepicker">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="weekday[]" value="Monday" data-parsley-errors-container="#ch_error" /><?php echo lang('monday'); ?>
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" name="weekday[]" value="Tuesday" /> <?php echo lang('tuesday'); ?>
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" name="weekday[]" value="Wednesday" /> <?php echo lang('wednesday'); ?>
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" name="weekday[]" value="Thursday" /> <?php echo lang('thursday'); ?>
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" name="weekday[]" value="Friday" /> <?php echo lang('friday'); ?>
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" name="weekday[]" value="Saturday" /> <?php echo lang('saturday'); ?>
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" name="weekday[]" value="Sunday" /> <?php echo lang('sunday'); ?>  
                                            </label>
                                            <div id="ch_error"></div>
                                        </div>
                                       <span id="errors-container-repeat-time"></span>
                                    </div>
                                </div>
                            </div>                                
                        </div><!-- end repeat -->                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Type of Appointment / Event <span class="astrick">*</span></label>
                                        <select class="form-control chosen-select" name="appointment_type" required="" data-parsley-errors-container="#errors-container-mp">
                                         <option value="">Select Type</option>
                                         <option <?=(!empty($mp_data[0]['appointment_type']) && $mp_data[0]['appointment_type'] == 'Activity')?'selected':''?> value="Activity">Activity</option>
                                         <option <?=(!empty($mp_data[0]['appointment_type']) && $mp_data[0]['appointment_type'] == 'Family Contact')?'selected':''?> value="Family Contact">Family Contact</option>
                                         <option <?=(!empty($mp_data[0]['appointment_type']) && $mp_data[0]['appointment_type'] == 'Peer Contact')?'selected':''?> value="Peer Contact">Peer Contact</option>
                                         <option <?=(!empty($mp_data[0]['appointment_type']) && $mp_data[0]['appointment_type'] == 'Clubs')?'selected':''?> value="Clubs">Clubs</option>
                                         <option <?=(!empty($mp_data[0]['appointment_type']) && $mp_data[0]['appointment_type'] == 'Other')?'selected':''?> value="Other">Other</option>
                                     </select>
                                     <span id="errors-container-mp"></span>                                           
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Comments</label>
                                    <textarea name="comments" rows="5" class="form-control tinyeditor"><?=!empty($mp_data[0]['comments'])?$mp_data[0]['comments']:''?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">                                        
                                <input type="hidden" name="yp_id" value="<?php echo $ypid;?>" id="yp_id" >
                                <!-- nikunj ghelani  add carehome id and archive id for care to care archive functionality 21-9-2018 -->
                                <input type="hidden" name="care_home_id" value="<?php echo $care_home_id;?>" id="care_home_id" >                                        
                                <!-- end here -->										
                                <input type="hidden" name="planner_id" value="<?=!empty($mp_data[0]['planner_id']) ? $mp_data[0]['planner_id'] : '' ?>">
                                <input type="submit" class="btn btn-default updat_bn" name="submit_medform" id="submit_medform" value="<?=!isset($edit)?'Add':'Update'?> Appointment / Event" />
                                <div class="pull-right btn-section">
                                    <div class="btn-group">
                                        <a href="<?=base_url('Appointments/index/'.$ypid)?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>        
        </div>
    </div>
</div>

