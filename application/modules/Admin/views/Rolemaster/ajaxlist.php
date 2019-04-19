<?php
/*
  @Description: Rolemaster
  @Author: Ritesh Rana
  @Date: 08-6-2017
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
                if (isset($sortfield) && $sortfield == 'role_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 25%;" onclick="apply_sorting('role_name', '<?php echo $sorttypepass; ?>')">Role Name</th>
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

                <th <?php
                if (isset($sortfield) && $sortfield == 'group_ref_id') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 25%;" onclick="apply_sorting('group_ref_id', '<?php echo $sorttypepass; ?>')">Group ref</th>

                <th><?= $this->lang->line('edit_delete_perms') ?></th>
                <th><?= $this->lang->line('edit_delete_role') ?></th>

            </tr>
        </thead>
        <tbody>
            <tr>   
                <?php if (isset($information) && count($information) > 0) { ?>
                    <?php
                    foreach ($information as $data) {
                        if ($data['status'] == 1) {
                            $data['status'] = lang('active');
                        } else {
                            $data['status'] = lang('inactive');
                        }
                        ?>
                    <tr>
                        <td><?= !empty($data['role_name']) ? $data['role_name'] : '' ?></td>
                        <td><?= !empty($data['status']) ? $data['status'] : '' ?></td>
                        <td><?= isset($data['group_ref_id']) ? $data['group_ref_id'] : '' ?></td>
                       
                        <td class="">
                            <?php if (checkAdminPermission('Rolemaster', 'view')) { ?>
                                <a class="btn btn-xs btn-primary" href="<?php echo base_url($crnt_view . '/view_perms_to_role_list/' . $data['role_id']); ?>" title="<?= lang('view') ?>" ><i class="fa fa-file-text-o"></i></a> <?php } ?>

                            <?php
                            if (checkAdminPermission('Rolemaster', 'edit')) {
                                if ($data['role_id'] != $role_id) {
                                    ?>
                                    <a class="btn btn-xs btn-primary" href="<?php echo base_url($crnt_view . '/editPermission/' . $data['role_id']); ?>" title="<?= lang('edit') ?>" ><i class="fa fa-pencil"></i></a> <?php
                                }
                            }
                            ?>
                        </td>

                        <td class="">
                        <?php if (checkAdminPermission('Rolemaster', 'edit')) { ?>
                                <a class="btn btn-xs btn-primary" href="<?php echo base_url($crnt_view . '/edit/' . $data['role_id']); ?>" title="<?= lang('edit') ?>" ><i class="fa fa-pencil"></i></a>
                                <?php } ?>
                                <?php if ($data['role_id'] != $role_id) { ?> <?php if (checkAdminPermission('Rolemaster', 'delete')) { ?><a href="javascript:;" onclick="deleteRole_t(<?php echo $data['role_id']; ?>);" class="btn btn-xs btn-danger" title="<?= lang('delete') ?>" ><i class="fa fa-times"></i></a><?php } ?> <?php } ?>
                                
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
    var baseurl = '<?php echo base_url() . 'Admin/'; ?>';
</script>

