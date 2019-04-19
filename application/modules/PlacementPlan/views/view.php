<script>
    var YPId = '<?=!empty($ypid)?$ypid:''?>';
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
                    <?php
                    /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                     if($past_care_id == 0){ ?>
                    <a href="<?=base_url('PlacementPlan/external_approval_list/'.$ypid.'/'.$ppid); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>
                    <?php }else{ ?>
                    <a href="<?=base_url('PlacementPlan/external_approval_list/'.$ypid.'/'.$ppid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>
                    <?php } ?>
                </div>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00 00:00:00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
		<?php
            $n = 0;
            if (!empty($pp_form_data)) {
                /* $diff = new Diffe;
                  $difference = new stdClass;
                  $difference->mode = 'w';
                  $difference->patch = true;
                  $after_patch = new stdClass; */
                foreach ($pp_form_data as $row) {

                    if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
                        <div class="<?= ($row['type'] == 'header') ? 'col-sm-12' : 'col-sm-6' ?>">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class ="slimScroll-120">
                                        <?php
                                        if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {

                                            if (!empty($form_old_data) && $row['name'] == $form_old_data[$n]['name']) {

                                                /* if($diff->FormatDiffAsHtml($form_old_data[$n]['value'],$row['value'], $difference)
                                                  && $diff->Patch($form_old_data[$n]['value'], $difference->difference, $after_patch))
                                                  {
                                                  echo  html_entity_decode($difference->html);

                                                  } */
                                                $diff = new HtmlDiff($form_old_data[$n]['value'], $row['value']);
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { ?>
                                                <?= !empty($edit_data[0][$row['name']]) ? html_entity_decode($edit_data[0][$row['name']]) : (isset($row['value']) ? $row['value'] : '') ?>
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

                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'radio-group') {
                        ?>
                        <div class="col-sm-6">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <label><?= !empty($row['label']) ? $row['label'] : '' ?></label>
                                    <div class="pull-right">
                                        <?php
                                        if (!empty($edit_data[0][$row['name']])) {
                                            echo ($edit_data[0][$row['name']] == 'yes') ? '<span class="label label-success">Yes</span>' : (($edit_data[0][$row['name']] == 'no') ? '<span class="label label-danger">No</span>' : '<span class="label label-success">' . $edit_data[0][$row['name']] . '</span>');
                                        } else {
                                            if (!empty($row['value'])) {
                                                echo ($row['value'] == 'yes') ? '<span class="label label-success">Yes</span>' : (($row['value'] == 'no') ? '<span class="label label-danger">No</span>' : '<span class="label label-success">' . $row['value'] . '</span>');
                                            }
                                        }
                                        ?></div>
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
            if (!empty($edit_data[0][$row['name']])) {
                $imgs = explode(',', $edit_data[0][$row['name']]);
                foreach ($imgs as $img) {
                    ?>
                                                <div class="col-sm-1 margin-bottom">
                                                <?php
                                                if (@is_array(getimagesize($this->config->item('pp_img_base_url') . $ypid . '/' . $img))) {
                                                    ?>
                                                        <img width="100" height="100" src="<?= $this->config->item('pp_img_base_url_small') . $ypid . '/' . $img ?>">
                                                    <?php
                                                } else {
                                                    ?>
                                                        <img width="100" height="100" src="<?= base_url('uploads/images/icons 64/file-ico.png') ?>">
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                    <?php
                                                }
                                            }
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
            <div id="section1">
					<div class="col-sm-12">
					<div class="panel panel-default tile tile-profile">
					<div class="panel-body">
						<h2><div id="mainHeader1">
							Pre Placement Information
						</div></h2>
						<div class="form-group"><div id="">
							 <?= !empty($edit_data[0]['pre_placement_info']) ? $edit_data[0]['pre_placement_info'] : '' ?>
						</div></div>
						</div>
					</div></div>
					<div class="col-sm-6">
					<div class="panel panel-default tile tile-profile">
					<div class="panel-body">
						<h2><div id="mainHeader1">
							Aims of Placement
							<small>Long Term Plans</small>
						</div></h2>
						
						<?php if(!empty($edit_data_pp_aim)){
					//pr($pp_health_archve_data);
					
					?>
				<?php foreach($edit_data_pp_aim as $aim_data){ //pr($health_data);?>
					
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php  /*!empty($health_data['pre_placement']) ? $health_data['pre_placement'] : lang('NA') */
						//echo $pp_health_archve_data['pre_placement']."</br>";
						//echo $health_data['pre_placement']."</br>";
						?>
						<?php 
						
						echo $aim_data['aims_of_placement_data']; ?>
					</div>
					
					
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
						
					
					
					</div>
					</div>
					</div>
					<div class="col-sm-6">
					<div class="panel panel-default tile tile-profile">
					<div class="panel-body">
                        <h2><div id="mainHeader1">
                            Actions from LAC Review 
                            <small>Medium Term Plans</small>
                        </div></h2>
                        
                      <?php if(!empty($edit_data_pp_lac)){
					//pr($pp_health_archve_data);
					
					?>
				<?php foreach($edit_data_pp_lac as $lac_data){ //pr($health_data);?>
					
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php  /*!empty($health_data['pre_placement']) ? $health_data['pre_placement'] : lang('NA') */
						//echo $pp_health_archve_data['pre_placement']."</br>";
						//echo $health_data['pre_placement']."</br>";
						?>
						<?php 
						
						echo $lac_data['lac_review_data']; ?>
					</div>
					
					
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
                   
                     
                    </div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
		 
			
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h2><div id="mainHeader1">
						Health
					</div></h2>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_health)){
						//pr($pp_health_archve_data);
					
					?>
				<?php foreach($edit_data_pp_health as $health_data){ //pr($health_data);?>
					
					<div class="col-md-12 pp_title_1">
						<?= !empty($health_data['heading']) ? $health_data['heading'] : lang('NA') ?></div>
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan(PP)</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php  /*!empty($health_data['pre_placement']) ? $health_data['pre_placement'] : lang('NA') */
						//echo $pp_health_archve_data['pre_placement']."</br>";
						//echo $health_data['pre_placement']."</br>";
						?>
						<?php 
						
						echo $health_data['pre_placement']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php
						echo $health_data['risk_assesment']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						
						
						<?php 
						echo $health_data['individual_strategies']; ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					
				</div>
			
				</div>
				
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h2><div id="mainHeader1">
						Education
					</div></h2>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_edu)){
					
					
					?>
				<?php foreach($edit_data_pp_edu as $edu_data){ //pr($edu_data);?>
					
					<div class="col-md-12 pp_title_1"><?= !empty($edu_data['heading_edu']) ? $edu_data['heading_edu'] : lang('NA') ?></div>
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan(PP)</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php 
						
						echo $edu_data['pre_placement_edu_sub']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php 
						echo $edu_data['risk_assesment_edu']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php 
						echo $edu_data['individual_strategies_edu']; ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					
				</div>
			
				</div>
				
				
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h2><div id="mainHeader1">
						Transport
					</div></h2>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_tra)){
					
					 
					?>
				<?php foreach($edit_data_pp_tra as $tra_data){ //pr($tra_data);?>
					
					<div class="col-md-12 pp_title_1"><?= !empty($tra_data['heading_tra']) ? $tra_data['heading_tra'] : lang('NA') ?></div>
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan(PP)</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php
						echo $tra_data['pre_placement_tra']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php 
						echo $tra_data['risk_assesment_tra']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
					<?php 
						echo $tra_data['individual_strategies_tra']; ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					
				</div>
			
				</div>
				
				
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h2><div id="mainHeader1">
						Contact
					</div></h2>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_con)){
					
					 
					?>
				<?php foreach($edit_data_pp_con as $con_data){ //pr($con_data);?>
					
					<div class="col-md-12 pp_title_1"><?= !empty($con_data['heading_con']) ? $con_data['heading_con'] : lang('NA') ?></div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan(PP)</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $con_data['pre_placement_con']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $con_data['risk_assesment_con']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $con_data['individual_strategies_con']; ?>
						
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					
				</div>
			
				</div>
				
				
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h2><div id="mainHeader1">
						Free Time
					</div></h2>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_ft)){
					
					 
					?>
				<?php foreach($edit_data_pp_ft as $ft_data){ //pr($ft_data);?>
					<div class="col-md-12 pp_title_1"><?= !empty($ft_data['heading_ft']) ? $ft_data['heading_ft'] : lang('NA') ?></div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan(PP)</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php
						echo $ft_data['pre_placement_ft']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						
						echo $ft_data['risk_assesment_ft']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $ft_data['individual_strategies_ft']; ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					
				</div>
			
				</div>
				
				
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h2><div id="mainHeader1">
						Mobile, Gaming & Internet
					</div></h2>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_mgi)){
					
					 
					?>
				<?php foreach($edit_data_pp_mgi as $mgi_data){ //pr($mgi_data);?>
					
					<div class="col-md-12 pp_title_1"><?= !empty($mgi_data['heading_mgi']) ? $mgi_data['heading_mgi'] : lang('NA') ?></div>
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan(PP)</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $mgi_data['pre_placement_mgi']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $mgi_data['risk_assesment_mgi']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						
						echo $mgi_data['individual_strategies_mgi']; ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					
				</div>
			
				</div>
				
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h2><div id="mainHeader1">
						Positive Relationships
					</div></h2>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_pr)){
					
					 
					?>
				<?php foreach($edit_data_pp_pr as $pr_data){ //pr($pr_data);?>
					
					<div class="col-md-12 pp_title_1"><?= !empty($pr_data['heading_pr']) ? $pr_data['heading_pr'] : lang('NA') ?></div>
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan(PP)</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php 
						echo $pr_data['pre_placement_pr'];?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php 
						echo $pr_data['risk_assesment_pr']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php 
						echo $pr_data['individual_strategies_pr']; ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					
				</div>
			
				</div>
				
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h2><div id="mainHeader1">
						Behaviour Concerns
					</div></h2>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_bc)){
					
					
					 
					?>
				<?php foreach($edit_data_pp_bc as $bc_data){ //pr($bc_data);?>
					
					<div class="col-md-12 pp_title_1"><?= !empty($bc_data['heading_bc']) ? $bc_data['heading_bc'] : lang('NA') ?></div>
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan(PP)</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $bc_data['pre_placement_bc']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $bc_data['risk_assesment_bc']; ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
						echo $bc_data['individual_strategies_bc'];?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php } } else { echo lang('NA'); } ?>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					
				</div>
			
				</div>

            <?php if (checkPermission('PlacementPlan', 'signoff')) { 
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
                        <?php
                        /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                         if($past_care_id == 0){ ?>
                    <a href="<?=base_url('PlacementPlan/external_approval_list/'.$ypid.'/'.$ppid); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>
                    <?php }else{ ?>
                    <a href="<?=base_url('PlacementPlan/external_approval_list/'.$ypid.'/'.$ppid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>