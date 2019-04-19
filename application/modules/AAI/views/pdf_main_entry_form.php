<table cellpadding="0" cellspacing="0" width="100%" border="1" style=" margin: 0; border-collapse: collapse;margin:0;padding: 0;border-color: #CCCCCC;font-family: arial">
    <tr style="background-color: #4e6031;margin:0;padding:0;">
        <td align="left" colspan="2" style="text-transform: uppercase;font-size: 18px; margin:0;color:white;text-align:left;height: auto;  "><p style="margin:0;padding:10px;">MAIN ENTRY FORM</p>
        </td>
    </tr>
   <?php if (isset($editMode) && $editMode > 0) {
    ?>
   <tr>
      <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Was physical intervention used?</b>
      </td>
      <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
            <?php if (!empty($checkEdu1) && $checkEdu1 == 'checked') {
        echo 'Education incident';
    } else {
        echo 'Care incident';
    }?>
          </p>
      </td>
  </tr>
  <?php }?>

  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <b class="page-title">Is the incident related to any other recorded incident?</b>
        </td>
        <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
              <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                   <?php
$relatedIncidentArray = explode(',', $relatedIncident);
if (!isset($createMode) && count($relatedIncidentArray) > 0 && $relatedIncident !== '') {
    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
} else {
    echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
}?>
              </div>
        </td>
  </tr>
   <?php if (!isset($createMode) && count($relatedIncidentArray) > 0 && $relatedIncident !== '') {
    ?>
  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" colspan="2" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <table style="border: 0px solid #ddd; width: 100%;max-width: 100%;margin-bottom: 20px;">
                                            <thead>
                                                <tr>
                                                    <th width="20px"></th>
                                                    <th align="left">incident reference number</th>
                                                    <th align="left">incident type</th>
                                                    <th align="left">Care home Name</th>

                                                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) {
        echo $sortfield;
    }
    ?>" />
                                                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) {
        echo $sortby;
    }
    ?>" />
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (isset($YPIncidentData) && count($YPIncidentData) > 0) {?>
                                                <?php foreach ($YPIncidentData as $incidentDetail) {?>
                                                    <tr style="text-align: left;" >
                                                        <td style="text-align: left;" align="left">

                                                        <?php if (in_array($incidentDetail['incident_id'], $relatedIncidentArray)) {?>
                                                          <img src="<?php echo base_url() . "/uploads/assets/front/images/check.png" ?>" border="0"/>
                                                        <?php } else {?>
                                                          <img src="<?php echo base_url() . "/uploads/assets/front/images/uncheck.png" ?>" border="0"/>
                                                        <?php }?>

                                                        </td>
                                                        <td style="text-align: left;" align="left">

                                                        <?php echo $incidentDetail['reference_number']; ?></td>
                                                         <td style="text-align: left;" align="left">

                                                        <?php echo ($incidentDetail['is_care_incident'] == 1) ? 'care incident' : 'education incident'; ?></td>
                                                        <td style="text-align: left;"><?=!empty($incidentDetail['care_home_name']) ? $incidentDetail['care_home_name'] : lang('NA')?></td>

                                                    </tr>
                                                <?php }?>
                                            <input type="hidden" name="related_incident" id="related_incident" value="<?=(isset($relatedIncident)) ? $relatedIncident : ''?>">
                                            <?php } else {?>
                                                    <tr>
                                                        <td colspan="6" class="text-center"><?=lang('common_no_record_found')?></td>

                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
        </td>
  </tr>
  <?php }?>

   <?php

