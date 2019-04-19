<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var MedicationId = '<?= !empty($edit_data[0]['medication_id']) ? $edit_data[0]['medication_id'] : '' ?>';
</script>
<?php $this->method = $this->router->fetch_method(); ?>
<div id="page-wrapper">
    <div class="main-page">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="page-title">
                 MEDICATION STOCK <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                             <a href="javascript:history.go(-1);" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> Back
                            </a>
                             
                        </div>
                    </div>
                </h1>
                
                <?php if(($this->session->flashdata('msg'))){ ?>
                <div class="col-sm-12 text-center" id="div_msg">
                    <?php echo $this->session->flashdata('msg');?>
                </div>
                <?php } ?>
                <div class="clearfix"></div>
                
           <div class="panel panel-default tile tile-profile m-t-10">
            <div class="panel-body min-h-310">
                <h2>Current Medication Stock</h2>
                <div class="clearfix"></div>
                <div class="whitebox" id="common_div">
                    <?php $this->load->view('checkedstock_ajaxlist'); ?>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="row">
        <h1 class="page-title">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group">
                            <a href="javascript:history.go(-1);" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> Back
                            </a>
                             
                    </div>
                </div>
            </div>
        </h1>
        </div>
</div>
</div>

<?= $this->load->view('/Common/common', '', true); ?>