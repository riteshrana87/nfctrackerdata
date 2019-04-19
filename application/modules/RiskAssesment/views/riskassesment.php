

<table cellspacing="0" cellpadding="5" width="100%" border="0" style="border:0px solid #4e6031;font-family:arial;" >
	
	<?php
		if (!empty($form_data)) {
			foreach ($form_data as $row) {  //foreach start
		//pr($row);
			
	
			if($row['type'] == 'header' ){ //if start
			?>
		
		<tr>
			<td style="font-size: 20px;color:#FFFFFF;overflow: hidden; width: auto; background-color: #4e6031;border: 1px solid #4e6031;">
				 <b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
			</td>
		</tr>
		<?php } //if end ?>
		
	
	<?php if($row['type'] != 'header' ){ ?>
	
		<tr>
			<td style="border-left:0px solid #4e6031;border-right:0px solid #4e6031;">
				<table cellspacing="0" cellpadding="5" width="100%" border="1" style="border-collapse: collapse;">
					
					<?php
					
					
				if ($row['type'] == 'radio-group'  || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
					
					?>
						<tr>
							<td style="color:#FFFFFF;overflow: hidden; width: auto; background-color: #4e6031;border: 1px solid #4e6031;">
								<b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
							</td>
						</tr>
						<?php if($row['type'] == 'radio-group'){?> 
						<tr>
							<td style="font-size: 14px; color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid #4e6031; ">
						  
						  
						  <?php if($row['value'] == 'high'){
                                            $class_name = "label-danger";
                                        }else if($row['value'] == 'low'){
                                            $class_name = "label-success";    
                                        }else if($row['value'] == 'Medium'){
                                                $class_name = "label-warning";  
                                        }

                                        echo ($row['value'] == 'high') ? '<table cellpadding="5" cellspacing="10" ><tr><td  style="
    
    background-color: #d9534f;
    color: white;
    font-weight: bold;">HIGH</td></tr></table>' : (($row['value'] == 'Medium') ? '<table  cellpadding="5" cellspacing="10"><tr><td style="background-color: #FA8128;color: white;font-weight: bold;">MEDIUM</td></tr></table>' : (($row['value'] == 'low') ? '<table  cellpadding="5" cellspacing="10"><tr><td style="
    
    background-color: #5cb85c;
    color: white;
    font-weight: bold;">LOW</td></tr></table>' : '<table  cellpadding="5" cellspacing="10"><tr><td style="
   
    background-color: #5cb85c;
    color: white;
    font-weight: bold;">' . $row['value'] . '</td></tr></table>'));?>
										
										
										
						  
						  
						  </td>
						  </tr><?php }?>
			<?php } elseif ($row['type'] == 'textarea'  || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group' || $row['type'] == 'radio-group') {

                     if ($row['type'] == 'textarea'  || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') { 

                                      $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                      ?>
						
                           <tr style="background-color: #4e6031;border: 1px solid #4e6031;">
                             
                               <td align="left" style="text-transform: uppercase;font-size: 14px; color:white;text-align:left;line-height: 20px;height: auto; ">
                                      <b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
                                    </td>
									
                             
                           </tr>
						   
			<?php } }?>
					
					<?php if($row['type']!='header' && $row['type']!='radio-group') {?>
						<tr>
                              
                              <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid #4e6031; padding: 0 10px;">
                                <table width="100%" style="overflow: wrap;">
                                  
                                    <tr>
                                    <td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
<p style="word-wrap: break-word; word-break: break-all;">

										<?php if($row['subtype'] == 'time'){ ?>
                                                            <?= !empty($data_textarea) ? nl2br(timeformat($data_textarea)) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                                       <?php }elseif($row['type'] == 'date'){ ?>
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
			<?php }?>
					
				</table>
			</td>
		</tr>
		
		
	
	
	<?php } }  } ?>
	
</table>

<table width="100%" style="font-size: 16px; margin: 0; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #4e6031;">
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 5px 10px;">NAME</th>
            
            <th width="50%" style="font-size: 14px; margin:0;color:#fff;text-align:center;line-height: 15px;height: auto;font-weight: bold; border: 1px solid; padding: 0px 10px;">DATE</th>
        </tr>
    </thead>
    <tbody>
      <?php if (!empty($ra_signoff_data)) {
      foreach ($ra_signoff_data as $sign_name) { ?>
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



<!--- working code -->
