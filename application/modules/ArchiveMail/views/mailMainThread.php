<div class="whitebox p-15">
    <div class="row">
        <div class="col-lg-9 col-xs-12 col-sm-12 col-md-12">
            <div class="form-group bd-mail-head bd-inbox">
                
            </div>
        </div>
        <div class="col-lg-3 col-xs-12 col-sm-12 col-sm-12">
            <div class="form-group">
                <div class="bd-mail-detail border-0">
                    <div class="bd-sesrch-contact ">
                        <div class="search-top">
                            <div class="input-group">
                                <!-- <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>"> -->
                                  
                                <!--  <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>">  -->

                                <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="Search for..." aria-controls="example1" placeholder="Search" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                                <span class="input-group-btn">
                                    <button onclick="data_search('changesearch')" class="btn btn-default"  title="Search"><?= $this->lang->line('common_search_title') ?> <i class="fa fa-search fa-x"></i></button>
                                    <button class="btn btn-default howler flt" onclick="reset_data()" title="Reset"><?= $this->lang->line('common_reset_title') ?><i class="fa fa-refresh fa-x"></i></button> 
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 bd-inbox-elem form-group" id="common_div">
            <?php echo $this->load->view('MailAjaxList'); ?>
        </div>
    </div>
</div>