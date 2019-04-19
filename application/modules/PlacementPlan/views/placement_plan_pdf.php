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

	if (!empty($pp_form_data)) {
		foreach ($pp_form_data as $row) { //pr($edit_data);




			if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {

				if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 

					$data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
					?>

					<tr style="background-color: #4e6031;border: 1px solid;">
						<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  5px 20px;">
							<b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
						</td>
					</tr>

					<tr>
						<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
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
					<tr><td style="padding-bottom:10px;"></td></tr>


					<?php
				}  
                                else if($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date')
                                {  ?>
							
							<tr style="background-color: #4e6031;border: 1px solid;">
						<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  5px 20px;">
							<b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
						</td>
					</tr>

					<tr>
						<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 5px 10px;">
							<table width="100%" style="overflow: wrap;">
								<tr>
									<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
										<p style="word-wrap: break-word; word-break: break-all;">
											<?= !empty(isset($row['value']) ? $row['value'] : '') ?>
										</p>                                   
									</td>
									
								</tr>
							</table>
						</td>
					</tr>
					<tr><td style="padding-bottom:10px;"></td></tr>
								
								 <?php
                                }
                                else if($row['type'] == 'radio-group')
                                {
                                ?>
								<tr style="background-color: #4e6031;border: 1px solid;">
									<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  5px 20px;">
										<b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
									</td>
								</tr>
								
								<tr>
								
									<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
										<table width="100%" style="overflow: wrap;">
											<tr>
												<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
													<p style="word-wrap: break-word; word-break: break-all;">
														<?php
                                        if (!empty($edit_data[0][$row['name']])) {
                                            echo ($edit_data[0][$row['name']] == 'yes') ? '<span class="label label-success">Yes</span>' : (($edit_data[0][$row['name']] == 'no') ? '<span class="label label-danger">No</span>' : '<span class="label label-success">' . $edit_data[0][$row['name']] . '</span>');
                                        } else {
                                            if (!empty($row['value'])) {
                                                echo ($row['value'] == 'yes') ? '<span class="label label-success">Yes</span>' : (($row['value'] == 'no') ? '<span class="label label-danger">No</span>' : '<span class="label label-success">' . $row['value'] . '</span>');
                                            }
                                        }
                                        ?>
													</p>                                   
												</td>
												
											</tr>
										</table>
									</td>
								</tr>
								<tr><td style="padding-bottom:10px;"></td></tr>
								
								
								<?php
                                    } else if ($row['type'] == 'header') {
                                        ?>
										<tr style="background-color: #4e6031;border: 1px solid;">
									<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:  5px 20px;">
										<b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
									</td>
								</tr>
								
								
								<tr><td style="padding-bottom:10px;"></td></tr>
					
						<?php
                            } else if ($row['type'] == 'file') {
                                ?>
								<tr style="background-color: #4e6031;border: 1px solid;">
									<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding: 5px 20px;">
										<b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
									</td>
								</tr>
								<tr>
									<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
										<table width="100%" style="overflow: wrap;">
											<tr>
												<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
													<p style="word-wrap: break-word; word-break: break-all;">
														<?php
                                                if (@is_array(getimagesize($this->config->item('pp_img_base_url') . $ypid . '/' . $img))) {
                                                    ?>
                                                        <img width="100" height="100" src="<?= $this->config->item('pp_img_base_url_small') . $ypid . '/' . $img ?>">
                                                    <?php
                                                } else {
                                                    ?>
                                                        <img width="100" height="100" src="<?= base_url('uploads/images/icons 64/file-ico.png') ?>">
                                                        <?php
                                                    }
                                                    ?>
													</p>                                   
												</td>
												
											</tr>
										</table>
									</td>
								</tr>
								<tr><td style="padding-bottom:10px;"></td></tr>
							<?php }?>
                <?php } //foreach
            }
        }
        ?>	
    </table>
    
    <table width="100%" style=" margin: 0; border-collapse: collapse;">
    	<tr>
    		<td>
    			<p style="text-align: center;"><em>&nbsp;</em></p>
    		</td>
    	</tr>
    </table>
    <table width="100%" style=" margin: 0; border-collapse: collapse;">


    	<tr style="background-color: #4e6031;border: 1px solid;">

    		<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding: 4 10px;">
    			<b>PRE PLACEMENT INFORMATION</b>
    		</td>
    	</tr>

    	<tr>
    		<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
    			<table width="100%" style="overflow: wrap;">
    				<tr>
    					<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;">
    						<p style="word-wrap: break-word; word-break: break-all;">
    							<?=!empty($edit_data[0]['pre_placement_info'])?html_entity_decode($edit_data[0]['pre_placement_info']):(isset($edit_data[0]['pre_placement_info'])?$edit_data[0]['pre_placement_info']:'')?>
    						</p>                                   
    					</td>
    				</tr>
    			</table>
    		</td>
    	</tr>
    	<tr><td style="padding-bottom:10px;"></td></tr>

    	<tr style="background-color: #4e6031;border: 1px solid;">

    		<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;padding:4px 10px;">
    			<b>Aims of Placement Long Term Plans</b>
    		</td>

    	</tr>
		<?php if(!empty($edit_data_pp_aim)){
					//$health_count=count($edit_data_pp_health);
    			$i=1;
    			$n= 0; 
    			?>

    			<?php foreach($edit_data_pp_aim as $aim_data){ //pr($pp_aim_archve_data);?>
    	<tr style="border:1px solid #000;">
    		<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
    			<table width="100%" style="overflow: wrap;">
    				<tr>
    		
    				<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    					<p style="word-wrap: break-word; word-break: break-all;">
    						<?php echo $aim_data['aims_of_placement_data'];?>
    					</p>
    				</td>
    				
    					</tr>
    				</table>
    			</td>
    		</tr>
			
			
			<?php $i++; } ?>
    					<script>
    					var x= "<?php echo $i;?>";
    					</script> <?php }?>


    				<tr><td style="padding-bottom:10px;"></td></tr>

    				<tr style="background-color: #4e6031;border: 1px solid;">

    					<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:4px 20px;">
    						<b>Actions from LAC Review Medium Term Plans</b>
    					</td>

    				</tr>
					<?php if(!empty($edit_data_pp_lac)){
							//$health_count=count($edit_data_pp_health);
									$i=1;
									$n= 0; 
									?>

									<?php foreach($edit_data_pp_lac as $lac_data){ //pr($pp_aim_archve_data);?>
    				<tr style="border:1px solid #000;">

    					<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
    						<table width="100%" style="overflow: wrap;">
										<tr>

								
										<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
											<p style="word-wrap: break-word; word-break: break-all;">
												<?php echo $lac_data['lac_review_data'];?>
											</p>
										</td>
										
										</tr>
    						</table>
    					</td>
    							</tr>
								<?php $i++; } ?>
										<script>
											var x= "<?php echo $i;?>";
											</script> <?php }?>
    							<tr><td style="padding-bottom:10px;"></td></tr>	   
    							<tr style="background-color: #4e6031;border: 1px solid;">

    								<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;padding:4px 20px;">
    									<b>Health</b>
    								</td>
    							</tr>

    							<?php if(!empty($edit_data_pp_health)){ ?>
    								<?php foreach($edit_data_pp_health as $health_data){?>
    									<tr style="margin-bottom: 6px">
    										<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
    											<p style="color:#4e6031;padding: 4px;font-size: 15px;padding:4px 0px;"><?= !empty($health_data['heading']) ? $health_data['heading'] : lang('NA') ?></p>
    											<table width="100%" style="overflow: wrap;">
    												<thead>
    													<tr style="background-color: #4e6031;border: 1px solid;">

    														<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0px 20px;">
    															<b>Placement Plan</b>
    														</th>
    														<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0px 20px;">
    															<b>Risk Assestment</b>
    														</th>
    														<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0px 20px;">
    															<b>INDIVIDUAL STRATEGIES</b>
    														</th>
    													</tr>
    												</thead>
    												<tbody>
    													<tr style="border:1px solid #000;">
    														<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    															<p style="word-wrap: break-word; word-break: break-all;">
    																<?=!empty($health_data['pre_placement'])?html_entity_decode($health_data['pre_placement']):(isset($health_data['pre_placement'])?$health_data['pre_placement']:'')?>
    															</p>                                   
    														</td>
    														<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; border:1px solid #000;">
    															<p style="word-wrap: break-word; word-break: break-all;">
    																<?=!empty($health_data['risk_assesment'])?html_entity_decode($health_data['risk_assesment']):(isset($health_data['risk_assesment'])?$health_data['risk_assesment']:'')?>
    															</p>                                   
    														</td>
    														<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    															<p style="word-wrap: break-word; word-break: break-all;">
    																<?=!empty($health_data['individual_strategies'])?html_entity_decode($health_data['individual_strategies']):(isset($health_data['individual_strategies'])?$health_data['individual_strategies']:'')?>
    															</p>                                   
    														</td>
    													</tr>
    												</tbody>
    											</table>
    										</td>
    									</tr>
    								<?php }}?>

    								<tr><td style="padding-bottom:10px;"></td></tr>	   
    								<tr style="background-color: #4e6031;border: 1px solid;">

    										<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;padding:4px 20px;">
    											<b>Education</b>
    										</td>
    								</tr>
    								<?php if(!empty($edit_data_pp_edu)){ ?>
    									<?php foreach($edit_data_pp_edu as $edu_data){?>

    										<tr style="margin-bottom: 6px">
    											<td width="100%" style="font-size: 14px;font-weight: 500; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
    												<p style="color:#4e6031;padding: 4px;font-size: 15px;padding:4px 0px;"><?= !empty($edu_data['heading_edu']) ? $edu_data['heading_edu'] : lang('NA') ?></p>
    												<table width="100%" style="overflow: wrap;">
    													<thead>
    														<tr style="background-color: #4e6031;border: 1px solid;">

    															<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																<b>Placement Plan</b>
    															</th>
    															<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																<b>Risk Assestment </b>
    															</th>
    															<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																<b>INDIVIDUAL STRATEGIES</b>
    															</th>
    														</tr>
    													</thead>
    													<tbody>

    														<tr style="border:1px solid #000;">
    															<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																<p style="word-wrap: break-word; word-break: break-all;">
    																	<?=!empty($edu_data['pre_placement_edu_sub'])?html_entity_decode($edu_data['pre_placement_edu_sub']):(isset($edu_data['pre_placement_edu_sub'])?$edu_data['pre_placement_edu_sub']:'')?>
    																</p>                                   
    															</td>
    															<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; border:1px solid #000;">
    																<p style="word-wrap: break-word; word-break: break-all;">
    																	<?=!empty($edu_data['risk_assesment_edu'])?html_entity_decode($edu_data['risk_assesment_edu']):(isset($edu_data['risk_assesment_edu'])?$edu_data['risk_assesment_edu']:'')?>
    																</p>                                   
    															</td>
    															<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																<p style="word-wrap: break-word; word-break: break-all;">
    																	<?=!empty($edu_data['individual_strategies_edu'])?html_entity_decode($edu_data['individual_strategies_edu']):(isset($edu_data['individual_strategies_edu'])?$edu_data['individual_strategies_edu']:'')?>
    																</p>                                   
    															</td>


    														</tr>


    													</tbody>
    												</table>
    											</td>
    											</tr><?php }}?>

    											<tr><td style="padding-bottom:10px;"></td></tr>	   
    											<tr style="background-color: #4e6031;border: 1px solid;">

    												<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto; padding:4px 20px;">
    													<b>Transport</b>
    												</td>


    											</tr>
    											<?php if(!empty($edit_data_pp_tra)){ ?>
    												<?php foreach($edit_data_pp_tra as $tra_data){?>
    													<tr style="margin-bottom: 6px">
    														<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;height: auto; border: 1px solid; padding: 0 10px;">
    															<p style="color:#4e6031;padding: 4px;font-size: 15px;padding:4px 0px;"><?= !empty($tra_data['heading_tra']) ? $tra_data['heading_tra'] : lang('NA') ?></p>
    															<table width="100%" style="overflow: wrap;">
    																<thead>
    																	<tr style="background-color: #4e6031;border: 1px solid;">

    																		<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																			<b>Placement Plan</b>
    																		</th>
    																		<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																			<b>Risk Assestment</b>
    																		</th>
    																		<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																			<b>INDIVIDUAL STRATEGIES</b>
    																		</th>
    																	</tr>
    																</thead>
    																<tbody>

    																	<tr style="border:1px solid #000;">
    																		<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																			<p style="word-wrap: break-word; word-break: break-all;">
    																				<?=!empty($tra_data['pre_placement_tra'])?html_entity_decode($tra_data['pre_placement_tra']):(isset($tra_data['pre_placement_tra'])?$tra_data['pre_placement_tra']:'')?>
    																			</p>                                   
    																		</td>
    																		<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; border:1px solid #000;">
    																			<p style="word-wrap: break-word; word-break: break-all;">
    																				<?=!empty($tra_data['risk_assesment_tra'])?html_entity_decode($tra_data['risk_assesment_tra']):(isset($tra_data['risk_assesment_tra'])?$tra_data['risk_assesment_tra']:'')?>
    																			</p>                                   
    																		</td>
    																		<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																			<p style="word-wrap: break-word; word-break: break-all;">
    																				<?=!empty($tra_data['individual_strategies_tra'])?html_entity_decode($tra_data['individual_strategies_tra']):(isset($tra_data['individual_strategies_tra'])?$tra_data['individual_strategies_tra']:'')?>
    																			</p>                                   
    																		</td>


    																	</tr>


    																</tbody>
    															</table>
    														</td>
    														</tr><?php }}?>


    														<tr><td style="padding-bottom:10px;"></td></tr>	   
    														<tr style="background-color: #4e6031;border: 1px solid;">

    															<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;padding:4px 20px;">
    																<b>Contact</b>
    															</td>


    														</tr>
    														<?php if(!empty($edit_data_pp_con)){ ?>
    															<?php foreach($edit_data_pp_con as $con_data){ ?>
    																<tr style="margin-bottom: 6px">
    																	<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;height: auto; border: 1px solid; padding: 0 10px;">
    																		<p style="color:#4e6031;padding: 4px;font-size: 15px;padding:4px 0px;">	<?= !empty($con_data['heading_con']) ? $con_data['heading_con'] : lang('NA') ?></p>
    																		<table width="100%" style="overflow: wrap;">
    																			<thead>
    																				<tr style="background-color: #4e6031;border: 1px solid;">

    																					<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding:0  20px;">
    																						<b>Placement Plan</b>
    																					</th>
    																					<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding: 0 20px;">
    																						<b>Risk Assestment</b>
    																					</th>
    																					<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;line-height: 20px;height: auto;  padding: 0 20px;">
    																						<b>INDIVIDUAL STRATEGIES</b>
    																					</th>
    																				</tr>
    																			</thead>
    																			<tbody>

    																				<tr style="border:1px solid #000;">
    																					<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																						<p style="word-wrap: break-word; word-break: break-all;">
    																							<?=!empty($con_data['pre_placement_con'])?html_entity_decode($con_data['pre_placement_con']):(isset($con_data['pre_placement_con'])?$con_data['pre_placement_con']:'')?>	
    																						</p>                                   
    																					</td>
    																					<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; border:1px solid #000;">
    																						<p style="word-wrap: break-word; word-break: break-all;">
    																							<?=!empty($con_data['risk_assesment_con'])?html_entity_decode($con_data['risk_assesment_con']):(isset($con_data['risk_assesment_con'])?$con_data['risk_assesment_con']:'')?>
    																						</p>                                   
    																					</td>
    																					<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																						<p style="word-wrap: break-word; word-break: break-all;">
    																							<?=!empty($con_data['individual_strategies_con'])?html_entity_decode($con_data['individual_strategies_con']):(isset($con_data['individual_strategies_con'])?$con_data['individual_strategies_con']:'')?>
    																						</p>                                   
    																					</td>


    																				</tr>


    																			</tbody>
    																		</table>
    																	</td>
    																</tr>
    															<?php }} ?>

    															<tr><td style="padding-bottom:10px;"></td></tr>	   
    															<tr style="background-color: #4e6031;border: 1px solid;">

    																<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;padding:4px 20px;">
    																	<b>Free Time</b>
    																</td>


    															</tr>
    															<?php if(!empty($edit_data_pp_ft)){ ?>
    																<?php foreach($edit_data_pp_ft as $ft_data){ ?>
    																	<tr style="margin-bottom: 6px">
    																		<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
    																			<p style="color:#4e6031;padding: 4px;font-size: 15px;padding:4px 0px;"><?= !empty($ft_data['heading_ft']) ? $ft_data['heading_ft'] : lang('NA') ?></p>
    																			<table width="100%" style="overflow: wrap;">
    																				<thead>
    																					<tr style="background-color: #4e6031;border: 1px solid;">

    																						<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0  20px;">
    																							<b>Placement Plan</b>
    																						</th>
    																						<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0  20px;">
    																							<b>Risk Assestment</b>
    																						</th>
    																						<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																							<b>INDIVIDUAL STRATEGIES</b>
    																						</th>
    																					</tr>
    																				</thead>
    																				<tbody>

    																					<tr style="border:1px solid #000;">
    																						<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																							<p style="word-wrap: break-word; word-break: break-all;">
    																								<?=!empty($ft_data['pre_placement_ft'])?html_entity_decode($ft_data['pre_placement_ft']):(isset($ft_data['pre_placement_ft'])?$ft_data['pre_placement_ft']:'')?>		
    																							</p>                                   
    																						</td>
    																						<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; border:1px solid #000;">
    																							<p style="word-wrap: break-word; word-break: break-all;">
    																								<?=!empty($ft_data['risk_assesment_ft'])?html_entity_decode($ft_data['risk_assesment_ft']):(isset($ft_data['risk_assesment_ft'])?$ft_data['risk_assesment_ft']:'')?>
    																							</p>                                   
    																						</td>
    																						<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																							<p style="word-wrap: break-word; word-break: break-all;">
    																								<?=!empty($ft_data['individual_strategies_ft'])?html_entity_decode($ft_data['individual_strategies_ft']):(isset($ft_data['individual_strategies_ft'])?$ft_data['individual_strategies_ft']:'')?>
    																							</p>                                   
    																						</td>


    																					</tr>

    																				</tbody>
    																			</table>
    																		</td>
    																	</tr>
    																<?php }}?>
    																<tr><td style="padding-bottom:10px;"></td></tr>	   
    																<tr style="background-color: #4e6031;border: 1px solid;">

    																	<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;padding:4px 20px;">
    																		<b>Mobile, Gaming & Internet</b>
    																	</td>


    																</tr>
    																<?php if(!empty($edit_data_pp_mgi)){ ?>
    																	<?php foreach($edit_data_pp_mgi as $mgi_data){?>
    																		<tr style="margin-bottom: 6px">
    																			<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;height: auto; border: 1px solid; padding: 0 10px;">
    																				<p style="color:#4e6031;padding: 4px;font-size: 15px;padding:4px 0px;"><?= !empty($mgi_data['heading_mgi']) ? $mgi_data['heading_mgi'] : lang('NA') ?></p>
    																				<table width="100%" style="overflow: wrap;">
    																					<thead>
    																						<tr style="background-color: #4e6031;border: 1px solid;">

    																							<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																								<b>Placement Plan</b>
    																							</th>
    																							<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																								<b>Risk Assestment</b>
    																							</th>
    																							<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0  20px;">
    																								<b>INDIVIDUAL STRATEGIES</b>
    																							</th>
    																						</tr>
    																					</thead>
    																					<tbody>

    																						<tr style="border:1px solid #000;">
    																							<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																								<p style="word-wrap: break-word; word-break: break-all;">
    																									<?=!empty($mgi_data['pre_placement_mgi'])?html_entity_decode($mgi_data['pre_placement_mgi']):(isset($mgi_data['pre_placement_mgi'])?$mgi_data['pre_placement_mgi']:'')?>	
    																								</p>                                   
    																							</td>
    																							<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; border:1px solid #000;">
    																								<p style="word-wrap: break-word; word-break: break-all;">
    																									<?=!empty($mgi_data['risk_assesment_mgi'])?html_entity_decode($mgi_data['risk_assesment_mgi']):(isset($mgi_data['risk_assesment_mgi'])?$mgi_data['risk_assesment_mgi']:'')?>
    																								</p>                                   
    																							</td>
    																							<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																								<p style="word-wrap: break-word; word-break: break-all;">
    																									<?=!empty($mgi_data['individual_strategies_mgi'])?html_entity_decode($mgi_data['individual_strategies_mgi']):(isset($mgi_data['individual_strategies_mgi'])?$mgi_data['individual_strategies_mgi']:'')?>
    																								</p>                                   
    																							</td>
    																						</tr>
    																					</tbody>
    																				</table>
    																			</td>
    																		</tr>
    																	<?php }}?>

    																	<tr><td style="padding-bottom:10px;"></td></tr>	   
    																	<tr style="background-color: #4e6031;border: 1px solid;">

    																		<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:4px 20px;">
    																			<b>Positive Relationships</b>
    																		</td>


    																	</tr>
    																	<?php if(!empty($edit_data_pp_pr)){ ?>
    																		<?php foreach($edit_data_pp_pr as $pr_data){?>
    																			<tr style="margin-bottom: 6px;">
    																				<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;height: auto; border: 1px solid; padding: 0 10px;">
    																					<p style="color:#4e6031;padding: 4px;font-size: 15px;padding:4px 0px;"><?= !empty($pr_data['heading_pr']) ? $pr_data['heading_pr'] : lang('NA') ?></p>
    																					<table width="100%" style="overflow: wrap;">
    																						<thead>
    																							<tr style="background-color: #4e6031;border: 1px solid;">

    																								<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0  20px;">
    																									<b>Placement Plan</b>
    																								</th>
    																								<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 0 20px;">
    																									<b>Risk Assestment </b>
    																								</th>
    																								<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0  20px;">
    																									<b>INDIVIDUAL STRATEGIES</b>
    																								</th>
    																							</tr>
    																						</thead>
    																						<tbody>

    																							<tr style="border:1px solid #000;">
    																								<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																									<p style="word-wrap: break-word; word-break: break-all;">
    																										<?=!empty($pr_data['pre_placement_pr'])?html_entity_decode($pr_data['pre_placement_pr']):(isset($pr_data['pre_placement_pr'])?$pr_data['pre_placement_pr']:'')?>
    																									</p>                                   
    																								</td>
    																								<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; border:1px solid #000;">
    																									<p style="word-wrap: break-word; word-break: break-all;">
    																										<?=!empty($pr_data['risk_assesment_pr'])?html_entity_decode($pr_data['risk_assesment_pr']):(isset($pr_data['risk_assesment_pr'])?$pr_data['risk_assesment_pr']:'')?>
    																									</p>                                   
    																								</td>
    																								<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																									<p style="word-wrap: break-word; word-break: break-all;">
    																										<?=!empty($pr_data['individual_strategies_pr'])?html_entity_decode($pr_data['individual_strategies_pr']):(isset($pr_data['individual_strategies_pr'])?$pr_data['individual_strategies_pr']:'')?>
    																									</p>                                   
    																								</td>


    																							</tr>


    																						</tbody>
    																					</table>
    																				</td>
    																			</tr>
    																		<?php }}?>

    																		<tr><td style="padding-bottom:10px;"></td></tr>	   
    																		<tr style="background-color: #4e6031;border: 1px solid;">

    																			<td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:4px 20px;">
    																				<b>Behaviour Concerns </b>
    																			</td>


    																		</tr>
    																		<?php if(!empty($edit_data_pp_bc)){ ?>
    																			<?php foreach($edit_data_pp_bc as $bc_data){?>
    																				<tr style="margin-bottom: 6px;">
    																					<td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;height: auto; border: 1px solid; padding: 0 10px;">
    																						<p style="color:#4e6031;padding: 4px;font-size: 15px;padding:4px 0px;"><?= !empty($bc_data['heading_bc']) ? $bc_data['heading_bc'] : lang('NA') ?></p>
    																						<table width="100%" style="overflow: wrap;">
    																							<thead>
    																								<tr style="background-color: #4e6031;border: 1px solid;">

    																									<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0  20px;">
    																										<b>Placement Plan</b>
    																									</th>
    																									<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0  20px;">
    																										<b>Risk Assestment</b>
    																									</th>
    																									<th align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:0  20px;">
    																										<b>INDIVIDUAL STRATEGIES</b>
    																									</th>
    																								</tr>
    																							</thead>
    																							<tbody>

    																								<tr style="border:1px solid #000;">
    																									<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																										<p style="word-wrap: break-word; word-break: break-all;">
    																											<?=!empty($bc_data['pre_placement_bc'])?html_entity_decode($bc_data['pre_placement_bc']):(isset($bc_data['pre_placement_bc'])?$bc_data['pre_placement_bc']:'')?>
    																										</p>                                   
    																									</td>
    																									<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px; border:1px solid #000;">
    																										<p style="word-wrap: break-word; word-break: break-all;">
    																											<?=!empty($bc_data['risk_assesment_bc'])?html_entity_decode($bc_data['risk_assesment_bc']):(isset($bc_data['risk_assesment_bc'])?$bc_data['risk_assesment_bc']:'')?>
    																										</p>                                   
    																									</td>
    																									<td style="font-size: 14px; margin:0;color:#000;text-align:left;line-height: 20px;height: auto;  padding: 0 10px;border:1px solid #000;">
    																										<p style="word-wrap: break-word; word-break: break-all;">
    																											<?=!empty($bc_data['individual_strategies_bc'])?html_entity_decode($bc_data['individual_strategies_bc']):(isset($bc_data['individual_strategies_bc'])?$bc_data['individual_strategies_bc']:'')?>
    																										</p>                                   
    																									</td>


    																								</tr>


    																							</tbody>
    																						</table>
    																					</td>
    																				</tr>
    																			<?php }}?>

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