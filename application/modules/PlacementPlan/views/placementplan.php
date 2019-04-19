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
						
						  <a href="<?=base_url('Mail/ComposeMailpp/'.$pp_id.'/'.$ypid); ?>" class="btn btn-default">
                                 <i class="fa fa-edit"></i> Email
                            </a>
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
                                        if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {
                                            if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0][$row['name']], $edit_data[0][$row['name']]);
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
						<?php //echo $cpt_item_archive[0]['pre_placement_info']."</br>";
							  //echo $edit_data[0]['pre_placement_info']."</br>";
						?>

                             <?php if (!empty($prev_archive_edit)) {

                                        $diff = new HtmlDiff($prev_archive_edit[0]['pre_placement_info'], $edit_data[0]['pre_placement_info']);
                                        $diff->build();
                                        echo $diff->getDifference();
                                        ?>
                                     <?php } else { ?>
                                            <?= !empty($edit_data[0]['pre_placement_info']) ? $edit_data[0]['pre_placement_info'] : '' ?>
                                     <?php } ?>
									 

						</div></div>
						</div>
					</div></div>
					<div class="col-sm-6">
					<div class="panel panel-default tile tile-profile">
					<div class="panel-body" id="aims_placement">
					<h2><div id="mainHeader1">
						Aims of Placement
						<small>Long Term Plans</small>
					</div></h2>
					
						
						<div class="add_aims" id="add_aims"> 
							<?php if(!empty($edit_data_pp_aim)){
					//$health_count=count($edit_data_pp_health);
					$i=1;
					$n= 0; 
					?>
					
				<?php foreach($edit_data_pp_aim as $aim_data){ //pr($pp_aim_archve_data);?>
				
				<?php if (!empty($pp_aim_archve_data[$n]['aims_of_placement_data']) && $pp_aim_archve_data[$n]['aims_of_placement_id'] == $aim_data['aims_of_placement_id']) {

                                        $diff = new HtmlDiff($pp_aim_archve_data[$n]['aims_of_placement_data'], $aim_data['aims_of_placement_data']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_aim_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $aim_data['aims_of_placement_data']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_aim_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $aim_data['aims_of_placement_data']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($aim_data['aims_of_placement_data']) ? nl2br($aim_data['aims_of_placement_data']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					
					<p></p>
							
									<div class="clearfix"></div>
									
				<?php 
                $i++; 
                $n++;
            } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						</div>
					

					<div class="clearfix"></div>
					
					
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
						
						<div class="add_aims" id="add_aims"> 
							<?php if(!empty($edit_data_pp_lac)){
					//$health_count=count($edit_data_pp_health);
					$i=1;
					$n= 0; 
					?>
					
				<?php foreach($edit_data_pp_lac as $lac_data){ //pr($pp_lac_archve_data);?>
				
				<?php if (!empty($pp_lac_archve_data[$n]['lac_review_data']) && $pp_lac_archve_data[$n]['lac_review_id'] == $lac_data['lac_review_id']) {

                                        $diff = new HtmlDiff($pp_lac_archve_data[$n]['lac_review_data'], $lac_data['lac_review_data']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_lac_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $lac_data['lac_review_data']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_lac_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $lac_data['lac_review_data']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($lac_data['lac_review_data']) ? nl2br($lac_data['lac_review_data']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					
					<p></p>
							
									<div class="clearfix"></div>
									
				<?php 
                $i++;
                $n++;
                 } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						</div>
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
					$n= 0; ?>
				<?php foreach($edit_data_pp_health as $health_data){ //pr($health_data);?>
				<div class="col-md-12 pp_title_1">

					<?php 

					if (!empty($pp_health_archve_data[$n]['heading']) && $pp_health_archve_data[$n]['pp_health_id'] == $health_data['pp_health_id']) {
						
										$diff = new HtmlDiff($pp_health_archve_data[$n]['heading'],$health_data['heading']);
										$diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_health_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $health_data['heading']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_health_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $health_data['heading']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($health_data['heading']) ? nl2br($health_data['heading']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>



					</div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php if (!empty($pp_health_archve_data[$n]['pre_placement']) && $pp_health_archve_data[$n]['pp_health_id'] == $health_data['pp_health_id']) {

                                        $diff = new HtmlDiff($pp_health_archve_data[$n]['pre_placement'], $health_data['pre_placement']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_health_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $health_data['pre_placement']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_health_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $health_data['pre_placement']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($health_data['pre_placement']) ? nl2br($health_data['pre_placement']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php if (!empty($pp_health_archve_data[$n]['risk_assesment']) && $pp_health_archve_data[$n]['pp_health_id'] == $health_data['pp_health_id']) {

                                        $diff = new HtmlDiff($pp_health_archve_data[$n]['risk_assesment'], $health_data['risk_assesment']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_health_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $health_data['risk_assesment']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_health_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $health_data['risk_assesment']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($health_data['risk_assesment']) ? nl2br($health_data['risk_assesment']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php if (!empty($pp_health_archve_data[$n]['individual_strategies']) && $pp_health_archve_data[$n]['pp_health_id'] == $health_data['pp_health_id']) {

                                        $diff = new HtmlDiff($pp_health_archve_data[$n]['individual_strategies'], $health_data['individual_strategies']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_health_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $health_data['individual_strategies']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_health_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $health_data['individual_strategies']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($health_data['individual_strategies']) ? nl2br($health_data['individual_strategies']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php $n++; } } else { echo lang('NA'); } ?>
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
					$e = 0;
					
					?>
				<?php foreach($edit_data_pp_edu as $edu_data){ //pr($edu_data);?>
					<div class="col-md-12 pp_title_1">
					<?php 
					if (!empty($pp_edu_archve_data[$e]['heading_edu']) && $pp_edu_archve_data[$e]['pp_edu_id'] == $edu_data['pp_edu_id']) {
										$diff = new HtmlDiff($pp_edu_archve_data[$e]['heading_edu'], $edu_data['heading_edu']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_edu_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $edu_data['heading_edu']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_edu_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $edu_data['heading_edu']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($edu_data['heading_edu']) ? nl2br($edu_data['heading_edu']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
							
						</div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php if (!empty($pp_edu_archve_data[$e]['pre_placement_edu_sub']) && $pp_edu_archve_data[$e]['pp_edu_id'] == $edu_data['pp_edu_id']) {
										$diff = new HtmlDiff($pp_edu_archve_data[$e]['pre_placement_edu_sub'], $edu_data['pre_placement_edu_sub']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_edu_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $edu_data['pre_placement_edu_sub']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_edu_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $edu_data['pre_placement_edu_sub']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($edu_data['pre_placement_edu_sub']) ? nl2br($edu_data['pre_placement_edu_sub']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>

					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php if (!empty($pp_edu_archve_data[$e]['risk_assesment_edu']) && $pp_edu_archve_data[$e]['pp_edu_id'] == $edu_data['pp_edu_id']) {
										$diff = new HtmlDiff($pp_edu_archve_data[$e]['risk_assesment_edu'], $edu_data['risk_assesment_edu']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_edu_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $edu_data['risk_assesment_edu']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_edu_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $edu_data['risk_assesment_edu']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($edu_data['risk_assesment_edu']) ? nl2br($edu_data['risk_assesment_edu']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>


					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php if (!empty($pp_edu_archve_data[$e]['individual_strategies_edu']) && $pp_edu_archve_data[$e]['pp_edu_id'] == $edu_data['pp_edu_id']) {
										$diff = new HtmlDiff($pp_edu_archve_data[$e]['individual_strategies_edu'], $edu_data['individual_strategies_edu']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_edu_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $edu_data['individual_strategies_edu']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_edu_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $edu_data['individual_strategies_edu']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($edu_data['individual_strategies_edu']) ? nl2br($edu_data['individual_strategies_edu']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php $e++; }  } else { echo lang('NA'); } ?>
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
					$e = 0;
					 
					?>
				<?php foreach($edit_data_pp_tra as $tra_data){ //pr($tra_data);?>
					<div class="col-md-12 pp_title_1">
						<?php 
					if (!empty($pp_tra_archve_data[$e]['heading_tra']) && $pp_tra_archve_data[$e]['pp_tra_id'] == $tra_data['pp_tra_id']) {
										$diff = new HtmlDiff($pp_tra_archve_data[$e]['heading_tra'], $tra_data['heading_tra']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_tra_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $tra_data['heading_tra']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_tra_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $tra_data['heading_tra']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($tra_data['heading_tra']) ? nl2br($tra_data['heading_tra']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>

							
						</div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_tra_archve_data[$e]['pre_placement_tra']) && $pp_tra_archve_data[$e]['pp_tra_id'] == $tra_data['pp_tra_id']) {
										$diff = new HtmlDiff($pp_tra_archve_data[$e]['pre_placement_tra'], $tra_data['pre_placement_tra']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_tra_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $tra_data['pre_placement_tra']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_tra_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $tra_data['pre_placement_tra']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($tra_data['pre_placement_tra']) ? nl2br($tra_data['pre_placement_tra']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment </h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_tra_archve_data[$e]['risk_assesment_tra']) && $pp_tra_archve_data[$e]['pp_tra_id'] == $tra_data['pp_tra_id']) {
										$diff = new HtmlDiff($pp_tra_archve_data[$e]['risk_assesment_tra'], $tra_data['risk_assesment_tra']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_tra_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $tra_data['risk_assesment_tra']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_tra_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $tra_data['risk_assesment_tra']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($tra_data['risk_assesment_tra']) ? nl2br($tra_data['risk_assesment_tra']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
				<?php 
					if (!empty($pp_tra_archve_data[$e]['individual_strategies_tra']) && $pp_tra_archve_data[$e]['pp_tra_id'] == $tra_data['pp_tra_id']) {
										$diff = new HtmlDiff($pp_tra_archve_data[$e]['individual_strategies_tra'], $tra_data['individual_strategies_tra']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_tra_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $tra_data['individual_strategies_tra']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_tra_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $tra_data['individual_strategies_tra']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($tra_data['individual_strategies_tra']) ? nl2br($tra_data['individual_strategies_tra']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php $e ++;} } else { echo lang('NA'); } ?>
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
					$e = 0;
					 
					?>
				<?php foreach($edit_data_pp_con as $con_data){ //pr($con_data);?>
					<div class="col-md-12 pp_title_1">
							<?php 
					if (!empty($pp_con_archve_data[$e]['heading_con']) && $pp_con_archve_data[$e]['pp_con_id'] == $con_data['pp_con_id']) {
										$diff = new HtmlDiff($pp_con_archve_data[$e]['heading_con'], $con_data['heading_con']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_con_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $con_data['heading_con']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_con_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $con_data['heading_con']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($con_data['heading_con']) ? nl2br($con_data['heading_con']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>

						</div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_con_archve_data[$e]['pre_placement_con']) && $pp_con_archve_data[$e]['pp_con_id'] == $con_data['pp_con_id']) {
										$diff = new HtmlDiff($pp_con_archve_data[$e]['pre_placement_con'], $con_data['pre_placement_con']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_con_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $con_data['pre_placement_con']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_con_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $con_data['pre_placement_con']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($con_data['pre_placement_con']) ? nl2br($con_data['pre_placement_con']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_con_archve_data[$e]['risk_assesment_con']) && $pp_con_archve_data[$e]['pp_con_id'] == $con_data['pp_con_id']) {
										$diff = new HtmlDiff($pp_con_archve_data[$e]['risk_assesment_con'], $con_data['risk_assesment_con']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_con_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $con_data['risk_assesment_con']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_con_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $con_data['risk_assesment_con']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($con_data['risk_assesment_con']) ? nl2br($con_data['risk_assesment_con']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_con_archve_data[$e]['individual_strategies_con']) && $pp_con_archve_data[$e]['pp_con_id'] == $con_data['pp_con_id']) {
										$diff = new HtmlDiff($pp_con_archve_data[$e]['individual_strategies_con'], $con_data['individual_strategies_con']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_con_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $con_data['individual_strategies_con']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_con_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $con_data['individual_strategies_con']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($con_data['individual_strategies_con']) ? nl2br($con_data['individual_strategies_con']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
						
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php $e++; } } else { echo lang('NA'); } ?>
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
					$e = 0;
					 
					?>
				<?php foreach($edit_data_pp_ft as $ft_data){ //pr($ft_data);?>
					<div class="col-md-12 pp_title_1">
						
						<?php 
					if (!empty($pp_ft_archve_data[$e]['heading_ft']) && $pp_ft_archve_data[$e]['pp_ft_id'] == $ft_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_ft_archve_data[$e]['heading_ft'], $ft_data['heading_ft']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_ft_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $ft_data['heading_ft']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_ft_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $ft_data['heading_ft']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($ft_data['heading_ft']) ? nl2br($ft_data['heading_ft']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>		

						</div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_ft_archve_data[$e]['pre_placement_ft']) && $pp_ft_archve_data[$e]['pp_ft_id'] == $ft_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_ft_archve_data[$e]['pre_placement_ft'], $ft_data['pre_placement_ft']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_ft_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $ft_data['pre_placement_ft']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_ft_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $ft_data['pre_placement_ft']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($ft_data['pre_placement_ft']) ? nl2br($ft_data['pre_placement_ft']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_ft_archve_data[$e]['risk_assesment_ft']) && $pp_ft_archve_data[$e]['pp_ft_id'] == $ft_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_ft_archve_data[$e]['risk_assesment_ft'], $ft_data['risk_assesment_ft']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_ft_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $ft_data['risk_assesment_ft']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_ft_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $ft_data['risk_assesment_ft']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($ft_data['risk_assesment_ft']) ? nl2br($ft_data['risk_assesment_ft']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_ft_archve_data[$e]['individual_strategies_ft']) && $pp_ft_archve_data[$e]['pp_ft_id'] == $ft_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_ft_archve_data[$e]['individual_strategies_ft'], $ft_data['individual_strategies_ft']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_ft_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $ft_data['individual_strategies_ft']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_ft_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $ft_data['individual_strategies_ft']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($ft_data['individual_strategies_ft']) ? nl2br($ft_data['individual_strategies_ft']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php $e++; } } else { echo lang('NA'); } ?>
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
					$e = 0;
					 
					?>
				<?php foreach($edit_data_pp_mgi as $mgi_data){ //pr($mgi_data);?>
					<div class="col-md-12 pp_title_1">
					<?php 
					if (!empty($pp_mgi_archve_data[$e]['heading_mgi']) && $pp_mgi_archve_data[$e]['pp_ft_id'] == $mgi_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_mgi_archve_data[$e]['heading_mgi'], $mgi_data['heading_mgi']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_mgi_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $mgi_data['heading_mgi']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_mgi_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $mgi_data['heading_mgi']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($mgi_data['heading_mgi']) ? nl2br($mgi_data['heading_mgi']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>	
					</div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_mgi_archve_data[$e]['pre_placement_mgi']) && $pp_mgi_archve_data[$e]['pp_ft_id'] == $mgi_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_mgi_archve_data[$e]['pre_placement_mgi'], $mgi_data['pre_placement_mgi']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_mgi_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $mgi_data['pre_placement_mgi']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_mgi_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $mgi_data['pre_placement_mgi']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($mgi_data['pre_placement_mgi']) ? nl2br($mgi_data['pre_placement_mgi']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>	
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_mgi_archve_data[$e]['risk_assesment_mgi']) && $pp_mgi_archve_data[$e]['pp_ft_id'] == $mgi_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_mgi_archve_data[$e]['risk_assesment_mgi'], $mgi_data['risk_assesment_mgi']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_mgi_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $mgi_data['risk_assesment_mgi']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_mgi_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $mgi_data['risk_assesment_mgi']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($mgi_data['risk_assesment_mgi']) ? nl2br($mgi_data['risk_assesment_mgi']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>	
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_mgi_archve_data[$e]['individual_strategies_mgi']) && $pp_mgi_archve_data[$e]['pp_ft_id'] == $mgi_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_mgi_archve_data[$e]['individual_strategies_mgi'], $mgi_data['individual_strategies_mgi']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_mgi_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $mgi_data['individual_strategies_mgi']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_mgi_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $mgi_data['individual_strategies_mgi']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($mgi_data['individual_strategies_mgi']) ? nl2br($mgi_data['individual_strategies_mgi']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>	
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php $e++; } } else { echo lang('NA'); } ?>
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
					$e=0;
					 
					?>
				<?php foreach($edit_data_pp_pr as $pr_data){ //pr($pr_data);?>
					<div class="col-md-12 pp_title_1">
					<?php 
					if (!empty($pp_pr_archve_data[$e]['heading_pr']) && $pp_pr_archve_data[$e]['pp_ft_id'] == $pr_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_pr_archve_data[$e]['heading_pr'], $pr_data['heading_pr']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_pr_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $pr_data['heading_pr']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_pr_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $pr_data['heading_pr']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($pr_data['heading_pr']) ? nl2br($pr_data['heading_pr']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
						</div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_pr_archve_data[$e]['pre_placement_pr']) && $pp_pr_archve_data[$e]['pp_ft_id'] == $pr_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_pr_archve_data[$e]['pre_placement_pr'], $pr_data['pre_placement_pr']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_pr_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $pr_data['pre_placement_pr']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_pr_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $pr_data['pre_placement_pr']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($pr_data['pre_placement_pr']) ? nl2br($pr_data['pre_placement_pr']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_pr_archve_data[$e]['risk_assesment_pr']) && $pp_pr_archve_data[$e]['pp_ft_id'] == $pr_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_pr_archve_data[$e]['risk_assesment_pr'], $pr_data['risk_assesment_pr']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_pr_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $pr_data['risk_assesment_pr']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_pr_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $pr_data['risk_assesment_pr']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($pr_data['risk_assesment_pr']) ? nl2br($pr_data['risk_assesment_pr']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_pr_archve_data[$e]['individual_strategies_pr']) && $pp_pr_archve_data[$e]['pp_ft_id'] == $pr_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_pr_archve_data[$e]['individual_strategies_pr'], $pr_data['individual_strategies_pr']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_pr_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $pr_data['individual_strategies_pr']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_pr_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $pr_data['individual_strategies_pr']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($pr_data['individual_strategies_pr']) ? nl2br($pr_data['individual_strategies_pr']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php $e++; } } else { echo lang('NA'); } ?>
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
					
					$e=0;
					 
					?>
				<?php foreach($edit_data_pp_bc as $bc_data){ //pr($bc_data);?>
					<div class="col-md-12 pp_title_1">
							<?php 
					if (!empty($pp_bc_archve_data[$e]['heading_bc']) && $pp_bc_archve_data[$e]['pp_ft_id'] == $bc_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_bc_archve_data[$e]['heading_bc'], $bc_data['heading_bc']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_bc_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $bc_data['heading_bc']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_bc_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $bc_data['heading_bc']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($bc_data['heading_bc']) ? nl2br($bc_data['heading_bc']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
						</div>
					
					<div class="row">
						<div class="col-sm-4">
							<h4>Placement Plan</h4>
							
							<div class="form-group">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_bc_archve_data[$e]['pre_placement_bc']) && $pp_bc_archve_data[$e]['pp_ft_id'] == $bc_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_bc_archve_data[$e]['pre_placement_bc'], $bc_data['pre_placement_bc']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_bc_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $bc_data['pre_placement_bc']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_bc_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $bc_data['pre_placement_bc']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($bc_data['pre_placement_bc']) ? nl2br($bc_data['pre_placement_bc']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>Risk Assessment</h4>
							<div class="form-group">
				
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_bc_archve_data[$e]['risk_assesment_bc']) && $pp_bc_archve_data[$e]['pp_ft_id'] == $bc_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_bc_archve_data[$e]['risk_assesment_bc'], $bc_data['risk_assesment_bc']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_bc_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $bc_data['risk_assesment_bc']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_bc_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $bc_data['risk_assesment_bc']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($bc_data['risk_assesment_bc']) ? nl2br($bc_data['risk_assesment_bc']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
					</div>
						</div>
						<div class="col-sm-4">
							<h4>INDIVIDUAL STRATEGIES</h4>
							<div class="form-group">
					<div id="">
					<div id="" style="border-top: 1px solid #ccc;">
						<?php 
					if (!empty($pp_bc_archve_data[$e]['individual_strategies_bc']) && $pp_bc_archve_data[$e]['pp_ft_id'] == $bc_data['pp_ft_id']) {
										$diff = new HtmlDiff($pp_bc_archve_data[$e]['individual_strategies_bc'], $bc_data['individual_strategies_bc']);
                                        $diff->build();
                                        echo nl2br($diff->getDifference());
                                        ?>
                                     <?php } else { ?>
                                     <?php if(!empty($pp_bc_archve_data))
                                        {
                                             $diff = new HtmlDiff('', $bc_data['individual_strategies_bc']);
                                                $diff->build();
                                                echo nl2br($diff->getDifference());
                                        }else{
                                            if(!empty($prev_archive_edit_data) && empty($pp_bc_archve_data))
                                                        {
                                                            $diff = new HtmlDiff('', $bc_data['individual_strategies_bc']);
                                                            $diff->build();
                                                            echo nl2br($diff->getDifference());
                                                        }else{
                                                        ?>
                                                 <?= !empty($bc_data['individual_strategies_bc']) ? nl2br($bc_data['individual_strategies_bc']) : '' ?>
                                                 <?php
                                                    }
                                        }
                                      } ?>
					</div>
						
					</div>
					
					</div>
					
						
						</div>
						
						<div class="clearfix"></div>
					
					</div>
					<?php $e++; } } else { echo lang('NA'); } ?>
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

                    <?php if (checkPermission('ArchivePlacementPlan', 'view')) { ?>
                        <a href="<?= base_url('ArchivePlacementPlan/index/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-search"></i> VIEW ARCHIVE
                        </a>
                    <?php } ?>
                    <?php if (!empty($edit_data)) { ?>
                        <a href="<?= base_url('PlacementPlan/DownloadPrint/' . $edit_data[0]['placement_plan_id'] . '/' . $ypid . '/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PRINT </a>
						
						  <a href="<?=base_url('Mail/ComposeMailpp/'.$pp_id.'/'.$ypid); ?>" class="btn btn-default">
                                 <i class="fa fa-edit"></i> EMAIL
                            </a>
                    <?php } ?>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
