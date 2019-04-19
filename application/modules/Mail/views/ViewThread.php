
<style type="text/css">
    * {
     font-size: 16px !important;
}

html, body {
     color: black;
     font-family: 'Roboto Condensed', sans-serif !important;
     margin: 0px;
}

a:link, a:active {
     color: rgba(78, 96, 49, 0.85) !important;
     font-family: 'Roboto Condensed', sans-serif !important;
     text-decoration: none !important;
     font-size: 16px !important;
    
}

a:visited {
     color: rgba(78, 96, 49, 0.85) !important;
     text-decoration: none !important;
     font-family: 'Roboto Condensed', sans-serif !important;
     font-size: 16px !important;
}
.bd-inbox-elem .table .inbox-table-body a
{
     font-size: 16px !important;
}
.font-bold {
    font-weight: bold;
}
.uploadArea
{
      min-height: auto !important;
}
/*a:hover {
     color: rgba(78, 96, 49, 0.85) !important;
}*/
</style>
<div class="modal-dialog modal-lg" >
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?php echo isset($subject) ? $subject : ''; ?></div></h4>
        </div>
       <!-- <form id="viewEmailTemplate" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>  --> 
        <div class="modal-body modal-noborder">	
          <div class="row">
              <div class="col-lg-12 col-xs-12 col-sm-12">
                  <div class="form-group bd-mail-head bd-inbox">
                      <ul>

                          <li>
                              <a  href="<?php echo base_url('Mail/replyEmail/' . $uid); ?>" ><i class="bd-reply-ico"></i><span><?= lang('mail_reply') ?></span></a>
                          </li>
                          <li>
                              <a href="<?php echo base_url('Mail/replyEmailAll/' . $uid); ?>"  ><i class="bd-replyall-ico"></i><span><?= lang('mail_reply_all') ?></span></a>
                          </li>
                          <li>
                              <a href="<?php echo base_url('Mail/forwardEmail/' . $uid); ?>" ><i class="bd-forward-ico"></i><span><?= lang('mail_forward') ?></span></a>
                          </li>
                      </ul>
                  </div>
              </div>
            </div>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div>
                    <div class="whitebox">
                        <form method="post" action="<?php echo base_url('Mail/sendEmail'); ?>" id="compose-form">
                            <div class="row">
                                <div class="col-lg-12 col-xs-12 col-sm-12">
                                    <!--  <div class="form-group bd-mail-head">
                                        <ul>
                                            <li><a href="<?php echo base_url('Mail'); ?>"><i class="bd-back-ico"></i><span>Back</span></a></li>
                                            <li>
                                                <button type="submit" id="sentmail" name="sentmail" ><i class="bd-send-ico"></i><span>Send Message</span></button>
                                            </li>
                                            <li>
                                                <button type="button" onclick="$('#upl').trigger('click');" ><i class="bd-attach-ico"></i><span>Attach File</span></button>
                                            </li>
                                            <li>
                                                <button type="button" onclick="saveConcept();" ><i class="bd-save-ico"></i><span>Save Concept</span></button>
                                            </li>
                                            <li>
                                                <button type="button" onclick="signatureBox();" ><i class="bd-sign-ico"></i><span>Insert Signature</span></button>
                                            </li>
                                        </ul>
                                    </div> -->
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                                            <label class="col-lg-3 col-md-3 col-sm-2 col-xs-12"><?= lang('mail_from') ?>:</label>                                
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo (isset($emailData)) ? $emailData[0]['from_mail'] : ''; ?></span>
                <!--                                    <select class="form-control">
                                                        <option>Sharif Hussainali</option>
                                                        <option></option>
                                                    </select>-->
                                                   <!--  <input type="hidden" name="mailtype" value="<?php echo isset($mailtype) ? $mailtype : ''; ?>">
                                                    <input type="hidden" name="uid" value="<?php echo isset($uid) ? $uid : ''; ?>">
                                                    <input type="hidden" name="msg_no" value="<?php echo isset($emailData) ? $emailData[0]['msg_no'] : ''; ?>">
                                                    <input type="text" name="from" required="" id="from" class="form-control" readonly="" value="<?php echo $fromMail; ?>"> -->
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                                            <label class="col-lg-3 col-md-3 col-sm-2 col-xs-12"><?= lang('mail_cc') ?>:</label>
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo isset($emailData) ? $emailData[0]['cc_email'] : ''; ?>                                	</span>
                                           <!-- <input id="cc" value="<?php echo isset($emailData) ? $emailData[0]['cc_email'] : ''; ?>"  name="cc" type="text" class="form-control" placeholder=""> --> 
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                            <label class="col-lg-3 col-md-3 col-sm-2 col-xs-12"><?= lang('mail_to') ?>:</label>
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo (isset($to)) ? $to : ''; ?></span>
                                            <!-- <input name="to" value="<?php echo (isset($to)) ? $to : ''; ?>"  id="to" type="text" class="form-control" placeholder=""> -->
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                            <label  class="col-lg-3 col-md-3 col-sm-2 col-xs-12"><?= lang('mail_bcc') ?>:</label>
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo isset($emailData) ? $emailData[0]['bcc_email'] : ''; ?></span>
                                                <!-- <input id="bcc" value="<?php echo isset($emailData) ? $emailData[0]['bcc_email'] : ''; ?>" name="bcc" type="text" class="form-control" placeholder=""> --> 
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                                            <label class="col-lg-3 col-md-3 col-sm-2 col-xs-12"><?= lang('mail_subject') ?>:</label>
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo isset($subject) ? $subject : ''; ?></span>
                                            <!-- <input value="<?php //echo isset($subject) ? $subject : '';                ?>" id="subject" required="" name="subject" type="text" class="form-control" placeholder=""> -->
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12  bd-rsp-image
                                         <?php echo (strlen($defaultBody) > 50) ? 'bd-desc-minheight' : '' ?>">
                                        <div class="col-xs-12">  <p ><?php echo isset($defaultBody) ? html_entity_decode($defaultBody) : ''; ?></p></div>

                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                            </div>
                            <?php
                            if (count($mail_files) > 0) {
                                ?>
                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <div class="bd-mail-detail form-group uploadArea bd-dragimage border-non">

                                        <?php
                                       
                                        if (isset($mail_files) && count($mail_files) > 0) {
                                            $i = 15482564;
                                            foreach ($mail_files as $image) {
                                                if ($image['file_name_app'] == 0) {
                                                    $path = htmlentities($image['file_path']);
                                                    $name = $image['file_name'];
                                                    $pathrel = htmlentities($image['file_path_abs']);
                                                    $arr_list = explode('.', $name);
                                                    if (isset($arr_list[1])) {
                                                        $arr = $arr_list[1];
                                                    }

                                                    if (file_exists($path)) {
                                                        ?>
                                                        
                                                        <div id="img_<?php echo $image['auto_id']; ?>" class="eachImage">
                    
                                                            <span id="<?php echo $i; ?>" class="preview">
                                                                <a href='<?php echo base_url('Mail/download/' . $image['auto_id']); ?>' target="_blank">


                                                                    <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>                  
                                                                        <img src="<?= $pathrel; ?>"  width="75"/>        <?php } else { ?>
                                                                        <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/><p class="img_show"><?php echo $arr; ?></p></div>
                                                                    <?php } ?>
                                                                </a>
                                                                <p class="img_name"><?php echo $name; ?></p>
                                                                <span class="overlay" style="display: none;">
                                                                    <span class="updone">100%</span></span>
                                                                <input type="hidden" value="<?php echo $path; ?>" name="fileToUploadext[]">
                                                            </span>
                                                        </div>

                                                    <?php } ?>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </div>

                                </div>
                            <?php } ?>
                                </div>
                                    </div>

                        </form>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
          </div>
        </div>
        <!-- </form>  -->  
    </div>
    <div class="clearfix"></div>
</div>

