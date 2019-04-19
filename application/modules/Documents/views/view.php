<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
</script>
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Documents   <small>New Forest Care</small>
            <div class="pull-right for-tab">
			<?php 
						/*ghelani nikunj
						08/10/2018
						if in care to care archive then no need to show button
						*/
						if($is_archive_page==0){?>
                <div class="btn-group">
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?=base_url('Documents/index/'.$ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i>  Documents 
                        </a>
                    
                    <a href="<?= base_url('Documents/DownloadPrint/' . $edit_data[0]['docs_id'] .'/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                   
                </div>
						<?php } else { ?>
						<div class="btn-group">
                    
                   <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                    <a href="<?=base_url('Documents/index/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i>  Documents 
                        </a>
                    
                   
                </div>
						
						<?php }?>
            </div>
        </h1>
       <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
        <?php
        if(!empty($form_data))
        {
            foreach ($form_data as $row) {

             if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                ?>
                    <div class="<?=($row['type'] == 'header')?'col-sm-12':'col-sm-12'?>">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                <div class ="slimScroll-120">
                                <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>
                                    
                                    <?php if($row['subtype'] == 'time'){ ?> 
                                        <?=!empty($edit_data[0][$row['name']])?nl2br(timeformat($edit_data[0][$row['name']])):(isset($row['value'])?timeformat($row['value']):'')?>
                                    <?php }elseif($row['type'] == 'date'){ ?>
                                        <?=!empty($edit_data[0][$row['name']])?nl2br(configDateTime($edit_data[0][$row['name']])):(isset($row['value'])?configDateTime($row['value']):'')?>
                                    <?php }else{ ?>
                                        <?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?> 
                                    <?php } ?>
                                   

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
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <?php
                }
                else if($row['type'] == 'radio-group') {
                    ?>
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <label><?=!empty($row['label'])?$row['label']:''?></label>
                                <div class="pull-right">
                    <?php
                                     if(!empty($edit_data[0][$row['name']])) {
                                        echo ($edit_data[0][$row['name']] == 'yes')?'<span class="label label-success">Yes</span>':(($edit_data[0][$row['name']] == 'no')?'<span class="label label-danger">No</span>':'<span class="label label-success">'.$edit_data[0][$row['name']].'</span>');
                                     }
                                     else
                                     { 
                                         if(count($row['values']) > 0) {
                                                
                                             foreach ($row['values'] as $chked) {
                                              
                                                 echo isset($chked['selected'])?($chked['value'] == 'no')?'<span class="label label-danger">No</span>':(($chked['value'] == 'yes')?'<span class="label label-warning">Yes</span>':''):'';
                                                 }
                                                
                                            }
                                     }
                                     ?></div>
                        </div>
                            </div>
                                </div>
                <?php
                }
                else if ($row['type'] == 'header') {
                   ?>
                   <div class="col-sm-12">
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
                    <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                <div class="">   
                                    <?php 
                                    /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                    $fileViewArray = array(
                                        'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                                        'filePathMain' => $this->config->item('docs_img_base_url') . $ypid,
                                        'filePathThumb' => $this->config->item('docs_img_base_url') . $ypid,
                                        'class' => 'btn btn-link btn-blue'
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
		 <?php 
						/*ghelani nikunj
						08/10/2018
						if in care to care archive then no need to show button
						*/
						if($is_archive_page==0){?>
            <?php if (!empty($edit_data)) { 
                if (checkPermission('Documents', 'signoff')) { 
                ?> 
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>sign off</h2>
                <?php
                if(empty($check_signoff_data)){
                 ?>
                        <input type="checkbox" name="docs_signoff" onclick="manager_request(<?php echo $ypid ; ?>,<?php echo $edit_data[0]['docs_id'];?>);" required="true" class="" value="1">
                <?php
                            $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
                            echo getUserName($login_user_id);
                 } ?>
                    
                    <?php

                       if (!empty($docs_signoff_data)) {
                                foreach ($docs_signoff_data as $sign_name) {
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
                
            <?php }} }?>
            <?php 
						/*ghelani nikunj
						08/10/2018
						if in care to care archive then no need to show button
						*/
						if($is_archive_page==0){?>
             <?php if (!empty($edit_data)) { 
            if (checkPermission('Documents', 'document_signoff')) {
                    ?>
            <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                 <a href="javascript:;" data-href="<?php echo base_url() . 'Documents/signoff/'.$ypid.'/'.$doc_id; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning"><?php echo lang('external_approval'); ?></span></a>
                                 <?php if(!empty($check_external_signoff_data)){ ?>
                                    <a style="text-decoration:none;" href="<?php echo base_url() . 'Documents/reportReviewedBy/' . $doc_id . '/' .$ypid; ?>"  aria-hidden="true" data-refresh="true" ><span class="label label-warning"><?php echo lang('external_approval_list'); ?></span></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div> 

						<?php }} } ?> 
        </div>
        
    </div>
    <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
				<?php 
						/*ghelani nikunj
						08/10/2018
						if in care to care archive then no need to show button
						*/
						if($is_archive_page==0){?>
                    <div class="btn-group capsBtn">
                        <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                        </a>
                        <a href="<?=base_url('Documents/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i>  Documents 
                            </a>
                            <a href="<?= base_url('Documents/DownloadPrint/' . $edit_data[0]['docs_id'] .'/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                    </div>
						<?php } else {?>
						<a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                        </a>
                        <a href="<?=base_url('Documents/index/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i>  Documents 
                            </a>
						
						<?php }?>
                </div>
            </div>
        </div>
</div>