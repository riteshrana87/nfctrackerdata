<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypId; ?>';
</script>
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');                    
                } ?>
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                    Accident And Incident Record <small>New Forest Care</small>
                    <div class="pull-right for-tab">
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
                                <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypId); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('AAI','add')){ ?>
                          <a href="<?=base_url('AAI/create/'.$isCareIncident.'/'.$ypId); ?>" class="btn btn-default">
                                    <i class="fa fa-edit"></i> CREATE INCIDENT RECORD
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
                </div>
            </div>
        </div>
         <?= $this->load->view('/Common/common', '', true); ?>
