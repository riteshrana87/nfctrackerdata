<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Medical Information <small>New Forest Care</small>
        </h1>
        
        <div class="row">
            <form action="<?= base_url('Medical/insert_appointment') ?>" method="post" id="docsform" name="docsform" data-parsley-validate enctype="multipart/form-data">
                <div class="panel panel-default tile tile-profile m-t-10">
                            <div class="panel-body min-h-310">
                                <h2>MEDICAL APPOINTMENT</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> Professionals :</label>
                                             <?=!empty($mp_data[0]['mp_name'])?html_entity_decode($mp_data[0]['mp_name']):''?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Appointment :</label>
                                               <?=!empty($mp_data[0]['appointment_date'])?configDateTime($mp_data[0]['appointment_date']):''?> 
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>TIME : </label>
                                             <?=!empty($mp_data[0]['appointment_time'])?timeformat($mp_data[0]['appointment_time']):''?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Comments :</label>
                                           <?=!empty($mp_data[0]['comments'])?html_entity_decode($mp_data[0]['comments']):''?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="<?=base_url('Admin/MedicalView/appointment/'.$mp_data[0]['yp_id'])?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To Appointment</a>
                                    </div>
                                </div>
                            </div>
                        </div>

            </form>        
        </div>

    </div>
</div>

