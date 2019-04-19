<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Care Plan Target <small>New Forest Care</small>

        </h1>
        <!-- display yp details -->
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
      </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    <h4 >Care Plan Target Saved</h4>
                    <p>
                        The Care Plan Target has been saved. Please review your notes.
                    </p>
                    <p>
                        <a class="" href="<?php echo base_url('CarePlanTarget/view/'.$cpt_id.'/'.$yp_id); ?>">
                        <button class="btn btn-primary" type="button">Review Care Plan Target</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>