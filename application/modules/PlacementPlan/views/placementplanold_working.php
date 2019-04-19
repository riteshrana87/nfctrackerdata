<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
    var already_pp_review = '<?php echo $this->lang->line('already_pp_review')?>';

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
            Placement Plan <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <?php if (checkPermission('PlacementPlan', 'edit')) { ?>
                        <a href="<?= base_url('PlacementPlan/edit/' . $ypid); ?>" class="btn btn-default">                                    <i class="fa fa-edit"></i> EDIT PP
                        </a>
                    <?php } ?>

                    <?php if (checkPermission('ArchivePlacementPlan', 'view')) { ?>
                        <a href="<?= base_url('ArchivePlacementPlan/index/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-search"></i> VIEW ARCHIVE
                        </a>
                    <?php } ?>
                    <?php if (!empty($edit_data)) { ?>
                        <a href="<?= base_url('PlacementPlan/DownloadPrint/' . $edit_data[0]['placement_plan_id'] . '/' . $ypid . '/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
                    <?php } ?>
                </div>
            </div>
        </h1>
        <h1 class="page-title">
		<?php 
		
		$currentDate =  date("d/m/Y H:i:s", strtotime($edit_data[0]['modified_date']));
		//echo $currentDate;
		?>
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?><?php if($updated_by != ""){?><small>&nbsp;&nbsp;Updated By:</small> <?php echo $updated_by;?><?php }?><br><small>Last Edit Date:</small>  <?= (!empty($edit_data[0]['modified_date']) && $edit_data[0]['modified_date'] != '0000-00-00') ? $currentDate : '' ?>
        </h1>
    </div>
        <div class="row">
            <?php
            if (!empty($pp_form_data)) {
                foreach ($pp_form_data as $row) {

                    if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
                        <div class="<?= ($row['type'] == 'header') ? 'col-sm-12' : 'col-sm-6 test-loop' ?>">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class ="slimScroll-120">
                                        <?php
                                        if ($row['type'] == 'textarea' || $row['type'] == 'number') {
                                            if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0][$row['name']], $edit_data[0][$row['name']]);
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? html_entity_decode($edit_data[0][$row['name']]) : (isset($row['value']) ? $row['value'] : '') ?>
                                            <?php } ?>
                                        <?php } else if ($row['type'] == 'text') {  
                                            if($row['subtype'] == 'time'){
                                                if (!empty($prev_edit_data)) {
                                                $diff = new HtmlDiff(timeformat($prev_edit_data[0][$row['name']]), timeformat($edit_data[0][$row['name']]));
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? timeformat($edit_data[0][$row['name']]) : (isset($row['value']) ? timeformat($row['value']) : '') ?>
                                            <?php } ?>
                                            <?php }else{ ?>
                                            <?php if (!empty($prev_edit_data)) {
                                                $diff = new HtmlDiff($prev_edit_data[0][$row['name']], $edit_data[0][$row['name']]);
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? html_entity_decode($edit_data[0][$row['name']]) : (isset($row['value']) ? $row['value'] : '') ?>
                                            <?php } ?>

                                            <?php } ?>


                                          <?php
                                        } else if ($row['type'] == 'date') {
                                              if (!empty($prev_edit_data)) {
                                                $diff = new HtmlDiff(configDateTime($prev_edit_data[0][$row['name']]),configDateTime($edit_data[0][$row['name']]));
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? configDateTime($edit_data[0][$row['name']]) : (isset($row['value']) ? configDateTime($row['value']) : '') ?>
                                            <?php } ?>
                                             

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
                                        } else if ($row['type'] == 'radio-group' || $row['type'] == 'select') {
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
                                            'filePathMain' => $this->config->item('pp_img_base_url') . $ypid,
                                            'filePathThumb' => $this->config->item('pp_img_base_url_small') . $ypid
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
                    if (checkPermission('PlacementPlan', 'signoff')) { 
                    ?> 
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                            <h2>sign off</h2>
                            <?php if(empty($check_signoff_data)){ ?>

                                    <input type="checkbox" onclick="manager_request(<?php echo $ypid . ',' . $edit_data[0]['placement_plan_id']; ?>);" name="pp_signoff" required="true" class="pp_signoff" value="1">
                                    <?php
                                     $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
                            echo getUserName($login_user_id);
                                
                            }
                        
                            ?>

                            <?php
                       if (!empty($signoff_data)) {
                                foreach ($signoff_data as $sign_name) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo date("d/m/Y H:i:s", strtotime($sign_name['created_date'])) ?></small></p>
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
                <?php if (!empty($edit_data[0]['placement_plan_id'])) { 
                     if (checkPermission('PlacementPlan', 'document_signoff')) {
                    ?>
                 <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                     <a href="javascript:;" data-href="<?php echo base_url() . 'PlacementPlan/signoff/'.$ypid.'/'.$edit_data[0]['placement_plan_id']; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning"><?php echo lang('external_approval'); ?></span></a>
                                     <?php if(!empty($check_external_signoff_data)){ ?>
                                     <a href="<?php echo base_url() . 'PlacementPlan/external_approval_list/'.$ypid.'/'.$edit_data[0]['placement_plan_id']; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
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
                    <div class="btn-group">
                        <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                        </a>
                        <?php if (checkPermission('PlacementPlan', 'edit')) { ?>
                            <a href="<?= base_url('PlacementPlan/edit/' . $ypid); ?>" class="btn btn-default">                                    <i class="fa fa-edit"></i> EDIT PP
                            </a>
                        <?php } ?>
                        <?php 
						/* Start - Mantis issue #7391, Maitrak Modi, Dt: 6th Dec 2017 */
						/*
						if (checkPermission('ArchivePlacementPlan', 'add')) { ?>
                            <a href="<?= base_url('ArchivePlacementPlan/createArchive/' . $ypid); ?>" class="btn btn-default">
                                <i class="fa fa-edit"></i> CREATE ARCHIVE
                            </a>
                        <?php } 
						*/
						/* End - Mantis issue #7391, Maitrak Modi, Dt: 6th Dec 2017 */
						?>

                        <?php if (checkPermission('ArchivePlacementPlan', 'view')) { ?>
                            <a href="<?= base_url('ArchivePlacementPlan/index/' . $ypid); ?>" class="btn btn-default">
                                <i class="fa fa-search"></i> VIEW ARCHIVE
                            </a>
                        <?php } ?>
						
						<?php if (!empty($edit_data)) { ?>
                        <a href="<?= base_url('PlacementPlan/DownloadPrint/' . $edit_data[0]['placement_plan_id'] . '/' . $ypid . '/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
