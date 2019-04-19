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
                <?php foreach ($information['headerData'] as $indexval=>$passHeader) {  ?>
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
                <?php if(isset($information['fieldData'][0]) && $information['fieldData'][0] == 'docs.`input_single_file`'){ ?>
                <th> Download</th>
                
                <?php } ?>
                <th> Action</th>
            </tr>
        </thead>
        <tbody> 
                <?php if (!empty($information['informationData'])) { ?>
                    <?php foreach ($information['informationData'] as $rowData) { ?>
                        <tr>
                            <?php foreach ($rowData as $ind=>$row) { if($ind != 'docs_id' && $ind != 'id'){$ypid='';$docs_id='';?>
                                <td><?php if(($ind == 'date_of_birth') && $row !='0000-00-00'){ echo $rowData['date_of_birth'];}
                                        elseif(($ind == 'created_date') && $row !='0000-00-00'){ echo $rowData['created_date'];}
                                        else{echo (strlen($row) > 100) ? substr($row, 0, 100) . '...' : $row;}?></td>
                            <?php }else{if($ind == 'docs_id'){$docs_id=$row;}if($ind == 'id'){$ypid=$row;} } } ?> 
                            <?php if(isset($information['fieldData'][0]) && $information['fieldData'][0] == 'docs.`input_single_file`'){ ?>
                            <td> <?php if(!empty($rowData['input_single_file'])){ 
                                $file = explode(',', $rowData['input_single_file']);
                                foreach ($file as $file_name) {
                                    ?>
                                    <a href="<?php echo base_url('Admin/Reports/download_documents/'.$rowData['docs_id'].'/'.$file_name);?>" class="btn btn-link btn-blue">
                                    <i class="fa fa-download fa-1" aria-hidden="true"></i>
                                    </a>
                                    <?php
                                }
                                }?></td>
                                
                                  <?php } ?>
                                  <td><a href="<?php echo base_url($view_url.$docs_id.'/'.$ypid);?>" class="btn btn-link btn-blue"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></td>
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

