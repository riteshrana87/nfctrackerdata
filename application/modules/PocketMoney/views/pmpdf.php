<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                NFC Tracker
                <small class="pull-right">Date: <?=date('d/m/Y')?></small>
            </h2>
        </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-xs-12">
            <p class="lead" style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">Pocket Money</p>
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
    
        
            <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            
                            <?php
                        if(!empty($form_data))
                        {
                            ?>
                            <th> Date/Time</th>
                            <?php
                            foreach ($form_data as $row) {
                                if(!empty($row['displayonlist']))
                                {
                                    
                                ?>
                                <th>  <?=!empty($row['label'])?$row['label']:''?></th>
                            <?php } } }?>
                           
                           <th> Created By</th>
                           
                           <?php /* <th class="text-center">Action</th> */ ?>
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
                        </tr>
                    </thead>
                        <tbody>
                                <?php if (isset($edit_data) && count($edit_data) > 0) { ?>
                                    <?php foreach ($edit_data as $data) { ?>
                                        
                                            <tr>
                                            <?php
                                        if(!empty($form_data))
                                        {
                                            ?>
                                            <td><?=!empty($data['created_date'])?configDateTimeFormat($data['created_date']):''?></td>
                                            <?php
                                            foreach ($form_data as $row) {
                                                if(!empty($row['displayonlist'])){
                                                    if($row['type'] == 'date'){
                                                ?>
                                                <td> 
                                                    <?php 
                                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')){
                                                            echo date('d/m/Y', strtotime($data[$row['name']]));
                                                    }
                                                    ?>
                                                </td>
                                                <?php }else if (isset($row['subtype']) && $row['subtype'] == 'time'){ ?>
                                                <td> 
                                                    <?php 
                                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')){
                                                            echo timeformat($data[$row['name']]);
                                                    }
                                                    ?>
                                                </td>
                                                <?php }else if ($row['type'] == 'select') { ?>
                                                <td> 
                                                <?php
                                                                    if (!empty($data[$row['name']])) {
                                                                        if(!empty($row['description']) && $row['description'] == 'get_user'){
                                                                        
                                                                        $get_data = $this->common_model->get_single_user($data[$row['name']]);
                                                                        echo!empty($get_data[0]['username']) ? $get_data[0]['username'] : '';
                                                                    }else if(!empty($row['description']) && $row['description'] == 'get_medication'){
                                                     
                              $get_medication_data = $this->common_model->get_single_medication($data[$row['name']]);
                              ?>
                              
                              <?php
                              echo!empty($get_medication_data[0]['medication_name']) ? $get_medication_data[0]['medication_name'] : ''; ?>
                              </td>
                              <?php
                                        }else{ echo $data[$row['name']];} ?>

                                                <?php }}else{ ?>    
                                <td> <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? (nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?></td>

                            <?php } } }}?>
                                <?php /* <td><?php echo $data['balance']; ?></td> */?>
                                <td><?php echo $data['name']; ?></td>
                               

                            </tr>
                        <?php } ?>
                        <tr><td colspan="10"  style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">Pocket Money balance : <?=!empty($yp_pocket_money[0]['total_balance'])?$yp_pocket_money[0]['total_balance']:0?></td></tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($form_data)?count($form_data)+3:'10'?>"  class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
                </table>
        
      </section><!-- /.content -->