<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                NFC Tracker
                <small class="pull-right">Date: <?= (!empty($edit_data_lastedit_data[0]['modified_date'])) ? configDateTime($edit_data_lastedit_data[0]['modified_date']) : '' ?></small>
            </h2>
        </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-xs-12">
            <p class="lead" style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">RiskAssesment</p>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12">
            <strong>YP Name:</strong>
            <span>
                <?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?>
                <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?>
            </span>
        </div>
        <div class="col-sm-12">
            <strong>DOB:</strong>
            <span>
                <?= !empty($YP_details[0]['date_of_birth']) ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
            </span>
        </div>
        <div class="col-sm-12">
            <strong>LAST EDIT DATE:</strong>
            <span>
                <?= !empty($edit_data_lastedit_data[0]['modified_date']) ? configDateTimeFormat($edit_data_lastedit_data[0]['modified_date']) : '' ?>
            </span>
        </div>
        
        <div class="col-sm-12">
            <strong>Email</strong>
            <span>
                <?= !empty($YP_details[0]['email']) ? $YP_details[0]['email'] : '' ?>
            </span>
        </div>
    </div><!-- /.row -->
    <div class="clearfix"></div>
    <div class="row">
            <?php
            if (!empty($ra_form_data)) {
                foreach ($ra_form_data as $row) {

                    if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
                      <div class="<?= ($row['type'] == 'header') ? 'col-xs-12' : 'col-xs-12' ?> dont-break" >
                        <p class="lead"><?= !empty($row['label']) ? $row['label'] : '' ?></p>   
                      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { ?>
                          <?php 
                              $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                ?>

                                <?php if($row['subtype'] == 'time'){ ?>
                                                            <?= !empty($data_textarea) ? nl2br(timeformat($data_textarea)) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                                       <?php }elseif($row['type'] == 'date'){ ?>
                                                            <?= !empty($data_textarea) ? nl2br(configDateTime($data_textarea)) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                                       <?php }else{ ?>
                                                            <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>
                                                       <?php } ?>
                                            
                        </p>
                        <div class="table-responsive">
                        <table class="table">
                            <tr>
                        <?php
                                        } else if ($row['type'] == 'checkbox-group') {
                                            if (!empty($edit_data[0][$row['name']])) {
                                                $chk = explode(',', $edit_data[0][$row['name']]);
                                                foreach ($chk as $chk) {?>
                                                    <th><?php echo $chk . "\n";?></th>
                                               <?php }
                                            } else {

                                                if (count($row['values']) > 0) {

                                                    foreach ($row['values'] as $chked) {?>
                                                        <th><?php echo isset($chked['selected']) ? '<li>' . $chked['value'] . "\n" : '';?></th>
                            
                                                        <?php }
                                                }
                                            }
                                            ?>

                                            <?php
                                        } else if ($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                            if (!empty($edit_data[0][$row['name']])) { ?>
                                                
                                                <td class="text-right"><span class="label <?php if($edit_data[0][$row['name']] == 'yes'){?> label-success <?php }else{ ?> label-danger <?php }?>"><?php echo!empty($edit_data[0][$row['name']]) ? nl2br(htmlentities($edit_data[0][$row['name']])) : ''; ?></span></td>
                                           <?php  } else {
                                                if (count($row['values']) > 0) {
                                                    foreach ($row['values'] as $chked) { ?>
                                                        <td class="text-right"><span class="label <?php if($chked['value'] == 'yes'){?> label-success <?php }else{ ?> label-danger <?php }?> "><?php echo isset($chked['selected']) ? $chked['value'] : ''; ?></span></td>
                                                        
                                                    <?php }
                                                }
                                            }
                                        }
                                        ?>
                        </tr>
                        </table>
                    </div>
                </div>
                        </div>
            
                        <?php
                    } else if ($row['type'] == 'header') {
                        ?>
                        <div class="col-xs-12">
                            <p class="lead">
                                <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                <?php echo '<' . $head . ' class="page-title cl">'; ?>
                                <?= !empty($row['label']) ? $row['label'] : '' ?>

                                <?php echo '</' . $head . '>'; ?>
                            </p>
                        </div>
                        <?php
                    } else if ($row['type'] == 'file') {
                        ?>
                        <div class="col-xs-12">
                            <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class="">   
                                    <?php 
                                    /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                    $fileViewArray = array(
                                        'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                                        'filePathMain' => $this->config->item('ra_img_base_url') . $ypid,
                                        'filePathThumb' => $this->config->item('ra_img_base_url_small') . $ypid
                                    );
                                    echo getFileView($fileViewArray);
                                    ?>                              
                                    </div>
                            </div>
                        </div>
                        <?php
                    }
                } //foreach
            }
            ?>
        </div><!-- /.row -->
      </section><!-- /.content -->