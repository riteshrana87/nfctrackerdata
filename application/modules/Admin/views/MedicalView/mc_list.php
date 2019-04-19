<?php $this->method = $this->router->fetch_method(); ?>
<div id="page-wrapper">
            <div class="main-page">
               
                <h1 class="page-title">
                    MEDICAL INFORMATION <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                            <a href="<?=base_url('Admin/MedicalView/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> MEDS
                            </a>
                           
                            
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default tile tile-profile m-t-10">
                            <div class="panel-body min-h-310">
                                
                                
                        <?php echo $this->session->flashdata('msg'); ?>
                        <h2>Medical Communication Log: <?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?></h2>
                       <div class="whitebox" id="common_div">
                            <?php $this->load->view('mc_ajaxlist'); ?>
                        </div>

                            </div>
                        </div>
                    </div>
                    <div class="clearfix"> </div>
                </div>
                 
            </div>
        </div>
<?= $this->load->view('/Common/common', '', true); ?>