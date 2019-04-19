
<?php
  foreach ($wr_form_data as $row) {
 if(($row['label'] == 'Health' || $row['label'] == 'health') && !empty($appointments)){ ?>

                            <table width='100%' border='1' style="margin-bottom: 20px;padding-bottom: 10px; border-collapse: collapse;" cellpadding="5">
                                <tr><td style="background-color: #4e6031; color: #fff;" colspan="3" align="center"><strong>Health Appointments Attended</strong></td></tr>
                                   <tr>
									<th>Health Appointments Attended</th>
									<th>Date</th>
									<th>Outcome/ Actions</th>
									
								   </tr>
                                   <?php foreach ($appointments as $app) { ?>
                                    <tr>
                                       <td align="center">
                                       	   <?= !empty($app['mp_name']) ? $app['mp_name'] : '' ?>
                                       </td> 
									   <td align="center">
                                               <?=(!empty($app['appointment_date']) && $app['appointment_date'] !='0000-00-00')?configDateTime($app['appointment_date']):''?>
                                            </td> 
											
											<td align="left">
											 <?= !empty($app['comments']) ? $app['comments'] : '' ?>
                                                                             <?php               if(!empty($appointment_view_comments)){ 
                                                foreach ($appointment_view_comments as $comments_data) {
                                                    if($app['appointment_id'] == $comments_data['md_appoint_id']){ ?>                                                        
                                                                    <p class="small"> <?php echo $comments_data['md_comment']?></p>
                                                                    <p class="date"><small><?php echo $comments_data['create_name'] ?>:   <?php echo configDateTime($comments_data['created_date']) ?></small></p>
                                                    <?php } ?>
                                                <?php } } ?>
                                            </td>
                                    </tr>
									 <?php
                                           
                                        }
                                        ?>
									
                                </table>

  <?php }} ?>
