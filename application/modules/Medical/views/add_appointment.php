<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var Appointment_id = '<?= !empty($mp_data[0]['appointment_id']) ? $mp_data[0]['appointment_id'] : '' ?>';
    var check_Do_url = "<?php echo base_url('DailyObservation/isDuplicateDo'); ?>";
</script>
<?php
  if(isset($edit))
  {
     $url = base_url('Medical/update_appointment');
  }
  else
  {
      $url = base_url('Medical/insert_appointment');
  }

?>
<div id="page-wrapper">
    <div class="main-page">
      <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Medical Information <small>New Forest Care</small>
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
                                <h2><?=!isset($edit)?'ADD':'EDIT'?> MEDICAL APPOINTMENT</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Professionals <span class="astrick">*</span></label>
                                             <select class="form-control chosen-select" name="dr_name[]" <?=!isset($edit)?'multiple=""':''?> required="" data-parsley-errors-container="#errors-container-mp" data-placeholder="Select Professional">
                                             
                                              <?php if(!empty($mp_yp_data)) {
                                                 foreach ($mp_yp_data as $select) {
                                                    ?>
                                                     <option <?=(!empty($mp_id) && $mp_id == $select['mp_id'])?"selected='selected'":''?> value="<?=!empty($select['mp_id'])?$select['mp_id']:''?>">
                                                         <?=!empty($select[$form_data[1]['name']])?$select[$form_data[1]['name']]:''?>
                                                         <?=!empty($select[$form_data[2]['name']])?$select[$form_data[2]['name']]:''?>
                                                         <?=!empty($select[$form_data[3]['name']])?$select[$form_data[3]['name']]:''?>
                                                          <?=!empty($select[$form_data[0]['name']])?'('.$select[$form_data[0]['name']].')':''?>
                                                           
                                                     </option>
                                                 <?php } } ?>
                                                 </select>
                                                 <span id="errors-container-mp"></span>
                                        </div>
                                    </div>
                                </div>
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
                                              <label>Date of Appointment <span class="astrick">*</span></label>
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
                                              <label>Date of Start Appointment<span class="astrick">*</span></label>
                                                  <div class="input-group input-append date add_start_date">
                                                  <input value="<?=!empty($mp_data[0]['appointment_start_date'])?configDateTime($mp_data[0]['appointment_start_date']):''?>" class="form-control" type="text" value="" name="appointment_start_date" id="appointment_start_date" readonly="" data-parsley-errors-container="#errors-container-startdate" >
                                                   <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                              </div>
                                              <span id="errors-container-startdate"></span>
                                             
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <div class="form-group">
                                              <label>Date of End Appointment<span class="astrick">*</span></label>
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
                                        <input type="hidden" name="mp_id" value="<?= !empty($mp_data[0]['mp_id']) ? $mp_data[0]['mp_id'] : '' ?>" id="" >
                                        <input type="hidden" name="appointment_id" value="<?= !empty($mp_data[0]['appointment_id']) ? $mp_data[0]['appointment_id'] : '' ?>" id="" >
                                        
                                        
                                        <input type="submit" class="btn btn-default updat_bn" name="submit_medform" id="submit_medform" value="<?=!isset($edit)?'Add':'Update'?> Medical Appointment" />
										<div class="pull-right btn-section">
											<div class="btn-group">
										  
                                        <a href="<?=base_url('Medical/index/'.$ypid)?>" class=" btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To Meds</a>
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

