<table cellpadding="0" cellspacing="0" width="100%" border="1" style=" margin: 0; border-collapse: collapse;margin:0;padding: 0;border-color: #CCCCCC;font-family: arial">
    <tr style="background-color: #4e6031;margin:0;padding:0;">
        <td align="left" colspan="2" style="text-transform: uppercase;font-size: 18px; margin:0;color:white;text-align:left;height: auto;  "><p style="margin:0;padding:10px;">INCIDENT L5 PROCESS (YP INJURED) </p>
        </td>
    </tr>
   
<?php if (!empty($l5_form_data)) {
    foreach ($l5_form_data as $row) {
        $dd = $row['name'];
        $n  = 'display:none!important;';
        $y  = 'display:block!important;';

        if($row['name'] == 'where_did_accident' && $row['value'] != 48) {
            $yesno[] = 'l5_where_did_accident_other';
        }
      ?>
<?php if ($row['type'] == 'header') { ?>
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
  <?php } ?>
<?php if($row['name']=='description_injury'){ ?>
	 <tr>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
             <input class="btn btn-default bodymap" type="button" name="body_map" value="Body Map" />
          </p>
        </td>
  </tr>
<?php } elseif($row['name']=='l5_debrief_comments'){ ?>
 <tr style="margin:0;padding: 0">
                <td valign="middle" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto; margin:0; padding: 5px 10px;width:50%;color:#000000"><b class="page-title" style="">Is The YP Making A Complaint?</b>
                </td>
                <td align="left" style="font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;margin:0; padding: 4px 10px;width:50%;color:#000000">
                    <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
                         <?php
                            if (isset($l5_is_complaint) && $l5_is_complaint == 'Yes') {
                                echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                            } else {
                                echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                            } ?>
                    </p>
                </td>
              </tr>

              <tr>
  </tr>

<?php } ?>

<?php } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') { ?>
      
<?php if (!in_array($dd, $yesno)) { ?>
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

<?php if($row['name'] == 'time_injured') { ?> 
      <tr style="background-color: #eaeaea;margin:0;padding: 0;color:#000000;bordre:1px solid #dddddd">
      <td colspan="2" align="left" style="text-transform: uppercase;font-size: 16px; margin:0;text-align:left;height: auto;margin:0;padding: 0 ;bordre:1px solid #dddddd">
                                    <p style="margin:0; padding:10px;"> <b class="page-title">Detail's Of Injury</b></p>
                                    </td>
            </tr>

<?php if(!empty($l5_body_map['fm_head']) && $l5_body_map['fm_head']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front Head</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_head']) && $l5_body_map['fm_head']!=''){ echo $l5_body_map['fm_head']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_nose']) && $l5_body_map['fm_nose']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front Nose</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_nose']) && $l5_body_map['fm_nose']!=''){ echo $l5_body_map['fm_nose']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_left_eye']) && $l5_body_map['fm_left_eye']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Left Eye</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_eye']) && $l5_body_map['fm_left_eye']!=''){ echo $l5_body_map['fm_left_eye']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_right_eye']) && $l5_body_map['fm_right_eye']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Right Eye</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_eye']) && $l5_body_map['fm_right_eye']!=''){ echo $l5_body_map['fm_right_eye']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_mouth']) && $l5_body_map['fm_mouth']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Mouth</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_mouth']) && $l5_body_map['fm_mouth']!=''){ echo $l5_body_map['fm_mouth']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_left_ear']) && $l5_body_map['fm_left_ear']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Left Ear</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_ear']) && $l5_body_map['fm_left_ear']!=''){ echo $l5_body_map['fm_left_ear']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_right_ear']) && $l5_body_map['fm_right_ear']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Right Ear</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_ear']) && $l5_body_map['fm_right_ear']!=''){ echo $l5_body_map['fm_right_ear']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_neck']) && $l5_body_map['fm_neck']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front Neck</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_neck']) && $l5_body_map['fm_neck']!=''){ echo $l5_body_map['fm_neck']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_chest']) && $l5_body_map['fm_chest']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front Chest</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_chest']) && $l5_body_map['fm_chest']!=''){ echo $l5_body_map['fm_chest']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_abdomen']) && $l5_body_map['fm_abdomen']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front Abdomen</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_abdomen']) && $l5_body_map['fm_abdomen']!=''){ echo $l5_body_map['fm_abdomen']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_pelvis']) && $l5_body_map['fm_pelvis']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front Pelvis</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_pelvis']) && $l5_body_map['fm_pelvis']!=''){ echo $l5_body_map['fm_pelvis']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_pubis']) && $l5_body_map['fm_pubis']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front Pubis</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_pubis']) && $l5_body_map['fm_pubis']!=''){ echo $l5_body_map['fm_pubis']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_left_thigh']) && $l5_body_map['fm_left_thigh']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left thigh</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_thigh']) && $l5_body_map['fm_left_thigh']!=''){ echo $l5_body_map['fm_left_thigh']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_right_thigh']) && $l5_body_map['fm_right_thigh']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right thigh</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_thigh']) && $l5_body_map['fm_right_thigh']!=''){ echo $l5_body_map['fm_right_thigh']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_right_thigh']) && $l5_body_map['fm_right_thigh']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left knee</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_knee']) && $l5_body_map['fm_left_knee']!=''){ echo $l5_body_map['fm_left_knee']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_left_knee']) && $l5_body_map['fm_left_knee']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left knee</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_knee']) && $l5_body_map['fm_left_knee']!=''){ echo $l5_body_map['fm_left_knee']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_right_knee']) && $l5_body_map['fm_right_knee']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right knee</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_knee']) && $l5_body_map['fm_right_knee']!=''){ echo $l5_body_map['fm_right_knee']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_left_leg']) && $l5_body_map['fm_left_leg']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left leg</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_leg']) && $l5_body_map['fm_left_leg']!=''){ echo $l5_body_map['fm_left_leg']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_right_leg']) && $l5_body_map['fm_right_leg']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right leg</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_leg']) && $l5_body_map['fm_right_leg']!=''){ echo $l5_body_map['fm_right_leg']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_left_ankle']) && $l5_body_map['fm_left_ankle']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left ankle</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_ankle']) && $l5_body_map['fm_left_ankle']!=''){ echo $l5_body_map['fm_left_ankle']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>


