<script>
    var baseurl = '<?php echo base_url(); ?>';
</script>
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">Set Logout Time</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="<?php echo base_url(); ?>SetLogoutTime/insert_theme_data" name="update_myprofile" id="update_myprofile"  data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate class="form-horizontal">
                           <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label col-sm-4 col-md-3">Set Logout Time:</label>
                                    <div class="col-sm-4 col-md-3">
                                <input type="number" placeholder="Minutes" name="set_logout_time" min="0" max="999" minlength="1" maxlength="3" value="<?= !empty($information[0]['set_time']) ? $information[0]['set_time'] : '' ?>" required="" class="form-control"> 
                                </div>
                                </div>
                            </div>

                            <div class="clearfix"> </div>
                            <div class="modal-footer">
                                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                                 <input type="hidden" name="user_set_logout_id" value="<?= !empty($information[0]['user_set_logout_id']) ? $information[0]['user_set_logout_id'] : '' ?>">
                                
                                <input type="submit" value="Submit" id="submit_btn" name="submit_btn" class="btn btn-default">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
