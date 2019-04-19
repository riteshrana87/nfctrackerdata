<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading padding-for-stic title_top_padding" id="sticky-heading">
            <h1 class="page-title care_home_title col-lg-3">
               <span class="care_nam"> <?=!empty($care_home_name_data['care_home_name'])?$care_home_name_data['care_home_name']:''?></span>
                <?php 
                if(!empty($care_home_name_data['address']) && trim($care_home_name_data['address']) != ''){ ?>
                <span class="care_address"><i class="fa fa-map-marker" aria-hidden="true"></i><?= !empty($care_home_name_data['address']) ? $care_home_name_data['address'].',' : '' ?></span>
                <?php } ?>
                <?php if(!empty($care_home_name_data['city']) && $care_home_name_data['city'] != ''){ ?>
                <span class="care_city"><?= !empty($care_home_name_data['city']) ? $care_home_name_data['city'].',' : '' ?> </span>
                <?php } ?>
                <?php if(!empty($care_home_name_data['town']) && $care_home_name_data['town'] != ''){ ?>    
                <span class="care_town"><?= !empty($care_home_name_data['town']) ? $care_home_name_data['town'].',' : '' ?> </span>
                <?php } ?>
                <?php if(!empty($care_home_name_data['county']) && $care_home_name_data['county'] != ''){ ?>    
                <span class="care_country"> <?= !empty($care_home_name_data['county']) ? $care_home_name_data['county'].',' : '' ?></span>
                <?php } ?>

                <div class="clearfix"></div>
                <?php if(!empty($care_home_name_data['postcode']) && $care_home_name_data['postcode'] != ''){ ?>
                    <span class="care_postcode"><i class="fa fa-map-pin" aria-hidden="true"></i><?= !empty($care_home_name_data['postcode']) ? $care_home_name_data['postcode'].',' : '' ?></span>    
                <?php } ?>
                <?php if(!empty($care_home_name_data['contact_number']) && $care_home_name_data['contact_number'] != ''){ ?>
                <span class="care_num"><i class="fa fa-phone" aria-hidden="true"></i><?= !empty($care_home_name_data['contact_number']) ? $care_home_name_data['contact_number'].',' : '' ?></span>
                <?php } ?>
                <?php if(!empty($care_home_name_data['care_home_email']) && $care_home_name_data['care_home_email'] != ''){ ?>
                <span class="care_email"><i class="fa fa-envelope" aria-hidden="true"></i>
                <?=!empty($care_home_name_data['care_home_email'])?$care_home_name_data['care_home_email']:''?></span>
                <?php } ?>
            </small>

            </h1>
            <div class="col-lg-9 p-l-r-0">


                <div class="text-right pull-right page_view_btns">
                    <div class="btn-group btn-view-selector" role="group" aria-label="...">
                        <button type="button" class="btn grid_list_btn btn-default yp-listtype <?=($yp_list_type == 'display-list')?'active':''?>" id="display-list"><i class="fa fa-table" aria-hidden="true"></i></button>

                        <button type="button" class="btn grid_list_btn_1 btn-default yp-listtype <?=($yp_list_type == 'display-table')?'active':''?>" id="display-table"><i class="fa fa-list-ul" aria-hidden="true"></i></button>  
                        <input type="hidden" id="yp-listtype" name="yp-listtype" value="">
                    </div>
                </div>
                <div class="form-inline text-right pull-right page_search_btn">
                    <div class="input-group search title_marging">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                        <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="<?=$this->lang->line('EST_LISTING_SEARCH_FOR')?>" value="<?=!empty($searchtext)?$searchtext:''?>">
                        <div class="input-group-btn">
                            <button onclick="data_search('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                            </button>
                            <button class="btn btn-default" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="clearfix visible-sm"></div>
                <div class="btn-group pull-right page_top_btns">
                    <?php if(checkPermission('AAI','view')){ ?>  
                        <a href="<?= base_url('AAI/create/1/0/'.$care_home_id); ?>" class="btn btn-default"> &nbsp;&nbsp; AAI &nbsp;&nbsp;  </a>  
                    <?php }  ?>
                    <?php if(checkPermission('YoungPerson','add')){ ?>
                        <a data-href="<?php echo base_url() . 'YoungPerson/registration/'.$care_home_id; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-default" >Add New YP</a>
                    <?php } ?>
                    <?php if (checkPermission('ArchiveYoungPerson', 'view')) { ?>
                        <a href="<?= base_url('ArchiveYoungPerson/index/'.$care_home_id); ?>" class="btn btn-default">
                            Archived YP List
                        </a>
                    <?php } ?>
                    <?php if (checkPermission('MedicationStock', 'view')) { ?>
                        <a href="<?= base_url('MedicationStock/medicationStockCheck/'.$care_home_id); ?>" class="btn btn-default">
                            MEDS STOCK CHECK
                        </a>
                    <?php } ?>
                    <?php if (checkPermission('Planner', 'view')) { ?>
                        <a title="Planner" href="<?php echo base_url('Planner/carehome/' . $care_home_id); ?>" class="btn btn-default">Planner</a>
                    <?php } ?>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">

            <div class="panel panel-default young_person_bg">
                <div class="panel-body">
                    <div class="row" id="searchForm">
                        <div class="col-sm-8">

                        </div>

                    </div>

                    <?php echo $this->session->flashdata('msg'); ?>
                    <div class="whitebox" id="common_div">
                        <?php $this->load->view('ajaxlist'); ?>
                    </div>
                </div>
            </div>

            <div class="clearfix"> </div>
        </div>
    </div>
</div>
<?= $this->load->view('/Common/common', '', true); ?>
<script>
    var url="<?php echo base_url() . 'YoungPerson/CronJob/'?>";
    var url_calcle="<?php echo base_url() . 'YoungPerson/canclecron/'?>";
</script>