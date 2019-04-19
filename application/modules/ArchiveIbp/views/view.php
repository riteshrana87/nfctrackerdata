<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title ">
                    INDIVIDUAL BEHAVIOUR PLAN <small>New Forest Care</small>
                    <div class="pull-right for-tab">
                        <div class="btn-group">

                            <?php 
                            /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                            if($past_care_id == 0){ ?>
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('Ibp','view')){ ?>
                            <a href="<?=base_url('Ibp/index/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> Return To Current IBP
                                </a>
                            <?php } ?>
                            <a href="<?=base_url('ArchiveIbp/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> Return To Archive
                            </a>
                            <?php }else{ ?>

                            <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                            <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                            </a>     

                            <a href="<?=base_url('ArchiveIbp/index/'.$ypid .'/'. $care_home_id .'/'. $past_care_id); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> Return To Archive
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
			   <?php 
		
		$currentDate =  configDateTimeFormat($formsdata[0]['modified_date']);
		//echo $currentDate;
		?>
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>&nbsp;&nbsp;&nbsp;&nbsp;<small>Last Edit Date:</small>  
					<?php if(!empty($formsdata[0]['modified_date']) && $formsdata[0]['modified_date'] != '0000-00-00 00:00:00'){
						echo $currentDate;
						
					}elseif($formsdata[0]['modified_date'] == '0000-00-00 00:00:00'){
						
						echo "";
						
					}else{
						
						echo "";
					}?>
					
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
                            <div class="<?=($row['type'] == 'header')?'col-sm-12':'col-sm-6'?>">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <div class ="slimScroll-120">
                                        <?php if($row['type'] == 'textarea' || $row['type']== 'number') { 
                                            if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) {
                                               
                                                $diff = new HtmlDiff( str_replace("\'","'",$form_old_data[$n]['value']),str_replace("\'","'",$row['value']) );
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else {?>
                                                <?=!empty($row['value'])?str_replace("\'","'",$row['value']):''?>
                                           <?php  } ?>

                                        <?php  } else if($row['type'] == 'text') {
                                                        if($row['subtype'] == 'time') {
                                                             if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) {
                                               
                                                                $diff = new HtmlDiff(timeformat($form_old_data[$n]['value']),timeformat($row['value']) );
                                                                $diff->build();
                                                                echo $diff->getDifference()
                                                                ?>
                                                            <?php } else {?>
                                                                <?=!empty($row['value'])?timeformat($row['value']):''?>
                                           <?php  } }else{ 

                                                if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) {
                                               
                                                $diff = new HtmlDiff( str_replace("\'","'",$form_old_data[$n]['value']),str_replace("\'","'",$row['value']) );
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?=!empty($row['value'])?str_replace("\'","'",$row['value']):''?>
                                           <?php } ?>
                                           <?php } ?>

                                           <?php  } else if($row['type'] == 'date') {
                                                             if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) {
                                               
                                                                $diff = new HtmlDiff(configDateTime($form_old_data[$n]['value']),configDateTime($row['value']) );
                                                                $diff->build();
                                                                echo $diff->getDifference()
                                                                ?>
                                                            <?php } else { ?>
                                                                <?=!empty($row['value'])?configDateTime($row['value']):''?>
                                                              <?php } ?>  

                                           <?php  } else if($row['type'] == 'checkbox-group') {
                                                    if(count($row['values']) > 0) {
                                                     foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                        }
                                                    }
                                                ?>

                                           <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                             
                                                 if(count($row['values']) > 0) {
                                                     foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected'])?$chked['value']:'';
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
                            <div class="col-sm-6">
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
                                                if(!empty($row['value'])) {
                                                    echo ($row['value'] == 'yes')?'<span class="label label-success">Yes</span>':(($row['value'] == 'no')?'<span class="label label-danger">No</span>':'<span class="label label-success">'.$row['value'].'</span>');
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
                                                'filePathMain' => $this->config->item('ibp_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('ibp_img_base_url_small') . $ypid
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
                    <?php
            if (checkPermission('ArchiveIbp', 'signoff')) { 
                if (!empty($signoff_data)) {
            ?>

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
                            <?php }  ?>
                    </div>

                </div>
            </div>
<?php } } ?>
                    
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group capsBtn">
                                <?php
                                /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                                 if($past_care_id == 0){ ?>
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('Ibp','view')){ ?>
                            <a href="<?=base_url('Ibp/index/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> Return To Current IBP
                                </a>
                            <?php } ?>
                            <a href="<?=base_url('ArchiveIbp/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> Return To Archive
                            </a>
                            <?php }else{ ?>

                            <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                            <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                            </a>     

                            <a href="<?=base_url('ArchiveIbp/index/'.$ypid .'/'. $care_home_id .'/'. $past_care_id); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> Return To Archive
                            </a>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>