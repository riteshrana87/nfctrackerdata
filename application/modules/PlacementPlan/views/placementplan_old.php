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
		
		$currentDate =  configDateTimeFormat($edit_data[0]['modified_date']);
		//echo $currentDate;
		?>
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?><?php if($updated_by != ""){?><small>&nbsp;&nbsp;Updated By:</small> <?php echo $updated_by;?><?php }?><br><small>Last Edit Date:</small>  <?= (!empty($edit_data[0]['modified_date']) && $edit_data[0]['modified_date'] != '0000-00-00') ? $currentDate : '' ?>
        </h1>
    </div>
        <div class="row">
		
				<div id="section1">
					<div class="col-sm-12">
					<div class="panel panel-default tile tile-profile">
					<div class="panel-body">
						<h2><div id="mainHeader1">
							Pre Placement Information
						</div></h2>
						<div class="form-group"><div id="">
						<?php //echo $cpt_item_archive[0]['pre_placement_info']."</br>";
								//echo $edit_data[0]['pre_placement_info']."</br>";
						?>
						<?php $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_info'], $edit_data[0]['pre_placement_info']);$diff->build();
						
						echo $diff->getDifference()
						
						?>
						
							
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
						
						<?php if(!empty($edit_data[0]['pre_placement_family'])){ ?>
						<div class="form-group">
						<div id="">
						<?php $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_family'], $edit_data[0]['pre_placement_family']);$diff->build();echo $diff->getDifference() ?>
								
						</div>
						</div>
						<?php }?>
						<?php if(!empty($edit_data[0]['pre_placement_edu'])){ ?>
						<div class="form-group">
						<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_edu'], $edit_data[0]['pre_placement_edu']);$diff->build();echo $diff->getDifference() ?>
						</div>
						</div>
						<?php }?>
						<?php if(!empty($edit_data[0]['pre_placement_relation'])){ ?>
						<div class="form-group">
						<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_relation'], $edit_data[0]['pre_placement_relation']);$diff->build();echo $diff->getDifference() ?>
						</div>
						</div>
						<?php }?>
						
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
						
						<?php if(!empty($edit_data[0]['pre_placement_school'])){ ?>
						<div class="form-group">
						<div id="" style="border-top: 1px solid #ccc;">
						<?php  $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_school'], $edit_data[0]['pre_placement_school']);$diff->build();echo $diff->getDifference();  ?>
						</div>
						</div>
						<?php }  ?>
						<?php if(!empty($edit_data[0]['pre_placement_contact'])){ ?>
						<div class="form-group">
						<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_contact'], $edit_data[0]['pre_placement_contact']);$diff->build();echo $diff->getDifference() ?>
							
						</div>
						</div>
						<?php }  ?>
						<?php if(!empty($edit_data[0]['pre_placement_therapy'])){ ?>
						<div class="form-group">
						<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_therapy'], $edit_data[0]['pre_placement_therapy']);$diff->build();echo $diff->getDifference() ?>
						</div>
						</div>
						<?php }  ?>
						<?php if(!empty($edit_data[0]['pre_placement_appointment'])){ ?>
						<div class="form-group">
						<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_appointment'], $edit_data[0]['pre_placement_appointment']);$diff->build();echo $diff->getDifference() ?>
						</div>
						</div>
						<?php }  ?>
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
						<?php $diff = new HtmlDiff($pp_health_archve_data['pre_placement'],$health_data['pre_placement']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php $diff = new HtmlDiff($pp_health_archve_data['risk_assesment'],$health_data['risk_assesment']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						
						
						<?php $diff = new HtmlDiff($pp_health_archve_data['individual_strategies'],$health_data['individual_strategies']);
						$diff->build();
						
						echo $diff->getDifference() ?>
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
						
						<?php $diff = new HtmlDiff($pp_edu_archve_data['pre_placement_edu_sub'],$edu_data['pre_placement_edu_sub']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php $diff = new HtmlDiff($pp_edu_archve_data['risk_assesment_edu'],$edu_data['risk_assesment_edu']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php $diff = new HtmlDiff($pp_edu_archve_data['individual_strategies_edu'],$edu_data['individual_strategies_edu']);
						$diff->build();
						
						echo $diff->getDifference() ?>
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
						
						<?php $diff = new HtmlDiff($pp_tra_archve_data['pre_placement_tra'],$tra_data['pre_placement_tra']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php $diff = new HtmlDiff($pp_tra_archve_data['risk_assesment_tra'],$tra_data['risk_assesment_tra']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
					<?php $diff = new HtmlDiff($pp_tra_archve_data['individual_strategies_tra'],$tra_data['individual_strategies_tra']);
						$diff->build();
						
						echo $diff->getDifference() ?>
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
						<?php $diff = new HtmlDiff($pp_con_archve_data['pre_placement_con'],$con_data['pre_placement_con']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_con_archve_data['risk_assesment_con'],$con_data['risk_assesment_con']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_con_archve_data['individual_strategies_con'],$con_data['individual_strategies_con']);
						$diff->build();
						
						echo $diff->getDifference() ?>
						
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
						<?php $diff = new HtmlDiff($pp_ft_archve_data['pre_placement_ft'],$ft_data['pre_placement_ft']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_ft_archve_data['risk_assesment_ft'],$ft_data['risk_assesment_ft']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_ft_archve_data['individual_strategies_ft'],$ft_data['individual_strategies_ft']);
						$diff->build();
						
						echo $diff->getDifference() ?>
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
						<?php $diff = new HtmlDiff($pp_mgi_archve_data['pre_placement_mgi'],$mgi_data['pre_placement_mgi']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_mgi_archve_data['risk_assesment_mgi'],$mgi_data['risk_assesment_mgi']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_mgi_archve_data['individual_strategies_mgi'],$mgi_data['individual_strategies_mgi']);
						$diff->build();
						
						echo $diff->getDifference() ?>
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
						
						<?php $diff = new HtmlDiff($pp_pr_archve_data['pre_placement_pr'],$pr_data['pre_placement_pr']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php $diff = new HtmlDiff($pp_pr_archve_data['risk_assesment_pr'],$pr_data['risk_assesment_pr']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						
						<?php $diff = new HtmlDiff($pp_pr_archve_data['individual_strategies_pr'],$pr_data['individual_strategies_pr']);
						$diff->build();
						
						echo $diff->getDifference() ?>
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
						<?php $diff = new HtmlDiff($pp_bc_archve_data['pre_placement_bc'],$bc_data['pre_placement_bc']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment (RA)</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_bc_archve_data['risk_assesment_bc'],$bc_data['risk_assesment_bc']);
						$diff->build();
						
						echo $diff->getDifference() ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES(IS)</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_bc_archve_data['individual_strategies_bc'],$bc_data['individual_strategies_bc']);
						$diff->build();
						
						echo $diff->getDifference() ?>
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
                        <a href="<?= base_url('PlacementPlan/DownloadPrint/' . $edit_data[0]['placement_plan_id'] . '/' . $ypid . '/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PRINT </a>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
