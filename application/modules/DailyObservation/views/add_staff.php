<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Daily Observation <small>New Forest Care</small>
          
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
        <div class="row">
            <form action="<?=base_url('DailyObservation/insert_staff')?>" method="post" id="doform" name="doform" data-parsley-validate enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <div class="">
                            <h1 class="page-title">ADD STAFF</h1>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                    <h2>Select Staff <span class="astrick">*</span></h2>
                                    <select name="staff[]" multiple id="staff" class="form-control chosen-select" required="" placeholder="Placeholder goes here">
                                        <?php if(!empty($userdata)) { 
                                            foreach ($userdata as $row) {
                                           ?>
                                           <option <?=($this->session->userdata('LOGGED_IN')['ID'] == $row['login_id'])?'selected="selected"':''?> value="<?=!empty($row['login_id'])?$row['login_id']:''?>"><?=!empty($row['username'])?$row['username']:''?></option>
                                           <?php
                                        }}?>
                                    </select>
                            </div>
                        </div>
                    </div>
                       
                     <div class="col-sm-12">
                            
                            <input type="hidden" name="do_id" id="do_id" value="<?=!empty($do_id)?$do_id:''?>">
                            <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($ypid)?$ypid:''?>">
                        <div class="pull-right btn-section">
                                 <div class="btn-group capsBtn">
   
                            <button type="submit" class="btn btn-default" name="submit_doform" id="submit_doform" value="submit" style="default">Add Staff</button>
                            <a href="<?=base_url('DailyObservation/view/'.$do_id.'/'.$ypid)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To Daily Observation</a>
                      </div>
                  </div>
                    </div>
                   
                    
            </form>        
        </div>
        
    </div>
</div>
        