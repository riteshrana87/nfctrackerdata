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

            if (!empty($form_data)) {
                foreach ($form_data as $row) { 
				

                  if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
					if($row['type'] == 'select'){ ?>
					<tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
                                    </td>
                             
                           </tr>
						    <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php if(!empty($row['values'])) {
                                                        if(!empty($row['description']) && ($row['description'] == 'get_user' ||$row['description'] == 'select_multiple_user'))
                                                        {
                                                              $userAr = explode(',',$row['values']);
                                                              if(!empty($userAr))
                                                              {
                                                                foreach ($userAr as $uid) {
                                                                    echo '<p>'.getUserName($uid).'</p>';
                                                                }
                                                              }
                                                        }
                                                        else
                                                        {
                                                            echo !empty($row['values'])?nl2br(htmlentities($row['values'])):'';
                                                        }
                                                        
                                                     }
                                                     else
                                                     {
                                                        echo lang('NA');
                                                     } ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td style="padding-bottom:10px;"></td></tr>
					
					 
						
						
				   <?php } 
                     else if ($row['type'] == 'textarea' ||  $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 
                            if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($row['name']) && !empty($row['name'])){
                                $data_textarea = timeformat($row['value']);
                            }
                            if($row['type'] == 'date' && isset($row['name']) && !empty($row['name'])){
                                $data_textarea = configDateTime($row['value']);
                            }else{
                                      $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $row['value']);
                            }  
                                      ?>
						
                           <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?= !empty($data_textarea)? nl2br(html_entity_decode($data_textarea)) : '' ?>
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
  <tr>
    <td>
      <tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b>COMMENTS</b>
                                    </td>
                             
                           </tr>
						   <tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  <?php foreach($comments as $cm){?>
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?php echo $cm['rmp_comments'];?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  <?php }?>
								  
                                </table>
                              </td>
                             
                           </tr>
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

<!-- <table width="100%">
  <tr>
    <td style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
      <p style="text-align: center;"><em>&nbsp;</em></p>
    </td>
  </tr>
</table> -->

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
            <td width="50%" style="font-size: 14px; margin:0;color:black;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">
                <p>
                  <?php 
				  $strname=$sign_name['name'];
				  //$strname.=!empty($sign_name['name']) ? $sign_name['name'] . ',' : '';
                        //echo substr($strname, 0, -1);
						echo $strname;
						?>
                </p>
            </td>
            <td width="50%" style="font-size: 14px; margin:0;color:#0b1327;text-align:center;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                <p><?= (!empty($sign_name['created_date']) && $sign_name['created_date'] != '0000-00-00') ? configDateTime($sign_name['created_date']) : '' ?></p>
            </td>
        </tr>
        <?php }
        }
      ?>
    </tbody>
</table>