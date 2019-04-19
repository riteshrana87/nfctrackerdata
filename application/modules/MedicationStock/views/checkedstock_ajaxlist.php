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
                     <th <?php
                        if (isset($sortfield) && $sortfield == 'medication_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('medication_name', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">
                        <?php echo wordwrap('Medication Name',15,"<br>\n");?>
                    </th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'medication_type') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('medication_type', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">
                        <?php echo wordwrap('Medication Type',15,"<br>\n");?>
                    </th>

                          
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
                        ?> onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">
                    <?php echo wordwrap('Prescribed to or Purchased for',15,"<br>\n");?>
                </th>
                    <th <?php
                        if (isset($sortfield) && $sortfield == 'stock_checked_name') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('stock_checked_name', '<?php echo $sorttypepass; ?>')" style="min-width: 160px">
                    <?php echo wordwrap('Stock Checked/Adjustment by',15,"<br>\n");?>
                </th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'stock_checked_date') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('stock_checked_date', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">
                    <?php echo wordwrap('Checked Date and Time',15,"<br>\n");?>
                </th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'comment') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> style="min-width: 130px" onclick="apply_sorting('comment', '<?php echo $sorttypepass; ?>')">Comment</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'quantity_type') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('quantity_type', '<?php echo $sorttypepass; ?>')" style="min-width: 150px">
                    <?php echo wordwrap('Stock Checked/Adjustment',15,"<br>\n");?>
                </th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'stock_adjust_value') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> onclick="apply_sorting('stock_adjust_value', '<?php echo $sorttypepass; ?>')" style="min-width: 130px">
                    <?php echo wordwrap('Stock Adjustment Quantity',15,"<br>\n");?>
                </th>
                        <th style="min-width: 120px"><?php echo wordwrap('Remaining Stock',15,"<br>\n");?></th>
                        
                         
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$medication_id.'/'.$care_home_id.'/'.$uri_segment:$medication_id.'/'.$care_home_id.'/0'?>"> 
                </tr>
                </thead>
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php foreach ($information as $data) { ?>
                        <tbody>
                            <tr>
                                <td><?=!empty($data['medication_name'])?$data['medication_name']:lang('NA'); ?> </td>
                                <td><?=!empty($data['medication_type'])?$data['medication_type']:lang('NA'); ?> </td>
                                <td><?=!empty($data['name'])?$data['name']:lang('NA'); ?></td>
                                <td>

                                    <?=!empty($data['stock_checked_name'])?$data['stock_checked_name']:lang('NA'); ?>
                                    
                                    </td>
                                <td>
                               <?=(!empty($data['stock_checked_date']) && $data['stock_checked_date'] !='0000-00-00 00:00:00')?configDateTimeFormat($data['stock_checked_date']):lang('NA'); ?>
                                </td>
                                <td>
                                   <?= (!empty($data['comment'])) ? ((strlen ($data['comment']) > 30) ? $substr = substr (trim(strip_tags($data['comment'])), 0, 30) . '...<a data-href="'.base_url('YoungPerson'.'/readmore_comment/'.$data['stock_check_id']).'/comment" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data['comment']))):lang('NA') ?>
                                </td>
                                <td><?php if(!empty($data['quantity_type'])  && $data['quantity_type'] == 3){echo 'Stock Checked';}
                                else if(!empty($data['quantity_type'])  && $data['quantity_type'] == 2){echo 'Stock Decreased';}
                                else if(!empty($data['quantity_type'])  && $data['quantity_type'] == 1){echo 'Stock Increased';} ?>
                                </td>
                                <td><?=!empty($data['stock_adjust_value'])?$data['stock_adjust_value']:''; ?></td>
                                <td><?=!empty($data['remaing_stock'])?number_format($data['remaing_stock'],2):0; ?></td>
                                
                               
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="10" class="text-center"><?= lang('common_no_record_found') ?></td>

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
