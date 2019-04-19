<style>
.bd-inbox-elem .table .inbox-table-body a , #leftbar .nav > li > a
{
  font-size:16px !important;
}
.bd-inbox-elem .inbox-table-body li.ActiveTr a , .bd-inbox-elem .inbox-table-body li.ActiveTr a , .bd-inbox-elem .inbox-table-body li.ActiveTr a
{
  font-family: 'Roboto Condensed', sans-serif !important;
  font-size:16px !important;
  color: #fff !important;
}
.bd-inbox-elem .table .inbox-table-head a {
    color: #fff !important;
    font-size: 16px !important;
}
.table-mail-resp a:hover
{
    color: #fff !important;
}
.tabl-style a
{
    cursor: pointer !important;
}
</style>

                    

<?php
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div id="mailTable" class="main-design">
    <div class="table-responsive" >
        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>"/>
        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>"/>
        <input type="hidden" id="ypid"  value="<?php if (isset($ypid)) echo $ypid; ?>"/>
        <input type="hidden" id="box_types"  value="<?php if (isset($box_types)) echo $box_types; ?>"/>
        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$ypid.'/'.$uri_segment:$ypid.'/0'?>"> 
        

        <div class="table table-bordered ">
            <ul class="inbox-table-head table-li-design">
                <li class="bd-select col-sm-2 col-md-2 sorting_asc"><input type="checkbox" class="checkbox" id="selectall"></li>
            <!--    <li class="bd-in-stats"><a href="javascript:;"></a></li> --> <!-- this is not required and unnecessary li, commented by Jignesh shrimali on 26th Oct 2018 21:00 -->
                <li  <?php				
                if (isset($sortfield) && $sortfield == 'mail_subject') {
                    if ($sortby == 'asc') {
                        echo "class = 'col-sm-2 col-md-4 sorting_desc'";
                    } else {
                        echo "class = ' col-sm-2 col-md-4 sorting_asc'";
                    }
                } else {
                    echo "class = ' col-sm-2 col-md-4 sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('mail_subject', '<?php echo $sorttypepass; ?>')" ><a href="javascript:;"> <?= lang('mail_subject') ?></a></li>
				<?php if($box_types == "INBOX" || $box_types == "inbox" || $box_types == "Inbox" ){
				
				
				?>
                <li  <?php
                if (isset($sortfield) && $sortfield == 'from_mail') {
                    if ($sortby == 'asc') {
                        echo "class = 'col-sm-2 col-md-5 sorting_desc'";
                    } else {
                        echo "class = 'col-sm-2 col-md-5 sorting_asc'";
                    }
                } else {
                    echo "class = 'col-sm-2 col-md-5 sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('from_mail', '<?php echo $sorttypepass; ?>')"><a href="javascript:;">From</a></li>
				<?php }else{ ?>
				 <li  <?php
                if (isset($sortfield) && $sortfield == 'to_mail') {
                    if ($sortby == 'asc') {
                        echo "class = 'col-sm-2 col-md-5 sorting_desc'";
                    } else {
                        echo "class = 'col-sm-2 col-md-5 sorting_asc'";
                    }
                } else {
                    echo "class = 'col-sm-2 col-md-5 sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('to_mail', '<?php echo $sorttypepass; ?>')"><a href="javascript:;">To</a></li>
				<?php } ?>
                <li  <?php
                if (isset($sortfield) && $sortfield == 'send_date') {
                    if ($sortby == 'asc') {
                        echo "class = 'col-sm-2 col-md-2 sorting_desc'";
                    } else {
                        echo "class = 'col-sm-2 col-md-2 sorting_asc'";
                    }
                } else {
                    echo "class = 'col-sm-2 col-md-2 sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('send_date', '<?php echo $sorttypepass; ?>')"><a href="javascript:;"> <?= lang('mail_datetime') ?></a> </li>
               
                <li class="bd-in-attach attach_icon"><a href="javascript:;"><i class="bd-attach-ico"></i></a></li>


            </ul>
            <div class="table">
                <div class="clearfix"></div>
                <ul class="inbox-table-body">
                    <?php 
                    if (count($mail_data) > 0) { ?>
                        <?php
                        foreach ($mail_data as $mail) {
                        //pr($mail);
						//die;
                            $flagReply = strpos($mail['mail_subject'], 'RE:');
                            ?>


                            <li class="mail-tr <?php echo ($mail['is_flagged'] == 1) ? ' bd-in-mark' : ''; ?> "  data-forward="<?php echo base_url('Mail/forwardEmail/' . $mail['uid'].'/'.$mail['boxtype']); ?>" data-reply="<?php echo base_url('Mail/replyEmail/' . $mail['uid'].'/'.$mail['boxtype']); ?>" data-replyall="<?php echo base_url('Mail/replyEmailAll/' . $mail['uid'].'/'.$mail['boxtype']); ?>">
                                <div class="bd-select tabl-style"><input type="checkbox" name="checkedIds[]" id="<?php echo $mail['uid']; ?>" class="checkbox-inline actioncheckbx" ></div>
                           <!--     <div class="bd-in-stats tabl-style"><?php // echo ($flagReply !== false ) ? '<a href="javascript:;"><i class="bs-share-ico"></i></a>' : '<a href="javascript:;"></a>'; ?></div> --> <!-- this is not required and unnecessary li, commented by Jignesh shrimali on 26th Oct 2018 21:00 -->
                                <div class="col-sm-2 col-md-4 bd-desc tabl-style">
                                    <a title="<?php echo $mail['mail_subject'];?>" onclick="markasUnread('<?php echo $mail['uid']; ?>')" href="<?php echo base_url('Mail/viewThread/' . $mail['uid'].'/'.$ypid.'/'.$mail['boxtype']); ?>">

                                        <?php $mail_subject = (isset($mail['mail_subject'])) ? strlen($mail['mail_subject']) > 50 ? htmlentities(substr($mail['mail_subject'],0,50))."..." : htmlentities($mail['mail_subject'])  : '';
                                                ?>

                                        <?php echo ($mail['is_unread'] == 1) ? "<span id=" . $mail['uid'] . " class='font-bold'>" . $mail_subject . "</span>" : $mail_subject; ?>
                                        
                                    </a></div>
                                <div class="col-sm-2 col-md-5 tabl-style table-mail-resp">
                                <a  onclick="markasUnread('<?php echo $mail['uid']; ?>')" href="<?php echo base_url('Mail/viewThread/' . $mail['uid'].'/'.$ypid.'/'.$mail['boxtype']); ?>" >


                                        <?php 
										if($box_types == "INBOX" || $box_types == "inbox" || $box_types == "Inbox" ){
                                        
                                            if($mail['from_mail'] == $yp_email){
                                                $yp_initials = $YP_details[0]['yp_initials'];
                                                $yp_init = substr($yp_initials,0,3);
                                                $fromMail = htmlentities(YpInitialsWithEmail($yp_init,$mail['from_mail']));
                                            }else{
                                             $obj = json_decode($mail['header_data']);
                                             $fromMail = htmlentities(str_replace('"','',$obj[0]->fromaddress));  
                                            }

                                        echo ($mail['is_unread'] == 1) ? "<span id=" . $mail['uid'] . " class='font-bold'>" . $fromMail . "</strong>" : $fromMail;

										}else{

										echo ($mail['is_unread'] == 1) ? "<span id=" . $mail['uid'] . " class='font-bold'>" . $mail['to_mail'] . "</strong>" : $mail['to_mail'];

										}
										 ?>
                                        
                                    </a>
                                </div>

                                <div class="col-sm-2 col-md-2 tabl-style hidden-xs hidden-sm bd-in-date">
                                    <a  onclick="markasUnread('<?php echo $mail['uid']; ?>')" href="<?php echo base_url('Mail/viewThread/' . $mail['uid'].'/'.$ypid.'/'.$mail['boxtype']); ?>" >
                                        <?php echo ($mail['is_unread'] == 1) ? "<span id=" . $mail['uid'] . " class='font-bold'>" . date("d/m/Y H:i",strtotime($mail['send_date'])) . "</strong>" : date("d/m/Y H:i",strtotime($mail['send_date'])); ?>
                                    </a>
                                </div>

                                <div class="visible-xs visible-sm bd-mail-date"><?php echo date('d/m/Y H:i', strtotime($mail['send_date'])); ?></div>
                                <!-- 
                                <div class="visible-xs visible-sm bd-detaildesc">
                                    <p>
                                        <?php  $mail_body = strip_tags($mail['mail_body']);?>
                                        <?= !empty($mail['mail_body']) ? substr($mail_body, 0, 50) : '' ?>
                                    </p>
                                </div>
 -->
                                <div class="bd-in-attach tabl-style">
                                    <?php
                                    if ($mail['file_size'] > 0) {
                                        $url = base_url('Mail/saveAttachment/' . $mail['email_unq_id'].'/'.$ypid);
                                        $icon = '<i class="bd-attach-ico"></i>';
                                    } else {
                                        $url = 'javascript:;';
                                        $icon = '';
                                    }
                                    ?>
                                    <a target="_blank" href="<?php echo $url; ?>"><?php echo $icon; ?></a>
                                </div>
                                <div class="clearfix"></div>
                            </li>

                            <?php
                        }
                    } else {
                        ?>
                        <center> <?= lang('mail_not_found') ?> </center>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div id="common_tb" class="no_of_records">
                <?php
                if (isset($pagination)) {
                    echo $pagination;
                }
                ?>
            </div>
        </div>
    </div>
</div>
