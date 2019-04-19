<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
</script>
<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Appointment / Event <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group capsBtn">
                    <!--  <a href="<?=base_url($this->viewname .'/index/'.$ypid)?>" class="btn btn-default" style="default">Back To Pocket Money</a>        -->      
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid) ?>" class="btn btn-default" style="default">Back To YP Info</a>                                
                </div>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
        </div>
        <?php if (($this->session->flashdata('msg'))) { ?>
        <div class="col-sm-12 text-center" id="div_msg">
            <?php echo $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">  
            <div id='calendar'></div>     
        </div>
    </div>
</div>
<div id="ajaxModal" class="modal">
    <?=$this->load->view($this->viewname.'/ajax_add','',true);?>
    <!-- /.content-wrapper -->
</div> 
<script type="text/javascript">
    var yp_id = <?=!empty($ypid)?$ypid:''?>;
    var accessEdit = false;
    var accessAdd = false;
    var slot_duration = 60;
    <?php if(checkPermission('EventPlanner','edit')){ ?>
        accessEdit = true;
    <?php } ?>
    <?php if(checkPermission('EventPlanner','add')){ ?>
        accessAdd = true;
    <?php } ?>
</script>