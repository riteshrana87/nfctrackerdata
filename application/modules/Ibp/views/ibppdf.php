
<style>

</style>
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
            <p class="lead" style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">Individual Behaviour Plan</p>
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
            <strong>Address</strong>
            <address>
                <?= !empty($YP_details[0]['address_1']) ? $YP_details[0]['address_1'] : '' ?><br>
                <?= !empty($YP_details[0]['town']) ? $YP_details[0]['town'] : '' ?>  <?= !empty($YP_details[0]['county']) ? $YP_details[0]['county'] : '' ?> <?= !empty($YP_details[0]['postcode']) ? $YP_details[0]['postcode'] : '' ?><br>
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            <strong>Phone</strong>
            <address>
                <?= !empty($YP_details[0]['mobile']) ? $YP_details[0]['mobile'] : '' ?>
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
            <?php
            if (!empty($ibp_form_data)) {
                $i =0;
                foreach ($ibp_form_data as $row) {

                    if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        $i++;
                        ?>
                      <div class="<?= ($row['type'] == 'header') ? 'col-xs-6' : 'col-xs-6' ?> dont-break" >
                        <p class="lead"><?= !empty($row['label']) ? $row['label'] : '' ?></p>   
                      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <?php if ($row['type'] == 'textarea' || $row['type'] == 'number') { ?>
                          <?php 
                              $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                ?>
                                <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>

                          <?php }else if($row['type'] == 'text'){
                                if($row['subtype'] == 'time'){
                                    $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                ?>
                                <?= !empty($data_textarea) ? timeformat($data_textarea) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                             <?php }else{ 
                               $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                ?>
                                <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>
                             <?php } ?>

                          <?php }else if($row['type'] == 'date'){ 
                             $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                ?>
                                <?= !empty($data_textarea) ? configDateTime($data_textarea) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>

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
            
            <?php 
                    echo ($i % 2 == 0 && $i != 0) ? '<div class="clearfix"></div>' : ''; ?>
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
                                'filePathMain' => $this->config->item('ibp_img_base_url') . $ypid,
                                'filePathThumb' => $this->config->item('ibp_img_base_url_small') . $ypid
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
        <!-- /.row -->
      </section><!-- /.content -->