<?php if(!empty($l5_body_map['fm_right_ankle']) && $l5_body_map['fm_right_ankle']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right ankle</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_ankle']) && $l5_body_map['fm_right_ankle']!=''){ echo $l5_body_map['fm_right_ankle']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_left_foot']) && $l5_body_map['fm_left_foot']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left foot</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_foot']) && $l5_body_map['fm_left_foot']!=''){ echo $l5_body_map['fm_left_foot']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_right_foot']) && $l5_body_map['fm_right_foot']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right foot</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_foot']) && $l5_body_map['fm_right_foot']!=''){ echo $l5_body_map['fm_right_foot']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_left_shoulder']) && $l5_body_map['fm_left_shoulder']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left shoulder</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_shoulder']) && $l5_body_map['fm_left_shoulder']!=''){ echo $l5_body_map['fm_left_shoulder']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_right_shoulder']) && $l5_body_map['fm_right_shoulder']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right shoulder</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_shoulder']) && $l5_body_map['fm_right_shoulder']!=''){ echo $l5_body_map['fm_right_shoulder']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_left_arm']) && $l5_body_map['fm_left_arm']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left arm</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_arm']) && $l5_body_map['fm_left_arm']!=''){ echo $l5_body_map['fm_left_arm']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_right_arm']) && $l5_body_map['fm_right_arm']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right arm</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_arm']) && $l5_body_map['fm_right_arm']!=''){ echo $l5_body_map['fm_right_arm']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_left_albow']) && $l5_body_map['fm_left_albow']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left albow</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_albow']) && $l5_body_map['fm_left_albow']!=''){ echo $l5_body_map['fm_left_albow']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_right_albow']) && $l5_body_map['fm_right_albow']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right albow</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_albow']) && $l5_body_map['fm_right_albow']!=''){ echo $l5_body_map['fm_right_albow']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_left_forearm']) && $l5_body_map['fm_left_forearm']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left forearm</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_forearm']) && $l5_body_map['fm_left_forearm']!=''){ echo $l5_body_map['fm_left_forearm']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_right_forearm']) && $l5_body_map['fm_right_forearm']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right forearm</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_forearm']) && $l5_body_map['fm_right_forearm']!=''){ echo $l5_body_map['fm_right_forearm']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>


