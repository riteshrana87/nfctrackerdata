<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var Doid = <?=!empty($dodata[0]['do_id'])?$dodata[0]['do_id']:''?>;
    var YPId = <?=!empty($dodata[0]['yp_id'])?$dodata[0]['yp_id']:$ypid?>;

</script>
<div id="page-wrapper">
<form action="<?=base_url($this->viewname.'/insert_overview')?>" method="post" id="ppform" name="ppform" data-parsley-validate enctype="multipart/form-data">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            DAILY OBSERVATIONS <small>New Forest Care</small>
            <div class="pull-right btn-group">
                <button type="submit" class="btn btn-default" name="submit_doform" id="submit_doform" value="submit" style="default">Update</button>
                <a href="<?=base_url('DailyObservation/view/'.$dodata[0]['do_id'].'/'.$dodata[0]['yp_id'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To Daily Observation</a>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            
                    <div class="col-sm-12">
                        <div class="">
                            <h1 class="page-title">EDIT OVERVIEW</h1>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>WAKING TIME</h2>
                                    <div class="input-group input-append bootstrap-timepicker">
                                         <input class="form-control timepicker addtime" type="text" name="awake_time" id="awake_time" readonly="" value="<?=(!empty($dodata[0]['awake_time']) && $dodata[0]['awake_time'] != '00:00:00')?date('h:i a',strtotime($dodata[0]['awake_time'])):''?>">
                                        <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                        
                                    </div>
                                    
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>BED TIME</h2>
                                    <div class="input-group input-append bootstrap-timepicker">
                                         <input class="form-control timepicker addtime" type="text" name="bed_time" id="bed_time" readonly="" value="<?=(!empty($dodata[0]['bed_time']) && $dodata[0]['bed_time'] != '00:00:00')?date('h:i a',strtotime($dodata[0]['bed_time'])):''?>">
                                        <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                   
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>CONTACT</h2>
                                    <textarea class="form-control" type="text" name="contact" id="contact" ><?=!empty($dodata[0]['contact'])?$dodata[0]['contact']:''?></textarea>
                            </div>
                        </div>
                    </div>
                     <div class="col-sm-12">
                        <div class="">
                        <input type="hidden" name="do_id" id="do_id" value="<?=!empty($dodata[0]['do_id'])?$dodata[0]['do_id']:''?>">
                        <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($dodata[0]['yp_id'])?$dodata[0]['yp_id']:$ypid?>">
                        <?php 
                        /*
                        Ritesh Rana
                        need to change when client will approved for archive functionality
                        */
                        ?>
                        <input type="hidden" name="care_home_id" id="care_home_id" value="<?=!empty($care_home_id)?$care_home_id:''?>">
                        <div class="pull-right for-tab">
                            <div class="btn-group">
                            <button type="submit" class="btn btn-default" name="submit_doform" id="submit_doform" value="submit" style="default">Update</button>
                            <a href="<?=base_url('DailyObservation/view/'.$dodata[0]['do_id'].'/'.$dodata[0]['yp_id'])?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To Daily Observation</a>
                        </div>
                    </div>
                </div>
            </div>
                   
              
        </div>
        
    </div>
    </form>      
</div>
        