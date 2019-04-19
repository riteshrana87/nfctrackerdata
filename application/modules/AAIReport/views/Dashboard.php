<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypId; ?>';
</script>


        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');                    
                } ?>
                <div class="sticky-heading for_center_ver row" id="sticky-heading">
                <h1 class="page-title col-md-5 col-xs-12">
                    Accident And Incident Record <small>New Forest Care</small>
                   
                </h1>
                 <div class="pull-right for-tab col-md-7 col-xs-12 text-right">
                        <div class="btn-group">
                                <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypId); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('AAI','add')){ ?>
                            <a href="<?=base_url('AAI/create/'.$isCareIncident.'/'.$ypId); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> Create Incident Record
                                </a>
                            <?php } ?>
                            <?php /*if(checkPermission('ArchiveAAI','view')){*/ ?>
<!--                            <a href="<?=base_url('ArchiveAAI/index/'.$ypId); ?>" class="btn btn-default">
                                <i class="fa fa-search"></i> VIEW ARCHIVE
                            </a>-->
                            <?php /*} */ ?>
                            
                            
                        </div>
                    </div>
               
            </div>
                <div class="row dashboard_aai_re">
                  
					
						
						<div class="col-lg-4 col-sm-6">
							<div class="panel panel-default tile tile-profile">
								<div class="panel-body">
								<a href="<?php echo base_url("AAIReport/Dashboard"); ?>">
									<h2 class="">AAI Reports</h2>
								</a>
								</div>
							</div>
						</div><div class="col-lg-4 col-sm-6">
							<div class="panel panel-default tile tile-profile">
								<div class="panel-body">
									<h2 class="">Tracker Stack Reports</h2>
								</div>
							</div>
						</div><div class="col-lg-4 col-sm-6">
							<div class="panel panel-default tile tile-profile">
								<div class="panel-body">
									<h2 class="">Banding Reports</h2>
								</div>
							</div>
						
					
                    </div>
                
                </div>
            </div>
        </div>
         <?= $this->load->view('/Common/common', '', true); ?>
