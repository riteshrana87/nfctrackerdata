<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
</script>
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
            <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                    Key Sessions <small>New Forest Care</small>
                    <div class="pull-right for-tab">
                        <div class="btn-group">
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <a href="<?=base_url('KeySession/index/'.$ypid); ?>" class="btn btn-default">
                                     <i class="fa fa-mail-reply"></i> Key Sessions
                                </a>
                            <?php if(checkPermission('ArchiveKs','add')){ ?>
                            <a href="<?=base_url('ArchiveKs/createArchive/'.$ks_id.'/'.$ypid); ?>" class="btn btn-default">
                                 <i class="fa fa-edit"></i> CREATE ARCHIVE
                            </a>
                            <?php } ?>
                            
                            <a href="<?= base_url('KeySession/DownloadPrint/' . $ks_id .'/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                            
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
            </div>
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                        <?php
                         if(!empty($ks_form_data))
                        {
                        foreach ($ks_form_data as $row) {
                        if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                                ?>
                                
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                                <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                 <label class="value large">
                                                <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>

                                                   <?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                                   <?php  }
                                                    else if($row['type'] == 'checkbox-group') {
                                                    if(!empty($edit_data[0][$row['name']])) {
                                                        $chk = explode(',',$edit_data[0][$row['name']]);
                                                                foreach ($chk as $chk) {
                                                                    echo $chk."\n";
                                                                }
                                                             
                                                        
                                                    }else{

                                                            if(count($row['values']) > 0) {
                                                               
                                                             foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                                 }
                                                               
                                                            }
                                                        }?>

                                                   <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                     if(!empty($edit_data[0][$row['name']])) {
                                                        echo !empty($edit_data[0][$row['name']])?nl2br(htmlentities($edit_data[0][$row['name']])):'';
                                                     }
                                                     else
                                                     {
                                                         if(count($row['values']) > 0) {
                                                                
                                                             foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected'])?$chked['value']:'';
                                                                 }
                                                                
                                                            }
                                                     }
                                                    } ?>
                                                </label>
                                            
                                        </div>
                                    </div>
                                    
                                <?php
                                } } } ?>
                        </div>
                    </div>
                    </div>
                <?php
                if(!empty($ks_form_data))
                {
                    foreach ($ks_form_data as $row) {

                        if ($row['type'] == 'textarea') { ?>
                            <div class="col-lg-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        else if ($row['type'] == 'header') {
                           ?>
                           <div class="col-lg-12">
                                        <div class="">
                                            <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                            <?php echo '<'.$head.' class="page-title">'; ?>
                                            <?=!empty($row['label'])?$row['label']:''?>
                                                
                                            <?php echo '</'.$head.'>'; ?>
                                        </div>
                                    </div>
                           <?php
                        }
                        else if ($row['type'] == 'file') { ?>
                            <div class="col-lg-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <div class="">   
                                            <?php 
                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                            $fileViewArray = array(
                                                'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                                                'filePathMain' => $this->config->item('cm_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('cm_img_base_url_small') . $ypid
                                            );
                                            echo getFileView($fileViewArray);
                                            ?>                               
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } //foreach
                }
                 ?>
                 <?php if (!empty($edit_data)) { 
                    if (checkPermission('KeySession', 'signoff')) { 
                    ?>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                <h2>sign off</h2>

                        <?php 
                        if(empty($check_ks_signoff_data)){ ?>
                                <input type="checkbox" name="ks_signoff" onclick="manager_request(<?php echo $ypid ; ?>,<?php echo $ks_id;?>);" class="" value="1" >
                        <?php 
                        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
                            echo getUserName($login_user_id);
                        }
                         ?> 


                        <?php
                       if (!empty($ks_signoff_data)) {
                                foreach ($ks_signoff_data as $sign_name) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php }
                        }
                        ?> 

                                </div>
                            </div>
                        </div>
                    <?php } } ?>




<div class="col-lg-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Comments Box</h2>
                                <?php if(!empty($comments)){ 
                                    foreach ($comments as $comments_data) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
                                        <?php echo $comments_data['ks_comments']?>
                                            </p>
                                            <p class="date"><small><?php echo $comments_data['create_name'] ?>:   <?php echo configDateTime($comments_data['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                                <?php } }?>   

<?php if (checkPermission('KeySession', 'comment')) { ?>
                               <form data-parsley-validate="true" action="<?=base_url('KeySession/add_commnts')?>" method="post">
                            <input type="hidden" name="ks_id" value="<?=$ks_id?>">
                            <input type="hidden" name="yp_id" value="<?=$ypid?>">
                             
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>Comments</h2>
                                    <textarea class="form-control" name="ks_comments" placeholder="add comments" id="ks_comments" required=""></textarea>
                                </div>
                            </div>
                        </div>
                   
                            
                            <button type="submit" class="btn btn-default">
                               Submit
                            </button>
                            

                        </form>
                         <?php } ?>  
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($edit_data)) { 
                if (checkPermission('KeySession', 'document_signoff')) {
                        ?>
                <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                     <a href="javascript:;" data-href="<?php echo base_url() . 'KeySession/signoff/'.$ypid.'/'.$ks_id; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning"><?php echo lang('external_approval'); ?></span></a>
                                     <?php if(!empty($check_external_signoff_data)){ ?>
                                         <a href="<?php echo base_url() . 'KeySession/external_approval_list/'.$ypid.'/'.$ks_id; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                         </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div> 
                
               <?php }} ?>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group capsBtn">
                                <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                                <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>
                                <a href="<?=base_url('KeySession/index/'.$ypid); ?>" class="btn btn-default">
                                     <i class="fa fa-mail-reply"></i> Key Sessions
                                </a>

                                <?php if(checkPermission('ArchiveKs','add')){ ?>
                            <a href="<?=base_url('ArchiveKs/createArchive/'.$ks_id.'/'.$ypid); ?>" class="btn btn-default">
                                 <i class="fa fa-edit"></i> CREATE ARCHIVE
                            </a>
                            <?php } ?>
                                           <a href="<?= base_url('KeySession/DownloadPrint/' . $ks_id .'/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PRINT </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>