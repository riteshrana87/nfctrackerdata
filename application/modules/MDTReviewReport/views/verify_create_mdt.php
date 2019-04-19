<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            MDT Report <small>New Forest Care</small>
          
        </h1>
       
        <div class="row">
            <form action="<?=base_url('DailyObservation/insertDo')?>" method="post" id="doform" name="doform" data-parsley-validate enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <div class="">
                            <h1 class="page-title">Checking Date For Daily Observation</h1>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                            <?php
                                if($is_created == true)
                                {
                                    ?>
                                    <h4 class="text-success">SUCCESS</h4>
                                    <p>MDT report for <?=$report_start_date?> and <?=$report_end_date?> has been created!</p>
                                    <?php
                                }
                                else
                                {
                                     ?>
                                     <h4 class="text-danger">WARNING</h4>
                                    <p>A MDT report already exists for  <?=$report_start_date?> and <?=$report_end_date?></p>
                                    <?php
                                }
                            ?>
                                    
                                    
                            </div>
                        </div>
                    </div>
                       
                     <div class="col-sm-12">
                        
   
                        <input type="hidden" name="mdt_report_id" id="mdt_report_id" value="<?=!empty($mdt_report_id)?$mdt_report_id:''?>">
                        
                             <a href="<?=base_url('MDTReviewReport/view/'.$mdt_report_id.'/'.$ypid)?>" class="btn btn-default m-b-xs-15" name="submit_ppform" id="submit_ppform" value="submit" style="default">View MDT Review Report</a>
                            <a href="<?=base_url()?>" class="pull-right btn btn-default pull-none-xs m-b-xs-15" name="submit_ppform" id="submit_ppform" value="submit" style="default">Return to Crisis Dashboard</a>
                                                        <?php if(checkPermission('MDTReviewReport','view')){ ?>
                    <a href="<?=base_url('MDTReviewReport/index/'.$ypid); ?>" class="pull-right btn btn-default"><i class="fa fa-mail-reply"></i> MDT </a>
                    <?php } ?>
 
                      
                    </div>
                   
                    
            </form>        
        </div>
        
    </div>
</div>
