<!-- main content start-->
<script> 
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
</script>
<div id="page-wrapper">
    <div class="main-page"> 
    <div class="sticky-heading" id="sticky-heading">               
            <h1 class="page-title">
                CONCERNS <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <?php /* condition added by Dhara Bhalala for carehome YP archive */
                    if($isArchiveCareHomePage == 0){ ?>
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?= base_url('Concerns/index/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> Concerns
                    </a>
                    <a href="<?= base_url('Concerns/view/' . $ypc_id . '/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> Back to current Concern
                    </a>                        
                    <?php }else{ ?>
                     <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>
                    <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$care_home_id.'/'.$isArchiveCareHomePage); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?=base_url('Concerns/index/'.$ypid.'/'.$care_home_id.'/'.$isArchiveCareHomePage); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> Concerns
                    </a>
                    <a href="<?=base_url('Concerns/view/'.$ypc_id.'/'.$ypid.'/'.$care_home_id.'/'.$isArchiveCareHomePage); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> Back to current Concern
                    </a>                    
                   <?php } ?>                    
                </div>
            </div>
            </h1>
            <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>CONCERNS LIST</h2>
                        <div class="refresh-btn">
                                            <button class="btn btn-default btn-sm" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                        </button>
                                       </div>
                       
                        <div class="clearfix"></div>

                        <?php
                        if (($this->session->flashdata('msg'))) {
                            echo $this->session->flashdata('msg');
                        }
                        ?>
                        <?php
                        if (($this->session->flashdata('errormsg'))) {
                            echo '<div class="alert alert-danger text-center">' . $this->session->flashdata('errormsg') . '</div>';
                        }
                        ?>
                        <?php
                        if (($this->session->flashdata('successmsg'))) {
                            echo '<div class="alert alert-success text-center">' . $this->session->flashdata('successmsg') . '</div>';
                        }
                        ?>
                        <div class="whitebox" id="common_div">
<?php $this->load->view('reviewed_ajaxlist'); ?>
                            <!-- Listing of User List Table: End -->
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group capsBtn">
                        <?php /* condition added by Dhara Bhalala for carehome YP archive */
                        if($isArchiveCareHomePage == 0){ ?>
                        <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                        </a>
                        <a href="<?= base_url('Concerns/index/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> Concerns
                        </a>
                        <a href="<?= base_url('Concerns/view/' . $ypc_id . '/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> Back to current Concern
                        </a>
                            
                <?php        }else{?>
                         <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>
                    <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$care_home_id.'/'.$isArchiveCareHomePage); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                        <a href="<?= base_url('Concerns/index/' . $ypid.'/'.$care_home_id.'/'.$isArchiveCareHomePage); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> Concerns
                        </a>
                        <a href="<?= base_url('Concerns/view/' . $ypc_id . '/' . $ypid.'/'.$care_home_id.'/'.$isArchiveCareHomePage); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> Back to current Concern
                        </a>
                    
                            
                  <?php      } ?>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->load->view('/Common/common', '', true); ?>