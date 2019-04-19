<table cellpadding="0" cellspacing="0" width="100%" border="1" style=" margin: 0; border-collapse: collapse;margin:0;padding: 0;border-color: #CCCCCC;font-family: arial">
    <tr style="background-color: #4e6031;margin:0;padding:0;">
        <td align="left" colspan="2" style="text-transform: uppercase;font-size: 18px; margin:0;color:white;text-align:left;height: auto;  "><p style="margin:0;padding:10px;">INCIDENT L8 PROCESS (Staff Member or injury to a person that is not YP)</p>
        </td>
    </tr>
   <?php if (!empty($l8_form_data)) {
            foreach ($l8_form_data as $row) {
                $dd = $row['name'];
        ?>
<?php if ($row['type'] == 'header') { ?>
  <tr id="div_<?=$row['name']?>" style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
    <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
      <p style="margin:0; padding:10px;"><b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b></p>
    </td>
   </tr>

    <tr>
        
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(isset($is_other_injured) && $is_other_injured == 1){ 
                                    if(!empty($l1_data) && !empty($l1_data['l1_reference_number'])){
                                      echo $l1_data['l1_reference_number'].'</br>';
                                    }
                                    if(!empty($l2_data) && !empty($l2_data['l2_l3_reference_number'])){ 
                                      echo $l2_data['l2_l3_reference_number'].'</br>';  
                                    }
                                    if(!empty($l4_data) && !empty($l4_data['l4_reference_number'])){ 
                                        echo $l4_data['l4_reference_number'].'</br>';  
                                   }
                                  /* if(!empty($l5_data) && !empty($l5_data['l5_reference_number'])){ 
                                        echo $l5_data['l5_reference_number'].'</br>';  
                                   }*/
                                   if(!empty($l6_data) && !empty($l6_data['l6_reference_number'])){ 
                                        echo $l6_data['l6_reference_number'].'</br>';  
                                   }

                                   /*if(!empty($l7_data) && !empty($l7_data['l7_reference_number'])){ 
                                        echo $l7_data['l7_reference_number'].'</br>';  
                                   }*/
                                    ?>
                            <?php } ?>
          </p>
        </td>
  </tr>
    
<?php } elseif ($row['type'] == 'textarea') { 
  if (!in_array($dd, $yesno)) {
  ?>
  <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
             <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
          </p>
        </td>
  </tr>
  <?php } ?>
<?php } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') { ?>
      <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
            <?php 
                     if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['value']) && !empty($row['value'])){
                            $row['value'] = timeformat($row['value']);
                        }
                        if($row['type'] == 'date' && isset($row['value']) && !empty($row['value'])){
                            $row['value'] = configDateTime($row['value']);
                        }
                        ?>

                        <?= (isset($row['value']))? nl2br(html_entity_decode($row['value'])):'' ?>
          </p>
        </td>
      </tr>

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
  if (!in_array($dd, $yesno)) { ?>
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
<?php } else if ($row['type'] == 'select'){ 
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
<?php } else { ?>

                 <tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                               <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title"><?=!empty($row['label']) ? $row['label'] : ''?></b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                                       <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">

                                          <?php if(count($row['values']) > 0) {
                                         foreach ($row['values'] as $select) {
                                             if(!empty($select['label'])) {
                                          ?>
                                              <?php 
                        if(!empty($row['value']) && $row['value'] == $select['value']){ ?>
                            <?=!empty($select['label'])?$select['label']:''?>
                        <?php } ?>
                    <?php } } } //select loop ?>


                        
                        
          </p>
        </td>
      </tr>


               <?php }} ?>
                <?php if($row['name']=='l8_form_status'){ ?>
                    <tr>
                    <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
                                      <b class="page-title">Report Compiler</b>
                    </td>

                              <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
                                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                                    <?php 
                                $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'L8');
                                if(!empty($aai_report_com)){ 
                                    foreach ($aai_report_com as $repcom_value) { ?>
                                    <div>
                                    <?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormat($repcom_value['created_date']); ?>
                                    </div>
                        <?php } } ?> 
                                    </p>
                              </td>
                           </tr>
                <?php }?>
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