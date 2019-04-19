<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
</script>
        <div id="page-wrapper">
            <div class="main-page">    
             <div class="sticky-heading" id="sticky-heading">            
                <h1 class="page-title">
                    Weekly Report to social worker <small>New Forest Care</small>
                    <div class="pull-right for-tab">
                        <div class="btn-group">
                            <?php
                            /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                             if($past_care_id == 0){ ?>
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('WeeklyReport','add')){ ?>
                            <a href="<?=base_url('WeeklyReport/create/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> CREATE WR
                                </a>
                            <?php } ?>
                            <?php if(checkPermission('ArchiveWr','view')){ ?>
                            <a href="<?=base_url('ArchiveWr/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-search"></i> VIEW ARCHIVE
                            </a>
                            <?php } ?>
                            <?php }else{ ?>
                            <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                            <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                            </a>     

                            <?php if(checkPermission('ArchiveWr','view')){ ?>
                            <a href="<?=base_url('ArchiveWr/index/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                <i class="fa fa-search"></i> VIEW ARCHIVE
                            </a>
                            <?php } ?>

                            <?php } ?>
                            
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
            </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row m-b-10" id="searchForm">
                                    <div class="col-sm-8">
                                        <div class="form-inline">
                                            <div class="input-group search">
                                                <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="<?=$this->lang->line('EST_LISTING_SEARCH_FOR')?>" value="<?=!empty($searchtext)?$searchtext:''?>">
                                                  <div class="input-group-btn">
                                                        <button onclick="data_search('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                                                        </button>
                                                        <button class="btn btn-default" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                                        </button>
                                                  </div>
                                            </div>
                                    </div>
                                    </div>
                                    <div class="col-sm-4 text-right"></div>
                                </div>
                                
								<?php if(($this->session->flashdata('msg'))){ 
										echo $this->session->flashdata('msg');
									
								} ?>
								<?php if(($this->session->flashdata('errormsg'))){ 
										echo '<div class="alert alert-danger text-center">'.$this->session->flashdata('errormsg').'</div>';
									
								} ?>
								<?php if(($this->session->flashdata('successmsg'))){ 
										echo '<div class="alert alert-success text-center">'.$this->session->flashdata('successmsg').'</div>';
									
								} ?>
							   <div class="whitebox" id="common_div">
									<?php $this->load->view('ajaxlist'); ?>
									<!-- Listing of User List Table: End -->
								</div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group">
                               <?php
                            /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                             if($past_care_id == 0){ ?>
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('WeeklyReport','add')){ ?>
                            <a href="<?=base_url('WeeklyReport/create/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> CREATE WR
                                </a>
                            <?php } ?>
                            <?php if(checkPermission('ArchiveWr','view')){ ?>
                            <a href="<?=base_url('ArchiveWr/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-search"></i> VIEW ARCHIVE
                            </a>
                            <?php } ?>
                            <?php }else{ ?>
                            <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                            <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                            </a>     

                            <?php if(checkPermission('ArchiveWr','view')){ ?>
                            <a href="<?=base_url('ArchiveWr/index/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                <i class="fa fa-search"></i> VIEW ARCHIVE
                            </a>
                            <?php } ?>

                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <?= $this->load->view('/Common/common', '', true); ?>