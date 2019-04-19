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
                   <a href="<?= base_url('PlacementPlan/DownloadPdf/' . $ypid . '/' . $signoff_id); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Generate PDF </a>
                </div>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?><?php if($updated_by != ""){?><small>&nbsp;&nbsp;Updated By:</small> <?php echo $updated_by;?><?php }?>
        </h1>
		
        </div>
		
        <div class="row">
		<div class="col-sm-12">
		<div class="">
		<h1 class="page-title"> PRE PLACEMENT INFORMATION</h1>
                       
        </div>
        </div>
		<div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>PRE PLACEMENT INFORMATION</h2>
                                    <div class ="slimScroll-120">
                                      
									<?=!empty($edit_data[0]['pre_placement_info'])?html_entity_decode($edit_data[0]['pre_placement_info']):(isset($edit_data[0]['pre_placement_info'])?$edit_data[0]['pre_placement_info']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
        </div>
		<div class="clearfix"></div>
		<div class="col-sm-12">
		<div class="">
		<h1 class="page-title"> </h1>
                       
        </div>
        </div>
		<div class="col-sm-12">
			<h1 class="page-title"> Aims of Placement
										<small>Long Term Plans</small></h1>
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Return to family home
									</div></h2>
                                    <div class ="slimScroll-120">
                                      
									<?=!empty($edit_data[0]['pre_placement_family'])?html_entity_decode($edit_data[0]['pre_placement_family']):(isset($edit_data[0]['pre_placement_family'])?$edit_data[0]['pre_placement_family']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
							<div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Complete Education
									</div></h2>
                                    <div class ="slimScroll-120">
                                      
									<?=!empty($edit_data[0]['pre_placement_edu'])?html_entity_decode($edit_data[0]['pre_placement_edu']):(isset($edit_data[0]['pre_placement_edu'])?$edit_data[0]['pre_placement_edu']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
							
							<div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Repair Parent Relationship
									</div></h2>
                                    <div class ="slimScroll-120">
                                      
									<?=!empty($edit_data[0]['pre_placement_relation'])?html_entity_decode($edit_data[0]['pre_placement_relation']):(isset($edit_data[0]['pre_placement_relation'])?$edit_data[0]['pre_placement_relation']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
        </div>
		
		<div class="col-sm-12">
			<h1 class="page-title">Actions from LAC Review 
						<small>Medium Term Plans</small></h1>
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Transition Plan into school
									</div></h2>
                                    <div class ="slimScroll-120">
                                      
									<?=!empty($edit_data[0]['pre_placement_school'])?html_entity_decode($edit_data[0]['pre_placement_school']):(isset($edit_data[0]['pre_placement_school'])?$edit_data[0]['pre_placement_school']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
							<div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Create Home Contact Plan
									</div></h2>
                                    <div class ="slimScroll-120">
                                      
									<?=!empty($edit_data[0]['pre_placement_contact'])?html_entity_decode($edit_data[0]['pre_placement_contact']):(isset($edit_data[0]['pre_placement_contact'])?$edit_data[0]['pre_placement_contact']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
							
							<div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Commence therapy
									</div></h2>
                                    <div class ="slimScroll-120">
                                      
									<?=!empty($edit_data[0]['pre_placement_therapy'])?html_entity_decode($edit_data[0]['pre_placement_therapy']):(isset($edit_data[0]['pre_placement_therapy'])?$edit_data[0]['pre_placement_therapy']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
							
							<div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Attend Health appointments
									</div></h2>
                                    <div class ="slimScroll-120">
                                      
									<?=!empty($edit_data[0]['pre_placement_appointment'])?html_entity_decode($edit_data[0]['pre_placement_appointment']):(isset($edit_data[0]['pre_placement_appointment'])?$edit_data[0]['pre_placement_appointment']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
        </div>
            <div class="clearfix"></div>
			
			<div class="col-sm-12">
			<h1 class="page-title">Health </h1>
				<?php if(!empty($edit_data_pp_health)){ ?>
				<?php foreach($edit_data_pp_health as $health_data){?>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Placement Plan(PP)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($health_data['pre_placement'])?html_entity_decode($health_data['pre_placement']):(isset($health_data['pre_placement'])?$health_data['pre_placement']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Risk Assestment (RA)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($health_data['risk_assesment'])?html_entity_decode($health_data['risk_assesment']):(isset($health_data['risk_assesment'])?$health_data['risk_assesment']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div><div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										INDIVIDUAL STRATEGIES(IS)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($health_data['individual_strategies'])?html_entity_decode($health_data['individual_strategies']):(isset($health_data['individual_strategies'])?$health_data['individual_strategies']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<?php } }?>		
							
							
        </div>
		
		
		<div class="clearfix"></div>
			
			<div class="col-sm-12">
			<h1 class="page-title">Education </h1>
				<?php if(!empty($edit_data_pp_edu)){ ?>
				<?php foreach($edit_data_pp_edu as $edu_data){ //pr($edu_data);?>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Placement Plan(PP)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($edu_data['pre_placement_edu_sub'])?html_entity_decode($edu_data['pre_placement_edu_sub']):(isset($edu_data['pre_placement_edu_sub'])?$edu_data['pre_placement_edu_sub']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Risk Assestment (RA)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($edu_data['risk_assesment_edu'])?html_entity_decode($edu_data['risk_assesment_edu']):(isset($edu_data['risk_assesment_edu'])?$edu_data['risk_assesment_edu']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div><div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										INDIVIDUAL STRATEGIES(IS)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($edu_data['individual_strategies_edu'])?html_entity_decode($edu_data['individual_strategies_edu']):(isset($edu_data['individual_strategies_edu'])?$edu_data['individual_strategies_edu']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<?php } }?>		
							
							
        </div>
		
		<div class="clearfix"></div>
			
			<div class="col-sm-12">
			<h1 class="page-title">Transport </h1>
				<?php if(!empty($edit_data_pp_tra)){ ?>
				<?php foreach($edit_data_pp_tra as $tra_data){ //pr($tra_data);?>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Placement Plan(PP)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($tra_data['pre_placement_tra'])?html_entity_decode($tra_data['pre_placement_tra']):(isset($tra_data['pre_placement_tra'])?$tra_data['pre_placement_tra']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Risk Assestment (RA)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($tra_data['risk_assesment_tra'])?html_entity_decode($tra_data['risk_assesment_tra']):(isset($tra_data['risk_assesment_tra'])?$tra_data['risk_assesment_tra']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div><div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										INDIVIDUAL STRATEGIES(IS)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($tra_data['individual_strategies_tra'])?html_entity_decode($tra_data['individual_strategies_tra']):(isset($tra_data['individual_strategies_tra'])?$tra_data['individual_strategies_tra']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<?php } }?>		
							
							
        </div>
		
		
		<div class="clearfix"></div>
			
			<div class="col-sm-12">
			<h1 class="page-title">Contact </h1>
				<?php if(!empty($edit_data_pp_con)){ ?>
				<?php foreach($edit_data_pp_con as $con_data){ //pr($con_data);?>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Placement Plan(PP)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($con_data['pre_placement_con'])?html_entity_decode($con_data['pre_placement_con']):(isset($con_data['pre_placement_con'])?$con_data['pre_placement_con']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Risk Assestment (RA)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($con_data['risk_assesment_con'])?html_entity_decode($con_data['risk_assesment_con']):(isset($con_data['risk_assesment_con'])?$con_data['risk_assesment_con']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div><div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										INDIVIDUAL STRATEGIES(IS)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($con_data['individual_strategies_con'])?html_entity_decode($con_data['individual_strategies_con']):(isset($con_data['individual_strategies_con'])?$con_data['individual_strategies_con']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<?php } }?>		
							
							
        </div>
		
		
		<div class="clearfix"></div>
			
			<div class="col-sm-12">
			<h1 class="page-title">Free Time </h1>
				<?php if(!empty($edit_data_pp_ft)){ ?>
				<?php foreach($edit_data_pp_ft as $ft_data){ //pr($ft_data);?>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Placement Plan(PP)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($ft_data['pre_placement_ft'])?html_entity_decode($ft_data['pre_placement_ft']):(isset($ft_data['pre_placement_ft'])?$ft_data['pre_placement_ft']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Risk Assestment (RA)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($ft_data['risk_assesment_ft'])?html_entity_decode($ft_data['risk_assesment_ft']):(isset($ft_data['risk_assesment_ft'])?$ft_data['risk_assesment_ft']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div><div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										INDIVIDUAL STRATEGIES(IS)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($ft_data['individual_strategies_ft'])?html_entity_decode($ft_data['individual_strategies_ft']):(isset($ft_data['individual_strategies_ft'])?$ft_data['individual_strategies_ft']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<?php } }?>		
							
							
        </div>
		
		
		<div class="clearfix"></div>
			
			<div class="col-sm-12">
			<h1 class="page-title">Mobile, Gaming & Internet </h1>
				<?php if(!empty($edit_data_pp_mgi)){ ?>
				<?php foreach($edit_data_pp_mgi as $mgi_data){ //pr($mgi_data);?>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Placement Plan(PP)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($mgi_data['pre_placement_mgi'])?html_entity_decode($mgi_data['pre_placement_mgi']):(isset($mgi_data['pre_placement_mgi'])?$mgi_data['pre_placement_mgi']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Risk Assestment (RA)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($mgi_data['risk_assesment_mgi'])?html_entity_decode($mgi_data['risk_assesment_mgi']):(isset($mgi_data['risk_assesment_mgi'])?$mgi_data['risk_assesment_mgi']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div><div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										INDIVIDUAL STRATEGIES(IS)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($mgi_data['individual_strategies_mgi'])?html_entity_decode($mgi_data['individual_strategies_mgi']):(isset($mgi_data['individual_strategies_mgi'])?$mgi_data['individual_strategies_mgi']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<?php } }?>		
							
							
        </div>
		
		
		<div class="clearfix"></div>
			
			<div class="col-sm-12">
			<h1 class="page-title">Positive Relationships </h1>
				<?php if(!empty($edit_data_pp_pr)){ ?>
				<?php foreach($edit_data_pp_pr as $pr_data){ //pr($pr_data);?>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Placement Plan(PP)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($pr_data['pre_placement_pr'])?html_entity_decode($pr_data['pre_placement_pr']):(isset($pr_data['pre_placement_pr'])?$pr_data['pre_placement_pr']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Risk Assestment (RA)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($pr_data['risk_assesment_pr'])?html_entity_decode($pr_data['risk_assesment_pr']):(isset($pr_data['risk_assesment_pr'])?$pr_data['risk_assesment_pr']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div><div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										INDIVIDUAL STRATEGIES(IS)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($pr_data['individual_strategies_pr'])?html_entity_decode($pr_data['individual_strategies_pr']):(isset($pr_data['individual_strategies_pr'])?$pr_data['individual_strategies_pr']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<?php } }?>		
							
							
        </div>
		
		
		<div class="clearfix"></div>
			
			<div class="col-sm-12">
			<h1 class="page-title">Behaviour Concerns </h1>
				<?php if(!empty($edit_data_pp_bc)){ ?>
				<?php foreach($edit_data_pp_bc as $bc_data){ //pr($bc_data);?>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Placement Plan(PP)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($bc_data['pre_placement_bc'])?html_entity_decode($bc_data['pre_placement_bc']):(isset($bc_data['pre_placement_bc'])?$bc_data['pre_placement_bc']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										Risk Assestment (RA)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($bc_data['risk_assesment_bc'])?html_entity_decode($bc_data['risk_assesment_bc']):(isset($bc_data['risk_assesment_bc'])?$bc_data['risk_assesment_bc']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div><div class="col-sm-4">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                   <h2><div id="mainHeader1">
										INDIVIDUAL STRATEGIES(IS)
									</div></h2>
                                    <div class ="slimScroll-120">
                                     
									<?=!empty($bc_data['individual_strategies_bc'])?html_entity_decode($bc_data['individual_strategies_bc']):(isset($bc_data['individual_strategies_bc'])?$bc_data['individual_strategies_bc']:'')?>
                                       
                                    </div>

                                </div>
                            </div>
                </div>
				<?php } }?>		
							
							
        </div>
			
            <?php if (!empty($signoff_data)) { ?>
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
                            <?php } ?> 
                        </div>

                    </div>
                </div>
                <?php } ?> 
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">

                        <div class="panel-body">
                            <h2>User sign off</h2>
                                    <input type="hidden" id="email_data" value="<?php echo $key_data; ?>" name="email_data">
                                    <input type="checkbox" onclick="signoff_request(<?php echo $ypid .','. $signoff_id; ?>);" name="signoff_data" class="" value="active">
                            
                        </div>

                    </div>
                </div>
        </div>
       
    </div>
</div>