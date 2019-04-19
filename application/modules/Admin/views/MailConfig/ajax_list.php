<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
$admin_session = $this->session->userdata('nfc_admin_session');
?>
<?php
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped dataTable" id="example1" customer="grid" aria-describedby="example1_info">
        <thead>
            
            <tr customer="row">
                <th <?php if (isset($sortfield) && $sortfield == 'yp_fname') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('yp_fname', '<?php echo $sorttypepass; ?>')">First Name</th>
                
                <th <?php if (isset($sortfield) && $sortfield == 'yp_lname') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('yp_lname', '<?php echo $sorttypepass; ?>')">Last Name</th>
                <th <?php if (isset($sortfield) && $sortfield == 'care_home_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('care_home_name', '<?php echo $sorttypepass; ?>')">Care Home Name</th>
                
                
                <th class="sorting_disabled" data-filterable="true" customer="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="5%">Action</th>
                 
            </tr>
        </thead>
        <tbody>
            <tr>   
<?php
if (!empty($information)) {
    foreach ($information as $row) {
        $name = !empty($row['yp_fname']) ? $row['yp_fname'] : '';
        $name = str_replace("'", "\'", $name);
        ?>

                    <tr id="customer_ids">                        
                        <td><?= !empty($row['yp_fname']) ? $row['yp_fname'] : '' ?></td>
                        <td><?= !empty($row['yp_lname']) ? $row['yp_lname'] : '' ?></td>
                        <td><?= !empty($row['care_home_name']) ? $row['care_home_name'] : '' ?></td>
                        
                        <td class="">

         <a class="btn btn-xs btn-primary" href="<?= base_url(ADMIN_SITE.'/'.$this->viewname .'/edit/'.$row['yp_id']) ?>" title="Edit Record"><i class="fa fa-pencil"></i></a>
                           
            </td>
                        
                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                            <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                    </tr>
    <?php } ?> 
<?php }else { ?>
                <tr>
                    <td colspan="5">No records found</td>
                </tr>
<?php } ?>
                </tr>
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