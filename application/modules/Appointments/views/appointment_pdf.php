<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                NFC Tracker
                <small class="pull-right"></small>
            </h2>
        </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-xs-12">
            <p class="lead" style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">Appointment / Event</p>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-3 invoice-col">
            <strong>YP Name</strong>
            <address>
                <?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?>
                <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?>
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            <strong>Care home:-</strong>
            <address>
               <?= !empty($YP_details[0]['care_home_name']) ? $YP_details[0]['care_home_name'] : '' ?>
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            <strong>DOB:-</strong>
            <address>
               <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            <strong>Email</strong>
            <address>
                <?= !empty($YP_details[0]['email']) ? $YP_details[0]['email'] : '' ?>
            </address>
        </div>
    </div><!-- /.row -->
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-xs-12">
            <p class="lead">Appointment / Event</p>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>  Appointment / Event Date</th>
                        <th>  Appointment / Event Time</th>
                        <th>  Type of Appointment / Event</th>
                        <th>  Comments</th>
                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>"> 
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                <?php foreach ($information as $data) { ?>                       
                <tr>
                    <td><?=(!empty($data['appointment_date']) && $data['appointment_date'] !='0000-00-00')?configDateTime($data['appointment_date']):''?></td>
                    <td><?=(!empty($data['appointment_time']) && $data['appointment_time'] !='0000-00-00')?timeformat($data['appointment_time']):''?></td>
                    <td><?=(!empty($data['appointment_type']))?$data['appointment_type']:''?></td>
                    <td><?=(!empty($data['comments']))?$data['comments']:''?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>        
        </div>          
    </div><!-- /.row -->
 </section><!-- /.content -->