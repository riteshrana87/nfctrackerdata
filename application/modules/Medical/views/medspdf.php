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
            <p class="lead" style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">Medical Information</p>
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
    
    <div class="row invoice-info">
        <div class="clearfix"></div>
        <div class="col-sm-3 invoice-col">
            <strong>MED CARD No / NHS No :-</strong>
            <address>
                <?= !empty($mi_details[0]['medical_number']) ? $mi_details[0]['medical_number'] : '' ?>
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            <strong>Date Received:-</strong>
            <address>
               <?= (!empty($mi_details[0]['date_received']) && $mi_details[0]['date_received'] != '0000-00-00') ? configDateTime($mi_details[0]['date_received']) : '' ?>
            </address>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
                <!-- accepted payments column -->
                <div class="col-xs-6 dont-break">
                    <p class="lead">Allergies & Meds not to be Used</p>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <?= !empty($mi_details[0]['allergies_and_meds_not_to_be_used']) ? html_entity_decode($mi_details[0]['allergies_and_meds_not_to_be_used']) : '' ?>
                    </p>
                </div>
    </div>
    
    <div class="clearfix"></div>
    
    
    <div class="row">
        <div class="col-sm-12">
        <div class="">
                <div class="">
                    <h2>Medical Professionals</h2>
                            <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                 <?php
                                 if (isset($sortby) && $sortby == 'asc') {
                                    $sorttypepass = 'desc';
                                } else {
                                    $sorttypepass = 'asc';
                                }
                                    if(!empty($mp_form_data))
                                    {
                                        foreach ($mp_form_data as $row) {
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
                                    
                                    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                                        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($mp_details) && count($mp_details) > 0) { ?>
                                        <?php foreach ($mp_details as $data) { ?>
                                                <tr>
                                                <?php
                                            if(!empty($mp_form_data))
                                            {
                                                foreach ($mp_form_data as $row) {
                                                    if(!empty($row['displayonlist']))
                                                        {
                                                    ?>
                                                    <td> <?=(!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')?nl2br(html_entity_decode($data[$row['name']])):(isset($row['value'])?$row['value']:'')?></td>
                                                        <?php  } } }?>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                               <td colspan="<?=!empty($mp_form_data)?count($mp_form_data)+1:'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                                            </tr>
                                        <?php } ?>
                            </tbody>
                        </table>

                        
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
                    <h2>Other Medical Information</h2>
                    <?php
                    if (!empty($omi_form_data)) {
                        foreach ($omi_form_data as $row) {
                            ?>
                    <p class="lead"><?= !empty($row['label']) ? $row['label'] : '' ?></p>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <?php 
                        $omi_data = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $omi_details[0][$row['name']]); ?>
                        <?= (!empty($omi_details[0][$row['name']]) && $omi_details[0][$row['name']] != '0000-00-00') ? nl2br(html_entity_decode($omi_data)) : (isset($row['value']) ? $row['value'] : '') ?>
                            </p>
                            <hr class="hr-10" />
                            <?php
                        }
                    }
                    ?>
            
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <p class="lead" style="margin-bottom: 15px; font-size: 16px; text-transform: uppercase; font-weight: 300;">Inoculations</p>
             </div>
                <div class="clearfix"></div>
                    <?php
                    if (!empty($mi_form_data)) {
                        foreach ($mi_form_data as $row) {

                            if ($row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                                ?>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <strong><?= !empty($row['label']) ? $row['label'] : '' ?></strong>
                                        <address>
                                            <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 
                                                if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($miform_details[0][$row['name']]) && !empty($miform_details[0][$row['name']])){
                                                    $miform_details[0][$row['name']] = timeformat($miform_details[0][$row['name']]);
                                                }
                                                if($row['type'] == 'date' && isset($miform_details[0][$row['name']]) && !empty($miform_details[0][$row['name']])){
                                                    $miform_details[0][$row['name']] = configDateTime($miform_details[0][$row['name']]);   
                                                }
                                                ?>

                                                <?= !empty($miform_details[0][$row['name']]) ? nl2br(html_entity_decode($miform_details[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '') ?>
                                                <?php
                                            } else if ($row['type'] == 'checkbox-group') {
                                                if (!empty($miform_details[0][$row['name']])) {
                                                    $chk = explode(',', $miform_details[0][$row['name']]);
                                                    foreach ($chk as $chk) {
                                                        echo $chk . "\n";
                                                    }
                                                } else {

                                                    if (count($row['values']) > 0) {

                                                        foreach ($row['values'] as $chked) {
                                                            echo isset($chked['selected']) ? '<li>' . $chked['value'] . "\n" : '';
                                                        }
                                                    }
                                                }
                                                ?>

                                                <?php
                                            } else if ($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                if (!empty($miform_details[0][$row['name']])) {
                                                    echo!empty($miform_details[0][$row['name']]) ? nl2br(htmlentities($miform_details[0][$row['name']])) : '';
                                                } else {
                                                    if (count($row['values']) > 0) {

                                                        foreach ($row['values'] as $chked) {
                                                            echo isset($chked['selected']) ? $chked['value'] : '';
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </address>

                                    </div>
                                </div>
                                <?php
                            } else if ($row['type'] == 'textarea') {
                                ?>
                                <div class="col-xs-6">
                                    
                                        <strong><?= !empty($row['label']) ? $row['label'] : '' ?></strong>
                                        <address class="value large">
                                            <?= !empty($miform_details[0][$row['name']]) ? nl2br(html_entity_decode($miform_details[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '') ?>
                                        </address>

                                </div>
                                <?php
                            } else if ($row['type'] == 'header') {
                                ?>
                                <div class="col-xs-12">
                                    <div class="">
                                        <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                        <?php echo '<' . $head . ' class="page-title">'; ?>
                                        <?= !empty($row['label']) ? $row['label'] : '' ?>

                                        <?php echo '</' . $head . '>'; ?>
                                        <hr class="hr-10">
                                    </div>
                                </div>
                                <?php
                            } else if ($row['type'] == 'file') {
                                ?>
                                <div class="col-xs-12">
                                    <div class="panel panel-default tile tile-profile">
                                        <div class="panel-body">
                                            <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                            <div class="">   
                                                <?php
                                                /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                                $fileViewArray = array(
                                                    'fileArray' => (isset($miform_details[0][$row['name']]) && !empty($miform_details[0][$row['name']]))? $miform_details[0][$row['name']] : '',
                                                    'filePathMain' => $this->config->item('medical_img_base_url') . $ypid,
                                                    'filePathThumb' => $this->config->item('medical_img_base_url_small') . $ypid
                                                );
                                                echo getFileView($fileViewArray); 
                                                ?>                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            ?>

                            <?php
                        } //foreach
                    }
                    ?>

    </div>
    <div class="row invoice-info">
        <div class="col-xs-12">
            <p class="lead" style="margin-bottom: 15px; font-size: 22px; text-transform: uppercase; font-weight: 300;">Medical Authorisations & Consents</p>
            </div>
            <div class="clearfix"></div>
            <?php
            if (!empty($mac_form_data)) {
                foreach ($mac_form_data as $row) {

                    if ($row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
                        <div class="col-xs-3">
                                <strong><?= !empty($row['label']) ? $row['label'] : '' ?></strong>
                                <address>
                                    <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {
                                        if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($mac_details[0][$row['name']]) && !empty($mac_details[0][$row['name']])){
                                            $mac_details[0][$row['name']] = timeformat($mac_details[0][$row['name']]);
                                        }
                                        if($row['type'] == 'date' && isset($mac_details[0][$row['name']]) && !empty($mac_details[0][$row['name']])){
                                            $mac_details[0][$row['name']] = configDateTime($mac_details[0][$row['name']]);
                                        }
                                        ?>
                                        <?= !empty($mac_details[0][$row['name']]) ? nl2br(html_entity_decode($mac_details[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '') ?>
                                        <?php
                                    } else if ($row['type'] == 'checkbox-group') {
                                        if (!empty($mac_details[0][$row['name']])) {
                                            $chk = explode(',', $mac_details[0][$row['name']]);
                                            foreach ($chk as $chk) {
                                                echo $chk . "\n";
                                            }
                                        } else {

                                            if (count($row['values']) > 0) {

                                                foreach ($row['values'] as $chked) {
                                                    echo isset($chked['selected']) ? '<li>' . $chked['value'] . "\n" : '';
                                                }
                                            }
                                        }
                                        ?>

                                        <?php
                                    } else if ($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                        if (!empty($mac_details[0][$row['name']])) {
                                            echo!empty($mac_details[0][$row['name']]) ? nl2br(htmlentities($mac_details[0][$row['name']])) : '';
                                        } else {
                                            if (count($row['values']) > 0) {

                                                foreach ($row['values'] as $chked) {
                                                    echo isset($chked['selected']) ? $chked['value'] : '';
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </address>

                        </div>
                        <?php
                    } else if ($row['type'] == 'textarea') {
                        ?>
                        <div class="col-xs-9">
                                <strong><?= !empty($row['label']) ? $row['label'] : '' ?></strong>
                                <address>
                                    <?= !empty($mac_details[0][$row['name']]) ? nl2br(html_entity_decode($mac_details[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '') ?>
                                </address>
                            
                        </div>
                        <?php
                    } else if ($row['type'] == 'header') {
                        ?>
                        <div class="col-xs-12">
                            <p class="lead">
                                <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                <?php echo '<' . $head . '>'; ?>
                                <?= !empty($row['label']) ? $row['label'] : '' ?>

                                <?php echo '</' . $head . '>'; ?>
                                <hr class="hr-10">
                            
                            </p>
                        </div>
                        <?php
                    } else if ($row['type'] == 'file') {
                        ?>
                        <div class="col-xs-12">
                                    <p class="lead"><?= !empty($row['label']) ? $row['label'] : '' ?></p>
                                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                        <?php
                                        /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                        $fileViewArray = array(
                                            'fileArray' => (isset($mac_details[0][$row['name']]) && !empty($mac_details[0][$row['name']]))? $mac_details[0][$row['name']] : '',
                                            'filePathMain' => $this->config->item('medical_img_base_url') . $ypid,
                                            'filePathThumb' => $this->config->item('medical_img_base_url_small') . $ypid
                                        );
                                        echo getFileView($fileViewArray); 
                                        ?>                               
                                    </p>
                                
                        </div>
                    <?php }
                    ?>

                    <?php
                } //foreach
            }
            ?>
                
    </div>
</section><!-- /.content -->