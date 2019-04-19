<?php
/*
  @Description:Customers list
  @Author: Mehul patel
  @Date: 12-5-2017
 */
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
                
                
                <th <?php if (isset($sortfield) && $sortfield == 'email') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('email', '<?php echo $sorttypepass; ?>')">Email</th>
                <th <?php if (isset($sortfield) && $sortfield == 'mobile_number') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('mobile_number', '<?php echo $sorttypepass; ?>')">Phone Number</th>
                
                <th <?php if (isset($sortfield) && $sortfield == 'status') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')">Status</th>
                <th class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" customer="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="5%">Action</th>
                 
            </tr>
        </thead>
        <tbody>
            <tr>   
<?php
if (!empty($information)) {
    foreach ($information as $row) {

        if ($row['status'] == 'active') {
            $row['status'] = 'active';
        } else {
            $row['status'] = 'inactive';
        }
        $name = !empty($row['firstname']) ? $row['firstname'] : '';
        $name = str_replace("'", "\'", $name);
        ?>

                    <tr id="customer_ids">
                        <td><?= !empty($row['firstname']) ? $row['firstname'] : '' ?></td>
                        <td><?= !empty($row['lastname']) ? $row['lastname'] : '' ?></td>
                        <td><?= !empty($row['email']) ? $row['email'] : '' ?></td>
                        <td><?= !empty($row['mobile_number']) ? $row['mobile_number'] : '' ?></td>
                        <td><?= !empty($row['status']) ? $row['status'] : '' ?></td>
                        
                        <td class="hidden-xs hidden-sm">
         <a class="btn btn-xs btn-primary" href="<?php echo base_url($crnt_view.'/view/'.$row['login_id']);?>" title="View Record"><i class="fa fa fa-file-text-o"></i></a>
<!--                            <button class="btn btn-xs btn-danger" title="Delete Record"  onclick="delete_request(<?php echo $row['login_id'] ?>);"> <i class="fa fa-times"></i> </button>-->
                            
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

