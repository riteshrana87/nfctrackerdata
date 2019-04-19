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
            <h2>Clothing Money
            
                <label>- Balance : <?=!empty($total_balance)?$total_balance:'0'?></label>
            
            </h2>
            <div class="refresh-btn">
                                            <button class="btn btn-default btn-sm" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                        </button>
                                         <?php if (isset($edit_data) && count($edit_data) > 0) { ?>
                                         <a href="<?=base_url('ClothingMoney/generateExcelFile/'.$ypid); ?>" class="btn btn-default btn-sm">Export to Excel</a>
                                         <a href="<?=base_url('ClothingMoney/DownloadPrint/'.$ypid.'/print'); ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Print </a>
                                         <?php } ?>
                                       </div>
                       
            <div class="table-responsive m-t-10">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            
                            <?php
                        if(!empty($form_data))
                        { $sortfield =($sortfield =='CAST(`money_in` AS decimal)')?'money_in':($sortfield =='CAST(`money_out` AS decimal)'?'money_out':$sortfield);
                            ?>
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
                            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"> Date/Time</th>
                            <?php
                            foreach ($form_data as $row) {
                                if(!empty($row['displayonlist']))
                                {
                                ?>
                                <th <?php
                                if( $row['name'] !='staff') {
                                    if (isset($sortfield) && $sortfield == $row['name']) {
                                        if ($sortby == 'asc') {
                                            echo "class = 'sort-dsc'";
                                        } else {
                                            echo "class = 'sort-asc'";
                                        }
                                    } else {
                                        echo "class = 'sort'";
                                    }
                                    ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('<?=($row['name'] == 'money_in' || $row['name'] == 'money_out')?'CAST(`'.$row['name'].'` AS decimal)':$row['name']?>', '<?php echo $sorttypepass; ?>')" <?php } ?> >  <?=!empty($row['label'])?$row['label']:''?></th>
                            <?php } } }?>
                            <?php /* <th <?php
                            if (isset($sortfield) && $sortfield == 'balance') {
                                if ($sortby == 'asc') {
                                    echo "class = 'sort-dsc'";
                                } else {
                                    echo "class = 'sort-asc'";
                                }
                            } else {
                                echo "class = 'sort'";
                            }
                            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('balance', '<?php echo $sorttypepass; ?>')"> Balance</th> */?>
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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')"> CareHome</th>
						
                           
                            <?php /* <th class="text-center">Action</th> */ ?>
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
                        </tr>
                    </thead>
                        <tbody>
                                <?php if (isset($edit_data) && count($edit_data) > 0) { ?>
                                    <?php foreach ($edit_data as $data) { //pr($data);
									
									?>
                                        
                                            <tr>
                                            <?php
                                        if(!empty($form_data))
                                        {
                                            ?>
                                            <td><?=!empty($data['created_date'])?configDateTimeFormat($data['created_date']):''?></td>
                                            <?php
                                            foreach ($form_data as $row) {  //pr($row);
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

                                                <?php }else if (isset($row['subtype']) && $row['subtype'] == 'time') { ?>
                                                    <td> 
                                                    <?php 
                                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='00:00:00')){
                                                              echo timeformat($data[$row['name']]);
                                                    }
                                                    ?>
                                                </td>

                                                <?php }else if ($row['type'] == 'select') { ?>
                                                <td> 
                                                <?php
                                                                    if (!empty($data[$row['name']])) {
                                                                        if(!empty($row['description']) && $row['description'] == 'get_user'){
                                                                        
                                                                        $get_data = $this->common_model->get_single_user($data[$row['name']]);
                                                                        echo!empty($get_data[0]['username']) ? $get_data[0]['username'] : '';
                                                                    }else if(!empty($row['description']) && $row['description'] == 'get_medication'){
                                                     
                              $get_medication_data = $this->common_model->get_single_medication($data[$row['name']]);
                              ?>
                              
                              <?php
                              echo!empty($get_medication_data[0]['medication_name']) ? $get_medication_data[0]['medication_name'] : ''; ?>
                              </td>
                              <?php
                                        } ?>

                                                <?php }}else{ ?>    
                                <td> <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen (strip_tags($data[$row['name']])) > 50) ? substr (nl2br(strip_tags($data[$row['name']])), 0, 50)
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['clothing_money_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?></td>

                            <?php } } }}?>
                                <?php /* <td><?php echo $data['balance']; ?></td> */?>
                                <td><?php echo $data['name']; ?></td>
								
                                <td><?php echo $data['care_home_name']; ?></td>
								
                                <?php /* <td class="text-center">
                                  
                           
                                <?php if(checkPermission('ClothingMoney','delete')){ ?>
                                    <a onclick="delete_ks('<?php echo $data['clothing_money_id'] ?>','<?php echo $data['yp_id'] ?>');" href="javascript:;"class="btn btn-link btn-blue"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                                    <?php } ?>
                                    
                                </td> */ ?>
                            



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