<?php
/*
  @Description: SDQ List
  @Author: Ishani Dave
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
$role_id = $this->config->item('super_admin_role_id');
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped dataTable" id="example1" customer="grid" aria-describedby="example1_info">
        <thead>

            <tr customer="row">
                <th <?php
                if (isset($sortfield) && $sortfield == 'que') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 25%;" onclick="apply_sorting('que', '<?php echo $sorttypepass; ?>')">Question</th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'status') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 25%;" onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')">Status</th>

                <th  <?php
                if (isset($sortfield) && $sortfield == 'created_date') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 25%;" onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')">Created Date</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
                <?php 
                if (isset($cse_data) && count($cse_data) > 0) { ?>
                    <?php
                    foreach ($cse_data as $data) {
                        if ($data['status'] == 1) {
                            $data['status'] = lang('active');
                        } else {
                            $data['status'] = lang('inactive');
                        }
                        ?>
                    <tr>
                        <td><?= !empty($data['que']) ? $data['que'] : '' ?></td>
                        <td><?= !empty($data['status']) ? $data['status'] : '' ?></td>
                        <td><?= !empty($data['created_date']) ? $data['created_date'] : '' ?></td>

                        <td class="">
                        <?php //if (checkAdminPermission('Rolemaster', 'edit')) { ?>
                                <a class="btn btn-xs btn-primary" href="<?php echo base_url($crnt_view . '/edit/' . $data['cse_que_id']); ?>" title="<?= lang('edit') ?>" ><i class="fa fa-pencil"></i></a>
                                <?php //} ?>
                                <?php //if ($data['role_id'] != $role_id) { ?> <?php //if (checkAdminPermission('Rolemaster', 'delete')) { ?><a href="javascript:;" onclick="deleteCse(<?php echo $data['cse_que_id']; ?>);" class="btn btn-xs btn-danger" title="<?= lang('delete') ?>" ><i class="fa fa-times"></i></a><?php //} ?> <?php //} ?>
                                
                       <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                       <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                        </td>
                    </tr>
                <?php } ?> 
            <?php } else { ?>
                <tr>
                    <td colspan="5">No records found</td>
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
<script>
    var baseurl = '<?php echo base_url() . 'Admin/'; ?>';
</script>

