<table border="1" style=" margin: 0; border-collapse: collapse;" width="100%" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
        <td width="50%">
            <table width="100%" border="0" style=" margin: 0; border-collapse: collapse;">
              <tr>
              <td width="50%" style="background-color: #4e6031;color: #ffffff;font-size: 15px;">
              <p style="text-align: left;">
                  <?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?>
                  <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?>
              </p>
              </td>
              <td width="50%">
                <p>&nbsp;</p>
              </td>
              </tr>
            </table> 
        </td>
        <td width="50%">
            <table width="100%" border="1" style=" margin: 0; border-collapse: collapse;">
              <tr>
              <td width="50%" style="background-color: #4e6031;color: #ffffff;font-size: 15px;">
              <p style="text-align: left;font-size: 15px;">
                  Risk Management Plan
              </p>
              </td>
              <td width="50%" style="font-size: 15px;">
              <p style="text-align: left;"><?=date('d/m/Y')?></p>
              </td>
              </tr>
            </table> 
        </td>
    </tr>
  </tbody>
</table>
<table><tbody><tr><td></td></tr></tbody></table>
<table border="1" style=" margin: 0; border-collapse: collapse;" width="100%">
  <tbody>
    <tr>
        <td style="font-size: 20px;">Plan Detail</td>
    </tr>
    <?php 
     if(!empty($form_data))
        {
        foreach ($form_data as $row) { ?>
        <tr>
            <td style="background-color: #4e6031;color: #ffffff;font-size: 15px;"><?=!empty($row['label'])?$row['label']:''?></td>
        </tr>
        <tr>
            <td style="font-size: 15px;">
                <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {
                    if($row['type'] == 'date'){
                        if(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] !='0000-00-00'){
                                echo configDateTime($edit_data[0][$row['name']]);
                        }                                                        
                    }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                        if((!empty($edit_data[0][$row['name']]))){
                            echo timeformat($edit_data[0][$row['name']]);
                        }
                    }else{
                        echo !empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'');
                    }
                    }
                    else if($row['type'] == 'checkbox-group') {
                    if(!empty($edit_data[0][$row['name']])) {
                        $chk = explode(',',$edit_data[0][$row['name']]);
                                foreach ($chk as $chk) {
                                    echo $chk."\n";
                                }
                             
                        
                    }else{

                            if(count($row['values']) > 0) {
                               
                             foreach ($row['values'] as $chked) {
                                echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                 }
                               
                            }
                        }?>

                   <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') { 
                     if(!empty($edit_data[0][$row['name']])) {
                        if(!empty($row['description']) && ($row['description'] == 'get_user' ||$row['description'] == 'select_multiple_user'))
                        {
                              $userAr = explode(',',$edit_data[0][$row['name']]);
                              if(!empty($userAr))
                              {
                                foreach ($userAr as $uid) {
                                    echo '<p>'.getUserNameWithNumber($uid).'</p>';
                                }
                              }
                        }
                        else
                        {
                            echo !empty($edit_data[0][$row['name']])?nl2br(htmlentities($edit_data[0][$row['name']])):'';
                        }
                        
                     }
                     else
                     {
                        echo lang('NA');
                     }
                    } ?>
            </td>
        </tr>
    <?php } }?>
  </tbody>
</table>
<p style="font-size: 20px;">To Be signed off by Manager / Team Leader and all staff staffing the activity</p>
<table width="100%" border="1" style=" margin: 0; border-collapse: collapse;font-size: 20px;">
  <tbody>
    <tr>
        <td width="25%" style="font-size: 15px;">Completed by:Name</td>
        <td width="25%" style="background-color: #4e6031;color: #ffffff;font-size: 15px;">
           <p style="text-align: left;">
                  <?= !empty($edit_data[0]['name']) ? $edit_data[0]['name'] : '' ?>
              </p>
        </td>
        <td width="20%" style="font-size: 15px;">Sign Off</td>
        <td width="30%" style="background-color: #4e6031;color: #ffffff;font-size: 15px;"><?php
           if (!empty($ks_signoff_data)) {
                    foreach ($ks_signoff_data as $sign_name) {
                    ?>

                   
                                <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                            
                <?php }
            }
            ?> </td>
    </tr>
  </tbody>
</table>
