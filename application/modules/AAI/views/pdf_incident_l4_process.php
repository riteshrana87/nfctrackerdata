

<table cellpadding="0" cellspacing="0" width="100%" border="1" style=" margin: 0; border-collapse: collapse;margin:0;padding: 0;border-color: #CCCCCC;font-family: arial">
    <tr style="background-color: #4e6031;margin:0;padding:0;">
        <td align="left" colspan="2" style="text-transform: uppercase;font-size: 18px; margin:0;color:white;text-align:left;height: auto;  "><p style="margin:0;padding:10px;">INCIDENT L4 PROCESS (YP MISSING FROM CARE)</p>
        </td>
    </tr>

<?php if (!empty($l4_form_data)) {
    foreach ($l4_form_data as $row) {
        $dd = $row['name'];
        $n  = 'display:none!important;';
        $y  = 'display:block!important;';

      if($row['name'] == 'l4_police_informed_pi' && $row['value'] == 'No') {
            $yesno[]='l4_informed_by_pi';
            $yesno[]='l4_how_the_information_was_send_pi';
            $yesno[]='l4_who_was_informed_pi';
            $yesno[]='l4_police_reference_number_pi';
            $yesno[]='l4_date_informed_pi';
            $yesno[]='l4_time_informed_pi';
        }
      ?>
            <?php if ($row['type'] == 'header') {
               if (!in_array($dd, $yesno)) {
              ?>
    <tr id="div_<?=$row['name']?>" style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
    <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
      <p style="margin:0; padding:10px;">  <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b></p>
    </td>
  </tr>
  <?php } ?>
<?php } elseif ($row['type'] == 'textarea') { 
       if (!in_array($dd, $yesno)) {
  ?>
              <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style=""><?=!empty($row['label']) ? $row['label'] : ''?></b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                       <?=!empty($l4FormData[0][$row['name']]) ? nl2br(html_entity_decode($l4FormData[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '')?>
                    </p>
                </td>
              </tr>

               <?php if ($row['label'] == 'Details') {?>
               <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Send Notification To S.worker</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                      <input readonly placeholder="Notification sent date and time" type="text" class="form-control" name="calculate_notification_worker" id="calculate_notification_worker" value="<?php echo $l4calculate_notification_worker;?>"/>
                    </p>
                </td>
              </tr>
			  

               <?php } elseif ($row['name'] == 'state') {
                ?>
               <tr style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
                  <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
                    <p style="margin:0; padding:10px;"> <b class="page-title">Person Informed For YP Return</b></p>
                  </td>
               </tr>
               <?php if (!empty($l4return_data)) {
                    $ipr = 1;
                    foreach ($l4return_data as $retkey => $retvalue) {
                        ?>
                <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Persons Informed</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                      <?php if (!empty($persons_infromed)) {
                      foreach ($persons_infromed as $select) { 
                       if (isset($retvalue['person_informed']) && ($retvalue['person_informed'] == $select['value'])) { echo $select['label'];}
                       ?>

                     <?php }}?>
                    </p>
                </td>
              </tr>

              <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Name Of Person Informed</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <?php echo $retvalue['person_name']; ?>
                    </p>
                </td>
              </tr>
              <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Badge Number</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <?php echo $retvalue['badge_number']; ?>
                    </p>
                </td>
              </tr>
              <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Contact Number</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <?php echo $retvalue['contact_number']; ?>
                    </p>
                </td>
              </tr>

              <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Contact Email</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <?php echo $retvalue['contact_email']; ?>
                    </p>
                </td>
              </tr>
              <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Informed By</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                      <?php if (!empty($bambooNfcUsers)) {
                      foreach ($bambooNfcUsers as $select) { 
                       if (isset($retvalue['informed_by']) && ($retvalue['informed_by'] == $select['user_type'].'_'.$select['user_id'])) { echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email'];}
                        }
                  }?>
                    </p>
                </td>
              </tr>
              <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Date</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <?php echo configDateTime($retvalue['date_informed']); ?>
                    </p>
                </td>
              </tr>
               <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Time</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <?php echo timeFormatAi($retvalue['time_informed']); ?>
                    </p>
                </td>
              </tr>
        <?php $ipr++;}}?>
    <?php } } ?>
<?php } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date'){ 
       if (!in_array($dd, $yesno)) {
  ?>
               <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style=""><?=!empty($row['label']) ? $row['label'] : ''?></b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <?=(isset($row['value'])) ? nl2br(html_entity_decode($row['value'])) : ''?>
                    </p>
                </td>
              </tr>

               <?php if ($row['label'] == 'Location Last Seen'){ ?>
               <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Notification sent date and time</b>
                </td>
                <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <input readonly placeholder="Notification sent date and time" type="text" class="form-control" name="calculate_notification_missing" id="calculate_notification_missing" value="<?php if(isset($l4calculate_notification_missing)){echo $l4calculate_notification_missing;}?>"/>
                    </p>
                </td>
              </tr>
            <?php } elseif ($row['label'] == 'Time YP returned'){ ?>
            <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Total Time YP Was Missing</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                     <input type="hidden"  class="input-textar-style form-control" name="l4_total_duration" id="l4_total_duration" value="" />
                    </p>
                </td>
              </tr>
            <?php } }?>

        <?php } else if ($row['type'] == 'radio-group') { 
               if (!in_array($dd, $yesno)) {
          ?>
        <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>


                              <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
                                       <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                                        <?php

            if (count($row['values']) > 0) {
                foreach ($row['values'] as $radio) {
                    if (!empty($radio['label'])) {

                        ?>
                                        <?php
if (isset($row['value']) && $row['value'] == $radio['value'] && $row['value'] == 'Yes') {
                            echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                        } else if (isset($row['value']) && $row['value'] == $radio['value'] && $row['value'] == 'No') {
                            echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                        } else {
                            if (!isset($row['value'])) {
                                if (!empty($radio['selected']) && $radio['value'] == 'Yes') {
                                    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                                } else {
                                    echo '<div style=" color: #d9534f; font-weight: bold;">NO</div>';
                                }
                            }?>
                            <?php }
                        ?>
                     <?php }}}?>

                                       </div>

                              </td>
                           </tr>
                       <?php } ?>    
          <?php } else if ($row['type'] == 'checkbox-group') { 
                 if (!in_array($dd, $yesno)) {
            ?>
      <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if (count($row['values']) > 0) {
                $checkedValues = array();
                if (isset($row['value']) && $row['value'] !== '') {
                    $checkedValues = explode(',', $row['value']);
                }
                foreach ($row['values'] as $checkbox) {
                    if (!empty($checkbox['label'])) {
                        ?>
                          <input disabled="" class="<?=!empty($row['className']) ? $row['className'] : ''?> <?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"
                                                  name="<?=!empty($row['name']) ? $row['name'] . '[]' : ''?>" value="<?=!empty($checkbox['value']) ? $checkbox['value'] : ''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues)) ? 'checked="checked"' : !empty($checkbox['selected']) ? 'checked="checked"' : ''?>
                                                       <?=!empty($row['required']) ? 'required=true' : ''?>
                                                       type="checkbox">
                                                <?=!empty($checkbox['label']) ? $checkbox['label'] : ''?>

                                              <?php }}}?>
          </p>
        </td>
    </tr>
    <?php } ?>
<?php } else if ($row['type'] == 'select') { 
       if (!in_array($dd, $yesno)) {
  ?>
        <?php if ($row['className'] == 'bamboo_lookup') { ?>
        <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                               <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                                       <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
               <?php if (!empty($bambooNfcUsers)) {
                    foreach ($bambooNfcUsers as $select) {
                        if (isset($row['value']) && ($row['value'] == $select['user_type'] . '_' . $select['user_id'])) {
                            echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'];

                        }}}?>
          </p>
        </td>
      </tr>
<?php } else if($row['className'] == 'bamboo_lookup_multiple'){ ?>
              <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                               <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                                       <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                <?php if (!empty($bambooNfcUsers)) {
                    foreach ($bambooNfcUsers as $select) {
                        if (in_array($select['user_type'] . '_' . $select['user_id'], explode(',', $row['value']))) {
                            echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] . ',';
                        }}}?>
          </p>
        </td>
      </tr>
<?php } else { ?>


                <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                               <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                                       <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
              <?php if (!empty($row['name']) && !empty($row['className']) && $row['className'] == 'multiple') {
                    ?>
                  <?php if (count($row['values']) > 0) {
                        foreach ($row['values'] as $select) {
                            if ((in_array($select['value'], explode(',', $row['value'])))) {?>
                              <?=!empty($select['label']) ? $select['label'] . ',' : ''?>
                            <?php }}}?>
                  <?php } else {
                    ?>
                  <?php if (count($row['values']) > 0) {
                        foreach ($row['values'] as $select) {
                            if (isset($row['value']) && ($row['value'] == $select['value'])) {?>
                                <?=!empty($select['label']) ? $select['label'] : ''?>
                        <?php }}}}?>
          </p>
        </td>
      </tr>

               <?php } ?>
               <?php if ($row['name'] == 'form_status') { ?>
                <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title">Report Compiler</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">

                <?php 
                                $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'L4');
                                if(!empty($aai_report_com)){ 
                                    foreach ($aai_report_com as $repcom_value) { ?>
                                    <div>
                                   <?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormat($repcom_value['created_date']); ?>
                                   </div>
                        <?php } } ?>  
          </p>
        </td>
      </tr>


               <?php } elseif ($row['name'] == 'risk_level') { ?>
        <tr style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
            <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
                <p style="margin:0; padding:10px;"> <b class="page-title">Person Informed For YP Missing</b></p>
            </td>
        </tr>

          <?php if (!empty($l4missing_yp)) {
                    $i = 1;
                    foreach ($l4missing_yp as $key => $value) { ?>
                  <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title">Persons Informed</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
               <?php if (!empty($persons_infromed)) {
                                    foreach ($persons_infromed as $select) { 
                                     if (isset($value['person_informed']) && ($value['person_informed'] == $select['value'])) { echo $select['label'];}
                                     ?>
                                   <?php }}?>
                  </p>
                </td>
            </tr>

            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title">Name Of Person Informed</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo $value['person_name']; ?>
                      </p>
                </td>
            </tr>
            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Badge Number</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo $value['badge_number']; ?>
                      </p>
                </td>
            </tr>

            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Contact Number</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo $value['contact_number']; ?> 
                      </p>
                </td>
            </tr>

            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Contact Email</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo $value['contact_email']; ?>
                      </p>
                </td>
            </tr>
            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Informed By</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php if (!empty($bambooNfcUsers)) {
                                    foreach ($bambooNfcUsers as $select) { 
                                     if (isset($value['informed_by']) && ($value['informed_by'] == $select['user_type'].'_'.$select['user_id'])) { echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email'];}
                                      }
                                }?>
                      </p>
                </td>
            </tr>
            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Date</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo configDateTime($value['date_informed']); ?>
                      </p>
                </td>
            </tr>

            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Time</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo timeFormatAi($value['time_informed']); ?>
                      </p>
                </td>
            </tr>
          <?php $i++;}} ?>
          <tr style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
            <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
                <p style="margin:0; padding:10px;"> <b class="page-title">Sequence Of Event</b></p>
            </td>
          </tr>
    <?php 
    if (!empty($l4sequence_data)) {
                    $isq = 1;
                    foreach ($l4sequence_data as $seqkey => $seqvalue) {
                      $seq_datal4 = $seqvalue['l4seq_sequence_number'];
                      $seq_datal4 = substr($seq_datal4,1);
                     ?>
              <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Sequence Number</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo 'S'.$isq;?>
                      </p>
                </td>
            </tr>

            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Who(staff full name)</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php if (!empty($bambooNfcUsers)) {
                                        foreach ($bambooNfcUsers as $select) { 
                                         if (isset($seqvalue['who_raised']) && ($seqvalue['who_raised'] == $select['user_type'].'_'.$select['user_id'])) { echo $select['first_name'] . ' ' . $select['last_name'] . '-' . $select['email'];}
                                          }
                                    }?>
                      </p>
                </td>
            </tr>

            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">What Happned / what was done(include senior cover instruction)</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo $seqvalue['What_happened']; ?>
                      </p>
                </td>
            </tr>
            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Date</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo configDateTime($seqvalue['date']); ?>
                      </p>
                </td>
            </tr>
            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">Time</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php echo timeFormatAi($seqvalue['time']); ?>
                      </p>
                </td>
            </tr>

            <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title">All communication details</b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                       <?php echo $seqvalue['communication_details']; ?>
                      </p>
                </td>
            </tr>
  <?php $isq++;}}?>
               <?php }?>
               <?php } ?>
  <?php } else if ($row['type'] == 'file') { ?>
  <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                      <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                          <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                      </td>
                      <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                        <?php $fileViewArray = array(
                'fileArray'        => (isset($row['value']) && !empty($row['value'])) ? $row['value'] : '',
                'filePathMain'     => $this->config->item('aai_img_base_url') . $ypId,
                'filePathThumb'    => $this->config->item('aai_img_base_url_small') . $ypId,
                'deleteFileHidden' => 'hidden_' . $row['name'],
            );
            echo getFileView($fileViewArray);?>
                      </p>
                </td>
            </tr>
    <?php } }} ?>
</table>