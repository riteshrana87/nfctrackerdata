<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Medical Information <small>New Forest Care</small>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
           
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
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>COMMENT : </label>
                                             <?=!empty($mp_data[0]['comments'])?$mp_data[0]['comments']:''?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                 	
									<div class="col-lg-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Update / Comment</h2>
                                <?php if(!empty($comments)){ 
                                    foreach ($comments as $comments_data) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
                                        <?php echo $comments_data['md_comment']?>
                                            </p>
                                            <p class="date"><small><?php echo $comments_data['create_name'] ?>:   <?php echo configDateTime($comments_data['created_date']) ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                                <?php } }?>   
                            <form data-parsley-validate="true" action="<?=base_url('Medical/add_commnts')?>" method="post">
                            <input type="hidden" name="md_appoint_id" value="<?=$md_appoint_id?>">
                            <input type="hidden" name="yp_id" value="<?=$ypid?>">
                             
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>Please include Chronology, Evaluation and Outcome</h2>
                                    <textarea class="form-control" name="md_comment" placeholder="Please include Chronology, Evaluation and Outcome" id="md_comment" required=""></textarea>
                                </div>
                            </div>
                        </div>
                   
                            
                            <button type="submit" class="btn btn-default">
                               Submit
                            </button>
                            

                        </form>
                            </div>
                        </div>
                    </div>
                                </div>
								
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="<?=base_url('Medical/appointment/'.$mp_data[0]['yp_id'])?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To Appointment</a>
                                    </div>
                                </div>
                            </div>
                        </div>
      
        </div>

    </div>
</div>

