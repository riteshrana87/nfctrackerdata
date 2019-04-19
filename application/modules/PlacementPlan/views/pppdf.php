<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				NFC Tracker
				<small class="pull-right">Date: <?= (!empty($edit_data[0]['modified_date'])) ? configDateTime($edit_data[0]['modified_date']) : '' ?></small>
			</h2>
		</div><!-- /.col -->
	</div>
	<!-- info row -->
	<div class="row invoice-info">
		<div class="col-xs-12">
			<p class="lead" style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; font-weight: 900;">placement plan</p>
		</div>
		<div class="clearfix"></div>
		<div class="col-sm-12">
			<strong>YP Name</strong>
			<span>
				<?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?>
				<?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?>
			</span>
		</div>
		<div class="col-sm-12">
			<strong>DOB:</strong>
			<span>
				<?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00 00:00:00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
			</span>
		</div>
		<div class="col-sm-12">
			<strong>LAST EDIT DATE:</strong>
			<span>
				<?= (!empty($edit_data[0]['modified_date']) && $edit_data[0]['modified_date'] != '0000-00-00 00:00:00') ? configDateTime($edit_data[0]['modified_date']) : '' ?>
			</span>
		</div>
	</div><!-- /.row -->
	<div class="clearfix"></div>

	<div class="row">
	<?php
        if (!empty($pp_form_data)) {
            $i = 0;
            foreach ($pp_form_data as $row) {

                if ($row['type'] == 'textarea' || $row['type'] == 'radio-group' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                    $i++;
                    ?>
                    <div class="<?= ($row['type'] == 'header') ? 'col-xs-12' : 'col-xs-12' ?> dont-break paddi-z" >
                        <p class="lead"><?= !empty($row['label']) ? $row['label'] : '' ?></p>   
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            <?php if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {
                                ?>
                                <?php
                                $data_textarea = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $edit_data[0][$row['name']]);
                                ?>
                                <?= !empty($data_textarea) ? nl2br(html_entity_decode($data_textarea)) : (isset($row['value']) ? $row['value'] : '') ?>


                            </p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <?php
                                    } else if ($row['type'] == 'checkbox-group') {
                                        if (!empty($edit_data[0][$row['name']])) {
                                            $chk = explode(',', $edit_data[0][$row['name']]);
                                            foreach ($chk as $chk) {
                                                ?>
                                                <th><?php echo $chk . "\n"; ?></th>
                                                <?php
                                            }
                                        } else {

                                            if (count($row['values']) > 0) {

                                                foreach ($row['values'] as $chked) {
                                                    ?>
                                                    <th><?php echo isset($chked['selected']) ? '<li>' . $chked['value'] . "\n" : ''; ?></th>

                                                    <?php
                                                }
                                            }
                                        }
                                        ?>

                                        <?php
                                    } else if ($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                        if (!empty($edit_data[0][$row['name']])) {
                                            ?>

                                            <td class="text-right"><span class="label <?php if ($edit_data[0][$row['name']] == 'yes') { ?> label-success <?php } else { ?> label-danger <?php } ?>"><?php echo!empty($edit_data[0][$row['name']]) ? nl2br(htmlentities($edit_data[0][$row['name']])) : ''; ?></span></td>
                                            <?php
                                        } else {
                                            if (count($row['values']) > 0) {
                                                foreach ($row['values'] as $chked) {
                                                    ?>
                                                    <td class="text-right"><span class="label <?php if ($chked['value'] == 'yes') { ?> label-success <?php } else { ?> label-danger <?php } ?> "><?php echo isset($chked['selected']) ? $chked['value'] : ''; ?></span></td>

                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php echo ($i % 2 == 0 && $i != 0) ? '<div class="clearfix"></div>' : ''; ?>

                </div>

                <?php
            } else if ($row['type'] == 'header') {
                ?>
                <div class="col-xs-12">
                    <p class="lead">
                        <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                        <?php echo '<' . $head . ' class="page-title">'; ?>
                        <?= !empty($row['label']) ? $row['label'] : '' ?>

                        <?php echo '</' . $head . '>'; ?>
                    </p>
                </div>
                <?php
            } else if ($row['type'] == 'file') {
                ?>
                <div class="col-xs-12">
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
                <?php
            }
        } //foreach
    }
    ?>
		
		<div id="section1">
			<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
					<div class="panel-body">
						<h3><div id="mainHeader1">
							Pre Placement Information
						</div></h3>
						<div class="form-group"><div id="">
						<?php //echo $cpt_item_archive[0]['pre_placement_info']."</br>";
								//echo $edit_data[0]['pre_placement_info']."</br>";
						?>
						<?php $diff = new HtmlDiff($cpt_item_archive[0]['pre_placement_info'], $edit_data[0]['pre_placement_info']);$diff->build();
						
						echo $edit_data[0]['pre_placement_info'];
						
						?>
						

					</div></div>
				</div>
			</div></div>
			<div class="col-sm-6">
				<div class="panel panel-default tile tile-profile">
					<div class="panel-body">
						<h3><div id="mainHeader1">
							Aims of Placement
							<small>Long Term Plans</small>
						</div></h3>
						
					<?php if(!empty($edit_data_pp_aim)){ ?>
						<?php foreach($edit_data_pp_aim as $aim_data){?>
						<div class="form-group">
						<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_aim_archve_data['aims_of_placement_data'], $aim_data['aims_of_placement_data']);$diff->build();echo $aim_data['aims_of_placement_data']; ?>
						</div>
						</div>
						<?php } }?>
						
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default tile tile-profile">
					<div class="panel-body">
						<h3><div id="mainHeader1">
							Actions from LAC Review 
							<small>Medium Term Plans</small>
						</div></h3>
						
						
						
						<?php if(!empty($edit_data_pp_lac)){ ?>
						<?php foreach($edit_data_pp_lac as $lac_data){?>
						<div class="form-group">
						<div id="" style="border-top: 1px solid #ccc;">
						<?php $diff = new HtmlDiff($pp_lac_archve_data['lac_review_data'], $lac_data['lac_review_data']);$diff->build();echo $lac_data['lac_review_data']; ?>
						</div>
						</div>
						<?php } }?>
					
						
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>


		<div class="col-sm-12">
			<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review">
					<h3><div id="mainHeader1">
						Health
					</div></h3>
					<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_health)){
					//pr($pp_health_archve_data);

						?>
						<?php foreach($edit_data_pp_health as $health_data){ //pr($health_data);?>
							<div class="col-md-12 pp_title_1">
								<div class="row"><h4 class="print_hd"><?= !empty($health_data['heading']) ? $health_data['heading'] : lang('NA') ?></h4></div>
							</div>

								<div class="row">
									<div class="col-sm-4">
										<h6>Placement Plan</h6>

										<div class="form-group">
											<div id="" style="border-top: 1px solid #ccc;">

												<?php $diff = new HtmlDiff($pp_health_archve_data['pre_placement'],$health_data['pre_placement']);
												$diff->build();

												echo $health_data['pre_placement']; ?>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<h5>Risk Assessment</h5>
										<div class="form-group">

											<div id="" style="border-top: 1px solid #ccc;">

												<?php $diff = new HtmlDiff($pp_health_archve_data['risk_assesment'],$health_data['risk_assesment']);
												$diff->build();

												echo $health_data['risk_assesment']; ?>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<h5>INDIVIDUAL STRATEGIES</h5>
										<div class="form-group">
											<div id="">
												<div id="" style="border-top: 1px solid #ccc;">


													<?php $diff = new HtmlDiff($pp_health_archve_data['individual_strategies'],$health_data['individual_strategies']);
													$diff->build();

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
						<h3><div id="mainHeader1">
							Education
						</div></h3>
						<!--- this will be added by user from front end -->
						<?php if(!empty($edit_data_pp_edu)){


							?>
							<?php foreach($edit_data_pp_edu as $edu_data){ //pr($edu_data);?>
								<div class="col-md-12 pp_title_1">
									<div class="row"><h4 class="print_hd"><?= !empty($edu_data['heading_edu']) ? $edu_data['heading_edu'] : lang('NA') ?></h4></div>
								</div>

									<div class="row">
										<div class="col-sm-4">
											<h5>Placement Plan</h5>

											<div class="form-group">
												<div id="" style="border-top: 1px solid #ccc;">

													<?php $diff = new HtmlDiff($pp_edu_archve_data['pre_placement_edu_sub'],$edu_data['pre_placement_edu_sub']);
													$diff->build();

													echo $edu_data['pre_placement_edu_sub']; ?>
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<h5>Risk Assessment</h5>
											<div class="form-group">

												<div id="" style="border-top: 1px solid #ccc;">

													<?php $diff = new HtmlDiff($pp_edu_archve_data['risk_assesment_edu'],$edu_data['risk_assesment_edu']);
													$diff->build();

													echo $edu_data['risk_assesment_edu']; ?>
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<h5>INDIVIDUAL STRATEGIES</h5>
											<div class="form-group">
												<div id="">
													<div id="" style="border-top: 1px solid #ccc;">

														<?php $diff = new HtmlDiff($pp_edu_archve_data['individual_strategies_edu'],$edu_data['individual_strategies_edu']);
														$diff->build();

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
							<h3><div id="mainHeader1">
								Transport
							</div></h3>
							<!--- this will be added by user from front end -->
							<?php if(!empty($edit_data_pp_tra)){


								?>
								<?php foreach($edit_data_pp_tra as $tra_data){ //pr($tra_data);?>
									<div class="col-md-12 pp_title_1">
										<div class="row"><h4 class="print_hd"><?= !empty($tra_data['heading_tra']) ? $tra_data['heading_tra'] : lang('NA') ?></h4></div>
									</div>

									<div class="row">
										<div class="col-sm-4">
											<h5>Placement Plan</h5>

											<div class="form-group">
												<div id="" style="border-top: 1px solid #ccc;">

													<?php $diff = new HtmlDiff($pp_tra_archve_data['pre_placement_tra'],$tra_data['pre_placement_tra']);
													$diff->build();

													echo $tra_data['pre_placement_tra']; ?>
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<h5>Risk Assessment</h5>
											<div class="form-group">
												<div id="" style="border-top: 1px solid #ccc;">

													<?php $diff = new HtmlDiff($pp_tra_archve_data['risk_assesment_tra'],$tra_data['risk_assesment_tra']);
													$diff->build();

													echo $tra_data['risk_assesment_tra']; ?>
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<h5>INDIVIDUAL STRATEGIES</h5>
											<div class="form-group">
												<div id="">
													<div id="" style="border-top: 1px solid #ccc;">
														<?php $diff = new HtmlDiff($pp_tra_archve_data['individual_strategies_tra'],$tra_data['individual_strategies_tra']);
														$diff->build();

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
								<h3><div id="mainHeader1">
									Contact
								</div></h3>
								<!--- this will be added by user from front end -->
								<?php if(!empty($edit_data_pp_con)){


									?>
									<?php foreach($edit_data_pp_con as $con_data){ //pr($con_data);?>
										<div class="col-md-12 pp_title_1"><div class="row"><h4 class="print_hd"><?= !empty($con_data['heading_con']) ? $con_data['heading_con'] : lang('NA') ?></h4></div></div>

										<div class="row">
											<div class="col-sm-4">
												<h5>Placement Plan</h5>

												<div class="form-group">
													<div id="" style="border-top: 1px solid #ccc;">
														<?php $diff = new HtmlDiff($pp_con_archve_data['pre_placement_con'],$con_data['pre_placement_con']);
														$diff->build();

														echo $con_data['pre_placement_con']; ?>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<h5>Risk Assessment</h5>
												<div class="form-group">

													<div id="" style="border-top: 1px solid #ccc;">
														<?php $diff = new HtmlDiff($pp_con_archve_data['risk_assesment_con'],$con_data['risk_assesment_con']);
														$diff->build();

														echo $con_data['risk_assesment_con']; ?>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<h5>INDIVIDUAL STRATEGIES</h5>
												<div class="form-group">
													<div id="">
														<div id="" style="border-top: 1px solid #ccc;">
															<?php $diff = new HtmlDiff($pp_con_archve_data['individual_strategies_con'],$con_data['individual_strategies_con']);
															$diff->build();

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
									<h3><div id="mainHeader1">
										Free Time
									</div></h3>
									<!--- this will be added by user from front end -->
									<?php if(!empty($edit_data_pp_ft)){


										?>
										<?php foreach($edit_data_pp_ft as $ft_data){ //pr($ft_data);?>
											<div class="col-md-12 pp_title_1"><div class="row"><h4 class="print_hd"><?= !empty($ft_data['heading_ft']) ? $ft_data['heading_ft'] : lang('NA') ?></h4></div></div>

											<div class="row">
												<div class="col-sm-4">
													<h5>Placement Plan</h5>

													<div class="form-group">
														<div id="" style="border-top: 1px solid #ccc;">
															<?php $diff = new HtmlDiff($pp_ft_archve_data['pre_placement_ft'],$ft_data['pre_placement_ft']);
															$diff->build();

															echo $ft_data['pre_placement_ft']; ?>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<h5>Risk Assessment</h5>
													<div class="form-group">

														<div id="" style="border-top: 1px solid #ccc;">
															<?php $diff = new HtmlDiff($pp_ft_archve_data['risk_assesment_ft'],$ft_data['risk_assesment_ft']);
															$diff->build();

															echo $ft_data['risk_assesment_ft']; ?>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<h5>INDIVIDUAL STRATEGIES</h5>
													<div class="form-group">
														<div id="">
															<div id="" style="border-top: 1px solid #ccc;">
																<?php $diff = new HtmlDiff($pp_ft_archve_data['individual_strategies_ft'],$ft_data['individual_strategies_ft']);
																$diff->build();

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
										<h3><div id="mainHeader1">
											Mobile, Gaming & Internet
										</div></h3>
										<!--- this will be added by user from front end -->
										<?php if(!empty($edit_data_pp_mgi)){


											?>
											<?php foreach($edit_data_pp_mgi as $mgi_data){ //pr($mgi_data);?>
												<div class="col-md-12 pp_title_1"><div class="row"><h4 class="print_hd"><?= !empty($mgi_data['heading_mgi']) ? $mgi_data['heading_mgi'] : lang('NA') ?></h4></div></div>

												<div class="row">
													<div class="col-sm-4">
														<h5>Placement Plan</h5>

														<div class="form-group">
															<div id="" style="border-top: 1px solid #ccc;">
																<?php $diff = new HtmlDiff($pp_mgi_archve_data['pre_placement_mgi'],$mgi_data['pre_placement_mgi']);
																$diff->build();

																echo $mgi_data['pre_placement_mgi']; ?>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<h5>Risk Assessment</h5>
														<div class="form-group">

															<div id="" style="border-top: 1px solid #ccc;">
																<?php $diff = new HtmlDiff($pp_mgi_archve_data['risk_assesment_mgi'],$mgi_data['risk_assesment_mgi']);
																$diff->build();

																echo $mgi_data['risk_assesment_mgi']; ?>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<h5>INDIVIDUAL STRATEGIES</h5>
														<div class="form-group">
															<div id="">
																<div id="" style="border-top: 1px solid #ccc;">
																	<?php $diff = new HtmlDiff($pp_mgi_archve_data['individual_strategies_mgi'],$mgi_data['individual_strategies_mgi']);
																	$diff->build();

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
											<h3><div id="mainHeader1">
												Positive Relationships
											</div></h3>
											<!--- this will be added by user from front end -->
											<?php if(!empty($edit_data_pp_pr)){


												?>
												<?php foreach($edit_data_pp_pr as $pr_data){ //pr($pr_data);?>
													<div class="col-md-12 pp_title_1"><div class="row"><h4 class="print_hd"><?= !empty($pr_data['heading_pr']) ? $pr_data['heading_pr'] : lang('NA') ?></h4></div></div>

													<div class="row">
														<div class="col-sm-4">
															<h5>Placement Plan</h5>

															<div class="form-group">
																<div id="" style="border-top: 1px solid #ccc;">

																	<?php $diff = new HtmlDiff($pp_pr_archve_data['pre_placement_pr'],$pr_data['pre_placement_pr']);
																	$diff->build();

																	echo $pr_data['pre_placement_pr']; ?>
																</div>
															</div>
														</div>
														<div class="col-sm-4">
															<h5>Risk Assessment</h5>
															<div class="form-group">

																<div id="" style="border-top: 1px solid #ccc;">

																	<?php $diff = new HtmlDiff($pp_pr_archve_data['risk_assesment_pr'],$pr_data['risk_assesment_pr']);
																	$diff->build();

																	echo $pr_data['risk_assesment_pr']; ?>
																</div>
															</div>
														</div>
														<div class="col-sm-4">
															<h5>INDIVIDUAL STRATEGIES</h5>
															<div class="form-group">
																<div id="">
																	<div id="" style="border-top: 1px solid #ccc;">

																		<?php $diff = new HtmlDiff($pp_pr_archve_data['individual_strategies_pr'],$pr_data['individual_strategies_pr']);
																		$diff->build();

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
												<h3><div id="mainHeader1">
													Behaviour Concerns
												</div></h3>
												<!--- this will be added by user from front end -->
												<?php if(!empty($edit_data_pp_bc)){

													?>
													<?php foreach($edit_data_pp_bc as $bc_data){ //pr($bc_data);?>
														<div class="col-md-12 pp_title_1"><div class="row"><h4 class="print_hd"><?= !empty($bc_data['heading_bc']) ? $bc_data['heading_bc'] : lang('NA') ?></h4></div></div>

														<div class="row">
															<div class="col-sm-4">
																<h5>Placement Plan</h5>
																<div class="form-group">
																	<div id="" style="border-top: 1px solid #ccc;">
																		<?php $diff = new HtmlDiff($pp_bc_archve_data['pre_placement_bc'],$bc_data['pre_placement_bc']);
																		$diff->build();

																		echo $bc_data['pre_placement_bc']; ?>
																	</div>
																</div>
															</div>
															<div class="col-sm-4">
																<h5>Risk Assessment</h5>
																<div class="form-group">
																	<div id="" style="border-top: 1px solid #ccc;">
																		<?php $diff = new HtmlDiff($pp_bc_archve_data['risk_assesment_bc'],$bc_data['risk_assesment_bc']);
																		$diff->build();

																		echo $bc_data['risk_assesment_bc']; ?>
																	</div>
																</div>
															</div>
															<div class="col-sm-4">
																<h5>INDIVIDUAL STRATEGIES</h5>
																<div class="form-group">
																	<div id="">
																		<div id="" style="border-top: 1px solid #ccc;">
																			<?php $diff = new HtmlDiff($pp_bc_archve_data['individual_strategies_bc'],$bc_data['individual_strategies_bc']);
																			$diff->build();

																			echo $bc_data['individual_strategies_bc']; ?>
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

									</div>
</section><!-- /.content -->