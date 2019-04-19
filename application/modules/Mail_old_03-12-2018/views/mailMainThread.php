<div class="whitebox p-15">
    <div class="row">
        <div class="col-lg-9 col-xs-12 col-sm-12 col-md-12">
            <div class="form-group bd-mail-head bd-inbox">
                <ul>
                    <li id="refereshCode">
                        <button type="button" data-boxtype="INBOX" id="refreshBn"><i class=" bd-refresh-ico"></i><span><?= lang('mail_refresh') ?></span></button>
                    </li>
                    <li id="ComposeMail">
                        <a href="<?php echo base_url('Mail/ComposeMail/'.$ypid); ?>"><i class="bd-compose-ico"></i><span><?= lang('mail_compose') ?></span></a>
                    </li>
                    
                    <li id="replyEmail">
                        <button type="button" onclick="replyEmail(<?php echo $ypid;?>);"  ><i class="bd-reply-ico"></i><span><?= lang('mail_reply') ?></span></button>
                    </li>
                    
                    <li id="replyAll">
                        <button type="button" onclick="replyAll(<?php echo $ypid;?>);" ><i class="bd-replyall-ico"></i><span><?= lang('mail_reply_all') ?></span></button>    
                    </li>
                    <li id="forwardEmail">
                        <button type="button" onclick="forwardEmail(<?php echo $ypid;?>);"><i class="bd-forward-ico"></i><span><?= lang('mail_forward') ?></span></button>
                    </li>
                    <?php  if (checkPermission('Mail', 'delete')) { ?>
                    <li id="trashMail">
                        <button type="button" title="Delete"><i class="bd-remove-ico"></i><span><?= lang('mail_remove') ?></span></button>
                    </li>
                    <?php }?>
                   
                </ul>
            </div>
        </div>
        <div class="col-lg-3 col-xs-12 col-sm-12 col-sm-12">
            <div class="form-group">
                <div class="bd-mail-detail border-0">
                    <div class="bd-sesrch-contact ">
                        <div class="search-top">
                            <div class="input-group">
                                <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="Search for..." aria-controls="example1" placeholder="Search" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                                <span class="input-group-btn">
                                    <button onclick="data_search('changesearch')" class="btn btn-default"  title="Search"><?= $this->lang->line('common_search_title') ?> <i class="fa fa-search fa-x"></i></button>
                                    <button class="btn btn-default howler flt" onclick="reset_data()" title="Reset"><?= $this->lang->line('common_reset_title') ?><i class="fa fa-refresh fa-x"></i></button> 
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 bd-inbox-elem form-group" id="common_div">
            <?php echo $this->load->view('MailAjaxList'); ?>
        </div>
    </div>
</div>