
<?php 


$value_who = $seqwise_l2_l3_sequence_events;?>
        <?php if(!empty($bambooNfcUsers)) {
        foreach ($bambooNfcUsers as $select) {  ?>
            <option value="<?php echo $select['user_type'].'_'.$select['user_id'];?>" <?=(in_array($select['user_type'].'_'.$select['user_id'],$value_who)?'selected':'')?>> <?php echo $select['first_name'] . ' ' . $select['last_name'] . ' - ' . $select['email'] ;?> 
            </option>
<?php } } ?>
<?php if(!empty($pre_outside_agency)) {
        foreach ($pre_outside_agency as $select) {  ?>
        <option  value="<?php echo $select['value'];?>" <?=(in_array($select['value'],$value_who)?'selected':'')?>> <?php echo $select['label'];?> 
        </option>
<?php } } ?>
<?php if(!empty($other)) { ?>
        <option  value="<?php echo $other;?>" <?=(in_array($other,$value_who)?'selected':'')?>><?php echo $other;?></option>
<?php } ?>



