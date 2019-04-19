<div id="page-wrapper">
            <div class="main-page">
                <div class="row">
                   <div class="sticky-heading padding_title" id="sticky-heading">
                <div class="col-lg-6 col-md-6 col-sm-4">
                <h1 class="page-title"><span>Search YP Info</span>
                </h1>
                </div>
                 <?php if (checkPermission('YoungPerson', 'view')) { ?>                 
                <div class="col-lg-3 col-md-3 col-sm-4">
                    <div class="form-group">
                    <div class="input-group search">
                                    <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                                    <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="Search by YP name / Initials" value="<?=!empty($searchtext)?$searchtext:''?>">
                                      <div class="input-group-btn">
                                        <button onclick="data_search('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                                        </button>
                                        <button class="btn btn-primary" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                        </button>
                                      </div>
                                </div>
                                </div>
                                </div>
                                <?php } ?>
                                  <?php if (checkPermission('ArchiveYoungPerson', 'view')) { ?>
                                <div class="col-lg-3 col-md-3 col-sm-4 ">
                                    <div class="form-group">
                                    <div class="input-group search">
                                    <input type="text" name="searchtextyp" id="searchtextyp" class="form-control" placeholder="Search by ArchiveYP name / Initials" value="<?=!empty($searchtextyp)?$searchtextyp:''?>">
                                      <div class="input-group-btn">
                                        <button onclick="data_search_archiveyp('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                                        </button>
                                        <button class="btn btn-primary" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                        </button>
                                      </div>
                                      </div>
                                      </div>
                                </div>
                                <?php } ?>
</div>
</div>
<div class="whitebox" id="common_div">
    <?php $this->load->view('ajaxlist'); ?>
</div>

            </div>
        </div>
        <?= $this->load->view('/Common/common', '', true); ?>