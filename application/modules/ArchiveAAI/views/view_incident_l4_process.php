<div class="panel panel-default incident_process">
  <div class="panel-heading aai-page-header" role="tab" id="headingFive">
    <h4 class="panel-title">
      INCIDENT L4 PROCESS (YP MISSING FROM CARE)
    </h4>
  </div>
  <div class="panel-body form-horizontal">
    <div class="row aai-module clear-div-flex">
        <?php

        if (!empty($l4_form_data)) {
          foreach ($l4_form_data as $row) {
            $dd=$row['name'];
            if ($row['type'] == 'textarea') {
              ?>
              <div class="col-md-4 col-sm-6  " id="div_<?php echo $row['name']; ?>">
                  <div class="form-group">
                    <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
                    <div class="col-md-12">


                      <?php if (!empty($preveditl4Data)) {

                        $diff = new HtmlDiff(html_entity_decode($preveditl4Data["$dd"]['value']), html_entity_decode($row['value']));
                        $diff->build();
                        echo $diff->getDifference();
                        ?>
                      <?php } else { ?>
                       <?=!empty($l4_form_data[0][$row['name']]) ? nl2br(html_entity_decode($l4_form_data[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '')?>
                     <?php } ?>

                   </div>
                 </div>
             </div>

             <?php if ($row['name'] == 'state') {?>

              <div class="col-sm-12">
                  <div class="form-group">
                    <h2 class="aai-form-ttle">Person Informed For YP Return</h2>
                  </div>
              </div>
              <div class="col-sm-12 clear-div-flex" id="add_person_yp_return">
               <?php if (!empty($prel4return_data)) {foreach ($prel4return_data as $retkey => $retvalue) {?>
                 <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_' + count + '">
                   <div class="form-group">
                    <div class="col-md-6"><label>Persons Informed</label></div>
                    <div class="col-md-6">

                     <?php if (!empty($persons_infromed)) {
                      foreach ($persons_infromed as $select) { 
                       if (isset($retvalue[0]) && ($retvalue[0] == $select['value'])) { echo $select['label'];}
                       ?>

                     <?php }}?>


                   </div>
                 </div>
                 <div class="form-group">

                  <div class="col-md-6"><label>Name Of Person Informed</label></div>
                  <div class="col-md-6"><?php echo $retvalue[1]; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Badge Number</label></div>
                  <div class="col-md-6"><?php echo $retvalue[2]; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Contact Number</label></div>
                  <div class="col-md-6"><?php echo $retvalue[3]; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Contact Email</label></div>
                  <div class="col-md-6"><?php echo $retvalue[4]; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Informed By</label></div>
                  <div class="col-md-6"><?php echo $retvalue[5]; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Date</label></div>
                  <div class="col-md-6"><?php echo $retvalue[6]; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Time</label></div>
                  <div class="col-md-6 m-t-3"> <div class="input-group input-append bootstrap-timepicker"><?php echo $retvalue[7]; ?></div>
                </div>
              </div>

            </div>


          <?php }}?>
        </div>


      <?php }
    } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
      ?>

      <div class="col-md-4 col-sm-6  ">
          <div class="form-group">
            <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
            <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') {?>
           
                <div class="col-md-12">
                <?php }?>
                <div class=" col-md-12 <?=(!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : ''?><?=(!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['description']) && $row['description'] == 'dob') ? 'input-group input-append' : 'input-group input-append' : ''?>">
                 <?php if (!empty($preveditl4Data)) {
				//die('11');

                  $diff = new HtmlDiff(html_entity_decode($preveditl4Data["$dd"]['value']), html_entity_decode($row['value']));
                  $diff->build();
                  echo $diff->getDifference();
                  ?>
                <?php } else { //die('22');?>
                <?=!empty($l4_form_data[0][$row['name']]) ? nl2br(html_entity_decode($l4_form_data[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '')?>
              <?php } ?>

            </div>
            <span id="errors-container<?=$row['name']?>"></span>
            <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) {?>
            </div>
        <?php }?>
      </div>
  </div>
  <?php if ($row['label'] == 'Time YP returned') {
    echo '<div class="col-md-4 col-sm-6  ">
    <div class="form-group">
    <h2 class="aai-header-title col-md-12">Total Time YP Was Missing</h2>
    <div class="col-md-12">
    <input type="text"  class="input-textar-style form-control" name="l4_total_duration_main" id="l4_total_duration_main" value="" /><input type="hidden"  class="input-textar-style form-control" name="l4_total_duration" id="l4_total_duration" value="" /></div></div></div>';
  }
} else if ($row['type'] == 'radio-group') {
  ?>
  <?php if ($row['name'] == 'l4_debrief_offer') {?>
   <div class="col-md-4 col-sm-6  ">
      <div class="form-group">
        <h2 class="aai-header-title col-md-12">Was The YP Injured? </h2>
        <div class="col-md-12">
        <div class="radio-group">
          <div class="radio">
            <?php if($l4_yp_injured=='No'){?>
             <label class="radio-inline"><span class="label label-danger">No</span></label>
           <?php } else {?>
            <label class="radio-inline"><span class="label label-success">Yes</span></label>
          <?php }?>
        </div>
      </div>
      </div>
    </div>
</div>
<div class="col-md-4 col-sm-6  ">
  <div class="form-group">
    <h2 class="aai-header-title col-md-12">Is The YP Making A Complaint? </h2>
    <div class="col-md-12">
    <div class="radio-group">
      <div class="radio">
        <?php if($l4_is_complaint=='No'){?>
         <label class="radio-inline"><span class="label label-danger">No</span></label>
       <?php } else {?>
        <label class="radio-inline"><span class="label label-success">Yes</span></label>
      <?php }?>
    </div>
  </div>
  </div>
</div>
</div>
<div class="col-md-4 col-sm-6  ">
    <div class="form-group">
      <h2 class="aai-header-title col-md-12">Was A Staff Member Injured?</h2>
      <div class="col-md-12">
      <div class="radio-group">
        <div class="radio">
          <?php if($l4_is_staff_injured=='No'){?>
           <label class="radio-inline"><span class="label label-danger">No</span></label>
         <?php } else {?>
          <label class="radio-inline"><span class="label label-success">Yes</span></label>
        <?php }?>
      </div>
    </div>
    </div>
  </div>
</div>
<div class="col-md-4 col-sm-6  ">
    <div class="form-group">
      <h2 class="aai-header-title col-md-12">Was anyone else injured?</h2>
      <div class="col-md-12">
      <div class="radio-group">
        <div class="radio">
          <?php if($l4_is_anyone_injured=='No'){?>
           <label class="radio-inline"><span class="label label-danger">No</span></label>
         <?php } else {?>
          <label class="radio-inline"><span class="label label-success">Yes</span></label>
        <?php }?>
      </div>
    </div>
    </div>
  </div>
</div>
<div class="col-md-4 col-sm-6  " id="anyone_injured_yes">
    <div class="form-group">
      <h2 class="aai-header-title col-md-12">Enter Detail Of Anyone Injured</h2>
      <div class="col-md-12 ">
       <?=!empty($editL4Data[0][$row['name']]) ? nl2br(html_entity_decode($editL4Data[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '')?>
     </div>
   </div>
</div>
<div class="col-md-4 col-sm-6  ">
    <div class="form-group">
      <h2 class="aai-header-title col-md-12">Was YP Offered Treatment?</h2>
      <div class="col-md-12">
        <div class="radio-group">
        <div class="radio">
          <?php if($l4_is_yp_offered_treatment=='No'){?>
           <label class="radio-inline"><span class="label label-danger">No</span></label>
         <?php } else {?>
          <label class="radio-inline"><span class="label label-success">Yes</span></label>
        <?php }?>
      </div>

    </div>
  </div>
</div>
</div>
<div class="col-md-4 col-sm-6  " id="l4_yp_offered_treatment">
    <div class="form-group">
      <h2 class="aai-header-title col-md-12">Comments (Treatment given / response)</h2>
      <div class="col-md-12">
       <?=!empty($editL4Data[0][$row['name']]) ? nl2br(html_entity_decode($editL4Data[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '')?>
     </div>
   </div>
</div>
<div class="col-md-4 col-sm-6  " id="div_<?php echo $row['name']; ?>">
    <div class="form-group">
      <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
      <div class="col-md-12">
      <div class="radio-group">
       <?php if (count($row['values']) > 0) {
                foreach ($row['values'] as $radio) { //pr($radio['selected']);
                if (!empty($radio['label'])) {
                  ?>
                  <?php 
                  if(isset($row['value']) && $row['value'] == $radio['value'] && $row['value'] == 'Yes'){
                   echo '<label class="radio-inline p-l-r-0"><span class="label label-success">Yes</span></label>';
                 }else if(isset($row['value']) && $row['value'] == $radio['value'] && $row['value'] == 'No'){
                   echo '<label class="radio-inline p-l-r-0"><span class="label label-danger">No</span></label>';
                 }else{
                  if(!isset($row['value'])){
                    if(!empty($radio['selected']) && $radio['value'] == 'Yes'){
                      echo '<label class="radio-inline p-l-r-0"><span class="label label-success">Yes</span></label>';
                    }else{
                      echo '<label class="radio-inline p-l-r-0"><span class="label label-danger">No</span></label>';  
                    }    
                  }

                }
                ?>              
              <?php }}} //radio loop ?>
            </div>
          </div>
        </div>
      </div>

    <?php }?>


    <?php
  } else if ($row['type'] == 'checkbox-group') {
    ?>
    <div class="col-md-4 col-sm-6  " id="div_<?php echo $row['name']; ?>">
        <div class="form-group">
          <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
          <div class="col-md-12">
          <div class="checkbox-group">
           <?php if (count($row['values']) > 0) {
            $checkedValues = array();
            if (!empty($edit_data[0][$row['name']])) {
              $checkedValues = explode(',', $edit_data[0][$row['name']]);
            }
            foreach ($row['values'] as $checkbox) {
              if (!empty($checkbox['label'])) {?>

                <div class="<?=!empty($row['inline']) ? 'checkbox-inline' : 'checkbox'?>">
                 <label class="<?=!empty($row['toggle']) ? 'kc-toggle' : ''?>"><?=!empty($checkbox['value']) ? $checkbox['value'] : ''?>
                 <?=!empty($checkbox['label']) ? $checkbox['label'] : ''?></label>
               </div>
             <?php }}} //radio loop ?>
           </div>
         </div>
         </div>
     </div>
     <?php
   } else if ($row['type'] == 'select') {
    ?>
    <div class="col-md-4 col-sm-6  ">
        <div class="form-group">
          <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>

          <div class="col-md-12">

          <?php if (count($row['values']) > 0) {
            foreach ($row['values'] as $select) {

              if (!empty($select['label'])) { 
               if($row['value'] == $select['value']){
                 echo $select['label'];
               }
               ?>


             <?php }}} //select loop ?>

           </div>
           </div>
       </div>
       <?php
       if ($row['name'] == 'form_status') {?>

         <div class="col-md-4 col-sm-6  ">
            <div class="form-group">
              <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
                <div class="col-md-12">
                  <input type="text" disabled="true" class="form-control" value="<?=(isset($l4_report_compiler)) ? $l4_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
                  <input type="hidden" name="l4_report_compiler" class="form-control" value="<?=(isset($l4_report_compiler)) ? $l4_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
                </div>
            </div>
        </div>

      <?php } elseif ($row['name'] == 'risk_level') { ?>
        <div class="col-sm-12">
            <div class="form-group">
              <h2 class="aai-form-ttle col-md-12 aai-form-ttle">Person Informed For YP Missing</h2>
            </div>
        </div>
        <div class="col-sm-12 clear-div-flex" id="add_person_yp_missing">
          <?php if (!empty($prel4missing_yp)) {
            $i = 1;
            foreach ($prel4missing_yp as $key => $value) {
              ?>  <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_<?php echo $i; ?>">
                <div class="form-group">

                  <div class="col-md-6"><label>Persons Informed</label></div>
                  <div class="col-md-6">
                   <?php if (!empty($persons_infromed)) {
                    foreach ($persons_infromed as $select) { 
                     if (isset($value[0]) && ($value[0] == $select['value'])) { echo $select['label'];}
                     ?>
                   <?php }}?>
                 </div>
               </div>
               <div class="form-group">

                <div class="col-md-6"><label>Name Of Person Informed</label></div>
                <div class="col-md-6"><?php echo $value[1]; ?></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Badge Number</label></div>
                <div class="col-md-6"><?php echo $value[2]; ?></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Contact Number</label></div>
                <div class="col-md-6"><?php echo $value[3]; ?></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Contact Email</label></div>
                <div class="col-md-6"><?php echo $value[4]; ?></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Informed By</label></div>
                <div class="col-md-6"><?php echo $value[5]; ?></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Date</label></div>
                <div class="col-md-6"><?php echo $value[6]; ?></div>

              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Time</label></div>
                <div class="col-md-6 m-t-3"> <div class="input-group input-append bootstrap-timepicker"><?php echo $value[7]; ?></div>

              </div></div>

            </div>
            <?php $i++;}}?>
          </div>

          <div class="col-sm-12">
              <div class="form-group">
                <h2 class="aai-header-title col-md-12">Sequence Of Event</h2>
              </div>
          </div>
          <div class="col-sm-12 clear-div-flex" id="l4add_seqevent">
           <?php if (!empty($l4sequence_data)) {
             $isq = 1;
             foreach ($l4sequence_data as $seqkey => $seqvalue) { 
              $seq_datal4 = $seqvalue['l4seq_sequence_number'];
              $seq_datal4 = substr($seq_datal4,1);
              ?>
              <div class="col-md-6 col-sm-12 dynamic-div" id="item_new_sq_<?php echo $isq; ?>">
               <div class="form-group">
                <div class="col-md-6"><label>Sequence Number</label></div>
                <div class="col-md-6"><?php echo 'S'.$isq;?></div>
              </div>
              <div class="form-group">

                <div class="col-md-6"><label>Who(staff full name)</label></div>
                <div class="col-md-6"> <?php
                $value_who = $seqvalue['l4seq_who' . $seq_datal4];
                $priv_data = $l4seqresult_prev[$key]['l4seq_who' . $seq_datal4];
                ?>

                <?php if (!empty($bambooNfcUsers)) {
                  foreach ($bambooNfcUsers as $select) {
                    $staff_present_data = array_diff($value_who, $priv_data);

                    if (in_array($select['user_type'] . '_' . $select['user_id'], $staff_present_data)) {

                      $diff = new HtmlDiff('', $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] . ',');
                      $diff->build();
                      echo $diff->getDifference();

                    } else {
                      if (in_array($select['user_type'] . '_' . $select['user_id'], $value_who)) {
                        echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] . ',';
                      }
                    }
                  }} ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>What happened / what was done (include Senior Cover instructions)</label></div>
                  <div class="col-md-6"><?php echo $seqvalue['l4seq_what_happned']; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Date</label></div>
                  <div class="col-md-6"><?php echo $seqvalue['l4seq_date_event']; ?></div>

                </div>
                <div class="form-group">

                  <div class="col-md-6"><label>Time</label></div>
                  <div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker"><?php echo $seqvalue['l4seq_time_event']; ?></div>
                </div>
              </div>
              <div class="form-group">

                <div class="col-md-6"><label>All communication details</label></div>
                <div class="col-md-6"><?php echo $seqvalue['l4seq_communication']; ?></div>

              </div>
            </div>
            <?php $isq++; }}?>

            <script>
              var l4sq = "<?php echo $isq;?>";
            </script>
          </div>

        <?php }?>
        <?php
        if ($row['name'] == 'form_status') {?>

          <div class="col-md-4 col-sm-6  ">
            <div class="form-group">
             <h2 class="aai-header-title col-md-12">Report Compiler <span class="astrick">*</span></h2>
              <div class="col-md-12">
               <input type="text" disabled="true" class="form-control" value="<?=(isset($l4_report_compiler)) ? $l4_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
               <input type="hidden" name="l4_report_compiler" class="form-control" value="<?=(isset($l4_report_compiler)) ? $l4_report_compiler : $loggedInUser['FIRSTNAME'] . ' ' . $loggedInUser['LASTNAME']?>">
             </div>
       </div>
     </div>

   <?php } ?>
   <?php
 } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {
  ?>
  <?php if ($row['type'] == 'button') {?>
    <div class="col-md-4 col-sm-6  ">
        <div class="form-group">
          <div class="col-md-12 fb-button form-group">
            <button name="<?=!empty($row['name']) ? $row['name'] : ''?>" value="" type="<?=!empty($row['type']) ? $row['type'] : ''?>" class="<?=!empty($row['className']) ? $row['className'] : ''?>" name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>" style="<?=!empty($row['style']) ? $row['style'] : ''?>"><?=!empty($row['label']) ? $row['label'] : ''?></button>
          </div>
        </div>
    </div>
  <?php }
  if ($row['type'] == 'hidden') {?>
    <div class="col-sm-12">
      <input type="hidden" name="<?=!empty($row['name']) ? $row['name'] : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>" value="" />
    </div>
  <?php }
} else if ($row['type'] == 'header') {?>
  <div class="col-sm-12">
    <div class="form-group">
      <h2 class="aai-form-ttle col-sm-12"><?=!empty($row['label']) ? $row['label'] : ''?></h2>
    </div>
  </div>
<?php } else if ($row['type'] == 'file') {
  ?>
  <div class="col-md-4 col-sm-6  ">
      <div class="form-group">
        <h2 class="aai-header-title col-md-12"><?=!empty($row['label']) ? $row['label'] : ''?> <?=!empty($row['required']) ? '<span class="astrick">*</span>' : ''?></h2>
        <input type="file" name="<?=!empty($row['name']) ? $row['name'] . '[]' : ''?>" id="<?=!empty($row['name']) ? $row['name'] : ''?>"  class="<?=!empty($row['className']) ? $row['className'] : ''?>"
        <?=!empty($row['multiple']) ? 'multiple="true"' : ''?> <?=!empty($row['required']) ? 'required=true' : ''?>>
        <h2></h2>
        <div class="col-md-12">
        <?php
        /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
        $fileViewArray = array(
          'fileArray'        => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])) ? $edit_data[0][$row['name']] : $row['value'],
          'filePathMain'     => $this->config->item('ks_img_base_url') . $ypid,
          'filePathThumb'    => $this->config->item('ks_img_base_url_small') . $ypid,
          'deleteFileHidden' => 'hidden_' . $row['name'],
        );
        echo getFileView($fileViewArray);
        ?>
        <input type="hidden" name="<?=!empty($row['name']) ? 'hidden_' . $row['name'] : ''?>" id="<?=!empty($row['name']) ? 'hidden_' . $row['name'] : ''?>" value="">
      </div>
  </div>
<?php }
    } //foreach
    ?>


  <?php }?>
</div>
</div>

</div>
