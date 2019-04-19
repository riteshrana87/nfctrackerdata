<div class="col-md-12 col-lg-2 col-sm-12" >
    <div class=" whitebox">
        <div class="bd-mailbox-sidebar">
            <a class="pull-right"></a>
             <ul class="nav bd-mailbox-menu">
                <?php /*
                $unreadCount = 0;
                foreach ($mail_data as $key => $value) {
                    if($value['is_unread'] == 1){
                        $unreadCount++;
                    }
                }*/ ?>
                
                <?php if($box_types == 'INBOX'){
                    $active_inbox = "active";
                }elseif($box_types == '[Gmail]/Sent Mail' || $box_types == 'Sent' || $box_types == 'Send' || $box_types == 'Sent Items') {
                       $active_sent = "active";
                }
                ?>
                
                <li>
                    <a class="Inbox leftbx <?php echo $active_inbox;?>" href="<?php echo base_url('ArchiveMail/index/'.$ypid.'/INBOX/'.$care_home_id.'/'.$past_care_id); ?>">
                    Inbox
                    <span class="badge round_box"><?php echo (intval($inbox_count) > 0) ? $inbox_count : ''; ?></span></a>
                    <input type="hidden" name="inbox_box" class="inbox_box" value="<?php echo 'INBOX'; ?>">
                </li>
                <?php
                /*foreach ($leftMenuFolderCount as $folderName => $unreadCount) {
                    if ($folderName == 'INBOX' || $folderName == 'inbox' || $folderName=='Inbox') {
                        ?>
                        <li>
                            <!-- <a href="javascript:void(0);" data-boxtype="INBOX" id="refreshBn" class="Inbox <?php echo (strtolower($folderName) == strtolower('INBOX')) ? "active" : ""; ?>">
                    <?php 
                    $inbox_name = str_replace('[Gmail]/', '', $folderName);
                        if($inbox_name != ''){
                            $inbox_name = 'Inbox';
                        }else{
                            $inbox_name = $inbox_name;
                        }
                        echo $inbox_name;
                         ?> <span class="badge"><?php echo ($unreadCount > 0) ? $unreadCount : ''; ?></span></a> -->

                           <a href="javascript:void(0);" data_leftbox="Inbox" id="<?php echo 0; ?>" class="Inbox leftbx <?php echo (strtolower($folderName) == strtolower('INBOX')) ? "active" : ""; ?>" onclick='getMailBoxData("<?php echo $folderName; ?>",<?php echo 0; ?>)' >
                    <?php 
                    $inbox_name = str_replace('[Gmail]/', '', $folderName);
                        if($inbox_name != ''){
                            $inbox_name = 'Inbox';
                        }else{
                            $inbox_name = $inbox_name;
                        }
                        echo $inbox_name;
                         ?> <span class="badge"><?php echo ($unreadCount > 0) ? $unreadCount : ''; ?></span></a>

                     </li>
                        <?php
                    }
                }*/
                foreach ($leftMenuFolderCount as $folderName => $unreadCount) {
                    if ($folderName == '[Gmail]/Sent Mail' || $folderName == 'Sent' || $folderName == 'Send' || $folderName == 'Sent Items') {
                        ?>
                        <li>

                            <a href="<?php echo base_url('ArchiveMail/index/'.$ypid.'/'.$folderName.'/'.$care_home_id.'/'.$past_care_id); ?>" class="Sent leftbx <?php echo $active_sent;?>"><?php echo str_replace('[Gmail]/', '', $folderName); ?></a>
                            <input type="hidden" name="sent_box" class="sent_box" value="<?php echo $folderName; ?>">
                            

                        </li>
                        <?php
                    }
                }
               

                ?>
                <input type="hidden" name="yp_id" class="yp_id" value="<?php echo $ypid; ?>">
            </ul>
<!--            <div class="bd-storage bd-mail-head bd-inbox col-md-12">
                <i class="fa fa-store-ico"></i><span><?php //echo $percentageSize; ?><button id="refereshLeftbox"><i class=" bd-refresh-ico"></i></button></span>
            </div>-->
<div id ="refereshLeftbox"></div>
<div id ="refereshLeft"></div>
        </div>
    </div>
</div>

