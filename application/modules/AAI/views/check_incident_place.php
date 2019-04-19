<div id="page-wrapper">
    <div class="main-page">       
        <div class="sticky-heading" id="sticky-heading">
            <h1 class="page-title">Accident And Incident Record <small> New Forest Care</small></h1>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2 class="modal-title aai-model-title" id="exampleModalLabel">Please select the type of incident </h2>
                        <div class="form-group col-md-12">
                            <label class="radio-inline col-md-2"><input id="educheck" type="radio" name="placeType" onchange="check_incident_type();" value="Education"> Education incident</label>
                           
                            <label class="radio-inline col-md-4"><input id="carecheck" type="radio" name="placeType" onchange="check_incident_type();" value="Care" checked> Care incident</label>
                        </div>
                    <div class="col-sm-12">
                      <a id="careIncident" href="<?=base_url('AAI/create/1/'.$ypId); ?>" class="btn btn-default">Submit</a>
                      <a id="eduIncident" style="display: none" href="<?=base_url('AAI/create/2/'.$ypId); ?>" class="btn btn-default">Submit</a>
                    </div>
                 
                    </div>

                </div>
              
            </div>
            
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
                                                        <button onclick="aaidata_search('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
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
                            <?php $this->load->view('aai_main_ajaxlist'); ?>
                            <!-- Listing of User List Table: End -->
                        </div>
                </div>
                        </div>
                    </div>
                
                </div>

</div>
</div>
<?= $this->load->view('/Common/common', '', true); ?>