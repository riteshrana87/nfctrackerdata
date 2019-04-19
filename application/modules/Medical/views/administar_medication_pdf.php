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
              <p class="lead">Administration History</p>
            <table class="table table-bordered table-striped">
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
                        <?php if(isset($stock) && !empty($stock)){?>
                                    <th>Quantity Remaining</th>
                               <?php  } ?>

                         
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>"> 
                </tr>
                </thead>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        <tbody>
                            <tr>
                                <?php
                                if (!empty($form_data)) {
                                    foreach ($form_data as $row) {
                                        if (!empty($row['displayonlist'])) {
                                            ?>
                                            <td> 
                                                <?php
                                                if ($row['type'] == 'select') {
                                                    if (!empty($data[$row['name']])) {
                                                        if(!empty($row['description']) && $row['description'] == 'get_user'){
                                                        
                                                        $get_data = $this->common_model->get_single_user($data[$row['name']]);
                                                        echo!empty($get_data[0]['username']) ? wordwrap($get_data[0]['username'],15,"<br>\n") : '';

                                                    }else if(!empty($row['description']) && $row['description'] == 'get_medication'){
                                     
              $get_medication_data = $this->common_model->get_single_medication($data[$row['name']]);
              echo!empty($get_medication_data[0]['medication_name']) ? wordwrap($get_medication_data[0]['medication_name'],15,"<br>\n") : ''; 
                        } ?>

                                                <?php }} else if($row['type'] == 'date'){
                                                    ?>
                                                    <?php 
                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')){
                                            echo configDateTime($data[$row['name']]);
                                    }
                                    ?>

                                                <?php }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                                    ?>
                                                    <?php 
                                                    if((!empty($data[$row['name']]))){
                                                        echo timeformat($data[$row['name']]);
                                                    }
                                    ?>

                                <?php }else{ ?>
                                     <?=(!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')?nl2br(html_entity_decode($data[$row['name']])):(isset($row['value'])?$row['value']:'')?> 
                                <?php } ?>
                                            </td>

                                            <?php

                                        }
                                    }
                                }
                                ?>
                            <?php 
                                            if(!empty($data['available_stock']) && isset($data['available_stock'])){?>
                                            <td>
                                                    <?php echo $data['available_stock'];?>
                                            </td>
                                            <?php }else{ ?>
                                                <td>
                                                    <?php echo 0;?>
                                            </td>
                                            <?php } ?>


                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($mp_form_data)?count($mp_form_data):'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        
    </div>
          
        </div><!-- /.row -->
      </section><!-- /.content -->