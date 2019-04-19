<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="row" id="table-view">
    <div class="col-sm-12 m-t-10">    
        <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th <?php
                        if (isset($sortfield) && $sortfield == 'sanction_reference_number') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('sanction_reference_number', '<?php echo $sorttypepass; ?>')"> Sanction  Number</th>

                        <th <?php
                        if (isset($sortfield) && $sortfield == 'date_sanction_issued') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('date_sanction_issued', '<?php echo $sorttypepass; ?>')">Date Sanction Started</th>
                        

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
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('name', '<?php echo $sorttypepass; ?>')">Issued by</th>
                        <th <?php
                        if (isset($sortfield) && $sortfield == 'reason_for_sanction') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('reason_for_sanction', '<?php echo $sorttypepass; ?>')">Reason for Sanction</th>

                       <th <?php
                        if (isset($sortfield) && $sortfield == 'reference_number') {
                            if ($sortby == 'asc') {
                                echo "class = 'sort-dsc'";
                            } else {
                                echo "class = 'sort-asc'";
                            }
                        } else {
                            echo "class = 'sort'";
                        }
                        ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('reference_number', '<?php echo $sorttypepass; ?>')">Linked to incident number</th>
                        

                            <th class="text-center">Action</th> 
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                            <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $ypId . '/' . $uri_segment : $ypId . '/0' ?>">  
                        </tr>
                    </thead>
                    <tbody>                     
                    <?php if (isset($information) && count($information) > 0) { ?>
                        <?php foreach ($information as $data) { ?>

                            <tr>
                                <td> 
                                    <?= !empty($data['sanction_reference_number']) ? $data['sanction_reference_number'] : lang('NA') ?>
                                </td>
                                <td> 
                                    
                                    <?= !empty($data['date_sanction_issued']) ? configDateTime($data['date_sanction_issued']) : lang('NA') ?>
                                </td>
                                <td>
                                <?= !empty($data['name']) ? $data['name'] : lang('NA') ?> 
                                </td>
                                <td>
                                  <?= (!empty($data['reason_for_sanction']) && $data['reason_for_sanction'] !='0000-00-00') ? ((strlen (strip_tags($data['reason_for_sanction'])) > 50) ? substr (nl2br(strip_tags($data['reason_for_sanction'])), 0, 50).'...<a data-href="'.base_url($crnt_view.'/readmore/'.$data['sanctions_id'].'/reason_for_sanction').'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data['reason_for_sanction']))) :lang('NA') ?>
                                </td>
                                
                                <td>
                                    <?= !empty($data['reference_number']) ? $data['reference_number'] : lang('not_link') ?>    
                                </td>

                                <?php 
                                    $str = $data['process_id'];
                                    $process_id = (int) filter_var($str, FILTER_SANITIZE_NUMBER_INT);
                                    if($process_id == 3){
                                        $process_id = 2;
                                    }
                                ?>
                                <td class="text-center aai_action_btn">
                                     <a href="<?php echo base_url('Sanctions/sanctionsView/'.$data['yp_id'].'/'.$data['sanctions_id']);?>" class="btn btn-link"><i class="fa fa-file-text-o" title="View" aria-hidden="true"></i></a>
                                    
                                   <a href="<?php echo base_url('Sanctions/edit/'.$data['yp_id'].'/'.$data['sanctions_id']);?>" title="Edit" class="btn btn-link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                                   
                                </td>   

                            </tr>
                    <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9"  class="text-center"><?= lang('common_no_record_found') ?></td>
                         </tr>
    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
            <div class="" id="common_tb_concern">
                <?php if (isset($pagination) && !empty($pagination)) { ?>
                <div class="col-sm-12 padding-0">
                        <?php echo $pagination; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
