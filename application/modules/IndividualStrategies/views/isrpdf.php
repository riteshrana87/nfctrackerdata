	   <style>
                    td  *{
                      word-break: break-all;
                      word-wrap: break-word;
                      white-space: pre;           /* CSS 2.0 */
                      white-space: pre-wrap;      /* CSS 2.1 */
                      white-space: pre-line;      /* CSS 3.0 */
                      white-space: -pre-wrap;     /* Opera 4-6 */
                      white-space: -o-pre-wrap;   /* Opera 7 */
                      white-space: -moz-pre-wrap; /* Mozilla */
                      white-space: -hp-pre-wrap;  /* HP Printers */
                      word-wrap: break-word;
                    }
                  </style>
                    <table width="100%" style=" margin: 0; border-collapse: collapse;">
                        <?php

            if (!empty($ra_form_data)) {
                foreach ($ra_form_data as $row) {
				

                  if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {

                     if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 

                                      $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                      ?>
						
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b><?= !empty($row['label']) ? $row['label'] : '' ?>:</b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                        <?php if($row['subtype'] == 'time'){ ?>
                                       <?= !empty($data_textarea) ? nl2br(timeformat($data_textarea)) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                       <?php }elseif ($row['type'] == 'date') { ?>
                                          <?= !empty($data_textarea) ? nl2br(configDateTime($data_textarea)) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                         <?php }else{ ?>
                                         <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>
                                       <?php } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
                 
                     
                     <?php
                    }  
                } //foreach
            }
          }
            ?>
</table>
    <table width="100%">
  <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>AMENDMENTS:</b>
                                    </td>
                             
                           </tr>
               <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  <?php if (!empty($item_details)) {
                                                    foreach ($item_details as $row) {
                                                ?>
                                    <tr>
                  
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                        <?= !empty($row['amendments']) ? $row['amendments'] : '' ?>
                    <?php echo $row['create_name'] ?>:  <?php echo configDateTimeFormat($row['created_date']); ?>
   </p>    
  
                                    </td>
                  
                  
                                  </tr>
                  <?php }
                        }
                        ?>
                                </table>
                              </td>
                             
                           </tr>
               
</table>

<table width="100%">
  <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>CURRENT PROTOCOLS IN PLACE:</b>
                                    </td>
                             
                           </tr>
               <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  <?php if (!empty($protocols_details)) {
                                                    foreach ($protocols_details as $row) {
                                                ?>
                                    <tr>
                  
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                        <?= !empty($row['current_protocols_in_place']) ? $row['current_protocols_in_place'] : '' ?>
                    <?php echo $row['create_name'] ?>:  <?php echo configDateTimeFormat($row['created_date']); ?>
   </p>    
  
                                    </td>
                  
                  
                                  </tr>
                  <?php }
                        }
                        ?>
                                </table>
                              </td>
                             
                           </tr>
               
</table>
<table width="100%">
  <tr>
    <td>
      <p style="text-align: center;"><em>&nbsp;</em></p>
    </td>
  </tr>
</table>


<table width="100%">
  <tr>
    <td>
      <p style="text-align: center;"><em>&nbsp;</em></p>
    </td>
  </tr>
</table>


<table width="100%">
<tr>
<td>
<p style="text-align: center;"><em>&nbsp;</em></p>
</td>
</tr>
</table>                                              

<table width="100%">
    <tr>
      <td>
        <p style="text-align: center;"><em>&nbsp;</em></p>
      </td>
    </tr>
</table> 
<table width="100%" style="font-size: 16px; margin: 0; border-collapse: collapse;">
    <tbody>
        <tr>
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 3px 10px;">
                <p>
                </p>
            </td>
            
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; text-align:center; border: 1px solid; padding: 3px 10px;">
                <p>Date </p>
            </td>
        </tr>
         <tr>
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 3px 10px;">
                <p>
                   Reviewed by Young Person
                </p>
            </td>
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 3px 10px;">
                <p></p>
            </td>
        </tr>
    </tbody>
</table>  

<table width="100%" style="font-size: 16px; margin: 0; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #4e6031;">
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">NAME</th>
            
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 0px 10px;">DATE</th>
        </tr>
    </thead>
    <tbody>
      <?php if (!empty($signoff_data)) {
      foreach ($signoff_data as $sign_name) { ?>
      <tr>
            <td width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">
                <p>
                  <?php $strname.=!empty($sign_name['name']) ? $sign_name['name'] . ',' : '';
                        echo substr($strname, 0, -1);?>
                </p>
            </td>
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                <p></p>
            </td>
        </tr>
        <?php }
        }
      ?>
    </tbody>
</table>