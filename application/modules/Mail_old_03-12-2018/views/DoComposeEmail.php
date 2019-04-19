<!-- Example row of columns -->
<div id="page-wrapper" class="mail-module">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title email_title">
            <?=!empty($care_home_name_data)?$care_home_name_data:''?>
        </h1>
        <h1 class="page-title email_title">
                    <small>Name: </small> <span class="small-font"><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?></span><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small> Email:</small><span class="small-font"> <?=!empty($yp_mail_id)?$yp_mail_id:''?> </span>
        </h1>
    </div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
        <div id="main_div">
            <div class="" >
                <div class="whitebox p-15">
                    <form method="post" action="<?php echo base_url('Mail/sendEmail'); ?>" id="compose-form" autocomplete="off">
                        <div class="row">
                            <div class="col-lg-9 col-xs-12 col-sm-12 bd-mail-editor-postn">
                                <div class="form-group bd-mail-head bd-inbox">
                                    <ul >
                                        <li ><a href="<?php echo base_url('Mail/index/'.$ypid); ?>"><i class="bd-back-ico"></i><span><?php echo lang('mail_back'); ?></span></a></li>
                                        <li>
                                            <button type="submit" id="sentmail" name="sentmail" ><i class="bd-send-ico "></i><span><?php echo lang('mail_send_message'); ?></span></button>
                                        </li>
                                        <li>
                                            <button type="button" onclick="$('#upl').trigger('click');" ><i class="bd-attach-ico"></i><span><?php echo lang('mail_attach_file'); ?></span></button>
                                        </li>
                                        
                                    </ul>
                                </div>
                              <div class="row">
                                <div class="form-group col-lg-6 col-md-12 bd-form-group design-mrg-bottm-z">
                                    <label class="design-mrg-bottm"><?php echo lang('mail_from'); ?>:*</label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 bd-form-control design-mrg-bottm">
                                         <input type="hidden" name="ks_file_path" value="<?php echo isset($do_file_path) ? $do_file_path : ''; ?>">
                                        <input type="hidden" name="mailtype" value="<?php echo isset($mailtype) ? $mailtype : ''; ?>">
                                        <input type="hidden" name="uid" value="<?php echo isset($uid) ? $uid : ''; ?>">
                                        <input type="hidden" name="msg_no" value="<?php echo isset($emailData) ? $emailData[0]['msg_no'] : ''; ?>">
                                        <input type="hidden" name="from" required="" id="from" class="form-control" readonly value="<?php echo $fromMail; ?>">

                                        <input type="text" name="fromMail_data" required="" id="fromMail_data" class="form-control" readonly value="<?php echo $fromMail_data; ?>">

                                         <input type="hidden" class="ypid" name="ypid" value="<?php echo isset($ypid) ? $ypid : ''; ?>">
                                    </div>
                                    <div class="clearfix"></div>
									<label class="design-mrg-bottm"><?php echo lang('mail_to'); ?>:*</label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 bd-form-control design-mrg-bottm">
                                        <input name="to" value="<?php echo (isset($to)) ? $to : ''; ?>"  id="to" type="text" class="form-control" required="" placeholder="">
                                        <span id="email_error" class="error-danger" style="display: none;">Please enter a valid email address.</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group col-lg-6 col-md-12 bd-form-group design-mrg-bottm-z">
                                    <label class="design-mrg-bottm"><?php echo lang('mail_cc'); ?>:</label>
                                     <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 bd-form-control design-mrg-bottm">
                                        <input id="cc" value="<?php echo (isset($emailData) && $mailtype != 'forward') ? str_replace(",",";",$emailData[0]['cc_email']) : ''; ?><?php if($emailData[0]['cc_email'] && $mailtype != 'forward'){ echo ";"; } ?><?php echo (isset($defolt_cc)) ? $defolt_cc : ''; ?>"  name="cc" type="text" class="form-control" readonly placeholder="">
                                    </div>
                                    <div class="clearfix"></div>
									 <label class="design-mrg-bottm"><?php echo lang('mail_bcc'); ?>:</label>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 bd-form-control design-mrg-bottm">
                                        <input id="bcc" value="<?php echo (isset($emailData) && $mailtype != 'forward') ? $emailData[0]['bcc_email'] : ''; ?>" name="bcc" type="text" class="form-control" placeholder="">
                                        <span id="bcc_error" class="error-danger" style="display: none;">Please enter a valid email address.</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                 <div class="clearfix"></div>
                               
                                
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 bd-form-group">
                                    <label><?php echo lang('mail_subject'); ?>:*</label>
                                    <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12 bd-form-control">
                                        <input value="<?php echo isset($subject) ? $subject : ''; ?>" id="subject" required="" name="subject" type="text" class="form-control" placeholder="">
                                    </div>
                                    <div class="clearfix"></div>
                                </div> <div class="clearfix"></div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 bd-form-group bd-mail-editor">
                                    <textarea id="message"  name="message" class="form-control"><?php echo '<br/><br/><br/>' . $email_signature; ?><?php echo isset($defaultBody) ? $defaultBody : ''; ?>  </textarea>
                                    <div class="clearfix"></div>
                                </div> <div class="clearfix"></div>
                              </div>
                            </div>
                            <div class="col-lg-3 col-xs-12 col-sm-12">
                                <div class="">
                                    <div class="bd-mail-detail ">
                                        <div class="bd-sesrch-contact bd-search-head">
                                            <label><?php echo lang('mail_contacts'); ?>:</label>
                                            <div class="search-top">
                                                <div class="navbar-form row">
                                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                        <input type="text" placeholder="<?php echo lang('mail_search_contacts'); ?>" class="form-control col-lg-11" id='searchContact' name='searchContact' value="">
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" bd-select-box">
                                            <ul class="nav  " id="searchList">
                                                <?php
                                                if (count($contacts) > 0) {
                                                    foreach ($contacts as $key => $contactValue) {
                                                        $contactEmail = (isset($contactValue['email'])) ? $contactValue['email'] : $contactValue;
                                                        $contactName = (isset($contactValue['relationship'])) ? '<strong>' . $contactValue['relationship'] . '</strong>' : '';
                                                        ?><li>
                                                            <a class="cmail" id="contact-<?php echo $key; ?>" onclick="addselectedclass('contact-<?php echo $key; ?>','<?php echo addslashes($contactEmail)?>')" data-email="<?php echo addslashes($contactEmail)?>">
                                                                <?php echo $contactEmail.' ('.$contactName.')'; ?>
                                                            </a>
                                                        </li>

                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </ul>
                                        </div>
                                        <div class="bd-search-ftr">
                                            <button type="button" onclick="addtomail('to');" class="btn col-lg-4 col-md-4 col-sm-4"><?php echo lang('mail_to'); ?> <i class="fa fa-angle-double-right"></i></button>
                                            <button type="button" onclick="addtomail('bcc');" class="btn col-lg-8 col-md-8 col-sm-6"><?php echo lang('mail_bcc'); ?><i class="fa fa-angle-double-right"></i></button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <div class="bd-mail-detail form-group">
                                        <div class=" bd-search-head">
                                            <label><?php echo lang('mail_attachments'); ?>:</label>

                                        </div>
                                        <?php
                                        if (isset($mail_files) && count($mail_files) > 0) {
                                            $i = 15482564;
                                            foreach ($mail_files as $image) {
                                                if ($image['file_name_app'] == 0) {
                                                    $path = $image['file_path'];
                                                    $name = $image['file_name'];
                                                    $pathrel = $image['file_path_abs'];
                                                    $arr_list = explode('.', $name);
                                                    $arr = basename($name);
                                                    if (file_exists($path)) {
                                                        ?>
                                                        <div class="pad-10 text-left"> 
                                                            <a href='<?php echo base_url('Mail/download/' . $image['auto_id']); ?>' target="_blank">
                                                                <?php echo $name; ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="pad-10 text-center"> </div>
                                        <?php } ?>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                                        <div class="image_part">
                                            <label name="cost_files[]">
                                                <h1 style="top: -162px;">
                                                </h1>
                                                <input type="file" onchange="showimagepreview(this)" name="cost_files[]" style="display: none" id="upl" multiple />
                                            </label>
                                            <?php if (!empty($ypc_pdf_file)) { ?>
                                                <div class="eachImage eachImage_pdf ">
                                                    <span class="preview">
                                                        <div class="image_ext">
                                                            <img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png">
                                                            <p class="img_show"><?php echo "pdf"; ?></p>
                                                        </div>
                                                        </div>
                                                    <?php } ?>

                                        </div>

                                    </div>



                                </div>
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
</div>
</div>
