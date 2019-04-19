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

    .img-sp .image-property img{ 
    max-height: 60px !important;
    width: auto !important;
    display: inline-block !important;
    margin: 0px 10px 5px 0px !important;
    float: left !important;
}.col-sm-1.image-property {
    width: auto !important;
    float: left !important;
}
  </style>
  <table width="100%" style=" margin: 0; border-collapse: collapse;">
    <?php

    if (!empty($form_data)) {
      foreach ($form_data as $row) { 


        if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {

         if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { 
           if($row['type'] == 'date'){
            if((!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] !='0000-00-00')){
              $data_textarea = configDateTime($edit_data[0][$row['name']]);
            }                                                        
            if((!empty($row['value']) && $row['value'] !='0000-00-00')){
              $row['value'] = configDateTime($row['value']);
            }                                                        
          }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
            if((!empty($edit_data[0][$row['name']]))){
              $data_textarea = timeformat($edit_data[0][$row['name']]);
            }
            if((!empty($row['value']))){
              $row['value'] = timeformat($row['value']);
            }
          }else{
            $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
          }

          ?>

          <tr style="background-color: #4e6031;border: 1px solid;">

           <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:6px 10px;">
            <b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
          </td>

        </tr>

        <tr>

          <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid;">
            <table width="100%" style="overflow: wrap;">

              <tr>
                <td style="font-size: 14px; margin:0;color:#000;text-align:left;height: auto;  padding: 0 10px;">
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
   } else if ($row['type'] == 'file') { ?>
    <tr style="background-color: #4e6031;border: 1px solid;">

     <td align="left" style="text-transform: uppercase;font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding:6px 10px;">
      <b><?= !empty($row['label']) ? $row['label'] : '' ?></b>
    </td>

  </tr>

  <tr>

    <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid;">
      <table width="100%" style="overflow: wrap;">

        <tr>
              <?php
              $fileViewArray = array(
                'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                'filePathMain' => $this->config->item('cpt_img_base_url') . $ypid,
                'filePathThumb' => $this->config->item('cpt_img_base_url_small') . $ypid
              );
              echo getCptFileViewPdf($fileViewArray);
              ?>
                                            
           </tr>

      </table>
    </td>

  </tr>
<?php    }
}
}
?>
</table>

<table width="100%">
  <tr>
    <td>
      <tr style="background-color: #4e6031;border: 1px solid;">

       <td align="left" style="font-size: 14px; margin:0;color:white;text-align:left;height: auto;  padding: 6px 10px;">
        <b>COMMENTS</b>
      </td>

    </tr>
    <tr>

      <td width="100%" style="font-size: 14px; margin:0;color:#0b1327;text-align:left;line-height: 20px;height: auto; border: 1px solid; padding: 0 10px;">
        <table width="100%" style="overflow: wrap;margin-bottom: 10px;">
          <?php foreach($comments as $cm){?>
            <tr>
              <td style="font-size: 14px; margin:5px 0px;color:#000;text-align:left;height: auto; padding: 5px 10px; border-bottom: 1px solid #9a9a9a;">
                <table style="width: 100%;"> <tr><td style="word-wrap: break-word; word-break: break-all;width: 100%;">
                 <?php echo $cm['cpt_comments'];?>
               </td></tr>  </table>

                  <?php
                  $fileViewArray = array(
                    'fileArray' => (isset($cm['cpt_attacchment']) && !empty($cm['cpt_attacchment']))? $cm['cpt_attacchment'] : '',
                    'filePathMain' => $this->config->item('cpt_img_base_url') . $ypid,
                    'filePathThumb' => $this->config->item('cpt_img_base_url_small') . $ypid
                  );
                  echo getCptFileViewPdf($fileViewArray);
                  ?>
               
              <p class="date"><small><?php echo $cm['create_name'] ?>:   <?php echo configDateTime($cm['created_date']); ?></small></p>
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
<style>
.img-sp .image-property img{ 
    max-height: 60px;
    width: auto;
    display: inline-block;
    margin: 0px 10px 5px 0px;
    float: left;
}
</style>