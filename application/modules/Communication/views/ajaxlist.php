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
                                    ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('<?=$row['name']?>', '<?php echo $sorttypepass; ?>')" style="min-width: 130px"> 
                                     <?=!empty($row['label'])?wordwrap($row['label'],15,"<br>\n"):''?>    
                                     </th>
                            <?php } ?>
                        <?php } }?>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">Created Name
                    </th>

                    <th <?php
                        if (isset($sortfield) && $sortfield == 'care_home_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">Care Home Name
                    </th>

                         <th style="min-width: 100px;">View</th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <?php 
                /* condition added by Ritesh Ranan on 28/09/2018 to archive functionality */
                if($past_care_id == 0){ ?>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
                <?php }else{ ?>
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$care_home_id.'/'.$past_care_id.'/'.$uri_segment:$ypid.'/'.$care_home_id.'/'.$past_care_id.'/0'?>">     
                <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        
                            <tr>
                            <?php
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                if(!empty($row['displayonlist'])){
                                    if($row['type'] == 'date'){
                                        echo '<td>';
                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')){
                                        echo configDateTime($data[$row['name']]);
                                    }
                                        echo '</td>';
                                    }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                        echo '<td>';
                                        if((!empty($data[$row['name']]))){
                                            echo timeformat($data[$row['name']]);
                                        }
                                         echo '</td>';
                                    } else { ?>
                                                    
                                <td><?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen ($data[$row['name']]) > 100) ? $substr = wordwrap(substr (nl2br(strip_tags($data[$row['name']])), 0, 25),25,"<br>\n") . '...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['communication_log_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?></td>

                                <?php } } } } ?>
                                <td><?php echo $data['name']; ?></td>
                                <td><?php echo $data['care_home_name']; ?></td>
                                <td class="text-center">
                                    <?php 
                                    /* condition added by Ritesh Ranan on 28/09/2018 to archive functionality */
                                    if($past_care_id == 0){ ?>
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['communication_log_id']);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php }else{ ?> 
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['communication_log_id'].'/'.$care_home_id.'/'.$past_care_id);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($form_data)?count($form_data)+3:'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
  <div class="clearfix"></div>
  <div id="common_tb">
            <?php if (isset($pagination) && !empty($pagination)) { ?>
                <div class="col-sm-12">
                    <?php echo $pagination; ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
