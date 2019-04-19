<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="row" id="table-view">
    <div class="col-xs-12 m-t-10">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                     <?php
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                if(!empty($row['displayonlist']))
                                {
                                ?>
                                    <th <?php
                                    if (isset($sortfield) && $sortfield == $row['name']) {
                                        if ($sortby == 'asc') {
                                            echo "class = 'sort-dsc'";
                                        } else {
                                            echo "class = 'sort-asc'";
                                        }
                                    } else {
                                        echo "class = 'sort'";
                                    }
                                    ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('<?=$row['name']?>', '<?php echo $sorttypepass; ?>')" style="min-width: 120px">  
                                    <?= !empty($row['label']) ? wordwrap($row['label'],15,"<br>\n") : '' ?>
                                    </th>
                                <?php } } ?>

                                     <th <?php
                        if (isset($sortfield) && $sortfield == 'appointment') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('appointment', '<?php echo $sorttypepass; ?>')" style="min-width: 110px">  Appointment</th>
                                 <?php }?>
                        

                         
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>"> 
                </tr>
                </thead>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        <tbody>
                            <tr>
                            <?php
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                if(!empty($row['displayonlist']))
                                {
                                ?>
                                <td> 
                                       <?php
                                                if ($row['type'] == 'select') {
                                                    if (!empty($data[$row['name']])) {?>
                                                    <?=(!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')?nl2br(html_entity_decode($data[$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                        
                                                <?php }} else if($row['type'] == 'date'){
                                                    ?>
                                                    <?php 
                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')){ ?>
                                            
                                            <?=(!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')? configDateTime($data[$row['name']]):(isset($row['value'])?configDateTime($row['value']):'')?>
                                    <?php }
                                    ?>

                                                <?php }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){?>
                                                <?php 
                                                    if((!empty($data[$row['name']]))){
                                                         echo timeformat($data[$row['name']]);
                                                    } ?>

                                                <?php }else{ ?>

                        <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen (strip_tags($data[$row['name']])) > 25) ? wordwrap(substr (strip_tags($data[$row['name']]), 0, 25),20,"<br>\n")
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore_medical_communication/'.$data['communication_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?>

                                                <?php }?>
                                        
                                    </td>
                                <?php } } ?>
                                <td>
                                    <?php if(isset($data['appointment']) && !empty($data['appointment'])){ ?>
                                        <span class="label label-success">Yes</span>
                                    <?php } else {?>
                                        <span class="label label-danger">No</span>
                                    <?php } ?>

                                </td>
                            <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($form_data)?count($form_data)+1:'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
  <div class="clearfix"></div>
  <div class="col-xs-12 m-t-10" id="common_tb">
            <?php if (isset($pagination) && !empty($pagination)) { ?>
               
                    <?php echo $pagination; ?>
               
            <?php } ?>
        </div>
    </div>
</div>
