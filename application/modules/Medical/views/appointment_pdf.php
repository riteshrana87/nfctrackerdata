<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                NFC Tracker
                <small class="pull-right">Date: <?php echo date("d/m/Y"); ?></small>
            </h2>
        </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-xs-12">
            <p class="lead" style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">MEDICAL INFORMATION</p>
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
              <p class="lead">Appointments</p>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                     <th <?php
                        if (isset($sortfield) && $sortfield == 'mp_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1">  Professional Name</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'appointment_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1">  Appointment Date</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'appointment_time') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1">  Appointment Time</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'comments') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" >  Comments</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'address') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1">  Address</th>

                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>"> 
                </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                       
                            <tr>
                                <td><?=!empty($data['mp_name'])?$data['mp_name']:''?></td>
                                <td><?=(!empty($data['appointment_date']) && $data['appointment_date'] !='0000-00-00')?configDateTime($data['appointment_date']):''?> 
                                </td>
                                <td>
                                <?=(!empty($data['appointment_time']) && $data['appointment_time'] !='0000-00-00')?timeformat($data['appointment_time']):''?>
                                </td>
                                <td>
                                    <?=!empty($data['comments'])?$data['comments']:''?>
                                </td>
                                <td><?=!empty($data['address'])?$data['address']:''?></td>
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