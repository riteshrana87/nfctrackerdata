<table cellpadding="0" cellspacing="0" width="100%" border="1" style=" margin: 0; border-collapse: collapse;margin:0;padding: 0;border-color: #CCCCCC;font-family: arial">
    <tr style="background-color: #4e6031;margin:0;padding:0;">
        <td align="left" colspan="2" style="text-transform: uppercase;font-size: 18px; margin:0;color:white;text-align:left;height: auto;  "><p style="margin:0;padding:10px;">INCIDENT L1 PROCESS (INCIDENT NOT INVOLVING PHYSICAL INTERVENTION) </p>
        </td>
    </tr>
   <tr style="margin:0;padding: 0">
                               <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Report Compiler</b>
                               </td>
                              <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                                 <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                       <?=(isset($l1_report_compiler)) ? $l1_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>
                                 </p>
                              </td>
                           </tr>



<?php if (!empty($l1_form_data)) {
    foreach ($l1_form_data as $row) {
        $dd = $row['name'];
        $n  = 'display:none!important;';
        $y  = 'display:block!important;';

        if($row['name'] == 'l1_yp_treatment' && $row['value'] == 'No') {
             $yesno[] = 'l1_yp_treatment_accept';
             $yesno[] = 'l1_treatment_not_accepted_comments';
        }else{
          if($row['name'] == 'l1_yp_treatment_accept' && $row['value'] == 'Yes') {
             $yesno[]='l1_treatment_not_accepted_comments';
          }
        }

        if($row['name'] == 'l1_debrief_offer' && $row['value'] == 'No') {
             $yesno[] = 'l1_debrief_accept';
             $yesno[] = 'l1_debrief_comments';
        }else{
          if($row['name'] == 'l1_debrief_accept' && $row['value'] == 'Yes') {
             $yesno[]='l1_debrief_comments';
          }
        }

 if($row['name'] == 'l1_involved_incident'){
          $involved_data = explode(',', $row['value']);
          if (in_array("Outside Agency", $involved_data)){
              //$yesno[]='';
          }else{
              $yesno[]='l1_involved_agency';
          }

          if (in_array("Employee", $involved_data)){
            //$yesno[]='';
          }else{
            $yesno[]='l1_involved_employee';
          }
          if(in_array("Other", $involved_data)){
            //$yesno[]='';
          }else{
            $yesno[]='l1_involved_other';
          }
        } 


        ?>



<?php if ($row['type'] == 'header') { ?>
  <tr id="div_<?=$row['name']?>" style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
    <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
      <p style="margin:0; padding:10px;"><b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b></p>
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
             <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
          </p>
        </td>
  </tr>
  <?php } ?>
<?php } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') { ?>
    <?php if ($row['name'] == 'is_other_injured') {
                if ($is_other_injured == 1) { 
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
      <?php } ?>
<?php }} else { ?>
<?php if (!in_array($dd, $yesno)) { ?>
      <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
        </td>
        <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {
                      if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])){
                          $row['value'] = timeformat($row['value']);
                        }
                      if($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])){
                          $row['value'] = $row['value'];//configDateTime($row['value']);
                      }}
                                                ?>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
          </p>
        </td>
      </tr>
<?php }}?>
<?php if($row['name'] == 'l1_conclude_time'){ ?>
    <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Total L1 incident duration</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?=(isset($l1_total_duration) ? $l1_total_duration : '')?>
          </p>
        </td>
    </tr>
<?php } ?>
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
<?php } else if ($row['className'] == 'bamboo_lookup_multiple') { ?>
 <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                               <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                                       <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">
                <?php if (!empty($bambooNfcUsers)) {
                    foreach ($bambooNfcUsers as $select) {
                        if (in_array($select['user_type'] . '_' . $select['user_id'], $row['value'])) {
                            echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] . ',';
                        }}}?>
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
                        foreach ($row['values'] as $select) {
                            if ((in_array($select['value'], $row['value']))) {?>
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


               <?php }} ?>
               <?php } else if ($row['type'] == 'file') { ?>
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