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
              <p class="lead">Current Medication</p>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                     <?php
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                 if(!empty($row['displayonlist']))
                                {
                                ?>
                                    <th <?php
                                    if (isset($sortfield) && $sortfield == $row['name']) {
                                        if ($sortby == 'asc') {
                                            echo "class = 'sort-dsc'";
                                        } else {
                                            echo "class = 'sort-asc'";
                                        }
                                    } else {
                                        echo "class = 'sort'";
                                    }
                                    ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('<?=$row['name']?>', '<?php echo $sorttypepass; ?>')">  <?=!empty($row['label'])?$row['label']:''?></th>
                            <?php } } }?>

                </tr>
                </thead>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        <tbody>
                            <tr>
                            <?php
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                 if(!empty($row['displayonlist']))
                                {
                                    if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($data[$row['name']]) && !empty($data[$row['name']])){
                                        $data[$row['name']] = timeformat($data[$row['name']]);
                                    }
                                    if($row['type'] == 'date' && isset($data[$row['name']]) && !empty($data[$row['name']])){
                                        $data[$row['name']] = configDateTime($data[$row['name']]);   
                                    }
                                ?>
                                <td> <?=(!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')?nl2br(html_entity_decode($data[$row['name']])):(isset($row['value'])?$row['value']:'')?></td>
                            <?php } } }?>
                                
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($form_data)?count($form_data):'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        
    </div> 
          
        </div><!-- /.row -->
      </section><!-- /.content -->