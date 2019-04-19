<div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="<?= base_url('EventPlanner/insert') ?>" method="post" id="EventPlanner" name="EventPlanner" data-parsley-validate enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <h4><b>VIEW MEDICAL APPOINTMENT</b></h4>
            </div>
            <div class="modal-body">
                <!-- Main content -->

                
                            <div class="form-group">
                                <label> Professionals :</label>
                                 <p><?=!empty($mp_data[0]['mp_name'])?html_entity_decode($mp_data[0]['mp_name']):lang('NA')?></p>
                                
                            </div>
                        <div class="form-group">
                            <label>Date of Appointment :</label>
                               <p><?=!empty($mp_data[0]['appointment_date'])?configDateTime($mp_data[0]['appointment_date']):lang('NA')?> </p>
                        </div>
                        <div class="form-group">
                            <label>TIME : </label>
                            <p> <?=!empty($mp_data[0]['appointment_time'])?timeformat($mp_data[0]['appointment_time']):v?></p>
                            
                        </div>
                        <div class="form-group word-wrap">
                            <label>Comments :</label>
                           <p><?=!empty($mp_data[0]['comments'])?html_entity_decode($mp_data[0]['comments']):lang('NA')?></p>
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
