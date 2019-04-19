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
            <h2>ARCHIVED CARE PLAN TARGET</h2>
            <div class="refresh-btn">
                                            <button class="btn btn-default btn-sm" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                        </button>
                                       </div>
                       
                     <div class="clearfix"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped m-t-10">
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')">Care Home Name</th>

                            <th class="text-center">Action</th>
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                                <?php if($past_care_id == 0){ ?>
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
                                     if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                ?>
                                <td>
                                    <?php if((!empty($data[$row['name']]))){
                                             echo timeformat($data[$row['name']]);
                                        } ?>
                                </td>
                            <?php }else if(!empty($row['displayonlist'])){ ?>
                                <td> 
                                    <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen (strip_tags($data[$row['name']])) > 50) ? substr (nl2br(strip_tags($data[$row['name']])), 0, 50)
                                    .'...<a data-href="'.base_url('CarePlanTarget/readmore/'.$data['cpt_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : (($row['type'] == 'date' && !empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? configDateTime($data[$row['name']]) : nl2br(html_entity_decode($data[$row['name']])))) :(isset($row['value'])?$row['value']:'') ?>
                                </td>
                            <?php } }} }?>
                                <td><?php echo $data['name']; ?></td>
                                <td><?php echo $data['care_home_name']; ?></td>
                                <td class="text-center">
                                    <?php if($past_care_id == 0){?>
                                        <a href="<?php echo base_url($crnt_view.'/view/'.$data['cpt_id'].'/'.$data['yp_id']);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                <?php if(checkPermission('ArchiveCpt','hidden_archive')){ ?>
                                <a href="<?php echo base_url($crnt_view.'/undoArchive/'.$data['cpt_id'].'/'.$data['yp_id']);?>" class="btn btn-link btn-blue"><i class="fa fa fa-mail-reply" aria-hidden="true"></i></a>
                                <?php } ?>
                                    <?php }else{ ?>
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['cpt_id'].'/'.$data['yp_id'].'/'.$care_home_id.'/'.$past_care_id);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                 </td>   
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($form_data)?count($form_data)+3:'10'?>"  class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
          <div class="clearfix"></div>
          <div class="text-left">
                <div class="" id="common_tb">
                    <?php if (isset($pagination) && !empty($pagination)) { ?>
                        <div class="col-sm-12 row">
                            <?php echo $pagination; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>