<?php if(!empty($l5_body_map['fm_left_wrist']) && $l5_body_map['fm_left_wrist']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left wrist</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_wrist']) && $l5_body_map['fm_left_wrist']!=''){ echo $l5_body_map['fm_left_wrist']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_right_wrist']) && $l5_body_map['fm_right_wrist']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right wrist</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_wrist']) && $l5_body_map['fm_right_wrist']!=''){ echo $l5_body_map['fm_right_wrist']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['fm_left_hand']) && $l5_body_map['fm_left_hand']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front left hand</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_left_hand']) && $l5_body_map['fm_left_hand']!=''){ echo $l5_body_map['fm_left_hand']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_left_hand']) && $l5_body_map['fm_left_hand']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right hand</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_hand']) && $l5_body_map['fm_right_hand']!=''){ echo $l5_body_map['fm_right_hand']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['fm_right_hand']) && $l5_body_map['fm_right_hand']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Front right hand</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['fm_right_hand']) && $l5_body_map['fm_right_hand']!=''){ echo $l5_body_map['fm_right_hand']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>
<?php if(!empty($l5_body_map['bm_head']) && $l5_body_map['bm_head']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back Head</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_head']) && $l5_body_map['bm_head']!=''){ echo $l5_body_map['bm_head']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_neck']) && $l5_body_map['bm_neck']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back Neck</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_neck']) && $l5_body_map['bm_neck']!=''){ echo $l5_body_map['bm_neck']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_back']) && $l5_body_map['bm_back']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back Chest</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_back']) && $l5_body_map['bm_back']!=''){ echo $l5_body_map['bm_back']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>


<?php if(!empty($l5_body_map['bm_loin']) && $l5_body_map['bm_loin']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back loin</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_loin']) && $l5_body_map['bm_loin']!=''){ echo $l5_body_map['bm_loin']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_buttocks']) && $l5_body_map['bm_buttocks']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Buttocks</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_buttocks']) && $l5_body_map['bm_buttocks']!=''){ echo $l5_body_map['bm_buttocks']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>


<?php if(!empty($l5_body_map['bm_left_hrmstring']) && $l5_body_map['bm_left_hrmstring']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">left hrmstring</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_hrmstring']) && $l5_body_map['bm_left_hrmstring']!=''){ echo $l5_body_map['bm_left_hrmstring']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_hrmstring']) && $l5_body_map['bm_right_hrmstring']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">right hrmstring</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_hrmstring']) && $l5_body_map['bm_right_hrmstring']!=''){ echo $l5_body_map['bm_right_hrmstring']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>


<?php if(!empty($l5_body_map['bm_left_knee']) && $l5_body_map['bm_left_knee']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left knee</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_knee']) && $l5_body_map['bm_left_knee']!=''){ echo $l5_body_map['bm_left_knee']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_knee']) && $l5_body_map['bm_right_knee']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right knee</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_knee']) && $l5_body_map['bm_right_knee']!=''){ echo $l5_body_map['bm_right_knee']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>


<?php if(!empty($l5_body_map['bm_left_kalf']) && $l5_body_map['bm_left_kalf']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left kalf</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_kalf']) && $l5_body_map['bm_left_kalf']!=''){ echo $l5_body_map['bm_left_kalf']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>


<?php if(!empty($l5_body_map['bm_right_kalf']) && $l5_body_map['bm_right_kalf']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right kalf</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_kalf']) && $l5_body_map['bm_right_kalf']!=''){ echo $l5_body_map['bm_right_kalf']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_left_ankle']) && $l5_body_map['bm_left_ankle']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left ankle</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_ankle']) && $l5_body_map['bm_left_ankle']!=''){ echo $l5_body_map['bm_left_ankle']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_ankle']) && $l5_body_map['bm_right_ankle']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right ankle</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_ankle']) && $l5_body_map['bm_right_ankle']!=''){ echo $l5_body_map['bm_right_ankle']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_left_sole']) && $l5_body_map['bm_left_sole']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left sole</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_sole']) && $l5_body_map['bm_left_sole']!=''){ echo $l5_body_map['bm_left_sole']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>


