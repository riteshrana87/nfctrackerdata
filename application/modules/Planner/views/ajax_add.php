<div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?= base_url('EventPlanner/insert') ?>" method="post" id="EventPlanner" name="EventPlanner" data-parsley-validate enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <h4><b><?=lang('set_time_shedule')?></b></h4>
            </div>
            <div class="modal-body">
                <!-- Main content -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-primary">
                            <?= isset($validation) ? $validation : ''; ?>

                            <div class="box-body">
                                <div class="form-group clearfix ">
                                    <label for="name">Date :</label>
                                    <div id="display_start_time">
                                        <div class="input-group input-append date" id="event_date">
                                                <input value="<?=!empty($eventData[0]['event_date'])?configDateTime($eventData[0]['event_date']):''?>" class="form-control" type="text" required="true" value="" name="event_date" id="event_date" readonly="" data-parsley-errors-container="#errors-container-date" >
                                                 <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <span id="errors-container-date"></span>
                                    </div>
                                </div>
                                <div class="form-group clearfix ">
                                    <label for="name">Time : </label>
                                   <div id="display_end_time">
                                       <div class="input-group date" id="event_time">
                                                 <input value ="<?=!empty($eventData[0]['event_time'])?timeformat($eventData[0]['event_time']):''?>" class="form-control" type="text" name="event_time"  required="true"  data-parsley-errors-container="#errors-container-time" >
                                                <span class="input-group-addon appointment add-on"><i class="fa fa-clock-o"></i></span>
                                            </div>
                                            <span id="errors-container-time"></span>                                            
                                   </div>

                                </div>
                                <div class="form-group">
                                    <label>Title</label>
                                    <input name="title" rows="5" required="true"  class="form-control tinyeditor" value="<?=!empty($eventData[0]['title'])?$eventData[0]['title']:''?>">
                                </div> 
                                <div class="form-group">
                                    <label>Comment</label>
                                    <textarea name="comment" rows="5" class="form-control tinyeditor"><?=!empty($eventData[0]['comment'])?$eventData[0]['comment']:''?></textarea>
                                </div>  
                                   
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
               <!-- /.row -->
            </div>
            <div class="modal-footer">
                <div class="box-footer text-center">
                     <input type="hidden" name="date" id="date" value="<?=!empty($eventData[0]['date'])?$eventData[0]['date']:''?>">
                     <input type="hidden" name="event_id" id="event_id" value="<?=!empty($eventData[0]['event_id'])?$eventData[0]['event_id']:''?>">
                     <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($ypid)?$ypid:''?>">
                     <input type="hidden" name="start_time" id="start_time" value="<?=!empty($eventData[0]['start_time'])?$eventData[0]['start_time']:''?>">
                     <input type="hidden" name="end_time" id="end_time" value="<?=!empty($eventData[0]['end_time'])?$eventData[0]['end_time']:''?>">
                     <input type="hidden" name="total_minute" id="total_minute" value="">
                     <input type="hidden" name="total_duration" id="total_duration" value="">
                    <input class="btn btn-primary" id="submitButton"  type="submit"
                           value="<?= lang ('submit') ?>"/>
                    <input type="button" data-dismiss="modal" aria-label="Close" class="btn btn-primary" name="cancel"
                     id="cancel" value="Cancel">
                     <?php if(checkPermission('EventPlanner','delete')){ ?>
                     <?php if(!empty($eventData)) { ?>
                       <input type="button" data-dismiss="modal" onclick="return deletepopup(<?=!empty($eventData[0]['event_id'])?$eventData[0]['event_id']:''?>)" aria-label="Close" class="btn btn-primary" name="cancel"
                             id="delete" value="<?= lang ('delete') ?>">
                      <?php } } ?>
                </div>
            </div>
        </form>
    </div>
    </div>
