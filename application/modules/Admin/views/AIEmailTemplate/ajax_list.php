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
            <th <?php if (isset($sortfield) && $sortfield == 'subject') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('subject', '<?php echo $sorttypepass; ?>')">Email Subject</th>

                <th <?php if (isset($sortfield) && $sortfield == 'firstname') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('firstname', '<?php echo $sorttypepass; ?>')">First Name</th>
                
                <th <?php if (isset($sortfield) && $sortfield == 'lastname') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('lastname', '<?php echo $sorttypepass; ?>')">Last Name</th>
                
                
                <th <?php if (isset($sortfield) && $sortfield == 'status') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')">Status</th>
                <th class="sorting_disabled" data-filterable="true" customer="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="5%">Action</th>
                 
            </tr>
        </thead>
        <tbody>
            <tr>   
<?php
if (!empty($information)) {
    foreach ($information as $row) {

        if ($row['status'] == '1') {
            $row['status'] = 'active';
        } else {
            $row['status'] = 'inactive';
        }
        $name = !empty($row['firstname']) ? $row['firstname'] : '';
        $name = str_replace("'", "\'", $name);
        ?>

                    <tr id="customer_ids">
                        <td><?= !empty($row['subject']) ? $row['subject'] : '' ?></td>
                        <td><?= !empty($row['name']) ? $row['firstname'] : '' ?></td>
                        <td><?= !empty($row['lastname']) ? $row['lastname'] : '' ?></td>
                        <td><?= !empty($row['status']) ? $row['status'] : '' ?></td>
                        
                        <td class="">
         <a class="btn btn-xs btn-primary" href="<?php echo base_url($crnt_view.'/view/'.$row['template_id']);?>" title="View Record"><i class="fa fa fa-file-text-o"></i></a>

         <a class="btn btn-xs btn-primary" href="<?= $this->config->item('admin_base_url') . $this->viewname; ?>/edit/<?= $row['template_id'] ?>" title="Edit Record"><i class="fa fa-pencil"></i></a>
    <button class="btn btn-xs btn-danger" title="Delete Record"  onclick="delete_request(<?php echo $row['template_id'] ?>);"> <i class="fa fa-times"></i> </button>
                            

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
<script>
    var customerDeleteurl = "<?php echo base_url() . 'admin/customers/customerBulkDelete/'; ?>";
</script>

