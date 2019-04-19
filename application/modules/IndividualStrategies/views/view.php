<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var individual_strategies_id = '<?=!empty($edit_data[0]['individual_strategies_id'])?$edit_data[0]['individual_strategies_id']:''?>';   
    var edit_data = '<?=!empty($item_details)?$item_details:''?>';    
    var edit_protocols_details = '<?=!empty($protocols_details)?$protocols_details:''?>';    


</script>
<div id="page-wrapper">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }
        ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Individual Strategies <small>New Forest Care</small>
            <div class="pull-right 0000-00-00 00:00:00 for-tab">
			<?php 
			/*ghelani nikunj
			19/8/2018
			if in care to care archive then no need to show button
			*/
			if($is_archive_page==0){?>
                <div class="btn-group ">
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>

                    <?php if (checkPermission('IndividualStrategies', 'edit')) { ?>
                        <a href="<?= base_url('IndividualStrategies/edit/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-edit"></i> EDIT IS
                        </a>
                    <?php } ?>
                    
                    <?php if (checkPermission('ArchiveIndividualStrategies', 'view')) { ?>
                        <a href="<?= base_url('ArchiveIndividualStrategies/index/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-search"></i> VIEW ARCHIVE
                        </a>
                    <?php } ?>
                    <?php if (!empty($edit_data[0]['individual_strategies_id'])) { ?>
                        <a href="<?= base_url('IndividualStrategies/DownloadPdf/' . $edit_data[0]['individual_strategies_id'] . '/' . $ypid); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PDF </a>
                    <?php } ?>

                </div>
				<?php } else {?>
			 <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$careHomeId.'/'.$is_archive_page); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
			<?php }?>
            </div>
        </h1>
        <h1 class="page-title">
		<?php 
		
		$currentDate =  configDateTimeFormat($edit_data[0]['modified_date']);
		//echo $currentDate;
		?>
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>&nbsp;&nbsp;&nbsp;&nbsp;<small>Last Edit Date:</small>  <?= (!empty($edit_data[0]['modified_date']) && $edit_data[0]['modified_date'] != '0000-00-00 00:00:00') ? $currentDate : '' ?>
        </h1>
    </div>
        <div class="row">
            <?php
            if (!empty($form_data)) {
                foreach ($form_data as $row) { //pr($row);

                    if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
						
                        <div class="<?= ($row['type'] == 'header') ? 'col-sm-12' : 'col-sm-12' ?>">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class ="slimScroll-120">
                                        <?php
                                        if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {
                                            if($row['subtype'] == 'time'){
                                                if (!empty($prev_edit_data)) {
                                                $diff = new HtmlDiff(timeformat($prev_edit_data[0][$row['name']]), timeformat($edit_data[0][$row['name']]));
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? timeformat($edit_data[0][$row['name']]) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                            <?php } ?>
                                            <?php 
                                            }elseif($row['type'] == 'date'){
                                                if (!empty($prev_edit_data)) {
                                                $diff = new HtmlDiff(configDateTime($prev_edit_data[0][$row['name']]),configDateTime($edit_data[0][$row['name']]));
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? configDateTime($edit_data[0][$row['name']]) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                            <?php } ?>
                                            <?php 
                                            }else{
                                                if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0][$row['name']], $edit_data[0][$row['name']]);
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? html_entity_decode($edit_data[0][$row['name']]) : (isset($row['value']) ? $row['value'] : '') ?>
                                            <?php }} ?>
                                            
                                            
                                            <?php
                                        } else if ($row['type'] == 'checkbox-group') {
                                            if (!empty($edit_data[0][$row['name']])) {
                                                $chk = explode(',', $edit_data[0][$row['name']]);
                                                foreach ($chk as $chk) {
                                                    echo $chk . "\n";
                                                }
                                            } else {

                                                if (count($row['values']) > 0) {

                                                    foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected']) ? '<li>' . $chked['value'] . "\n" : '';
                                                    }
                                                }
                                            }
                                            ?>

                                            <?php
                                        } else if ($row['type'] == 'select') {
                                            if (!empty($edit_data[0][$row['name']])) {
                                                echo!empty($edit_data[0][$row['name']]) ? nl2br(htmlentities($edit_data[0][$row['name']])) : '';
                                            } else {
                                                if (count($row['values']) > 0) {

                                                    foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected']) ? $chked['value'] : '';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'radio-group') {
                        ?>
                        <div class="<?= ($row['type'] == 'header') ? 'col-sm-12' : 'col-sm-12' ?>">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <?php
                                    if (!empty($edit_data[0][$row['name']])) {
                                        if($edit_data[0][$row['name']] == 'high'){
                                            $class_name = "label-danger";
                                        }else if($edit_data[0][$row['name']] == 'low'){
                                            $class_name = "label-success";    
                                        }else if($edit_data[0][$row['name']] == 'Medium'){
                                                $class_name = "label-warning";  
                                        }

                                        echo ($edit_data[0][$row['name']] == 'high') ? '<span class="label label-danger">High</span>' : (($edit_data[0][$row['name']] == 'Medium') ? '<span class="label label-warning">Medium</span>' : (($edit_data[0][$row['name']] == 'low') ? '<span class="label label-success">Low</span>' : '<span class="label label-danger">' . $edit_data[0][$row['name']] . '</span>'));
                                    } else {
                                        if (count($row['values']) > 0) {

                                            foreach ($row['values'] as $chked) {
                                                echo isset($chked['selected']) ? ($chked['value'] == 'high') ? '<span class="label label-danger">High</span>' : (($chked['value'] == 'Medium') ? '<span class="label label-warning">Medium</span>' : '<span class="label label-success">Low</span>') : '';
                                            }
                                        }
                                    }
                                    ?>
                                    <!--code added for green edit-->
                                    <?php
                                    if (!empty($prev_edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])) {
                                        if ($prev_edit_data[0][$row['name']] != $edit_data[0][$row['name']]) {
                                            ?>
                                            <span class="text-success">&nbsp;<i class="fa fa-edit" aria-hidden="true"></i></span>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <!--code added for green check ends here-->
                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'header') {
                        ?>
                        <div class="col-sm-12">
                            <div class="">
                                <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                <?php echo '<' . $head . ' class="page-title">'; ?>
                                <?= !empty($row['label']) ? $row['label'] : '' ?>

                                <?php echo '</' . $head . '>'; ?>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'file') {
                        ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
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
                } //foreach
            }
            ?>

<div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>Amendments</h2>

                        <?php if (!empty($item_details)) {$n= 0;
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
                                            <p class="date"><small>
                                                <?php echo $row['create_name'] ?>: <?php echo configDateTimeFormat($row['modified_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php $n++; }
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
                                            <p class="date"><small>
                                                <?php echo $row['create_name'] ?>: <?php echo configDateTimeFormat($row['modified_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php $n++; }
                        }
                        ?>   
                        
                    </div>
                </div>
            </div> 

<?php if (!empty($edit_data)) { 
    if (checkPermission('IndividualStrategies', 'signoff')) {
    ?>
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>sign off</h2>
                        <?php
                            if(empty($check_signoff_data)){
                                ?>
                                
                                
                                <input type="checkbox" name="is_signoff" onclick="manager_request(<?php echo $ypid.','.$edit_data[0]['individual_strategies_id']; ?>);" class="ra_signoff" value="1">
                            <?php
                             $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
                            echo getUserName($login_user_id);
                             
                        }
                        ?>


                        <?php
                       if (!empty($ra_signoff_data)) {
                                foreach ($ra_signoff_data as $sign_name) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="date"><small><?php echo $sign_name['name'] ?>: <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php }
                        }
                        ?> 

                    </div>
                </div>
            </div>
            <?php }} ?>
            <?php if (!empty($edit_data[0]['individual_strategies_id'])) { 
                if (checkPermission('IndividualStrategies', 'document_signoff')) {
                ?>
            <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                     <a href="javascript:;" data-href="<?php echo base_url() . 'IndividualStrategies/signoff/'.$ypid.'/'.$edit_data[0]['individual_strategies_id']; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning"><?php echo lang('external_approval'); ?></span></a>

                                      <?php if(!empty($check_external_signoff_data)){ ?>
                                <a href="<?php echo base_url() . 'IndividualStrategies/external_approval_list/'.$ypid.'/'.$edit_data[0]['individual_strategies_id']; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                    </a>
                                      <?php } ?>
                                </div>
                            </div>
                        </div> 
                        <?php } } ?>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group">
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                       <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>

                    <?php if (checkPermission('IndividualStrategies', 'edit')) { ?>
                        <a href="<?= base_url('IndividualStrategies/edit/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-edit"></i> EDIT IS
                        </a>
                    <?php } ?>
                    
                    <?php if (checkPermission('ArchiveIndividualStrategies', 'view')) { ?>
                        <a href="<?= base_url('ArchiveIndividualStrategies/index/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-search"></i> VIEW ARCHIVE
                        </a>
                    <?php } ?>
                    <?php if (!empty($edit_data[0]['individual_strategies_id'])) { ?>
                        <a href="<?= base_url('IndividualStrategies/DownloadPdf/' . $edit_data[0]['individual_strategies_id'] . '/' . $ypid); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PDF </a>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>