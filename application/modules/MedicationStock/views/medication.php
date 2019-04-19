<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var care_home_id = '<?php echo $care_home_id ?>';
    var MedicationId = '<?= !empty($information[0]['medication_id']) ? $information[0]['medication_id'] : '' ?>';
</script>

<?php  $this->method = $this->router->fetch_method(); ?>
<div id="page-wrapper">
    <div class="main-page">
        <div class="row">
            <div class="col-xs-12">
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">MEDICATION STOCK INFORMATION<small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                             <a href="<?= base_url('YoungPerson/index/'.$care_home_id); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i>  CARE HOME YP LIST
                                </a>
                            
                            <?php if (checkPermission('MedicationStock', 'hidden_archive')) { ?>
                                <a href="<?= base_url('MedicationStock/medicationArchiveStock/'.$care_home_id); ?>" class="btn btn-default btn-xs-100">
                                     Medication Archive Stock
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </h1>
            </div>
                
                <?php if(($this->session->flashdata('msg'))){ ?>
                <div class="col-sm-12 text-center" id="div_msg">
                    <?php echo $this->session->flashdata('msg');?>
                </div>
                <?php } ?>
                <div class="clearfix"></div>
                
           <div class="panel panel-default tile tile-profile m-t-10">
            <div class="panel-body min-h-310">
                <h2>Current Medication Stock</h2>
                <div class="row" id="searchForm">
                    <div class="col-sm-8">
                        <div class="form-inline">
                            <div class="input-group search">
                               
                                <select class="chosen-select form-control" data-parsley-errors-container="#parent_error" placeholder="Select YP"  name="searchtext" id="searchtext" required="">
                                            <option value="">Select YP</option>
                                            <?php foreach($YP_details as $rows){
                                                ?>
                                                    <option value="<?php echo $rows['yp_id'];?>"><?php echo $rows['yp_fname'].' '.$rows['yp_lname'];?></option>
                                                <?php }?>
                                        </select>
										<input type="hidden" name="ypid" id="ypid" value="" />
										<input type="hidden" name="care_home_id" id="care_home_id" value="<?php echo $care_home_id;?>" />
                                <div class="input-group-btn">
                                <button onclick="data_search('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                                </button>
                                <button class="btn btn-default" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                </button>
								<a href="javascript:;" class="btn btn-default export "><?=$this->lang->line('common_reset_title')?><i class="fa fa-file-excel-o fa-x"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="whitebox" id="common_div">
                    <?php $this->load->view('medication_ajaxlist'); ?>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="row">
        <h1 class="page-title">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group">
                        <a href="<?= base_url('YoungPerson/index/'.$care_home_id); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> Back
                                </a>
                            <a href="<?= base_url('YoungPerson/index/'.$care_home_id); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i>  CARE HOME YP LIST
                                </a>
                             <?php if (checkPermission('MedicationStock', 'hidden_archive')) { ?>
                                <a href="<?= base_url('MedicationStock/medicationArchiveStock/'.$care_home_id); ?>" class="btn btn-default btn-xs-100">
                                     Medication Archive Stock
                                </a>
                            <?php } ?>
                    </div>
                </div>
            </div>
        </h1>
        </div>
</div>
</div>

<?= $this->load->view('/Common/common', '', true); ?>