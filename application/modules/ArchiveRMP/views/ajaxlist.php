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
            <h2>ARCHIVED Risk Management Plan</h2>
            <div class="refresh-btn">
                                            
                                       </div>
									   <div class="row" id="searchForm">
                          
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                             <div class="form-group">
                            <div class="input-group input-append date " id="datepicker_search">
                            <input class="form-control" name="search_date" id="search_date" placeholder="" value="<?=!empty($search_date)?$search_date:''?>" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>


                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                          <div class="form-group">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input class="red form-control  addtime" title="" name="search_start_time" id="search_start_time" readonly="" placeholder="" value="<?=!empty($start_time_data)?$start_time_data:''?>" data-parsley-errors-container="#errors-containertime" type="text">
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>



                            </div>
                          </div>
                        </div>
						
						<div class="col-lg-3 col-md-3 col-sm-6 text-left">
                          <div class="form-group">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input class="red form-control  addtime" title="" name="search_end_time" id="search_end_time" readonly="" placeholder="" value="<?=!empty($end_time_data)?$end_time_data:''?>" data-parsley-errors-container="#errors-containertime" type="text">
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>



                            </div>
                          </div>
                        </div>
						<input type="hidden" name="search" id="search" value="" />
						<input type="hidden" name="professional_name" id="professional_name_value" value="" />
						<input type="hidden" name="search_date" id="search_date_value" value="" />
						<input type="hidden" name="search_start_time" id="search_start_time_value" value="" />
						<input type="hidden" name="search_end_time" id="search_end_time_value" value="" />
                        <div class="col-lg-3 col-md-6 col-sm-6 text-right">
                            <div class="form-inline">
                              <div class="form-group">
                                <div class="input-group search">
                                    <div class="input-group-btn">
									
                                        <button onclick="data_search_rmp('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                                    </button>
									<?php /* 
									<a href="javascript:;" class="btn btn-default export "><?=$this->lang->line('common_reset_title')?><i class="fa fa-file-excel-o fa-x"></i></a>
									*/ ?>
                                    <button class="btn btn-default" onclick="reset_data_rmp()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                </button>
								
                            </div>
                        </div>
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

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'created_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('modified_time', '<?php echo $sorttypepass; ?>')"> Created Date</th>
						<th <?php
                        if (isset($sortfield) && $sortfield == 'modified_time') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('modified_time', '<?php echo $sorttypepass; ?>')"> Time</th>

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
                                    }else{ ?>    
                                <td> <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen (strip_tags($data[$row['name']])) > 50) ? substr (nl2br(strip_tags($data[$row['name']])), 0, 50)
                                    .'...<a data-href="'.base_url('RiskManagementPlan'.'/readmore/'.$data['rmp_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?></td>

                            <?php } } }}?>
                                <td><?php echo $data['name']; ?></td>
                                <td><?php echo $data['care_home_name']; ?></td>
                                <td><?php echo configDateTimeFormat($data['created_date']) ?></td>
								<td><?php echo timeformat($data['modified_time']); ?></td>
                                <td class="text-center">
                                    <?php if($past_care_id == 0){ ?>
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['rmp_id'].'/'.$data['yp_id']);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                <?php if(checkPermission('ArchiveRMP','hidden_archive')){ ?>
                                <a href="<?php echo base_url($crnt_view.'/undoArchive/'.$data['rmp_id'].'/'.$data['yp_id']);?>" class="btn btn-link btn-blue"><i class="fa fa fa-mail-reply" aria-hidden="true"></i></a><?php } ?>

                                <?php }else{ ?>
                                    <a href="<?php echo base_url($crnt_view.'/view/'.$data['rmp_id'].'/'.$data['yp_id'].'/'.$care_home_id.'/'.$past_care_id);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                <?php } ?>
                            </td>
                            



                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?=!empty($form_data)?count($form_data)+2:'10'?>"  class="text-center"><?= lang('common_no_record_found') ?></td>

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