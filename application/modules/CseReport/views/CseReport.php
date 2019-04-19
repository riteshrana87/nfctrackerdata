<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
<div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">Search CSE Record Sheet</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                  <?php 
                  /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
                  ?>
                    <?php
                    if($past_care_id == 0){
                    ?>
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <?php }else{ ?>

                    <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                     <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <?php } ?>
                

<?php
if($past_care_id == 0){
 if(checkPermission('CseReport','view')){
            ?>
          <a href="<?= base_url('CseReport/CseRecordReport/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-forward"></i> CSE Record Report
                    </a>
        <?php 
        }}else{
            if(checkPermission('CseReport','view')){
            ?>
          <a href="<?= base_url('CseReport/CseRecordReport/' . $ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-forward"></i> CSE Record Report
                    </a>
 
        <?php }} ?>
                  

                </div>
            </div>
          <div class="clearfix"></div>
        </h1>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row" id="searchForm">
                            <div class="col-sm-8">
                                <div class="form-inline">
                                    <div class="input-group search">
                                        <?php /*  commented by ritesh rana same code use in multiple file ?>
                                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                                        <?php */ ?>
                                        <input type="text" name="searchtext" id="searchtextsdq" class="form-control" placeholder="<?= $this->lang->line('EST_LISTING_SEARCH_FOR') ?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                                        <div class="input-group-btn">
                                            <button onclick="data_searchsdq('changesearch')" class="btn btn-primary"  title="<?= $this->lang->line('search') ?>"><?= $this->lang->line('common_search_title') ?> <i class="fa fa-search fa-x"></i>
                                            </button>
                                            <button class="btn btn-default" onclick="reset_datasdq()" title="<?= $this->lang->line('reset') ?>"><?= $this->lang->line('common_reset_title') ?><i class="fa fa-refresh fa-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                            </div>
                        </div>

                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="whitebox" id="common_div">
                            <?php $this->load->view('ajaxlist'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>

