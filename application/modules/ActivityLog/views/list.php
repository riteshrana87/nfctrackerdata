<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">AUDIT LOG</small></h1>
        <div class="row activity-form" id="searchForm">
            <form method="post" id="auditlogform">
                <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                    <div class="form-group">
                        <select class='form-control chosen-select' id='staff_name' name='staff_name'>
                            <option value=''> Select Staff </option>
                            <?php if(!empty($staffdata)) {
                                foreach ($staffdata as $select) { ?>
                                    <option <?php if($staff_name==$select['user_id']){ echo "selected='selected'"; }?> value="<?php echo $select['user_id'];?>"> <?php echo $select['user_name'];?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                    <div class="form-group">
                        <select class='form-control chosen-select' id='yp_name' name='yp_name'>
                            <option value=''> Select YP </option>
                            <?php if(!empty($yp_name)) {
                                foreach ($yp_name as $select) {  ?>
                                    <option <?php if($search_yp_name==$select['yp_id']){ echo "selected='selected'"; }?>  value="<?php echo $select['yp_id'];?>"> <?php echo $select['yp_name'];?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>                    
                <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                    <div class="form-group">
                        <div class="input-group input-append date " id="audit_log_datepicker_search">
                            <input class="form-control" name="search_start_date" id="search_start_date" placeholder="StartDate" value="<?php if(isset($filter_search_start_date)){ echo $filter_search_start_date; }?>" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">
                            <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                    <div class="form-group">
                        <div class="input-group input-append date " id="audit_log_datepicker_end_search">
                            <input class="form-control" name="search_end_date" id="search_end_date" placeholder="EndDate" value="<?php if(isset($filter_search_end_date)){ echo $filter_search_end_date; }?>" readonly="" data-parsley-errors-container="#errors-containerdate" type="text">
                            <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                    <div class="form-group">
                        <select class='form-control chosen-select' id='module_name' name='module_name'>
                            <option value=''> Select Module </option>
                            <?php if(!empty($module_name)) {
                                foreach ($module_name as $select) {  ?>
                                    <option <?php if($search_module_name==$select['module_name']){ echo "selected='selected'"; }?> value="<?php echo $select['module_name'];?>"><?php echo $select['module_name'];?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="search" id="search" value="" />
                <input type="hidden" name="result_type" id="result_type" value="ajax" />
                <input type="hidden" name="module_name" id="module_name" value="" />
                <input type="hidden" name="staff_name" id="staff_name" value="" />
                <input type="hidden" name="yp_name" id="yp_name" value="" />
                <input type="hidden" name="search_start_date" id="search_start_date" value="" />
                <input type="hidden" name="search_end_date" id="search_end_date" value="" />

                <div class="col-lg-3 col-lg-offset-6 col-md-6 col-sm-6 col-md-offset-3  text-right">
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="input-group search">
                                <div class="input-group-btn">
                                    <a onclick="data_search_auditlog('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i></a>
                                    <button type="button" class="btn btn-default" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>			
        <div class="whitebox auditlog audit_table" id="common_div">
            <?php $this->load->view('ajaxlist'); ?>
        </div>
    </div>	
</div>
<script>
var alogurl='<?php echo $this->config->item('base_url') . '/' . $this->viewname.'/'.$this->method ?>/';
</script>
<?= $this->load->view('/Common/common', '', true); ?>