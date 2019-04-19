<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?=!empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:$ypid?>';
	var care_plan_target = '<?=!empty($care_plan_target)?'1':''?>'; 
</script>
<?php //echo htmlspecialchars_decode($pp_forms[0]['form_data']);exit; ?>
<!-- main content start-->
        <div id="page-wrapper">
        <form action="<?=base_url('PlacementPlan/insert')?>" method="post" id="ppform" name="ppform" data-parsley-validate enctype="multipart/form-data">
            <div class="main-page">
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                    Placement Plan <small>New Forest Care</small>
                    <div class="pull-right for-tab">
                                <div class="btn-group capsBtn">
                                    <button type="submit" <?php /*onclick="needToConfirm = false;" */?> class="btn btn-default" name="submit_ppform" id="submit_ppforms" value="submit" style="default">Update</button>
                                    
                                    <!-- <a href="javascript:;" class="btn btn-default" onclick="SavePlacementPlanForm('print');"> <i class="fa fa-print"></i>Print</a> -->
                                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">CARE HOME YP LIST</a>
                                    <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="btn btn-default" name="back_to_yp_info" id="back_to_yp_info" value="submit" style="default">Back To YP Info</a>
                                    <a href="<?=base_url('PlacementPlan/index/'.$ypid)?>" class="btn btn-default"  value="submit" style="default">BACK TO CURRENT PP</a>
                                    
                                </div>
                            </div>
                </h1>
                <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
            </div>
                <div class="row">
				
				<?php
                        
                        if(!empty($pp_form_data))
                        {
                            foreach ($pp_form_data as $row) {
                              
                            if($row['type'] == 'textarea') {
                                ?>
                                    <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                
                                                 <textarea 
                                                 class="<?=!empty($row['className'])?$row['className']:''?> <?=($row['subtype'] == 'tinymce')?'tinyeditor':''?>" 
                                                 <?=!empty($row['required'])?'required=true':''?>
                                                 name="<?=!empty($row['name'])?$row['name']:''?>" 
                                                 placeholder="<?=!empty($row['placeholder'])?$row['placeholder']:''?>"
                                                 <?php if($row['subtype'] != 'tinymce') { ?>
                                                 <?=!empty($row['maxlength'])?'maxlength="'.$row['maxlength'].'"':''?>
                                                 <?=!empty($row['rows'])?'rows="'.$row['rows'].'"':''?>
                                                 <?php } ?>
                                                 id="<?=!empty($row['name'])?$row['name']:''?>" ><?=!empty($edit_data[0][$row['name']])?html_entity_decode($edit_data[0][$row['name']]):(isset($row['value'])?$row['value']:'')?></textarea>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                else if($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date')
                                { 
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                             <?php if((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date'){ ?>
                                        <div class="row">
                                            <div class="col-sm-4">
                                        <?php } ?>
                                          <div class="<?=(!empty($row['subtype']) && $row['subtype'] == 'time')?'input-group input-append bootstrap-timepicker':''?><?=(!empty($row['type']) && $row['type'] == 'date')?(!empty($row['description']) && $row['description'] == 'dob')?'input-group input-append date dob':'input-group input-append date adddate':''?>">
                                                <input type="<?=(!empty($row['type']) && $row['type']=='number')?'number':((!empty($row['subtype']) && $row['subtype'] !='time')?$row['subtype']:'text')?>" 
                                                class="<?=!empty($row['className'])?$row['className']:''?> <?=($row['type'] == 'date')?'adddate':''?> <?=(!empty($row['subtype']) && $row['subtype'] == 'time')?'addtime':''?>" 
                                                <?=!empty($row['required'])?'required=true':''?>
                                                name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" 
                                                <?=!empty($row['maxlength'])?'maxlength="'.$row['maxlength'].'"':''?>
                                                <?=!empty($row['min'])?'min="'.$row['min'].'"':''?>
                                                <?=!empty($row['max'])?'max="'.$row['max'].'"':''?>
                                                <?=!empty($row['step'])?'step="'.$row['step'].'"':''?>
                                                placeholder="<?=!empty($row['placeholder'])?$row['placeholder']:''?>"
                                                value="<?=!empty($edit_data[0][$row['name']])?$edit_data[0][$row['name']]:(isset($row['value'])?$row['value']:'')?>" <?=($row['type'] =='date')?'readonly':''?> data-parsley-errors-container="#errors-container<?=$row['name']?>" />
                                                  <?php if(!empty($row['subtype']) && $row['subtype'] == 'time') {?>
                                                            <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                                    <?php } ?>
                                                 <?php if(!empty($row['type']) && $row['type'] == 'date') {?>
                                                <span class="input-group-addon add-on" ><i class="fa fa-calendar"></i></span>
                                                    <?php } ?>
                                                  </div>
                                                  <span id="errors-container<?=$row['name']?>"></span>
                                                 <?php if((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) { ?>
                                    </div>
                                    </div>
                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                   
                                <?php
                                }
                                else if($row['type'] == 'radio-group')
                                {
                                ?>
                                <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                
                                                 <div class="radio-group">
                                                 <?php if(count($row['values']) > 0) {
                                                 foreach ($row['values'] as $radio) {
                                                     if(!empty($radio['label'])) {
                                                  ?>
                                                 <div class="<?=!empty($row['inline'])?'radio-inline':'radio'?>">
                                                     <label ><input name="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?> 
                                                         class="<?=!empty($row['className'])?$row['className']:''?>" 
                                                         value="<?=!empty($radio['value'])?$radio['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $radio['value'])?'checked="checked"':!empty($radio['selected'])?'checked="checked"':''?>  type="radio">
                                                     <?=!empty($radio['label'])?$radio['label']:''?></label>
                                                 </div>
                                                <?php } } } //radio loop ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                else if($row['type'] == 'checkbox-group')
                                {
                                ?>
                                <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                                
                                                 <div class="checkbox-group">
                                                 <?php if(count($row['values']) > 0) {
                                                    $checkedValues =array();
                                                    if(!empty($edit_data[0][$row['name']]))
                                                    {
                                                    $checkedValues = explode(',',$edit_data[0][$row['name']]);
                                                    }
                                                 foreach ($row['values'] as $checkbox) {
                                                     if(!empty($checkbox['label'])) {
                                                  ?>
                                                 <div class="<?=!empty($row['inline'])?'checkbox-inline':'checkbox'?>">
                                                     <label class="<?=!empty($row['toggle'])?'kc-toggle':''?>"><input 
                                                        class="<?=!empty($row['className'])?$row['className']:''?> <?=!empty($row['toggle'])?'kc-toggle':''?>"
                                                       name="<?=!empty($row['name'])?$row['name'].'[]':''?>" value="<?=!empty($checkbox['value'])?$checkbox['value']:''?>" <?=(!empty($checkedValues) && in_array($checkbox['value'], $checkedValues))?'checked="checked"':!empty($checkbox['selected'])?'checked="checked"':''?>  
                                                            <?=!empty($row['required'])?'required=true':''?>
                                                            type="checkbox">
                                                     <?=!empty($checkbox['label'])?$checkbox['label']:''?></label>
                                                 </div>
                                                <?php } } } //radio loop ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                else if($row['type'] == 'select')
                                {
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                             
                                                 <select class="chosen-select <?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" <?=!empty($row['required'])?'required=true':''?>>
                                                 <option value="">Select</option>
                                                 <?php if(count($row['values']) > 0) {
                                                 foreach ($row['values'] as $select) {
                                                     if(!empty($select['label'])) {
                                                  ?>
                                                  <option value="<?=!empty($select['value'])?$select['value']:''?>" <?=(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $select['value'])?'selected="true"':!empty($select['selected'])?'selected="true"':''?> ><?=!empty($select['label'])?$select['label']:''?></option>
                                                <?php } } } //select loop ?>
                                                
                                                 </select>
                                                
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                else if($row['type'] == 'hidden' || $row['type'] == 'button')
                                {
                                    ?>
                                     <?php if($row['type'] == 'button'){ ?>
                                     <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                                <div class="fb-button form-group">
                                               
                                                    <button name="<?=!empty($row['name'])?$row['name']:''?>" value="" type="<?=!empty($row['type'])?$row['type']:''?>" class="<?=!empty($row['className'])?$row['className']:''?>" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" style="<?=!empty($row['style'])?$row['style']:''?>"><?=!empty($row['label'])?$row['label']:''?></button>
                                                
                                               
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                     <?php if($row['type'] == 'hidden'){ ?>
                                     <div class="col-sm-12">
                                        <input type="hidden" name="<?=!empty($row['name'])?$row['name']:''?>" id="<?=!empty($row['name'])?$row['name']:''?>" value="" />
                                        </div>
                                    <?php } ?>
                                <?php
                                }
                                else if($row['type'] == 'header')
                                {
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="">
                                            <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                                        </div>
                                    </div>
                                <?php } else if($row['type'] == 'file')
                                {?>
                                    <div class="col-sm-12">
                                        <div class="panel panel-default tile tile-profile">
                                            <div class="panel-body">
                                            <h2 class="page-title"><?=!empty($row['label'])?$row['label']:''?> <?=!empty($row['required'])?'<span class="astrick">*</span>':''?></h2>
                                            <input type="file" name="<?=!empty($row['name'])?$row['name'].'[]':''?>" id="<?=!empty($row['name'])?$row['name']:''?>"  class="<?=!empty($row['className'])?$row['className']:''?>" 
                                                <?=!empty($row['multiple'])?'multiple="true"':''?> <?= !empty($row['required']) ? 'required=true' : '' ?>>
                                                <h2></h2>
                                                        <?php 
                                                    if(!empty($edit_data[0][$row['name']]))
                                                    {
                                                        $imgs = explode(',',$edit_data[0][$row['name']]);
                                                        foreach ($imgs as $img) {
                                                            ?>
                                                                <div class="col-sm-1 margin-bottom upload_img">
                                                                <?php 
                                                                    if(@is_array(getimagesize($this->config->item ('pp_img_base_url').$ypid.'/'.$img))){
                                                                           ?>
                                                                           <img width="100" height="100" src="<?=$this->config->item ('pp_img_base_url_small').$ypid.'/'.$img?>">
                                                                           <?php
                                                                        } else {
                                                                            ?>
                                                                            <img width="100" height="100" src="<?=base_url('uploads/images/icons 64/file-ico.png')?>">
                                                                            <?php
                                                                        }
                                                                ?>
                                                                     <span class="astrick delete_img" onclick="delete_img(this,'<?=$img?>','<?='hidden_'.$row['name']?>')">X</span>
                                                                </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <input type="hidden" name="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" id="<?=!empty($row['name'])?'hidden_'.$row['name']:''?>" value="">
                                            </div>

                                        </div>
                                    </div>
                                <?php } ?>

                                <?php
                            } //foreach
                            ?>
                                   <?php /*
if (!empty($edit_data)) { ?>
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">

                    <div class="panel-body">
                        <h2>sign off</h2>

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
<?php } */ ?>
        
 
                             
                            <?php
                        }
                         ?>
                    
				<div id="section1">
				<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body">
					<h2><div id="mainHeader1">
						Pre Placement Information
					</div></h2>
					<?php //pr($edit_data);die;?>
					<div class="form-group"><div id="">
						<textarea id="pre_placement_info" class="form-control"  name="pre_placement_info"><?=!empty($edit_data[0]['pre_placement_info'])?html_entity_decode($edit_data[0]['pre_placement_info']):(isset($edit_data[0]['pre_placement_info'])?$edit_data[0]['pre_placement_info']:'')?></textarea>
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
							<?php if(!empty($edit_data_pp_aims)){
					//$health_count=count($edit_data_pp_health);
					$i=1;
					?>
				<?php foreach($edit_data_pp_aims as $aim_data){ //pr($aim_data);?>
					
					<div class="row" id="item_new_aims_<?php echo $aim_data['aims_of_placement_id'];?>">
                                    <div class="form-group col-md-11 col-sm-12">
                                        <div id="">
										<textarea id="aims_of_placement_data_<?php echo $i; ?>" class="form-control"  name="aims_of_placement_data<?= $aim_data['aims_of_placement_id'] ?>"><?php echo $aim_data['aims_of_placement_data'];?></textarea>
										
										</div>
                                    </div>
                                        <div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_aims_of_placement('item_new_aims_<?php echo $aim_data['aims_of_placement_id']; ?>');"></span></a></div>
								
					</div>
							
									<div class="clearfix"></div>
									
				<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						</div>
					

					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_aims_of_placement" name="delete_aims_of_placement" value="">
                                <a id="add_pp_aims_of_placement" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add Aims of Placement
                                </a>
                    </div>
					
				</div>
				</div>
				</div>
				<div class="col-sm-6">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="lac_placement">
					<h2><div id="mainHeader1">
						Actions from LAC Review 
						<small>Medium Term Plans</small>
					</div></h2>
					
						
						<div class="add_lac" id="add_lac"> 
							<?php if(!empty($edit_data_pp_lac)){
					
					$i=1;
					?>
				<?php foreach($edit_data_pp_lac as $lac_data){ //pr($lac_data);?>
					
					<div class="row" id="item_new_lac_<?php echo $lac_data['lac_review_id'];?>"><div class="form-group col-md-11 col-sm-12"><div id="">
										<textarea id="lac_of_placement_data_<?php echo $i; ?>" class="form-control"  name="lac_review_data<?= $lac_data['lac_review_id'] ?>"><?php echo $lac_data['lac_review_data'];?></textarea>
										
										</div></div>
										
								<div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_lac_of_placement('item_new_lac_<?php echo $lac_data['lac_review_id'];?>');"></span></a></div>
								
					</div>
							
									<div class="clearfix"></div>
									
				<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						</div>
					

					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_lac_of_placement" name="delete_lac_of_placement" value="">
                                <a id="add_pp_lac_of_placement" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add Actions from LAC review
                                </a>
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
					//$health_count=count($edit_data_pp_health);
					$i=1;
					?>
				<?php foreach($edit_data_pp_health as $health_data){?>
					<div class="row" id="item_new_<?php echo $i; ?>">
						
						<div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text " id="health_name_<?php echo $i;?>" name="health_name<?= $health_data['pp_health_id'] ?>" value="<?php echo $health_data['heading'];?>" /></div>
							<div class="col-sm-4">
								<h4>Placement Plan</h4>
									<div class="form-group">
										<div id="">
											<textarea id="pre_placement_<?php echo $i; ?>" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement<?= $health_data['pp_health_id'] ?>"><?php echo $health_data['pre_placement'];?></textarea>
										</div>
									</div>
								</div>
							<div class="col-sm-4">
								<h4>Risk Assessment</h4>
									<div class="form-group">
									   <div id=""><textarea id="risk_assesment_<?php echo $i; ?>" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment<?= $health_data['pp_health_id'] ?>"><?php echo $health_data['risk_assesment'];?></textarea></div>
									</div>
									</div>
									<div class="col-sm-4">
									   <h4>INDIVIDUAL STRATEGIES</h4>
									   <div class="form-group">
										  <div id=""><textarea id="individual_strategies_<?php echo $i; ?>" class="form-control"  placeholder="What are the strategies being used to minimise the risk?" name="individual_strategies<?= $health_data['pp_health_id'] ?>"><?php echo $health_data['individual_strategies'];?></textarea></div>
									   </div>
									</div>
									<div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_health('item_new_<?php echo $i; ?>');"></span></a></div>
									<div class="clearfix"></div>
									</div>
				<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						
				</div>
						
						<div class="clearfix"></div>
					</div>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_cpt_review_id" name="delete_cpt_review_id" value="">
                                <a id="add_new_cpt_review" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Health
                                </a>
                    </div>
					
					
					
					<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review_education">
					<h2><div id="mainHeader1">
						Education
					</div></h2>
				<!--- this will be added by user from front end -->
				<?php if(!empty($edit_data_pp_edu)){ //pr($edit_data_pp_edu);
					//$health_count=count($edit_data_pp_health);
					$i=1;
					?>
				<?php foreach($edit_data_pp_edu as $edu_data){?>
					<div class="row" id="item_new_edu_<?php echo $i; ?>">
					   <div class="col-md-12 pp_title_1"><h4>Title</h4><input id="title_education_<?php echo $i;?>" type="text" name="title_education<?= $edu_data['pp_edu_id'] ?>" value="<?php echo $edu_data['heading_edu'];?>"/></div>
					   <div class="col-sm-4">
						  <h4>Placement Plan</h4>
						  <div class="form-group">
							 <div id=""><textarea id="pre_placement_<?php echo $i; ?>" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_edu_sub<?= $edu_data['pp_edu_id'] ?>"><?php echo $edu_data['pre_placement_edu_sub']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>Risk Assessment</h4>
						  <div class="form-group">
							 <div id=""><textarea id="risk_assesment_<?php echo $i; ?>" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_edu<?= $edu_data['pp_edu_id'] ?>"><?php echo $edu_data['risk_assesment_edu']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>INDIVIDUAL STRATEGIES</h4>
						  <div class="form-group">
							 <div id=""><textarea id="individual_strategies_<?php echo $i; ?>" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_edu<?= $edu_data['pp_edu_id'] ?>"><?php echo $edu_data['individual_strategies_edu']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_edu('item_new_edu_<?php echo $i; ?>');"></span></a></div>
					   <div class="clearfix"></div>
					</div>
				<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						
				</div>
						
						<div class="clearfix"></div>
					</div>
				<!--- end this will be added by user from front end -->

					</div>
					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                             <input type="hidden" id="delete_cpt_review_edu_id" name="delete_cpt_review_edu_id" value="">
                                <a id="add_new_cpt_review_education" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Education
                                </a>
                    </div>
					
					
					<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review_transport">
					<h2><div id="mainHeader1">
						Transport
					</div></h2>
				<!--- this will be added by user from front end -->
				<?php if(!empty($edit_data_pp_tra)){ //pr($edit_data_pp_tra);
					//$health_count=count($edit_data_pp_health);
					$i=1;
					?>
				<?php foreach($edit_data_pp_tra as $tra_data){?>
					<div class="row" id="item_new_tra_<?php echo $i; ?>">
					   <div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" id="title_tra_<?php echo $i;?>" name="title_tra<?= $edu_data['pp_edu_id'] ?>"  value="<?php echo $tra_data['heading_tra'];?>"/></div>
					   <div class="col-sm-4">
						  <h4>Placement Plan</h4>
						  <div class="form-group">
							 <div id=""><textarea id="pre_placement_<?php echo $i; ?>" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_tra<?= $edu_data['pp_edu_id'] ?>"><?php echo $tra_data['pre_placement_tra']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>Risk Assessment</h4>
						  <div class="form-group">
							 <div id=""><textarea id="risk_assesment_<?php echo $i; ?>" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_tra<?= $edu_data['pp_edu_id'] ?>"><?php echo $tra_data['risk_assesment_tra']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>INDIVIDUAL STRATEGIES</h4>
						  <div class="form-group">
							 <div id=""><textarea id="individual_strategies_<?php echo $i; ?>" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_tra<?= $edu_data['pp_edu_id'] ?>"><?php echo $tra_data['individual_strategies_tra']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_tra('item_new_tra_<?php echo $i; ?>');"></span></a></div>
					   <div class="clearfix"></div>
					   
					</div>
					<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						
				</div>
						
						<div class="clearfix"></div>
					</div>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_cpt_review_tra_id" name="delete_cpt_review_tra_id" value="">
                                <a id="add_new_cpt_review_transport" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Transport
                                </a>
                    </div>
					
					
					<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review_contact">
					<h2><div id="mainHeader1">
						Contact
					</div></h2>
				<!--- this will be added by user from front end -->
				
				<?php if(!empty($edit_data_pp_con)){ //pr($edit_data_pp_tra);
					//$health_count=count($edit_data_pp_health);
					$i=1;
					?>
				<?php foreach($edit_data_pp_con as $con_data){?>
					<div class="row" id="item_new_con_<?php echo $i;?>">
						<div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" id="title_con_<?php echo $i;?>" name="title_con<?php echo $con_data['pp_con_id'];?>" value="<?php echo $con_data['heading_con'];?>" /></div>
					   <div class="col-sm-4">
						  <h4>Placement Plan</h4>
						  <div class="form-group">
							 <div id=""><textarea id="pre_placement_<?php echo $i;?>" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_con<?php echo $con_data['pp_con_id'];?>"><?php echo $con_data['pre_placement_con']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>Risk Assessment</h4>
						  <div class="form-group">
							 <div id=""><textarea id="risk_assesment_<?php echo $i;?>" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_con<?php echo $con_data['pp_con_id'];?>"><?php echo $con_data['risk_assesment_con']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>INDIVIDUAL STRATEGIES</h4>
						  <div class="form-group">
							 <div id=""><textarea id="individual_strategies_<?php echo $i;?>" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_con<?php echo $con_data['pp_con_id'];?>"><?php echo $con_data['individual_strategies_con']?></textarea></div>
						  </div>
					   </div>
					   <div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_con('item_new_con_<?php echo $i;?>');"></span></a></div>
					   <div class="clearfix"></div>
					   
					</div>
					<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						
				</div>
						
						<div class="clearfix"></div>
					</div>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_cpt_review_con_id" name="delete_cpt_review_con_id" value="">
                                <a id="add_new_cpt_review_contact" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Contact
                                </a>
                    </div>
					
					<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review_free_time">
					<h2><div id="mainHeader1">
						Free Time
					</div></h2>
				<!--- this will be added by user from front end -->
				<?php if(!empty($edit_data_pp_ft)){ //pr($edit_data_pp_tra);
					//$health_count=count($edit_data_pp_health);
					$i=1;
					?>
				<?php foreach($edit_data_pp_ft as $ft_data){?>
					<div class="row" id="item_new_ft_<?php echo $i;?>">
					   <div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" id="title_ft_<?php echo $i;?>"  name="title_ft<?php echo $ft_data['pp_ft_id'];?>" value="<?php echo $ft_data['heading_ft'];?>" /></div>
					   <div class="col-sm-4">
						  <h4>Placement Plan</h4>
						  <div class="form-group">
							 <div id=""><textarea id="pre_placement_<?php echo $i;?>" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_ft<?php echo $ft_data['pp_ft_id'];?>"><?php echo $ft_data['pre_placement_ft'];?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>Risk Assessment</h4>
						  <div class="form-group">
							 <div id=""><textarea id="risk_assesment_<?php echo $i;?>" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_ft<?php echo $ft_data['pp_ft_id'];?>"><?php echo $ft_data['risk_assesment_ft'];?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>INDIVIDUAL STRATEGIES</h4>
						  <div class="form-group">
							 <div id=""><textarea id="individual_strategies_<?php echo $i;?>" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_ft<?php echo $ft_data['pp_ft_id'];?>"><?php echo $ft_data['individual_strategies_ft'];?></textarea></div>
						  </div>
					   </div>
					   <div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_ft('item_new_ft_<?php echo $i;?>');"></span></a></div>
					   <div class="clearfix"></div>
					</div>
					<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						
				</div>
						
						<div class="clearfix"></div>
					</div>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_cpt_review_ft_id" name="delete_cpt_review_ft_id" value="">
                                <a id="add_new_cpt_review_free_time" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Free Time
                                </a>
                    </div>
					
					
					
					<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review_mgi">
					<h2><div id="mainHeader1">
						Mobile, Gaming & Internet
					</div></h2>
				<!--- this will be added by user from front end -->
				<?php if(!empty($edit_data_pp_mgi)){ //pr($edit_data_pp_tra);
					//$health_count=count($edit_data_pp_health);
					$i=1;
					?>
				<?php foreach($edit_data_pp_mgi as $mgi_data){?>
					<div class="row" id="item_new_mgi_<?php echo $i;?>">
				   <div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" id="title_mgi_<?php echo $i;?>" name="title_mgi<?php echo $mgi_data['pp_mgi_id'];?>" value="<?php echo $mgi_data['heading_mgi'];?>" /></div>
				   <div class="col-sm-4">
					  <h4>Placement Plan</h4>
					  <div class="form-group">
						 <div id=""><textarea id="pre_placement_<?php echo $i;?>" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_mgi<?php echo $mgi_data['pp_mgi_id'];?>"><?php echo $mgi_data['pre_placement_mgi'];?></textarea></div>
					  </div>
				   </div>
				   <div class="col-sm-4">
					  <h4>Risk Assessment</h4>
					  <div class="form-group">
						 <div id=""><textarea id="risk_assesment_<?php echo $i;?>" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_mgi<?php echo $mgi_data['pp_mgi_id'];?>"><?php echo $mgi_data['risk_assesment_mgi'];?></textarea></div>
					  </div>
				   </div>
				   <div class="col-sm-4">
					  <h4>INDIVIDUAL STRATEGIES</h4>
					  <div class="form-group">
						 <div id=""><textarea id="individual_strategies_<?php echo $i;?>" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_mgi<?php echo $mgi_data['pp_mgi_id'];?>"><?php echo $mgi_data['individual_strategies_mgi'];?></textarea></div>
					  </div>
				   </div>
				   <div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_mgi('item_new_mgi_<?php echo $i;?>');"></span></a></div>
				   <div class="clearfix"></div>
				</div>
					<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
						
				</div>
						
						<div class="clearfix"></div>
					</div>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_cpt_review_mgi_id" name="delete_cpt_review_mgi_id" value="">
                                <a id="add_new_cpt_review_mgi" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Mobile, Gaming & Internet
                                </a>
                    </div>
					
					
					<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review_pr">
					<h2><div id="mainHeader1">
						Positive Relationships
					</div></h2>
				<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_pr)){ //pr($edit_data_pp_tra);
					//$health_count=count($edit_data_pp_health);
					$i=1;
					?>
				<?php foreach($edit_data_pp_pr as $pr_data){?>
					<div class="row" id="item_new_pr_<?php echo $i;?>">
				  <div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" id="title_pr_<?php echo $i;?>" name="title_pr<?php echo $pr_data['pp_pr_id'];?>" value="<?php echo $pr_data['heading_pr'];?>" /></div>
				   <div class="col-sm-4">
					  <h4>Placement Plan</h4>
					  <div class="form-group">
						 <div id=""><textarea id="pre_placement_<?php echo $i;?>" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_pr<?php echo $pr_data['pp_pr_id'];?>"><?php echo $pr_data['pre_placement_pr'];?></textarea></div>
					  </div>
				   </div>
				   <div class="col-sm-4">
					  <h4>Risk Assessment</h4>
					  <div class="form-group">
						 <div id=""><textarea id="risk_assesment_<?php echo $i;?>" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_pr<?php echo $pr_data['pp_pr_id'];?>"><?php echo $pr_data['risk_assesment_pr'];?></textarea></div>
					  </div>
				   </div>
				   <div class="col-sm-4">
					  <h4>INDIVIDUAL STRATEGIES</h4>
					  <div class="form-group">
						 <div id=""><textarea id="individual_strategies_<?php echo $i;?>" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_pr<?php echo $pr_data['pp_pr_id'];?>"><?php echo $pr_data['individual_strategies_pr'];?></textarea></div>
					  </div>
				   </div>
				   <div class="col-md-1 col-sm-2 add_items_field mb44">
				   	<a class="btn btn-default btn_border">
				   		<span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_pr('item_new_pr_<?php echo $i;?>');">
				   		</span>
				   	</a>
				   </div>
				   <div class="clearfix"></div>
				</div>
						<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
				</div>
						
						<div class="clearfix"></div>
					</div>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_cpt_review_pr_id" name="delete_cpt_review_pr_id" value="">
                                <a id="add_new_cpt_review_pr" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Positive Relationships
                                </a>
                    </div>
					
					
					
					<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
				<div class="panel-body" id="add_cpt_review_bc">
					<h2><div id="mainHeader1">
						Behaviour Concerns
					</div></h2>
				<!--- this will be added by user from front end -->
					<?php if(!empty($edit_data_pp_bc)){ //pr($edit_data_pp_bc);
					//$health_count=count($edit_data_pp_health);
						$i=1;
						?>
					<?php foreach($edit_data_pp_bc as $bc_data){?>
					<div class="row new_bccls" id="item_new_bc_<?php echo $i;?>">
					   <div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" id="title_bc_<?php echo $i;?>" name="title_bc<?php echo $bc_data['pp_bc_id'];?>"  value="<?php echo $bc_data['heading_bc'];?>"/></div>
					   <div class="col-sm-4">
						  <h4>Placement Plan</h4>
						  <div class="form-group">
							 <div id=""><textarea id="pre_placement_<?php echo $i;?>" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_bc<?php echo $bc_data['pp_bc_id'];?>"><?php echo $bc_data['pre_placement_bc'];?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>Risk Assessment</h4>
						  <div class="form-group">
							 <div id=""><textarea id="risk_assesment_<?php echo $i;?>" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_bc<?php echo $bc_data['pp_bc_id'];?>"><?php echo $bc_data['risk_assesment_bc'];?></textarea></div>
						  </div>
					   </div>
					   <div class="col-sm-4">
						  <h4>INDIVIDUAL STRATEGIES</h4>
						  <div class="form-group">
							 <div id=""><textarea id="individual_strategies_<?php echo $i;?>" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_bc<?php echo $bc_data['pp_bc_id'];?>"><?php echo $bc_data['individual_strategies_bc'];?></textarea></div>
						  </div>
					   </div>
					   <div class="col-md-1 col-sm-2 add_items_field mb44">
					   	<a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash remove_bc" data-id="<?php echo $i;?>" onclick="delete_cpt_review_row_bc('item_new_bc_<?php echo $i;?>');"></span></a></div>
					   <div class="clearfix"></div>
					</div>
						<?php $i++; } ?>
				<script>
					var x= "<?php echo $i;?>";
				</script> <?php }?>
				</div>
						
						<div class="clearfix"></div>
					</div>
				<!--- end this will be added by user from front end -->
					</div>
					<div class="clearfix"></div>
					<div class=" section_four text-center mb30">
                                <input type="hidden" id="delete_cpt_review_bc_id" name="delete_cpt_review_bc_id" value="">
                                <a id="add_new_cpt_review_bc" class="btn btn-default" href="javascript:;">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Add More Behaviour Concerns
                                </a>
                    </div>
					<div class="col-sm-12 capsBtn">
                                <div class="">
                                <input type="hidden" name="placement_plan_id" id="placement_plan_id" value="<?=!empty($edit_data[0]['placement_plan_id'])?$edit_data[0]['placement_plan_id']:''?>">
                                <input type="hidden" name="yp_id" id="yp_id" value="<?=!empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:$ypid?>">

                                <input type="hidden" name="care_home_id" id="care_home_id" value="<?=!empty($YP_details[0]['care_home'])?$YP_details[0]['care_home']:''?>">
                                
                                <input type="hidden" name="hdn_submit_status" id="hdn_submit_status" value="1"/>
                                    <input type="hidden" name="HdnSubmitBtnVlaue" id="HdnSubmitBtnVlaue" value="save"/>
                                    <input type="hidden" name="HdnChangeEmailTmp" id="HdnChangeEmailTmp" value="no"/>
                                   <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                     <button type="submit" class="btn btn-default updat_bn" name="submit_ppform" id="submit_ppforms" value="submit" style="default">UPDATE</button>
 <div class="pull-right for-tab">
                                <div class="btn-group capsBtn">
                                    <!-- <a href="javascript:;" class="btn btn-default" onclick="SavePlacementPlanForm('print');"> <i class="fa fa-print"></i>Print</a> -->
                                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default">CARE HOME YP LIST</a>
                                    <a href="<?=base_url('YoungPerson/view/'.$ypid)?>" class="btn btn-default" name="back_to_yp_info" id="back_to_yp_info" value="submit" style="default">Back To YP Info</a>
                                    <a href="<?=base_url('PlacementPlan/index/'.$ypid)?>" class="btn btn-default"  value="submit" style="default">BACK TO CURRENT PP</a>
                                    
                                </div>
                            </div>                                    

  
                                </div>
                            </div>
				</div>
			
				</div>
		 
		 
        </div>
                       
                             
                            
                    </form>        
                </div>
            </div>
        </div>
