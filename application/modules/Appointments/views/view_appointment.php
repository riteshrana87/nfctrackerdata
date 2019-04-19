<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
            <h1 class="page-title">
                Appointment / Event <small>New Forest Care</small>
            </h1>
            <h1 class="page-title">
                <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
            </h1>
        </div>
        <div class="row">
            <div class="panel panel-default tile tile-profile m-t-10">
                <div class="panel-body min-h-310">
                    <h2>Appointment / Event</h2>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date of Appointment / Event:</label>
                                <?=!empty($mp_data[0]['appointment_date'])?configDateTime($mp_data[0]['appointment_date']):''?> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                               <label>TIME : </label>
                                <?=!empty($mp_data[0]['appointment_time'])?timeformat($mp_data[0]['appointment_time']):''?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Type of Appointment / Event :</label>
                               <?=!empty($mp_data[0]['appointment_type'])?html_entity_decode($mp_data[0]['appointment_type']):''?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Comments :</label>
                               <?=!empty($mp_data[0]['comments'])?html_entity_decode($mp_data[0]['comments']):''?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-group pull-right">
                            <?php if($is_archive_page==0){?>
                            <a href="<?=base_url('Appointments/index/'.$mp_data[0]['yp_id'])?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back</a>
                            <?php } else { ?>
                            <a href="<?=base_url('Appointments/index/'.$mp_data[0]['yp_id'].'/'.$careHomeId.'/'.$is_archive_page)?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back</a>
                            <?php }?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>                
        </div>
    </div>
</div>