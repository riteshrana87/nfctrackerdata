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
                        if (!empty($form_data)) {
                            foreach ($form_data as $row) {
                                if (!empty($row['displayonlist'])) {
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
                                    ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('<?= $row['name'] ?>', '<?php echo $sorttypepass; ?>')" style="min-width: 110px"><?= !empty($row['label']) ? wordwrap($row['label'],15,"<br>\n") : '' ?></th>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <th style="min-width: 90px"><?php echo wordwrap('Quantity Remaining',15,"<br>\n") ?></th>
                            <th style="min-width: 100px">Action</th>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $ypid . '/' . $uri_segment : $ypid . '/0' ?>"> 
                </tr>
                </thead>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        <tbody>
                            <tr>
                                <?php
                                if (!empty($form_data)) {
                                    foreach ($form_data as $row) {
                                        if (!empty($row['displayonlist'])) {
                                            ?>
                                            <td> 
                                                <?php
                                                if ($row['type'] == 'select') {
                                                    if (!empty($data[$row['name']])) {
                                                        if(!empty($row['description']) && $row['description'] == 'get_user'){
                                                        
                                                        $get_data = $this->common_model->get_single_user($data[$row['name']]);
                                                        echo!empty($get_data[0]['username']) ? wordwrap($get_data[0]['username'],15,"<br>\n") : '';

                                                    }else if(!empty($row['description']) && $row['description'] == 'get_medication'){
                                     
              $get_medication_data = $this->common_model->get_single_medication($data[$row['name']]);
              echo!empty($get_medication_data[0]['medication_name']) ? wordwrap($get_medication_data[0]['medication_name'],15,"<br>\n") : ''; 

                        } ?>

                                 <?php }} else if($row['type'] == 'date'){?>
                                    <?php 
                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')){
                                            echo configDateTime($data[$row['name']]);
                                    }
                                    ?>

                                <?php }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){ ?>
                                                     <?php 
                                                    if((!empty($data[$row['name']]))){
                                                            echo timeformat($data[$row['name']]);
                                                    }
                                    ?>
                                <?php }else{ ?>
                                <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen (strip_tags($data[$row['name']])) > 25) ?  wordwrap(substr (nl2br(strip_tags($data[$row['name']])), 0, 25),15,"<br>\n")
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore_administer_medication/'.$data['administer_medication_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?>

                                <?php } ?>
                                            </td>

                                            <?php

                                        }
                                    }
                                }
                                ?>
                            
                                            <td>
                                            <?php 
                                                if(isset($data['available_stock'])){
                                                    echo $data['available_stock'];
                                                 } ?>
                                            </td>
                                             <td class="text-center">
                                             <?php 
                                        if(checkPermission('Medical','edit')){ $redirect_flag = !empty($redirect_flag)?1:0; ?>
                                                    <a href="<?php echo base_url($crnt_view.'/edit_administer_medication/'.$data['administer_medication_id'].'/'.$data['yp_id'].'/'.$redirect_flag);?>" class="btn btn-link btn-blue">
                                                        <i aria-hidden="true" class="fa fa-pencil-square-o"></i>
                                                    </a>    
                                                    <?php } ?>
                                                <?php if(checkPermission('Medical','delete')){ ?>
                                                    <a onclick="administer_medication_deletepopup('<?php echo $data['administer_medication_id'] ?>','<?php echo $data['select_medication'] ?>','<?=$ypid?>','<?=!empty($redirect_flag)?1:0?>')" href="javascript:void(0);"class="btn btn-link btn-blue"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                    <?php } ?>
                                            </td>


                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?= !empty($mp_form_data) ? count($mp_form_data)+1 : '11' ?>" class="text-center"><?= lang('common_no_record_found') ?></td>

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
