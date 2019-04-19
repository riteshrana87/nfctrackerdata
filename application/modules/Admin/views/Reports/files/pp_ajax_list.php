<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<?php if (!empty($information['informationData'])) { ?>
  <div class="text-right m-b-10">
    <input name="exportFile" id="exportFile" value="Export File" style="" class="btn btn-primary" type="button">
                        </div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped dataTable" id="example1" customer="grid" aria-describedby="example1_info">
        <thead>
            <tr>
               <?php foreach ($information['headerData'] as $indexval=>$passHeader) { ?>
                    <th <?php if (isset($sortfield) && $sortfield == trim($information['fieldData'][$indexval])) {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    } ?>  onclick="apply_report_sorting('<?php echo trim($information['fieldData'][$indexval]); ?>', '<?php echo $sorttypepass; ?>')"> <?= $passHeader; ?></th>
                <?php } ?>
                <th>Action</th>
            </tr>
        </thead>
        <tbody> 
                <?php if (!empty($information['informationData'])) { ?>
                    
                    <?php foreach ($information['informationData'] as $rowData) {$ypid='';?>
                        <tr>
                            <?php foreach ($rowData as $ind=>$row) {if($ind=='yp_id'){$ypid=$row;}else{; ?>
                                <td><?php /*if(($ind == 'date_of_birth') && $row !='0000-00-00'){ echo configDateTime($rowData['date_of_birth']);}
                                        elseif(($ind == 'created_date') && $row !='0000-00-00'){ echo configDateTime($rowData['created_date']);}
                                        elseif(($ind == 'date_received')){ echo (!empty($row) && $row !='0000-00-00')?configDateTime($rowData['date_received']):'';}
                                        else */if($ind == 'id'){?>
                                        <a href="<?php echo base_url($view_url.$row.'/'.$ypid);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                        <?php }
                                        else{echo (strlen($row) > 100) ? substr($row, 0, 100) . '...' : $row;}?>
                                   
                                </td>
                            <?php } }?> 
                       
                        </tr>
                    <?php } ?> 
                <?php } else { ?>
                    <tr class="text-center">
                        <td colspan="<?php echo count($information['headerData']); ?>">No Records Found</td>
                    </tr>
                <?php } ?>        
            </tbody>
    </table>
</div>
<div class="clearfix"></div>
<div id="common_tb">
    <?php
    if (isset($pagination)) {
        echo $pagination;
    }
    ?>
</div>

