


                    <table cellpadding="5" cellspacing="0" width="100%" style=" margin: 0; border-collapse: collapse;font-family:arial">
                        <?php

            if (!empty($form_data)) {
                foreach ($form_data as $row) {  
				if ($row['type'] == 'header' ||  $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
					?>
					<tr style="background-color: #4e6031;border: 1px solid;">
                             
                               <td colspan="2" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></b>
                                    </td>
                             
                           </tr>
						  <tr><td colspan="0" style="padding-bottom:10px;"></td></tr> 
						   
						   
				

				<?php } elseif ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group' ) {

                     if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text' ) { 

                                      $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                      ?>
						<tr><td></td></tr>
                           <tr cellpadding="5" cellspacing="0" style="background-color: #4e6031;border: 1px solid;">
                             
                               <td colspan="2" cellpadding="5" cellspacing="0" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  20px;">
                                      <b class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></b>
                                    </td>
                             
                           </tr>

                           <tr>
                              
                              <td colspan="2" width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">
                                       <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>
   </p>                                   
                                    </td>
									
                                  </tr>
								  
                                </table>
                              </td>
                             
                           </tr>
						   <tr><td colspan="2" style="padding-bottom:10px;"></td></tr>
                 
                     
                     <?php
                    }  
                } elseif($row['type'] == 'radio-group'){?>
				<tr style="border: 1px solid #4e6031;">
                             
                               <td width="50%" align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto;  padding:  20px;background-color: #FFFFFF;">
                                      <b class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></b>
                                    </td>
                             
                       
							<td width="50%" style="font-size: 14px; margin:0;color:#000000;text-align:left;line-height: 20px;height: auto; border: 1px solid #4e6031;background-color: #FFFFFF;">
						  
						  
						  <?php if($ibp_edit_data[0][$row['name']] == 'yes'){
                                            $class_name = "label-success";
                                        }else if($ibp_edit_data[0][$row['name']] == 'no'){
                                            $class_name = "label-danger";    
                                        }else if($ibp_edit_data[0][$row['name']] == 'Medium'){
                                                $class_name = "label-warning";  
                                        }

                                        echo ($ibp_edit_data[0][$row['name']] == 'yes') ? '<table cellpadding="5"><tr><td  style="
   
    background-color: #5cb85c;
    color: white;
    font-weight: bold;">YES</td></tr></table>' : (($ibp_edit_data[0][$row['name']] == 'Medium') ? '<table cellpadding="5"><tr><td style="
    
    background-color: #FA8128;
    color: white;
    font-weight: bold;">MEDIUM</td></tr></table>' : (($ibp_edit_data[0][$row['name']] == 'no') ? '<table cellpadding="5"><tr><td style="
    
    background-color:  #d9534f;
    color: white;
    font-weight: bold;">NO</td></tr></table>' : '<table><tr><td style="
    
    background-color: #5cb85c;
    color: white;
    font-weight: bold;">' . $prev_edit_data[0][$row['name']] . '</td></tr></table>'));?>
										
										
										
						  
						  
						  </td>
						  
				<?php }
            }
          }
            ?>
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


<table width="100%" cellpadding="0" cellspacing="0" border="1" style="font-size: 16px; margin: 0; border-collapse: collapse;font-family:arial;border:1px solid #000000">
    <thead>
        <tr style="background-color: #4e6031;">
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid #4e6031; padding: 5px 10px;">NAME</th>
            
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid #4e6031; padding: 0px 10px;">DATE</th>
        </tr>
    </thead>
    <tbody>
      <?php if (!empty($ibp_signoff_data)) {
      foreach ($ibp_signoff_data as $sign_name) { ?>
      <tr>
            <td width="50%" style="font-size: 14px; margin:0;color:black;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">
                <p>
                  <?php
					$strname=$sign_name['name'];
				
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