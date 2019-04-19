<script>
var YPId="<?php echo $ypid;?>";
</script>
 <!-- main content start-->
 <div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            ARCHIVED Placement Plans <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <?php
                    /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                     if($past_care_id == 0){ ?>
                        <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                        </a>
                        <a href="<?=base_url('PlacementPlan/index/'.$ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> Return To Current PP
                        </a>
                    <?php }else{ ?>
                         <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                            <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                    <?php } ?>
                    
                </div>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>Archived Placement Plans</h2>
                        <div class="refresh-btn">
                            <?php if($past_care_id !== 0){ ?>
                                    <a href="<?php echo base_url() . 'PlacementPlan/external_approval_list/'.$ypid.'/'.$placement_plan_id.'/'.$care_home_id.'/'.$past_care_id; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?>sdasd</span>
                                    </a>
                                    <?php } ?>

                                    <button class="btn btn-default btn-sm" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                    </button>
                        </div>
						<div class="row" id="searchForm">
                           <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                             <div class="form-group">
                            <div class="input-group input-append date " id="datepicker_search">
                            <input class="form-control" name="search_date" id="search_date" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">

                                <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>


                            </div>
                             </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                          <div class="form-group">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input class="red form-control  addtime" title="" name="search_start_time" id="search_start_time" readonly="" placeholder="" value="" data-parsley-errors-container="#errors-containertime" type="text">
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>



                            </div>
                          </div>
                        </div>
						
						<div class="col-lg-3 col-md-3 col-sm-6 text-left">
                          <div class="form-group">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input class="red form-control  addtime" title="" name="search_end_time" id="search_end_time" readonly="" placeholder="" value="" data-parsley-errors-container="#errors-containertime" type="text">
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>



                            </div>
                          </div>
                        </div>
						<input type="hidden" name="search" id="search" value="" />
						<input type="hidden" name="professional_name" id="professional_name_value" value="" />
						<input type="hidden" name="search_date" id="search_date_value" value="" />
						<input type="hidden" name="search_start_time" id="search_start_time_value" value="" />
						<input type="hidden" name="search_end_time" id="search_end_time_value" value="" />
                        <div class="col-lg-3 col-md-3 col-sm-6 text-right">
                            <div class="form-inline">
                              <div class="form-group">
                                <div class="input-group search">
                                    <div class="input-group-btn">
									
                                        <a onclick="data_search_archive_placement_plan('changesearch')" class="btn btn-primary mtp-z"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                                    </a>
									<?php /* 
									<a href="javascript:;" class="btn btn-default export "><?=$this->lang->line('common_reset_title')?><i class="fa fa-file-excel-o fa-x"></i></a>
									*/ ?>
                                    <button class="btn btn-default" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                </button>
								
                            </div>
                        </div>
                              </div>




                    </div>
                </div>

            </div>
                       
                     <div class="clearfix"></div>
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="whitebox auditlog audit_table" id="common_div">
                            <?php $this->load->view('archive_ajax'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->load->view('/Common/common', '', true); ?>