<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>

<div class="col-sm-12">
    <div class="panel panel-default tile tile-profile">
        <div class="panel-body"> 
            <h2>Care Plan Target</h2>
            <div class="refresh-btn">
                    <button class="btn btn-default btn-sm" onclick="reset_data_ks()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                    </button>
            </div>
            <div class="table-responsive m-t-10">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="for-sor_caret">
                           
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
                                    ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('<?=$row['name']?>', '<?php echo $sorttypepass; ?>')">  <?=!empty($row['label'])?$row['label']:''?></th>
                            <?php } } }?>
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')"> Created By</th>
                         
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')"> Care Home Name</th>
                        <th>Comment Details</th>  
                            <th class="text-center">Action</th>
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                                <?php
                                /* condition added by Ritesh Ranan on 28/09/2018 to archive functionality */
                                 if($past_care_id == 0){?> 
                                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
                                <?php }else{ ?>
                                 <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$care_home_id.'/'.$past_care_id.'/'.$uri_segment:$ypid.'/'.$care_home_id.'/'.$past_care_id.'/0'?>">     
                                <?php } ?>
                        </tr>
                    </thead>
                        <tbody>
                <?php if (isset($edit_data) && count($edit_data) > 0) { ?>
                    <?php foreach ($edit_data as $data) { ?>
                        
                            <tr>
                            <?php
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                if(!empty($row['displayonlist'])){
                                    if($row['type'] == 'date'){
                                ?>

                                <td> 
                                    <?php 
                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')){
                                        echo configDateTime($data[$row['name']]);
                                    }
                                    ?>
                                </td>

                                <?php }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                                    ?>
                                                    <td> 
                                     <?php 
                                                    if((!empty($data[$row['name']]))){
                                                        echo timeformat($data[$row['name']]);
                                                    }
                                    ?>
                                </td>
                                                    
                                                   

                                                <?php }else{ ?>    
                                <td> <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen (strip_tags($data[$row['name']])) > 50) ? substr (nl2br(strip_tags($data[$row['name']])), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['cpt_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?></td>

                            <?php } } }}?>
                                <td><?php echo $data['name']; ?></td>
                                <td><?php echo $data['care_home_name']; ?></td>
                                <td><?php 
				/* changes done by Dhara Bhalala for conclude option and get other details */
                                $ks_title1 = $ks_title2 = $ks_title3 = '';
                                if($data['total_comments']>0){
                                    $ks_title1 = ($data['total_comments'] == 1)? '<b>1</b> Comment' : '<b>'.$data['total_comments'].'</b> Comments';                                        
                                    $ks_title2 = ' Last comment added by <b>' .$data['last_comment_added_by'].'</b>';
                                    $ks_title3 = ' on <b>'.configDateTimeFormat($data['last_comment_added_on']).'</b>';
                                    echo $ks_title1;
                                    echo '<br/>';
                                    echo $ks_title2;
                                    echo '<br/>';
                                    echo $ks_title3;
                                }  ?></td>
                                <td class="text-center">
                                    <?php if($past_care_id == 0){

                                  if(empty($data['draft']) && $data['draft'] == '0'){
                                   if(checkPermission('CarePlanTarget','view')){ ?>        
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['cpt_id'].'/'.$data['yp_id']);?>" class="btn btn-link"><i class="fa fa-file-text-o" title="View" aria-hidden="true"></i></a>
                               <?php }
                                   
				    
                               if($data['is_concluded'] == 0){
                                    $link1 = 'display:""';
                                    $link2 = 'display:none';
                                }else{
                                    $link1 = 'display:none';
                                    $link2 = 'display:""';
                                }
                               ?>

                                <a style="<?= $link2 ?>" id="concluded_option_<?= $data['cpt_id'] ?>" title="Concluded" href="javascript:;" class="btn btn-link"><i style="color:#70a51a" class="fa fa-check-circle" aria-hidden="true"></i></a>
                                   <?php  } ?>     

                            <?php if(!empty($data['draft']) && $data['draft'] == '1'){ ?>
                            <?php if(checkPermission('CarePlanTarget','delete')){ ?>
                                <a href="<?php echo base_url($crnt_view.'/edit_draft/'.$data['cpt_id'].'/'.$data['yp_id']);?>" title="Draft" class="btn btn-link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                               <?php }?>     

                                <?php if(checkPermission('CarePlanTarget','delete')){ ?>
                                    <a onclick="delete_ks('<?php echo $data['cpt_id'] ?>','<?php echo $data['yp_id'] ?>');" href="javascript:;" class="btn btn-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                                    <?php } ?>
                                    <?php } ?>                                    
                                <?php }else{     
                                
                                if(checkPermission('CarePlanTarget','view')){ ?>        
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['cpt_id'].'/'.$data['yp_id'].'/'.$care_home_id.'/'.$past_care_id);?>" class="btn btn-link"><i class="fa fa-file-text-o" title="View" aria-hidden="true"></i></a>
                             <?php } ?>

                                <?php if(!empty($data['draft']) && $data['draft'] == '1'){ ?>
                                   <a href="javascript:void(0);" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="it's a Draft"><i class="fa fa-info-circle"></i></i></a>
                                <?php }} ?>

                                </td>

                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($form_data)?count($form_data)+4:'10'?>"  class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
          <div class="clearfix"></div>
                <div class="" id="common_tb_keysession">
                    <?php if (isset($pagination) && !empty($pagination)) { ?>
                        <div class="row col-sm-12">
                            <?php echo $pagination; ?>
                        </div>
                    <?php } ?>
                </div>
         
        </div>
    </div>
</div>