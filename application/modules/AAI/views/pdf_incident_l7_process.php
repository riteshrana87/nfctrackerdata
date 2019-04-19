<table cellpadding="0" cellspacing="0" width="100%" border="1" style=" margin: 0; border-collapse: collapse;margin:0;padding: 0;border-color: #CCCCCC;font-family: arial">
    <tr style="background-color: #4e6031;margin:0;padding:0;">
        <td align="left" colspan="2" style="text-transform: uppercase;font-size: 18px; margin:0;color:white;text-align:left;height: auto;  "><p style="margin:0;padding:10px;">INCIDENT L7 PROCESS (SAFEGUARDING)</p>
        </td>
    </tr>
   <tr style="margin:0;padding: 0">
                               <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Report Compiler</b>
                               </td>
                              <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                                 <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                     <?php 
                                $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'L7');
                                if(!empty($aai_report_com)){ 
                                    foreach ($aai_report_com as $repcom_value) { ?>
                                    <div>
                                   <?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormat($repcom_value['created_date']); ?>
                                   </div>
                                        
                        <?php } } ?>  
                                 </p>
                              </td>
                           </tr>

<?php if (!empty($l7_form_data)) {

    $yesno = array();
    foreach ($l7_form_data as $row) {
        $dd = $row['name'];
        $n  = 'display:none!important;';
        $y  = 'display:block!important;';
        if ($row['name'] == 'is_disclosure_staff' && $row['value'] == 'No') {
            $yesno[] = 'l7_restricted_access';
            $yesno[] = 'allowed_access';
        } elseif ($row['name'] == 'is_evidence_information_passed_to_call' && $row['value'] == 'No') {
            $yesno[] = 'detail_evidence';
        } elseif ($row['name'] == 'l7_social_worker_informed' && $row['value'] == 'No') {
            $yesno[] = 'l7_lnformed_by_social_worker';
            $yesno[] = 'l7_who_was_informed_social_worker';
            $yesno[] = 'l7_how_the_information_was_send_sw';
            $yesno[] = 'l7_date_lnformed_social_worker';
            $yesno[] = 'l7_time_informed_sw';
        } elseif ($row['name'] == 'l7_edt_informed' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_edt';
            $yesno[] = 'l7_how_the_information_was_send_edt';
            $yesno[] = 'l7_who_was_informed_edt';
            $yesno[] = 'l7_date_informed_edt';
            $yesno[] = 'l7_time_informed_edt';
        } elseif ($row['name'] == 'l7_edt_informed' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_pci';
            $yesno[] = 'l7_how_the_information_was_send_pci';
            $yesno[] = 'l7_who_was_informed_pci';
            $yesno[] = 'l7_date_informed_pci';
            $yesno[] = 'l7_time_informed_pci';
        } elseif ($row['name'] == 'l7_police_informed_pi' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_pi';
            $yesno[] = 'l7_how_the_information_was_send_pi';
            $yesno[] = 'l7_who_was_informed_pi';
            $yesno[] = 'l7_police_reference_number_pi';
            $yesno[] = 'l7_date_informed_pi';
            $yesno[] = 'l7_time_informed_pi';
        } elseif ($row['name'] == 'l7_lado_informed_li' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_li';
            $yesno[] = 'l7_how_the_information_was_send_li';
            $yesno[] = 'l7_who_was_informed_li';
            $yesno[] = 'l7_date_informed_li';
            $yesno[] = 'l7_time_informed_li';
        } elseif ($row['name'] == 'l7_lscb_informed_lscb' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_lscb';
            $yesno[] = 'l7_how_the_information_was_send_lscb';
            $yesno[] = 'l7_who_was_informed_lscb';
            $yesno[] = 'l7_police_reference_number_lscb';
            $yesno[] = 'l7_date_informed_lscb';
            $yesno[] = 'l7_time_informed_lscb';
        } elseif ($row['name'] == 'l7_other_placing_authorities_informed_opai' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_opai';
            $yesno[] = 'l7_how_the_information_was_send_opai';
            $yesno[] = 'l7_who_was_informed_opai';
            $yesno[] = 'l7_police_reference_number_opai';
            $yesno[] = 'l7_date_informed_opai';
            $yesno[] = 'l7_time_informed_opai';
        } elseif ($row['name'] == 'l7_reg_40_ofsted_informed_reg_40' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_reg_40';
            $yesno[] = 'l7_how_the_information_was_send_reg_40';
            $yesno[] = 'l7_who_was_informed_reg_40';
            $yesno[] = 'l7_date_informed_reg_40';
            $yesno[] = 'l7_time_informed_reg_40';
        } elseif ($row['name'] == 'l7_education_informed_ei' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_ei';
            $yesno[] = 'l7_how_the_information_was_send_ei';
            $yesno[] = 'l7_who_was_informed_ei';
            $yesno[] = 'l7_date_informed_ei';
            $yesno[] = 'l7_time_informed_ei';
        } elseif ($row['name'] == 'l7_therapy_informed_ti' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_ti';
            $yesno[] = 'l7_how_the_information_was_send_ti';
            $yesno[] = 'l7_who_was_informed_ti';
            $yesno[] = 'l7_date_informed_ti';
            $yesno[] = 'l7_time_informed_ti';
        } elseif ($row['name'] == 'l7_other_informed_oi' && $row['value'] == 'No') {
            $yesno[] = 'l7_informed_by_oi';
            $yesno[] = 'l7_how_the_information_was_send_oi';
            $yesno[] = 'l7_who_was_informed_oi';
            $yesno[] = 'l7_date_informed_oi';
            $yesno[] = 'l7_time_informed_oi';
        } elseif ($row['name'] == 'l7_location_disclosure' && $row['value'] != 48) {
          $yesno[] = 'enter_location';
        } elseif ($row['name'] == 'witness_disclosure' && $row['value'] != 58) {
          $yesno[] = 'l7_enter_other_person';
        }
        ?>

            <?php if ($row['type'] == 'header') {?>
                <tr id="div_<?=$row['name']?>" style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
                               <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
                                    <p style="margin:0; padding:10px;">  <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b></p>
                                    </td>
                           </tr>

        <?php } elseif ($row['type'] == 'textarea') {
            if (!in_array($dd, $yesno)) {
                ?>
               <tr>
                  <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                           <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                  </td>
                  <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                           <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                              <?=(isset($row['value'])) ? nl2br(html_entity_decode($row['value'])) : ''?>
                           </p>
                  </td>
               </tr>

<?php }?>
                   <?php
} else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
            ?>
                            <?php if ($row['name'] == 'is_other_injured') {
                if ($is_other_injured == 1) {

                    if (!in_array($dd, $yesno)) {
                        ?>
                     <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>


                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                       <?=(isset($row['value'])) ? nl2br(html_entity_decode($row['value'])) : ''?>
   </p>

                              </td>

                           </tr>
                           <?php }?>

        <?php }} else {
                if (!in_array($dd, $yesno)) {
                    ?>
                          <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>


<?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                        if ((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])) {
                            $row['value'] = timeformat($row['value']);
                        }
                        if ($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])) {
                            $row['value'] = configDateTime($row['value']);
                        }}?>
                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                        <?=(isset($row['value'])) ? nl2br(html_entity_decode($row['value'])) : ''?>
   </p>

                              </td>

                           </tr>


        <?php }?>


        <?php }?>

        <?php if ($row['name'] == 'l7_time_informed_oi') {
                ?>
         
    <tr style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
      <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
                                    <p style="margin:0; padding:10px;"> <b class="page-title">Safeguarding updates</b></p>
                                    </td>
            </tr>
          <?php if (!empty($l7sequence_data)) {
                    $i = 1;
                    foreach ($l7sequence_data as $key => $value) {
                      $seq_datal7 = $value['l7sequence_number'];

                       $seq_datal7 = substr($seq_datal7,1);
                        ?>
                           <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title">Sequence Number</b>
                                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                      <?php echo 'S'.$i;?>
                                    </p>
                              </td>
                           </tr>

                           <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title">Daily action taken</b>
                                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                       <?=!empty($value['l7daily_action_taken']) ? $value['l7daily_action_taken'] : ''?>
                                    </p>
                              </td>
                           </tr>

                           <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title">Daily action outcome</b>
                                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                       <?=!empty($value['l7daily_action_outcome']) ? $value['l7daily_action_outcome'] : ''?>
                                    </p>
                              </td>
                           </tr>
                           <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title">Supporting documents</b>
                                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                        <?php
$fileViewArray_doc = array(
                            'fileArray'        => (isset($value['l7supporting_documents' . $seq_datal7]) && !empty($value['l7supporting_documents' . $seq_datal7])) ? $value['l7supporting_documents' . $seq_datal7] : '',
                            'filePathMain'     => $this->config->item('aai_img_base_url') . $ypId,
                            'filePathThumb'    => $this->config->item('aai_img_base_url_small') . $ypId,
                            'deleteFileHidden' => 'hidden_' . 'l7supporting_documents' . $seq_datal7,
                        );
                        echo getFileViewNotDelete($fileViewArray_doc);?>
                                    </p>
                              </td>
                           </tr>
                           <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title">Date</b>
                                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                       <?=!empty($value['l7date_safeguarding']) ? $value['l7date_safeguarding'] : ''?>
                                    </p>
                              </td>
                           </tr>

                           <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title">Time</b>
                                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                       <?=!empty($value['l7time_safeguard']) ? $value['l7time_safeguard'] : ''?>
                                    </p>
                              </td>
                           </tr>
               <?php $i++;}}?>
                  <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title">Report Compiler</b>
                                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                      <?=(isset($l7_report_compiler)) ? $l7_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>
                           <input type="hidden" name="l7_report_compiler_safeguarding_Outcome" class="form-control" value="<?=(isset($l7_report_compiler)) ? $l7_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
                                    </p>
                              </td>
                           </tr>
                     <?php }?>


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


                  <?php }?>

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


                   <?php }?>
            <?php } else if ($row['type'] == 'select') {
            if (!in_array($dd, $yesno)) {
                ?>
                     <?php if ($row['className'] == 'bamboo_lookup') {
                    ?>
                     <tr id="div_<?=$row['name']?>"  style="margin: 6px 0px;">
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
                                       <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                                          <?php if (!empty($bambooNfcUsers)) {
                        foreach ($bambooNfcUsers as $select) {
                            if (isset($row['value']) && ($row['value'] == $select['user_type'] . '_' . $select['user_id'])) {
                                ?>

                                    <?php if (!empty($preveditl1Data) && $preveditl1Data["$dd"]['value'] != $row['value']) {
                                    $diff = new HtmlDiff('', $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']);
                                    $diff->build();
                                    echo $diff->getDifference();
                                    ?>
                                    <?php } else {
                                    echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'];
                                    ?>
                                     <?php }}}}?>
                                       </p>
                                    </td>
                           </tr>

               <?php } else if ($row['className'] == 'bamboo_lookup_multiple') {
                    ?>
               <tr id="div_<?=$row['name']?>"  class="<?=$cls;?>" style="margin: 6px 0px;">
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; display: block;background:#FFFFFF;color:#000000">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                                       <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                                         <?php if (!empty($bambooNfcUsers)) {
                        foreach ($bambooNfcUsers as $select) {
                                if (in_array($select['user_type'] . '_' . $select['user_id'], explode(',', $row['value']))) {
                                    echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] . ',';
                                }
                        }}?>
                                       </p>
                                    </td>
                           </tr>


                <?php } else {
                    ?>
                  <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                               <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                                       <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                                        <?php if (!empty($row['name']) && !empty($row['className']) && $row['className'] == 'multiple') {
                        ?>

                                                        <?php if (count($row['values']) > 0) {
                            $userAr = array();
                            foreach ($row['values'] as $select) {
                                if ((in_array($select['value'], $row['value']))) {
                                    $staff_present_data = array_diff($row['value'], $preveditl1Data["$dd"]['value']);
                                    if (in_array($select['value'], $staff_present_data)) {

                                        $diff = new HtmlDiff('', $select['label'] . ',');
                                        $diff->build();
                                        echo $diff->getDifference();

                                    } else {?>
                                                                    <?=!empty($select['label']) ? $select['label'] . ',' : ''?>
                                                                <?php }}}}?>

                                            <?php } else {
                        ?>

                                                <?php if (count($row['values']) > 0) {
                            foreach ($row['values'] as $select) {
                                if (isset($row['value']) && ($row['value'] == $select['value'])) {
                                    ?>
                                                     <?php if (!empty($preveditl1Data) && $preveditl1Data["$dd"]['value'] != $row['value']) {
                                        $diff = new HtmlDiff('', $select['label']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                                <?php } else {?>
                                        <?=!empty($select['label']) ? $select['label'] : ''?>
                                    <?php }}}}}?>
                                       </p>
                                    </td>
                           </tr>

               <?php }?>
               <?php }?>

                <?php } elseif ($row['type'] == 'file') {
            ?>

                 <tr>
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                     <?php $fileViewArray = array(
                'fileArray'        => (isset($row['value']) && !empty($row['value'])) ? $row['value'] : '',
                'filePathMain'     => $this->config->item('aai_img_base_url') . $ypId,
                'filePathThumb'    => $this->config->item('aai_img_base_url_small') . $ypId,
                'deleteFileHidden' => 'hidden_' . $row['name'],
            );
            echo getpdfFileView($fileViewArray);?>
                                    </p>
                              </td>
                           </tr>





    <?php
}
    }}
?>
</table>