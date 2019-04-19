<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('successmsg'))) { ?>
            <div class='alert alert-success text-center'><?php echo $this->session->flashdata('successmsg');?></div>            
        <?php }
        if (($this->session->flashdata('errormsg'))) { ?>
            <div class='alert alert-danger text-center'><?php echo $this->session->flashdata('errormsg');?></div>
        <?php }
        ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            WEEKLY REPORT TO SOCIAL WORKER <small>New Forest Care</small>
          
        </h1>
        <h1 class="page-title">
            UPDATE WR
            
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    <h4 >WR Updated</h4>
                    <p>
                        The WR is now updated. Please check your editing carefully.
                    </p>
                    <p>
                        <a class="" href="<?php echo base_url('WeeklyReport/index/'.$id); ?>">
                        <button class="btn btn-primary" type="button">Check WR</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>