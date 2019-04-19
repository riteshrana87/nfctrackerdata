<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">My Profile</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="<?php echo base_url(); ?>MyProfile/updateProfile" name="update_myprofile" id="update_myprofile"  data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label"><?= $this->lang->line('firstname'); ?> :</label>
                                    <label class="control-label"><?php
                                        if ($profile_data['firstname'] != '') {
                                            echo htmlentities($profile_data['firstname']);
                                        }
                                        ?>
                                    </label>
                                    
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label"><?= $this->lang->line('lastname') ?> :</label>
                                    <label class="control-label"><?php
                                           if ($profile_data['lastname'] != '') {
                                               echo htmlentities($profile_data['lastname']);
                                           }
                                    ?>
                                    </label>
                                    
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label"><?= $this->lang->line('emails') ?> :</label>
                                    <label class="control-label"><?php
                                           if ($profile_data['email'] != '') {
                                               echo $profile_data['email'];
                                           }
                                    ?>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label">Mobile Number :</label>
                                    <label class="control-label"><?php
                                           if ($profile_data['mobile_number'] != '') {
                                               echo $profile_data['mobile_number'];
                                           }
                                    ?>
                                    </label>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label">Address :</label>
                                    <label class="control-label"><?php
                                           if ($profile_data['address'] != '') {
                                               echo htmlentities($profile_data['address']);
                                           }
                                    ?>
                                    </label>
                                   
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label"><?= $this->lang->line('user_status') ?> :</label>
                                    <label class="control-label"><?php
                                           if ($profile_data['status'] != '') {
                                               echo htmlentities($profile_data['status']);
                                           }
                                    ?>
                                    <span id="STATUS_error"></span>
                                </div>
                            </div>


                            <div class="clearfix"> </div>

                            <div class="modal-footer">
                                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                                <a href="<?=base_url()?>">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
