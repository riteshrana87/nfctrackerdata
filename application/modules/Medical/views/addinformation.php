<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var Miid = '<?= !empty($edit_record[0]['mi_id']) ? $edit_record[0]['mi_id'] : '' ?>';
</script>
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Medical Information <small>New Forest Care</small>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            <form action="<?= base_url('Medical/insert') ?>" method="post" id="docsform" name="docsform" data-parsley-validate enctype="multipart/form-data">
                <div class="panel panel-default tile tile-profile m-t-10">
                            <div class="panel-body min-h-310">
                                <h2>EDIT OVERVIEW</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Medical Number Card No</label>
                                            <input type="text" name="medical_number" value="<?= !empty($edit_record[0]['medical_number']) ? $edit_record[0]['medical_number'] : $medicalNumberId ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date Received</label>
                                            <div class="input-group input-append date medi_adddate">
                                                <input type="text" required="true" value="<?= (!empty($edit_record[0]['date_received']) && $edit_record[0]['date_received'] !='0000-00-00') ? configDateTime($edit_record[0]['date_received']) : '' ?>" name="date_received" id="create_date" readonly="" class="form-control">
                                                 <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="yp_id" value="<?php echo $ypid;?>" id="" >
                                        <input type="hidden" name="mi_id" value="<?= !empty($edit_record[0]['mi_id']) ? $edit_record[0]['mi_id'] : '' ?>" id="" >
                                        
                                        
                                        <input type="submit" class="btn btn-default hidden-xs" name="submit_medform" id="submit_medform" value="Add Medical Information" />
					<div class="pull-right btn-section">
						<div class="btn-group">
					<button type="submit" class="btn btn-default visible-xs" name="submit_medform" id="submit_medform" value="submit" style="default">Add Medical Information</button>
                                        <a href="<?=base_url('Medical/index/'.$ypid)?>" class="pull-right btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">Back To Meds</a>
                                    </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

            </form>        
        </div>

    </div>
</div>
