<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
</script>
<?php $this->method = $this->router->fetch_method(); ?>
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
            <h1 class="page-title">
                Appointment / Event <small>New Forest Care</small>
                <div class="pull-right for-tab">
                <?php  /*ghelani nikunj 19/8/2018 if in care to care archive then no need to show button */
                    if($is_archive_page==0){ ?>
                    <div class="btn-group">
                        <?php if(checkPermission('Appointments','add')){ ?>
                        <a href="<?=base_url('Appointments/add_appointment/'.$ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> ADD APPOINTMENT / EVENT
                        </a>
                        <?php } ?>
                        <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                        </a>
                        <a href="<?= base_url('Appointments/DownloadAppointment/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                    </div>
                    <?php }?>
                </div>         
            </h1>
            <h1 class="page-title">
                <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-success text-center" style="display:none;">Record deleted successfully</div>
                <?php $smsg= $this->session->flashdata('msg');if(!empty($smsg)){ ?>
                <div class="col-sm-12 text-center" id="div_msg">
                    <?php echo $this->session->flashdata('msg');?>
                </div>
                <?php } ?>
                <div class="clearfix"></div>
                <div class="panel panel-default tile tile-profile m-t-10">
                    <div class="panel-body min-h-310">
                    <h2>Appointment / Event</h2>                    
                        <div class="row" id="searchForm">   
                            <div class="col-lg-3 col-md-3 col-sm-5 text-left">
                                <div class="form-group">
                                    <div class="input-group input-append date" id="datepicker">
                                    <input class="form-control" name="search_date" id="search_date" placeholder="" value="" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">
                                        <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-5 text-left">
                              <div class="form-group">
                                <div class="input-group input-append bootstrap-timepicker">
                                    <input class="red form-control  addtime" title="" name="search_time" id="search_time" placeholder="" value="" data-parsley-errors-container="#errors-containertime" type="text">
                                    <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-2 text-right">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <div class="input-group search">
                                            <div class="input-group-btn">
                                                <button onclick="data_search('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i></button>
                                                <button class="btn btn-default" onclick="reset_data()" title="
                                                <?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                        <div class="panel panel-default tile tile-profile m-t-10">
                            <div class="panel-body min-h-310">
                                <div class="clearfix"></div>
                                <div class="whitebox" id="common_div">
                                    <?php $this->load->view('appointment_ajaxlist'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                        <div class="col-xs-12">
                            <div class="pull-right btn-section">
                <?php  /*ghelani nikunj on 19/8/2018 if in care to care archive then no need to show button */
                if($is_archive_page==0){?>
                                <div class="btn-group">
                                <?php if(checkPermission('Appointments','add')){ ?>       
                                    <a href="<?=base_url('Appointments/add_appointment/'.$ypid); ?>" class="btn btn-default">
                                        <i class="fa fa-mail-reply"></i> ADD APPOINTMENT / EVENT
                                    </a>
                                <?php } ?>
                                <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                                <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>
                                <a href="<?= base_url('Appointments/DownloadAppointment/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                                </div>
                <?php }?>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
</div>
</div>
<?= $this->load->view('/Common/common', '', true); ?>