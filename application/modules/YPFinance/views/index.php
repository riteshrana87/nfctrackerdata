<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
</script>
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
            <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                    YP Finance <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
						<?php 
							/*ghelani nikunj
							09/10/2018
							if in care to care archive then no need to show button
							*/
							if($is_archive_page==0){?>
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
							<?php }?>
							<?php 
							/*ghelani nikunj
							09/10/2018
							if in care to care archive then no need to show button
							*/
							if($is_archive_page==0){?>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
							<?php } else {?>
							<a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
								<i class="fa fa-mail-reply"></i> YP INFO
								</a>
							<?php }?>
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
            </div>
                <div class="row">
                <div class="col-xs-12">
                <!--<?php if(checkPermission('PocketMoney','view')){ ?>
                    <a href="<?=base_url('PocketMoney/index/'.$ypid); ?>" class="btn btn-default">
                        Pocket Money <?=!empty($yp_pocket_money[0]['total_balance'])?' Balance : '.$yp_pocket_money[0]['total_balance']:''?>
                    </a>
                <?php } ?>
                <?php if(checkPermission('ClothingMoney','view')){ ?>
                    <a href="<?=base_url('ClothingMoney/index/'.$ypid); ?>" class="btn btn-default">
                         Clothing Money <?=!empty($yp_clothing_money[0]['total_balance'])?' Balance : '.$yp_clothing_money[0]['total_balance']:''?>
                    </a>
                <?php } ?>
                <?php if(checkPermission('PocketMoney','view')){ ?>
                <label class="btn btn-default">Savings <?=!empty($yp_pocket_money[0]['saving_balance'])?' Balance : '.$yp_pocket_money[0]['saving_balance']:''?></label>
                <?php } ?>-->
                </div>
                </div>
              
                <div class="row">
                  <?php if(checkPermission('PocketMoney','view')){ ?>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                      <div class=" widget-margin-top">
					  <?php if($is_archive_page==0){?>
                              <a href="<?=base_url('PocketMoney/index/'.$ypid); ?>">
					  <?php } else {?>
					   <a href="<?=base_url('PocketMoney/index/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>">
					  <?php }?>
                                  <div class="stats-left">
                                      <i class="fa fa-money" aria-hidden="true"></i>
                                      <h4>Pocket Money</h4>
									  <?php if($is_archive_page==0){?>
                                      <h5>Balance : <?=!empty($yp_pocket_money[0]['total_balance'])?$yp_pocket_money[0]['total_balance']:'0'?> &nbsp;</h5>
									  <?php } else { ?>
									  <h5>Balance : <?=!empty($yp_pocket_money['balance'])?$yp_pocket_money['balance']:'0'?> &nbsp;</h5>
									  <?php }?>
                                  </div>
                                  <div class="clearfix"> </div>
                              </a>
                          </div>
                      </div>
                  <?php } ?>
                <?php if(checkPermission('ClothingMoney','view')){ ?>
              <div class="col-md-4 col-sm-4 col-xs-12">
                      <div class=" widget-margin-top">
					   <?php if($is_archive_page==0){?>
                              <a href="<?=base_url('ClothingMoney/index/'.$ypid); ?>">
						<?php } else {?>
						<a href="<?=base_url('ClothingMoney/index/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>">
						<?php }?>
						
                                  <div class="stats-left">
                                      <i class="fa fa-odnoklassniki" aria-hidden="true"></i>
                                      <h4>Clothing Money</h4>
									   <?php if($is_archive_page==0){?>
                                      <h5>Balance : <?=!empty($yp_clothing_money[0]['total_balance'])?$yp_clothing_money[0]['total_balance']:'0'?> &nbsp;</h5>
									   <?php } else { ?>
									   <h5>Balance : <?=!empty($yp_clothing_money['total_balance'])?$yp_clothing_money['total_balance']:'0'?> &nbsp;</h5>
									   <?php }?>
                                  </div>
                                  <div class="clearfix"> </div>
                              </a>
                          </div>
                      </div>
              <?php } ?>
                
              <?php if(checkPermission('PocketMoney','view')){ ?>
                <div class="col-md-4 col-sm-4 col-xs-12">
                      <div class=" widget-margin-top">
                              
                                  <div class="stats-left">
                                      <i class="fa fa-gbp" aria-hidden="true"></i>
                                      <h4>Savings</h4>
                                      <h5> Balance : <?=!empty($yp_pocket_money[0]['saving_balance'])?$yp_pocket_money[0]['saving_balance']:'0'?> &nbsp;</h5>
                                  </div>
                                  <div class="clearfix"> </div>
                              
                          </div>
                      </div>
                </div>
          <?php } ?>
                  
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group capsBtn">
                               <?php /* <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a> */?>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>