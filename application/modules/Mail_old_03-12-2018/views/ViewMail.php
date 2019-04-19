<div id="page-wrapper" class="mail-module">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg'); 
        }
        ?>
     <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title email_title">
            <?=!empty($care_home_name_data)?$care_home_name_data:''?>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                </div>
            </div>            
        </h1>
        <h1 class="page-title email_title">
                    <small>Name: </small> <span class="small-font"><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?></span><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small> Email:</small><span class="small-font"> <?=!empty($yp_mail_id)?$yp_mail_id:''?> </span>
        </h1>
        </div>
        <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="main_div">
                <div class="whitebox p-15">
    <div class="row">
              <div class="col-lg-12 col-xs-12 col-sm-12">
                  <div class="form-group bd-mail-head bd-inbox">
                      <ul>
                        <li ><a href="<?php echo base_url('Mail/index/'.$ypid.'?boxtype='.$emailData[0]['boxtype']); ?>">
                            <i class="bd-back-ico"></i><span><?php echo lang('mail_back'); ?></span></a></li>
                        <li>
                          <li>
                              <a  href="<?php echo base_url('Mail/replyEmail/' . $uid .'/'. $emailData[0]['boxtype'].'/'.$ypid); ?>" ><i class="bd-reply-ico"></i><span><?= lang('mail_reply') ?></span></a>
                          </li>
                          <li>
                              <a href="<?php echo base_url('Mail/replyEmailAll/' . $uid .'/'. $emailData[0]['boxtype'].'/'.$ypid); ?>"  ><i class="bd-replyall-ico"></i><span><?= lang('mail_reply_all') ?></span></a>
                          </li>
                          <li>
                              <a href="<?php echo base_url('Mail/forwardEmail/' . $uid .'/'. $emailData[0]['boxtype'].'/'.$ypid); ?>" ><i class="bd-forward-ico"></i><span><?= lang('mail_forward') ?></span></a>
                          </li>
                      </ul>
                      <span style="float:right"><?= date("d/m/Y H:i",strtotime($emailData[0]['send_date'])) ?></span>
                  </div>
              </div>
            </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 bd-inbox-elem form-group">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div>
                    <div class="whitebox p-15">
                        <form method="post" action="<?php echo base_url('Mail/sendEmail'); ?>" id="compose-form" class="for-lbl">
                            <div class="row">
                                <div class="col-lg-12 col-xs-12 col-sm-12">
                                   <div class="row">
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                                          <div class="row">
                                            <label class="col-lg-3 col-md-3 col-sm-2 col-xs-12 design-mrg-bottm"><?= lang('mail_from') ?>:</label>  
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12 design-mrg-bottm">
                                                <span>
                                    <a href="javascript:void(0)" title="<?= (isset($emailData)) ? $fromMail_data: '' ?>">

                                                    <?php echo (isset($emailData)) ? strlen($fromMail_data) > 50 ? htmlentities(substr($fromMail_data,0,50))."..." : htmlentities($fromMail_data)  : '';
                                                    ?>
                                                </a></span>
                                        </div>
											<label class="col-lg-3 col-md-3 col-sm-2 col-xs-12 design-mrg-bottm"><?= lang('mail_to') ?>:</label>
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12 design-mrg-bottm">
                                                <span>
                                                    <a href="javascript:void(0)" title="<?= (isset($to)) ? $to : '' ?>">
                                                        <?php echo (isset($to)) ? strlen($to) > 50 ? substr($to,0,50)."..." : $to : ''; ?>
                                                     </a>
                                                </span>
                                            </div>
										  </div>
										</div>
										<div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                                          <div class="row">
										    <label class="col-lg-3 col-md-3 col-sm-2 col-xs-12 design-mrg-bottm"><?= lang('mail_cc') ?>:</label>
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12 design-mrg-bottm">
                                                <span>
                                                    <a href="javascript:void(0)" title="<?= (isset($emailData)) ? $emailData[0]['cc_email'] : '' ?>">
                                                        <?php echo (isset($emailData)) ? strlen($emailData[0]['cc_email']) > 50 ? substr($emailData[0]['cc_email'],0,50)."..." : $emailData[0]['cc_email']  : ''; ?>
                                                     </a></span>
                                            </div>
											
											<?php 
                                             if ($emailData[0]['boxtype'] == '[Gmail]/Sent Mail' || $emailData[0]['boxtype'] == 'Sent' || $emailData[0]['boxtype'] == 'Send' || $emailData[0]['boxtype'] == 'Sent Items') { ?>

                                            <label  class="col-lg-3 col-md-3 col-sm-2 col-xs-12 design-mrg-bottm"><?= lang('mail_bcc') ?>:</label>
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12 design-mrg-bottm">
                                                <span>
                                                    <a href="javascript:void(0)" title="<?= (isset($emailData)) ? $emailData[0]['bcc_email'] : '' ?>">
                                                        <?php echo (isset($emailData)) ? strlen($emailData[0]['bcc_email']) > 50 ? substr($emailData[0]['bcc_email'],0,50)."..." : $emailData[0]['bcc_email'] : ''; ?>
                                                    </a>
                                                </span>
                                            </div>

                                        <?php } ?>
											
                                          </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                     
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                                          <div class="row">
                                            <label class="col-lg-3 col-md-3 col-sm-2 col-xs-12"><?= lang('mail_subject') ?>:</label>
                                            <div class="col-lg-9 col-md-9 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo isset($subject) ? $subject : ''; ?></span>
                                            </div>
                                          </div>
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
                                                                <a href='<?php echo base_url('CommonController/downloadFile/?file_name=' . urlencode(base64_encode($pathrel))) ?>' target="_blank">


                                                                    <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>                  
                                                                        <img src="<?= $pathrel; ?>"  width="75"/>        
                                                                        <?php } else { ?>
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
                                                
                                                ?>
                                                <?php
                                            }
                                        }exit;
                                        ?>

                                    </div>

                                </div>
                            <?php } ?>
                        
                        </form>
                    </div>
                </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
        </div>
    </div>
</div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
            
        </div>
        
    </div>
</div>

