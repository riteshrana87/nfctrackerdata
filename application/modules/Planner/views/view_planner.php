<div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?= base_url('EventPlanner/insert') ?>" method="post" id="EventPlanner" name="EventPlanner" data-parsley-validate enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <h4><b>VIEW APPOINTMENT / EVENT</b></h4>
            </div>
            <div class="modal-body">
                <!-- Main content -->

                
                    
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Date of Appointment / Event :</label>
                                               <p><?=!empty($eventData[0]['appointment_date'])?configDateTime($eventData[0]['appointment_date']):lang('NA')?> </p>
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>TIME : </label>
                                             <p><?=!empty($eventData[0]['appointment_time'])?timeformat($eventData[0]['appointment_time']):lang('NA')?></p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Type of Appointment / Event :</label>
                                          <p> <?=!empty($eventData[0]['appointment_type'])?html_entity_decode($eventData[0]['appointment_type']):lang('NA')?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Comments :</label>
                                          <p> <?=!empty($eventData[0]['comments'])?html_entity_decode($eventData[0]['comments']):lang('NA')?></p>
                                        </div>
                                    </div>
                                </div>
                 
                
               <!-- /.row -->
            </div>
            <div class="modal-footer">
                <div class="box-footer text-center">
                    
                    <input type="button" data-dismiss="modal" aria-label="Close" class="btn btn-primary" name="cancel"
                     id="cancel" value="Cancel">
                     
                </div>
            </div>
        </form>
    </div>
    </div>
