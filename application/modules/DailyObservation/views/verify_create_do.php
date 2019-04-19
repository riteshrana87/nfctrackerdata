<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Daily Observation <small>New Forest Care</small>
          
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
                                    <p>No daily observation existed for <?=$created_date?> so one has been created!</p>
                                    <?php
                                }
                                else
                                {
                                     ?>
                                     <h4 class="text-danger">WARNING</h4>
                                    <p>A daily observation already exists for <?=$created_date?></p>
                                    <?php
                                }
                            ?>
                                    
                                    
                            </div>
                        </div>
                    </div>
                       
                     <div class="col-sm-12">
                        
   
                        <input type="hidden" name="do_id" id="do_id" value="<?=!empty($do_id)?$do_id:''?>">
                        <div class="pull-right btn-section">
                         <div class="btn-group">
                             <a href="<?=base_url('DailyObservation/view/'.$do_id .'/'. $ypid)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">View Daily Observation</a>
                            <a href="<?=base_url()?>" class=" btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Return to Care Dashboard</a>
                                                        <?php if(checkPermission('DailyObservation','view')){ ?>
                    <a href="<?=base_url('DailyObservation/index/'. $ypid); ?>" class=" btn btn-default"><i class="fa fa-mail-reply"></i> DO </a>
                    <?php } ?>
                    </div></div>
                      
                    </div>
                   
                    
            </form>        
        </div>
        
    </div>
</div>
