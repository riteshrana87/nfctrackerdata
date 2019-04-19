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
            <h2>Documents</h2>
            <div class="refresh-btn">
                <div class="form-inline">
                    <div class="input-group search">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/'.'0'?>">
                        <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="Search for description" value="<?=!empty($searchtext)?$searchtext:''?>">
                          <div class="input-group-btn">
                            <button onclick="data_search('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                            </button>
                            <button class="btn btn-default" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                            </button>
                          </div>
                    </div>
                </div>
            </div>
                       
                        <div class="clearfix"></div>
            <div class="table-responsive m-t-10">
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
                                <th style="width:18%; word-break: break-all;" <?php
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
                                <?php }} }?>
                            <th style="width:10%;" <?php
                        if (isset($sortfield) && $sortfield == 'created_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"> Created Date</th>


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
                        if (isset($sortfield) && $sortfield == 'care_home_id') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_id', '<?php echo $sorttypepass; ?>')"> CareHome</th>
                            <th class="text-center"> View </th>
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
                        </tr>
                    </thead>
                     <tbody>
                <?php if (isset($edit_data) && count($edit_data) > 0) { ?>
                    <?php foreach ($edit_data as $data) { ?>
                        
                            <tr class="word-break-class">
                            <?php
                        if(!empty($form_data))
                        {
                            foreach ($form_data as $row) {
                                if(!empty($row['displayonlist']))
                                { 
                                ?>
                                <td> 
                                    <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen (strip_tags($data[$row['name']])) > 50) ? substr (nl2br(strip_tags($data[$row['name']])), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['docs_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?>

                                </td>
                                
                                <?php }} }?>
                                <td><?=(!empty($data['created_date']) && $data['created_date'] !='0000-00-00')?configDateTime($data['created_date']):'';?></td>
                                <td><?=(!empty($data['name']))?$data['name']:'';?></td>
                                <td><?=(!empty($data['care_home_name']))?$data['care_home_name']:'';?></td>
                                
                                <td class="text-center">
                                <?php 
                                /*ghelani nikunj
                                10/10/2018
                                if in care to care archive then no need to show button
                                */
                                if($is_archive_page==0){?>
                                    <?php if(checkPermission('Documents','view')){ ?>
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['docs_id'].'/'.$ypid);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                <?php } } else {?>
                                
                                <a href="<?php echo base_url($crnt_view.'/view/'.$data['docs_id'].'/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                
                                <?php }?>
                                <?php 
                                /*ghelani nikunj
                                10/10/2018
                                if in care to care archive then no need to show button
                                */
                                if($is_archive_page==0){?>
                                    <?php if(checkPermission('Documents','delete')){ ?>
                                        <a href="javascript:;" onclick="deleteDocsData(<?php echo $ypid ; ?>,<?php echo $data['docs_id'];?>);" class="btn btn-link btn-blue" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        <?php } ?>
                                <?php } ?>
                                </td>
                                

                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($form_data)?count($form_data)+4:'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
                    

                </table>
            </div>
          <div class="clearfix"></div>
          <div class="text-right">
                <div class="" id="common_tb">
                    <?php if (isset($pagination) && !empty($pagination)) { ?>
                        <div class="row">
                        <div class="col-sm-12">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>