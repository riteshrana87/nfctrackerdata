<!-- main content start-->
<div class="sticky-heading" id="sticky-heading"></div>
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <h1 class="page-title">
            Placement Plan <small>New Forest Care</small>
          
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
        <h1 class="page-title">
            UPDATE PP
            
        </h1>
      
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    <h4 >PP Updated</h4>
                    <p>
                        The PP is now updated. Please check your editing carefully.
                    </p>
                    <p>
                        <a class="" href="<?php echo base_url('PlacementPlan/index/'.$id); ?>">
                        <button class="btn btn-primary" type="button">Check PP</button></a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>