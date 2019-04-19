<div class="col-md-12 col-lg-2 col-sm-12" >
    <div class=" whitebox">
        <div class="bd-mailbox-sidebar">
            <a class="pull-right"></a>
             <ul class="nav bd-mailbox-menu">
               <?php if($box_types == 'INBOX'){
                    $active_inbox = "active";
                }elseif($box_types == '[Gmail]/Sent Mail' || $box_types == 'Sent' || $box_types == 'Send' || $box_types == 'Sent Items') {
                       $active_sent = "active";
                }
                ?>
                
                <li><a href="javascript:void(0);" data_leftbox="Inbox" id="<?php echo 0; ?>" class="Inbox leftbx <?php echo $active_inbox;?>" onclick='getMailBoxData("<?php echo 'INBOX'; ?>",<?php echo 0; ?>)' >
                    Inbox
                    <span class="badge round_box"><?php echo (intval($inbox_count) > 0) ? $inbox_count : ''; ?></span></a>
                    <input type="hidden" name="inbox_box" class="inbox_box" value="<?php echo 'INBOX'; ?>">
                </li>
                
                        <li>
                            <a href="javascript:void(0);" data_leftbox="Sent" id="<?php echo 1; ?>" class="Sent leftbx <?php echo $active_sent;?>" onclick='getMailBoxData("Sent Items",<?php echo 1; ?>)' >Sent Items</a>
                            <input type="hidden" name="sent_box" class="sent_box" value="Sent Items">
                            
                        </li>
                
                <input type="hidden" name="yp_id" class="yp_id" value="<?php echo $ypid; ?>">
            </ul>

<div id ="refereshLeftbox"></div>
<div id ="refereshLeft"></div>
        </div>
    </div>
</div>

