<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
         <?php
         if (isset($sortby) && $sortby == 'asc') {
            $sorttypepass = 'desc';
        } else {
            $sorttypepass = 'asc';
        }
            if(!empty($mp_form_data))
            {
                foreach ($mp_form_data as $row) {
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
                    ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="min-width: 110px" onclick="apply_sorting('<?=$row['name']?>', '<?php echo $sorttypepass; ?>')">  
                        <?= !empty($row['label']) ? wordwrap($row['label'],15,"<br>\n") : '' ?>
                    </th>
                                <?php } } }?>
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
                        ?> rowspan="1" colspan="1" style="min-width: 110px" onclick="apply_sorting('care_home_id', '<?php echo $sorttypepass; ?>')">CareHome</th>
            <?php if(checkPermission('Medical','add')){ ?>
            <th style="min-width: 110px" class="text-center">Appointments</th>
            <?php } ?>
            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  
        </tr>
    </thead>
    <tbody>
        <?php if (isset($mp_details) && count($mp_details) > 0) { ?>
                <?php foreach ($mp_details as $data) { ?>
                        <tr>
                        <?php
                    if(!empty($mp_form_data))
                    {
                        foreach ($mp_form_data as $row) { 
                            if(!empty($row['displayonlist'])){ ?>
                            <td>
                             <?= (!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00') ? ((strlen (strip_tags($data[$row['name']])) > 25) ? wordwrap(substr (nl2br(strip_tags($data[$row['name']])), 0, 25),15,"<br>\n")
                                    .'...<a data-href="'.base_url($crnt_view.'/readmore_medical_professionals/'.$data['mp_id'].'/'.$row['name']).'" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($data[$row['name']]))) :(isset($row['value'])?$row['value']:'') ?>
                            </td>
                            <?php }else if($row['type'] == 'date'){?>
                            <td>
                                <?php 
                                    if((!empty($data[$row['name']]) && $data[$row['name']] !='0000-00-00')){
                                       echo configDateTime($data[$row['name']]);
                                    }
                                    ?>
                            </td>
                            <?php }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){ ?>
                                <td>
                                    <?php if((!empty($data[$row['name']]))){
                                        echo timeformat($data[$row['name']]);
                                    } ?>
                                </td>
                                <?php }?>
                                
                                <?php   } }?>
						<td class="text-center">
                                <?php echo $data['care_home_name']?>
                            </td>
                            <?php if(checkPermission('Medical','add')){ ?>
                            <td class="text-center">
                                <a href="<?php echo base_url($crnt_view.'/add_appointment/'.$data['mp_id'].'/'.$ypid);?>">
                                <button type="button" class="btn btn-info btn-xs">
                                    <i class="fa fa-plus-circle"></i> Add &nbsp;
                                </button>
                                </a>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                       <td colspan="<?=!empty($mp_form_data)?count($mp_form_data)+1:'10'?>" class="text-center"><?= lang('common_no_record_found') ?></td>

                    </tr>
                <?php } ?>
    </tbody>
</table>
</div>
<div class="clearfix"></div>
<div class="col-xs-12" id="common_tb">
            <?php if (isset($pagination) && !empty($pagination)) { ?>
               
                    <?php echo $pagination; ?>
               
            <?php } ?>
        </div>