if (!empty($entry_form_data)) {
    foreach ($entry_form_data as $key => $row) {
        $dd = $row['name'];
        if (isset($createMode) && $createMode == 1) {

            if ($row['name'] == 'yp_fname') {
                $row['value'] = $yp_details[0]['yp_fname'];
            }
            if ($row['name'] == 'yp_surname') {
                $row['value'] = $yp_details[0]['yp_lname'];
            }
            if ($row['name'] == 'yp_email') {
                $row['value'] = $yp_details[0]['email_id'];
            }
            if ($row['name'] == 'yp_dob') {
                $row['value'] = configDateTime($yp_details[0]['date_of_birth']);
            }
            if ($row['name'] == 'yp_gender') {
                if ($yp_details[0]['gender'] == 'M') {
                    $row['value'] = 'Male';
                } elseif ($yp_details[0]['gender'] == 'F') {
                    $row['value'] = 'Female';
                }
            }
            $entry_report_compiler = $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME'];
        }

        ?>
            <?php if ($row['type'] == 'header') {
            ?>
            <tr id="div_<?=$row['name']?>" style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #000000">
                               <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;border:1px solid #000000">
                                    <p style="margin:0; padding:10px;">  <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b></p>
                                    </td>
                           </tr>
                           <?php if ($row['label'] == 'ABOUT THE PERSON REPORTING THE INCIDENT') {
                ?>
                           <tr>
                  <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                           <b class="page-title">Reporting Staff</b>
                  </td>
                  <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                           <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                               <?php if (!empty($bambooNfcUsers)) {
                    foreach ($bambooNfcUsers as $select) {
                        ?>

                                        <?php if (isset($reporting_user) && ($reporting_user == $select['user_type'] . '_' . $select['user_id'])) {

                            if (!empty($prevedit_entry_form_data) && $pre_reporting_user != $reporting_user) {
                                $diff = new HtmlDiff('', $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                                    <?php } else {
                                echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'];
                            }}
                        ?>

                                    <?php }}?>
                           </p>
                  </td>
               </tr>

               <tr>
                  <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                           <b class="page-title">Report Compiler</b>
                  </td>
                  <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                           <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                             <?php 
                                 $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'mainform');
                                 if(!empty($aai_report_com)){ 
                                  foreach ($aai_report_com as $repcom_value) { ?>
                                    <?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormatAi($repcom_value['created_date']); ?>
                                  <?php } } ?>  
                           </p>
                  </td>
               </tr>
            <?php }?>
            <?php } elseif ($row['type'] == 'text' && $row['subtype'] !== 'time') {?>
            <tr>
                  <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                           <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                  </td>
                  <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                           <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                              <?php echo nl2br(html_entity_decode($row['value'])); ?>
                           </p>
                  </td>
               </tr>
               <?php } elseif ($row['type'] == 'number') {?>
               <tr>
                  <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                           <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                  </td>
                  <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                           <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                              <?php echo nl2br(html_entity_decode($row['value'])); ?>
                           </p>
                  </td>
               </tr>
               <?php } elseif ($row['type'] == 'date' || $row['subtype'] == 'time') {?>
               <tr>
                  <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                           <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                  </td>
                  <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                           <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                            <?php if($row['subtype'] == 'time'){ 
                              echo timeformat($row['value']);
                            }else if($row['type'] == 'date'){
                              echo configDateTime($row['value']);
                            } ?>
                              
                           </p>
                  </td>
               </tr>

               <?php } else if ($row['type'] == 'select') {
            ?>
               <tr>
                  <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                           <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                  </td>
                  <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                           <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                              <?php
if (count($row['values']) > 0) {
                foreach ($row['values'] as $select) {
                    if (!empty($select['label'])) {
                        if (isset($row['value']) && ($row['value'] == $select['value'])) {
                            if (!empty($prevedit_entry_form_data) && $prevedit_entry_form_data["$dd"]['value'] != $row['value']) {
                                $diff = new HtmlDiff('', $select['label']);
                                $diff->build();
                                echo $diff->getDifference();
                                ?>
                                                    <?php } else {?>
                                                        <?=!empty($select['label']) ? $select['label'] : ''?>
                                                    <?php }

                        }
                    }
                }
            }?>
                          </p>
                  </td>
               </tr>

               <?php } else if ($row['type'] == 'textarea') {?>

               <tr>
                  <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                           <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                  </td>
                  <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                           <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                              <?=(isset($row['value'])) ? preg_replace('/\n+/', "\n", trim(nl2br(html_entity_decode($row['value'])))) : ''?>
                          </p>
                  </td>
               </tr>
                <?php } else if ($row['type'] == 'radio-group') {
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
          <?php } else if ($row['type'] == 'checkbox-group') {
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
                                  <label class="<?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"><input
                                       class="<?=!empty($row['className']) ? $row['className'] : ''?> <?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"
                                      name="<?=!empty($row['name']) ? $row['name'] . '[]' : ''?>" value="<?=!empty($checkbox['value']) ? $checkbox['value'] : ''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues)) ? 'checked="checked"' : !empty($checkbox['selected'] && empty($checkedValues)) ? 'checked="checked"' : ''?>
                                           <?=!empty($row['required']) ? 'required=true' : ''?>
                                           type="checkbox">
                                    <?=!empty($checkbox['label']) ? $checkbox['label'] : ''?></label>
                      <?php }}}?>
                          </p>
                  </td>
               </tr>
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
            <?php }?>
      <?php }}?>
</table>