<?php if(!empty($l5_body_map['bm_right_sole']) && $l5_body_map['bm_right_sole']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right sole</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_sole']) && $l5_body_map['bm_right_sole']!=''){ echo $l5_body_map['bm_right_sole']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_left_foot']) && $l5_body_map['bm_left_foot']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left foot</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_foot']) && $l5_body_map['bm_left_foot']!=''){ echo $l5_body_map['bm_left_foot']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_foot']) && $l5_body_map['bm_right_foot']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right foot</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_foot']) && $l5_body_map['bm_right_foot']!=''){ echo $l5_body_map['bm_right_foot']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_left_shoulder']) && $l5_body_map['bm_left_shoulder']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left shoulder</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_shoulder']) && $l5_body_map['bm_left_shoulder']!=''){ echo $l5_body_map['bm_left_shoulder']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_shoulder']) && $l5_body_map['bm_right_shoulder']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right shoulder</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_shoulder']) && $l5_body_map['bm_right_shoulder']!=''){ echo $l5_body_map['bm_right_shoulder']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_left_arm']) && $l5_body_map['bm_left_arm']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left arm</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_arm']) && $l5_body_map['bm_left_arm']!=''){ echo $l5_body_map['bm_left_arm']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_arm']) && $l5_body_map['bm_right_arm']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right arm</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_arm']) && $l5_body_map['bm_right_arm']!=''){ echo $l5_body_map['bm_right_arm']; } ?>
          </p>
        </td>
      </tr>
	<?php } ?>

	<?php if(!empty($l5_body_map['bm_left_albow']) && $l5_body_map['bm_left_albow']!=''){ ?>
	 <tr>
			<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
			  <b class="page-title">Back left albow</b>
			</td>
			<td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
			  <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
				  <?php if(!empty($l5_body_map['bm_left_albow']) && $l5_body_map['bm_left_albow']!=''){ echo $l5_body_map['bm_left_albow']; } ?>
			  </p>
			</td>
		  </tr>
	<?php } ?>

<?php if(!empty($l5_body_map['bm_right_albow']) && $l5_body_map['bm_right_albow']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right albow</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_albow']) && $l5_body_map['bm_right_albow']!=''){ echo $l5_body_map['bm_right_albow']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_left_forearm']) && $l5_body_map['bm_left_forearm']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left forearm</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_forearm']) && $l5_body_map['bm_left_forearm']!=''){ echo $l5_body_map['bm_left_forearm']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_forearm']) && $l5_body_map['bm_right_forearm']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right forearm</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_forearm']) && $l5_body_map['bm_right_forearm']!=''){ echo $l5_body_map['bm_right_forearm']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_left_wrist']) && $l5_body_map['bm_left_wrist']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left wrist</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_wrist']) && $l5_body_map['bm_left_wrist']!=''){ echo $l5_body_map['bm_left_wrist']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_wrist']) && $l5_body_map['bm_right_wrist']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right wrist</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_wrist']) && $l5_body_map['bm_right_wrist']!=''){ echo $l5_body_map['bm_right_wrist']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_left_hand']) && $l5_body_map['bm_left_hand']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back left hand</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_left_hand']) && $l5_body_map['bm_left_hand']!=''){ echo $l5_body_map['bm_left_hand']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

<?php if(!empty($l5_body_map['bm_right_hand']) && $l5_body_map['bm_right_hand']!=''){ ?>
 <tr>
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; padding: 4px 10px;">
          <b class="page-title">Back right hand</b>
        </td>
        <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; padding: 0 10px;">
          <p style="word-wrap: break-word; word-break: break-all;margin: 4px 0px;">
              <?php if(!empty($l5_body_map['bm_right_hand']) && $l5_body_map['bm_right_hand']!=''){ echo $l5_body_map['bm_right_hand']; } ?>
          </p>
        </td>
      </tr>
<?php } ?>

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
<?php } else if ($row['className'] == 'bamboo_lookup_multiple') {
                ?>
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
               <?php if($row['name']=='form_status'){ ?>
<tr id="div_<?=$row['name']?>" class="<?=$cls;?>" style="margin: 6px 0px;">
                               <td  align="left" style="text-transform: uppercase;font-size: 14px; margin:0;text-align:left;line-height: 20px;height: auto;padding: 5px 10px; background:#FFFFFF;color:#000000">
                                      <b class="page-title">Report Compiler</b>
                                    </td>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;display: table-cell;">
                                      <p style="word-wrap: break-word; word-break: break-all;margin: 6px 0px;">

                <?php 
                                $aai_report_com = getUserDetailUseINAAI($incidentData['incident_id'],$incidentData['yp_id'],'L5');
                                if(!empty($aai_report_com)){ 
                                    foreach ($aai_report_com as $repcom_value) { ?>
                                        <div>
                                           <?php echo $repcom_value['name'] ?>:  <?php echo configDateTimeFormat($repcom_value['created_date']); ?>
                                        </div>
                                   
                        <?php } } ?>    
          </p>
        </td>
      </tr>
<?php } ?>
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