<table width="100%" style="margin: 0; border-collapse: collapse;" cellpadding="10" cellspacing="0" border='1'>
    <?php
    if (!empty($wr_form_data)) { 
        $i = 0;
        foreach ($wr_form_data as $row) { 
            $prv_align = ($i != 0) ? $wr_form_data[$i - 1]['displayonalign'] : '';
            $nxt_type = ($i != count($wr_form_data) + 1) ? $wr_form_data[$i + 1]['type'] : '';
            if ($row['type'] == 'textarea' || $row['type'] == 'text' || $row['type'] == 'select' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'radio-group' || $row['type'] == 'checkbox-group' || $row['type'] == 'file' || $row['type'] == "header") {
                if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])){
                    $data_textarea = timeformat($edit_data[0][$row['name']]);
                }else if($row['type'] == 'date' && isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])){
                    $data_textarea = configDateTime($edit_data[0][$row['name']]);
                }else{
                $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                }
                ?>
                <?php
                if ($prv_align == '0' && $row['displayonalign'] == '0') {
                    echo "<tr><td></td>";
                }
                ?>
            <?php if ($row['displayonalign'] == '1') { ?>

                    <tr>
                        <?php
                        if ($row['type'] == 'textarea') {
                            $colspan = '2';
                        } else {
                            $colspan = '1';
                        }
                        ?>
                        <?php
                        if ($row['type'] == 'radio-group' || $row['type'] == 'checkbox-group') {
                            $padding = '0';
                        } else {
                            $padding = '10';
                        }
                        ?>
                        <td colspan="<?= $colspan; ?>" style="padding:<?= $padding; ?>">

                        <?php } else if ($row['displayonalign'] == '0') { ?>
                            <?php
                            if ($row['type'] == 'file') {
                                $colspan = '2';
                            } else {
                                $colspan = '3';
                            }
                            ?>
                        <td colspan="<?= $colspan; ?>">
                        <?php } else { ?>

                    <tr><td colspan="4" align="center" style="background-color: #4e6031; color: #fff;">
            <?php } ?>
                            <?php if ($row['type'] == 'radio-group' || $row['type'] == 'checkbox-group') { ?>

                            <table width='100%' border='1' style="margin: 0; border-collapse: collapse;" cellpadding="5">
                                <tr><td colspan="2" align="center"><strong><?= !empty($row['label']) ? $row['label'] : '' ?></strong></td></tr>
                                    <?php if (count($row['values']) > 0) { ?>
                                    <tr>
                                        <?php foreach ($row['values'] as $chked) { ?>
                                            <td align="center"><?php echo $chked['label']; ?></td>
                                        <?php } ?> 
                                    </tr>
                                      <tr>
                                        <?php foreach ($row['values'] as $chked) { ?>
                                            <td align="center">
                                                <?php if ($chked['value'] == $edit_data[0][$row['name']]){ ?>
                                                    <img width="20" height="20" src="<?= base_url('uploads/assets/img/checked-checkbox.png') ?>">
                                                <?php } else { ?>
                                                    <img width="20" height="20" src="<?= base_url('uploads/assets/img/unchecked-checkbox.png') ?>">                                                
                                                <?php } ?>
                                            </td>
                                            <?php
                                            $k++;
                                        }
                                        ?>
                                    </tr>
									<?php } ?>  
                                </table>
                                                 

                        <?php } else if ($row['type'] == 'file') { ?> 
                            <strong><?= !empty($row['label']) ? $row['label'] : '' ?></strong><br />
                            <?php
                            if (!empty($edit_data[0][$row['name']])) {
                                            $img = base_url($edit_data[0][$row['name']]);
                                                
                              if (@is_array(getimagesize($img))) {
                                                        ?>
                                                        <img width="100" height="100" src="<?= $img ?>">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img width="100" height="100" src="<?= base_url('uploads/images/icons 64/file-ico.png') ?>">
                                                        <?php
                                                    }
                                                    
                            }
                            ?>
            <?php } else if ($row['type'] == "header") {
                ?><h4><?= !empty($row['label']) ? $row['label'] : '' ?></h4>
                        <?php } else { ?> 

                            <strong><?= !empty($row['label']) ? $row['label'] : '' ?>:</strong><br />
                        <?php echo (!empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '')); ?>
                    <?php } ?>
                    </td>                       

                    <?php
                    if ($row['displayonalign'] == '0' || $row['displayonalign'] == '2') {
                        echo '</tr>';
                        if($nxt_type == 'header'){
                          echo '<tr style="padding: 5px; border: 0;"><td style="border: 0;" colspan="4"></td></tr>';
                         }
                    }
                    ?>

                    <?php
                } $i++;
            }
        }
        ?>
    <tr style="padding: 5px; border: 0;"><td style="border: 0;" colspan="4"></td></tr>
    <tr>
        <td colspan="4" style="padding:0;">
             <table width="100%" cellpadding="6" style="margin: 0; border-collapse: collapse;" border="1">
                <tbody>
                    <tr>
                        <td width="33.33%"><p></p></td>
                        <td width="33.33%" align="center"><p>Name </p></td>
                        <td width="33.33%" align="center"><p>Date </p></td>
                    </tr>
                    <?php 
                        $totalCnt = count($parent_signoff) + count($social_worker_signoff) + count($other_signoff); 
                        $totalStaff = array_merge($parent_signoff, $social_worker_signoff, $other_signoff);                         
                    ?>
                        <tr>
                            <?php 
                                if(!empty($totalStaff)){ 
                                    $t = 0;
                                    foreach($totalStaff as $staff){ 
                                        $t++; ?>
                                            <tr>
                                                <?php if($t == 1){ ?>
                                                    <td align="center" rowspan="<?php echo $totalCnt;?>"><p>Sent to:</p></td>
                                                <?php } ?>
                                                <td align="center"><p><?php echo $staff['staff_name']; ?></p></td>
                                                <td align="center"><p><?php echo configDateTime($staff['created_date']); ?></p></td>
                                            </tr>
                                    <?php } 
                                } else { ?>
                                    <td><p>Sent to:</p></td>
                                    <td></td>
                                    <td></td>
                            <?php } ?>                            
                        </tr>
                </tbody>
            </table>   
        </td>
    </tr> 
    <tr style="padding: 5px; border: 0;"><td style="border: 0;" colspan="4"></td></tr>
    <tr>
        <td colspan="4" style="padding:0;">
             <table width="100%" cellpadding="6" style="margin: 0; border-collapse: collapse;" border="1">
                <tbody>
                  <tr>
                        <td colspan="2" width="50%"><p></p></td>
                        <td colspan="2" width="50%" align="center"><p>Date</p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><p>Reviewed by Young Person</p></td>
                        <td colspan="2"><p> </p></td>
                    </tr>
                </tbody>
            </table>   
        </td>
    </tr> 
    <tr style="padding: 5px; border: 0;"><td style="border: 0;" colspan="4"></td></tr>
    <tr>
        <td width="30%"><b>Reported By</b></td>
        <td width="30%" style="background-color: #4e6031; color: #fff;"><?php echo $this->session->userdata('LOGGED_IN')['FIRSTNAME'] . " " . $this->session->userdata('LOGGED_IN')['LASTNAME']; ?></td>
        <td width="20%"><b>Date</b></td>
        <td width="20%" style="background-color: #4e6031; color: #fff;"><?php echo date("d/m/Y"); ?></td>
    </tr>
</table>
<p style="text-align: center;">All reports to be proof read by the Home Registered Manager or Team Leader before being sent</p>