<table cellpadding="0" cellspacing="0" width="100%" border="1" style=" margin: 0; border-collapse: collapse;margin:0;padding: 0;border-color: #CCCCCC;font-family: arial">
    <tr style="background-color: #4e6031;margin:0;padding:0;">
        <td align="left" colspan="2" style="text-transform: uppercase;font-size: 18px; margin:0;color:white;text-align:left;height: auto;  "><p style="margin:0;padding:10px;">TYPE OF INCIDENT</p>
        </td>
    </tr>
   
  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <b class="page-title">Was physical intervention used?</b>
        </td>
        <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
              <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                  <?php if ($is_pi == 1) {
                    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                  } else {
                    echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                  } ?>
              </div>
        </td>
  </tr>
  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <b class="page-title">Did YP go missing?</b>
        </td>
        <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
              <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                  <?php if ($is_yp_missing == 1) {
                    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                  } else {
                    echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                  } ?>
              </div>
        </td>
  </tr>
  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <b class="page-title">Was the YP injured?</b>
        </td>
        <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
              <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                  <?php if ($is_yp_injured == 1) {
                    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                  } else {
                    echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                  } ?>
              </div>
        </td>
  </tr>
  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <b class="page-title">Has a complaint been made either by the YP or on behalf of the YP?</b>
        </td>
        <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
              <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                  <?php if ($is_yp_complaint == 1) {
                    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                  } else {
                    echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                  } ?>
              </div>
        </td>
  </tr>
  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <b class="page-title">Is any part of this incident a safeguarding concern?</b>
        </td>
        <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
              <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                  <?php if ($is_yp_safeguarding == 1) {
                    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                  } else {
                    echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                  } ?>
              </div>
        </td>
  </tr>
  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <b class="page-title">Was a staff member injured?</b>
        </td>
        <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
              <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                   <?php if ($is_staff_injured == 1) {
                    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                  } else {
                    echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                  } ?>
              </div>
        </td>
  </tr>
  <tr id="div_<?=$row['name']?>" style="margin: 6px 0px;">
        <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;padding: 5px 10px;">
              <b class="page-title">Was anyone else injured?</b>
        </td>
        <td width="50%" style="font-size: 14px; color:#0b1327;text-align:left;height: auto; padding: 0;margin:0;">
              <div style="word-wrap: break-word; word-break: break-all;margin:0; padding: 5px 10px;">
                    <?php if ($is_other_injured == 1) {
                    echo '<div style="color: #5cb85c; font-weight: bold;">YES</div>';
                  } else {
                    echo '<div style="color: #d9534f; font-weight: bold;">NO</div>';
                  } ?>
              </div>
        </td>
  </tr>
</table>