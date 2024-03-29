<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                    RISK ASSESSMENT <small>New Forest Care</small>
                    <div class="pull-right for-tab">
                        <div class="btn-group">
						<?php 
						/*ghelani nikunj
						06/10/2018
						if in care to care archive then no need to show button
						*/
						if($is_archive_page==0){?>
						<a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('RiskAssesment','view')){ ?>
                            <a href="<?=base_url('RiskAssesment/index/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> Return To Current RA
                                </a>
                            <?php } ?>
                            <a href="<?=base_url('ArchiveRiskAssesment/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> Return To Archive
                            </a>
						<?php } else {?>
						 <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
						  <a href="<?=base_url('ArchiveRiskAssesment/index/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> Return To Archive
                            </a>
						<?php }?>
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
			   <?php 
		          $currentDate =  configDateTimeFormat($formsdata[0]['modified_date']);
		      ?>
			   
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?><small>Last Edit Date:</small>  <?= (!empty($formsdata[0]['modified_date']) && $formsdata[0]['modified_date'] != '0000-00-00 00:00:00') ? $currentDate : '' ?>
                </h1>
            </div>
            <div class="row">
                <?php
                $n = 0;
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
                                                    <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {
                                                        if($row['subtype']== 'time'){
                                                            if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) {
                                                            $diff = new HtmlDiff(timeformat($form_old_data[$n]['value']),timeformat($row['value']));
                                                            $diff->build();
                                                            echo $diff->getDifference()
                                                            ?>
                                                        <?php } else { ?>
                                                       <?=!empty($edit_data[0][$row['name']])?timeformat($edit_data[0][$row['name']]):(isset($row['value'])?timeformat($row['value']):'')?>
                                                       <?php } 
                                                        }elseif($row['type']== 'date'){
                                                    if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) {
                                                            $diff = new HtmlDiff( configDateTime($form_old_data[$n]['value']),configDateTime($row['value']) );
                                                            $diff->build();
                                                            echo $diff->getDifference()
                                                            ?>
                                                        <?php } else {?>
                                                       <?=!empty($edit_data[0][$row['name']])?configDateTime($edit_data[0][$row['name']]):(isset($row['value'])?configDateTime($row['value']):'')?>
                                                       <?php  } ?>
                                                    <?php  }else{ 

                                                    if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) {
                                                            $diff = new HtmlDiff(str_replace("\'","'",$form_old_data[$n]['value']),str_replace("\'","'",$row['value']));
                                                            $diff->build();
                                                            echo $diff->getDifference()
                                                            ?>
                                                        <?php } else {?>
                                                       <?=!empty($edit_data[0][$row['name']])?html_entity_decode(str_replace("\'","'",$edit_data[0][$row['name']])):(isset($row['value'])?str_replace("\'","'",$row['value']):'')?>
                                                        <?php  }} }else if($row['type'] == 'checkbox-group') {
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
                                    <div class="<?=($row['type'] == 'header')?'col-sm-12':'col-sm-12'?>">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                            <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                <?php
                                     if(!empty($edit_data[0][$row['name']])) {
                                        echo ($edit_data[0][$row['name']] == 'high')?'<span class="label label-danger">High</span>':(($edit_data[0][$row['name']] == 'Medium')?'<span class="label label-warning">Medium</span>':(($edit_data[0][$row['name']] == 'low')?'<span class="label label-success">Low</span>':'<span class="label label-success">'.$edit_data[0][$row['name']].'</span>'));
                                     }
                                     else
                                     { 
                                         if(!empty($row['value'])) {
                                                echo ($row['value'] == 'high')?'<span class="label label-danger">High</span>':(($row['value'] == 'Medium')?'<span class="label label-warning">Medium</span>':(($row['value'] == 'low')?'<span class="label label-success">Low</span>':'<span class="label label-success">'.$row['value'].'</span>'));
                                             }
                                     }
                                     ?>
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
                                                'filePathMain' => $this->config->item('ra_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('ra_img_base_url_small') . $ypid
                                            );
                                            echo getFileView($fileViewArray);
                                            ?>                               
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        $n++;
                    } //foreach
                }
                 ?>
                    <?php if (checkPermission('ArchiveRiskAssesment', 'signoff')) { 
                         if (!empty($signoff_data)) {?>
                    <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>sign off</h2>
                        <?php
                      
                                foreach ($signoff_data as $sign_name) {
                                ?>
                               <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php }   ?>
                    </div>

                </div>
            </div>
                    <?php } }?>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group">
                                <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                               <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                                </a>
                                <?php if(checkPermission('RiskAssesment','view')){ ?>
                                <a href="<?=base_url('RiskAssesment/index/'.$ypid); ?>" class="btn btn-default">
                                        <i class="fa fa-mail-reply"></i> RETURN TO CURRENT RA
                                    </a>
                                <?php } ?>
                                <a href="<?=base_url('ArchiveRiskAssesment/index/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> RETURN TO ARCHIVE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>