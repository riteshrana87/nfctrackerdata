<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                    Documents <small>New Forest Care</small>
                    <div class="pull-right for-tab">
					<div class="btn-group">
						<?php 
						/*ghelani nikunj
						10/10/2018
						if in care to care archive then no need to show button
						*/
						if($is_archive_page==0){?>
                        <div class="btn-group">
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('Documents','add')){ ?>
                            <a href="<?=base_url('Documents/create/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> Add File
                            </a>
                            <?php } ?>
                        </div>
						<?php } else {?>
						
						<a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
						<?php }?>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
            </div>
                                               
                                                
                                    
                <div class="row">
                    
                    <div class="whitebox" id="common_div">
                            <?php $this->load->view('ajaxlist'); ?>
                            <!-- Listing of User List Table: End -->
                        </div>
                </div>
                
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group capsBtn">
							<?php 
						/*ghelani nikunj
						10/10/2018
						if in care to care archive then no need to show button
						*/
						if($is_archive_page==0){?>
                                <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                                <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>
                                <?php if(checkPermission('Documents','add')){ ?>
                                <a href="<?=base_url('Documents/create/'.$ypid); ?>" class="btn btn-default">
                                        <i class="fa fa-edit"></i> Add File
                                </a>
                                <?php } ?>
						<?php } else {?>
						
						<a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
						
						<?php }?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->load->view('/Common/common', '', true); ?>