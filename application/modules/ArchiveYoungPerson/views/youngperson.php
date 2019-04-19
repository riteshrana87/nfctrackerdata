<div id="page-wrapper">
            <div class="main-page">
                 <div class="sticky-heading padding-for-stic" id="sticky-heading">
                <h1 class="page-title care_home_title col-lg-3"></h1>
                <div class="col-lg-9 p-l-r-0">
                        <div class="pull-right">
                        <div class="btn-group">
                               <a href="<?= base_url('YoungPerson/index/'.$care_home_id); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> CARE HOME YP LIST
                                </a>
                        </div>
                    </div>
                </div>
            </div>
                <div class="row margin-archive">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row" id="searchForm">
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
<?= $this->load->view('/Common/common', '', true); ?>