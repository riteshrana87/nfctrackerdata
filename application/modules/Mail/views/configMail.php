<div class="clearfix"></div>
<?php echo $this->session->flashdata('msg'); ?>
<div class="clearfix"></div>
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title"><?php echo $headerLbl; ?></h1>
        <div class="row">
            
               <div class="modal-dialog ">
    <form role="form" name="frm_addemail" id="frm_addemail" enctype="multipart/form-data" method="post" data-parsley-validate >

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">
                    <div class="modelTitle"><?php echo $headerLbl; ?></div>
                </h4>

            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo $emailLbl; ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input readonly="" type="email" name="email" value="<?php
                            if (isset($emailConfigData)) {
                                echo $emailConfigData['email_id'];
                            }else{
                                //echo $yp_email;
                                echo "cm.superadmin@newforestcare.co.uk";
                            }
                            ?>" class="form-control" required>

                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo $passLbl; ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input type="password" name="password" value="<?php
                            if (isset($emailConfigData)) {
                                //echo $emailConfigData['email_pass'];
                            }else{
                                echo "efb34368D6";
                            }
                            ?>" class="form-control" required>

                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo lang('mail_host'); ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input readonly="" type="text" name="email_server" id="email_server" value="outlook.office365.com" class="form-control" required>

                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label"><?php echo lang('mail_port'); ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input readonly="" type="text" name="email_port" id="email_port" value="993" class="form-control" required>

                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12 col-md-12">
                        <label class="  control-label">Select Encryption Method<span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <input readonly="" type="text" name="email_encryption" id="email_encryption" value="TLS" class="form-control" required>

                           <!--  <select readonly="" class="form-control" name="email_encryption" id="email_encryption" required>
                                <option value="" >Select Encryption Method</option>
                                <option value="SSL" SELECTED="<?php
                                if (isset($emailConfigData)) {
                                    echo $emailConfigData['email_encryption'];
                                }
                                ?>">SSL</option>
                                <option value="TLS" SELECTED="<?php
                                if (isset($emailConfigData)) {
                                    echo $emailConfigData['email_encryption'];
                                }
                                ?>">TLS</option>
                            </select> -->

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-md-12 row">
                            <label class="control-label"><?php echo lang('smtp_host'); ?> <span class="viewtimehide">*</span></label>

                            <div class="form-group">
                                <p><?php echo lang('mail_ssl_note');?><p>
                                    <input readonly="" type="text" name="email_smtp" id="email_smtp" value="smtp.office365.com" class="form-control" required>

                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-md-12 row">
                            <label class="  control-label"><?php echo lang('smtp_port'); ?> <span class="viewtimehide">*</span></label>

                            <div class="form-group">

                                <input readonly="" type="text" name="email_smtp_port" id="email_smtp_port" value="587" class="form-control" required>

                                <input type="hidden" name="yp_id" id="yp_id" value="<?php echo $ypid; ?>">

                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="clearfix"></div>
            <br/>

            <div class="modal-footer">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" id="camp_submit_btn" name="action" value="<?php echo $btnLbl; ?>">
                </div>
            </div>

            <div class="clearfix"></div>
            <br/>
        </div>
    </form>
</div>

            
        </div>
    </div>
</div>
