<!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                    INDIVIDUAL STRATEGIES <small>New Forest Care</small>
                    <div class="pull-right for-tab">
                        <div class="btn-group">
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('IndividualStrategies','view')){ ?>
                            <a href="<?=base_url('IndividualStrategies/index/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> Return To Current IS
                                </a>
                            <?php } ?>
                            <a href="<?=base_url('ArchiveIndividualStrategies/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> Return To Archive
                            </a>
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                <?php $currentDate =  configDateTimeFormat($formsdata[0]['modified_date']);?>
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>&nbsp;&nbsp;&nbsp;&nbsp;<small>Last Edit Date:</small>  <?= (!empty($formsdata[0]['modified_date']) && $formsdata[0]['modified_date'] != '0000-00-00 00:00:00') ? $currentDate : '' ?>
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
                                                           
                                                        $diff = new HtmlDiff( timeformat($form_old_data[$n]['value']),timeformat($row['value']));
                                                            $diff->build();
                                                            echo $diff->getDifference()
                                                            ?>
                                                        <?php } else {?>
                                                       <?=!empty($edit_data[0][$row['name']])?timeformat($edit_data[0][$row['name']]):(isset($row['value'])?timeformat($row['value']):'')?>
                                                       <?php } ?>
                                                       <?php }elseif ($row['type']== 'date') { ?>
                                                            <?php 
                                                              if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) { 
                                                           $diff = new HtmlDiff(configDateTime($form_old_data[$n]['value']),configDateTime($row['value']));
                                                            $diff->build();
                                                            echo $diff->getDifference()
                                                            ?>
                                                        <?php } else { ?>
                                                       <?=!empty($edit_data[0][$row['name']])?configDateTime($edit_data[0][$row['name']]):(isset($row['value'])?configDateTime($row['value']):'')?>
                                                       <?php } ?>
                                                            
                                                       <?php }else{ ?>
                                                       <?php if(!empty($form_old_data[$n]['name']) && ($row['name'] == $form_old_data[$n]['name'])) {
                                                            $diff = new HtmlDiff(str_replace("\'","'",$form_old_data[$n]['value']),str_replace("\'","'",$row['value']));
                                                            $diff->build();
                                                            echo $diff->getDifference()
                                                            ?>
                                                        <?php } else {?>
                                                       <?=!empty($edit_data[0][$row['name']])?html_entity_decode(str_replace("\'","'",$edit_data[0][$row['name']])):(isset($row['value'])?str_replace("\'","'",$row['value']):'')?>
                                                       <?php  } ?>
                                                       <?php  } ?>
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
                                    <div class="<?=($row['type'] == 'header')?'col-sm-12':'col-sm-12'?>">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                            <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                <?php
                                     if(!empty($edit_data[0][$row['name']])) {
                                        echo ($edit_data[0][$row['name']] == 'high')?'<span class="label label-danger">High</span>':(($edit_data[0][$row['name']] == 'medium')?'<span class="label label-warning">Medium</span>':(($edit_data[0][$row['name']] == 'low')?'<span class="label label-success">Low</span>':'<span class="label label-success">'.$edit_data[0][$row['name']].'</span>'));
                                     }
                                     else
                                     { 
                                         if(!empty($row['value'])) {
                                                echo ($row['value'] == 'high')?'<span class="label label-danger">High</span>':(($row['value'] == 'medium')?'<span class="label label-warning">Medium</span>':(($row['value'] == 'low')?'<span class="label label-success">Low</span>':'<span class="label label-success">'.$row['value'].'</span>'));
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
                                                'filePathMain' => $this->config->item('is_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('is_img_base_url_small') . $ypid
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
                    
                    <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>Amendments</h2>
                        <?php if (!empty($item_details)) { $n =0;
                                                    foreach ($item_details as $row) {
                                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
                                            <?php if (!empty($item_details_archive[$n]['amendments']) && $item_details_archive[$n]['amendments_id'] == $row['amendments_id']) {

                                                $diff = new HtmlDiff($item_details_archive[$n]['amendments'], $row['amendments']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                             <?php if(!empty($item_details_archive))
                                                {
                                                     $diff = new HtmlDiff('', $row['amendments']);
                                                        $diff->build();
                                                        echo $diff->getDifference();
                                                }else{
                                                    ?>
                                             <?= !empty($row['amendments']) ? $row['amendments'] : '' ?>
                                             <?php
                                                }
                                              } ?>
                                            </p>
											<?php 
												$display_amendments = '';
												if(!empty($row['amendments_modified_date'])){
													$display_amendments = $row['create_name'].':'.configDateTimeFormat($row['amendments_modified_date']);
												}
											?>
                                            <p class="date"><small><?php echo $display_amendments ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php $n++;}
                        }
                        ?>   
                        
                    </div>
                </div>
            </div>                
                                    
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>Current Protocols in place</h2>
                        <?php if (!empty($protocols_details)) { $n =0;
                                foreach ($protocols_details as $row) {
                        ?> 
                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
                                            <?php if (!empty($protocols_details_archive[$n]['current_protocols_in_place']) && $protocols_details_archive[$n]['protocols_in_place_id'] == $row['protocols_in_place_id']) {

                                                $diff = new HtmlDiff($protocols_details_archive[$n]['current_protocols_in_place'], $row['current_protocols_in_place']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                             <?php if(!empty($protocols_details_archive))
                                                {
                                                     $diff = new HtmlDiff('', $row['current_protocols_in_place']);
                                                        $diff->build();
                                                        echo $diff->getDifference();
                                                }else{
                                                    ?>
                                             <?= !empty($row['current_protocols_in_place']) ? $row['current_protocols_in_place'] : '' ?>
                                             <?php
                                                }
                                              } ?>
                                            </p>
											
											<?php 
												$display_protocols = '';
												if(!empty($row['protocol_modified_date'])){
													$display_protocols = $row['create_name'].':'.configDateTimeFormat($row['protocol_modified_date']);
												}
											?>
                                            <p class="date"><small><?php echo $display_protocols; ?></small></p>
											
                                        </div>
                                    </li>
                                </ul>
                            <?php $n++; }
                        }
                        ?>   
                        
                    </div>
                </div>
            </div> 
<?php if (checkPermission('ArchiveIndividualStrategies', 'signoff')) { 
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
                                            <p class="date"><small><?php echo $sign_name['create_name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php }
                        
                        ?> 
                         
                    </div>
                </div>
            </div>
                    <?php } } ?>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group capsBtn">
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                               <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <?php if(checkPermission('RiskAssesment','view')){ ?>
                            <a href="<?=base_url('IndividualStrategies/index/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> Return To Current IS
                                </a>
                            <?php } ?>
                            <a href="<?=base_url('ArchiveIndividualStrategies/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> Return To Archive
                            </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<script> var YPId = <?= $ypid; ?>; </script>