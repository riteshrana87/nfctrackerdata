<?php $i = 0; ?>
<div id="image-filemanger">
    <div class="filemanager-body" >
         <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h3 class="white-link"><?php echo lang('browse');?></h3>
                    </div>
                    <div class="col-xs-12 col-sm-6 text-right">
					    <?php if($past_care_id == 0){ ?>
                        <a data-href="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($parent) . '&yp_id=' . $yp_id); ?>" data-toggle="tooltip" title="" id="button-parent" class="btn btn-white-img" title="<?php echo lang('up'); ?>"><i class="fa fa-level-up"></i></a> 
                        <a data-href="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&yp_id=' . $yp_id); ?>" data-toggle="tooltip" title="<?php echo lang('refresh');?>" id="button-refresh" class="btn btn-white-img"><i class="fa fa-refresh"></i></a>

                        <?php if (checkPermission('Filemanager', 'add')) { ?>
                            <button type="button" data-toggle="tooltip"  id="button-folder"  class="btn btn-white-img"><?php echo lang('create_folder');?></button>
                            <button type="button" data-toggle="tooltip"  id="button-upload" class="btn btn-white-img"><?php echo lang('upload_file');?></button>
                        <?php } ?>
                       <?php if (checkPermission('Filemanager', 'delete')) { ?>
                      <label class="checkbox-inline bg-white"><input type="checkbox" name="checkedAll" id="checkedAll" />Check/Uncheck</label>
                        <button type="button" data-toggle="tooltip" title="<?php echo lang('delete');?>"  id="button-delete" class="btn btn-white-img"><i class="fa fa-trash-o"></i></button>
                        <?php } ?>
                        <?php }else{ ?>
                        <a data-href="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($parent) . '&yp_id=' . $yp_id.'&care_home_id='. $care_home_id .'&past_care_id=' .$past_care_id); ?>" data-toggle="tooltip" title="" id="button-parent" class="btn btn-white-img" title="<?php echo lang('up'); ?>"><i class="fa fa-level-up"></i></a>

                        <a data-href="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&yp_id=' . $yp_id .'&care_home_id='. $care_home_id .'&past_care_id=' .$past_care_id); ?>" data-toggle="tooltip" title="<?php echo lang('refresh');?>" id="button-refresh" class="btn btn-white-img"><i class="fa fa-refresh"></i></a>

                        <?php } ?>

                        <a href="<?=base_url('Filemanager/download/'. $yp_id); ?>" class="btn btn-white-img">Download all images
                         </a>	
                        
                    </div>
                    <div class="clr"></div>
                    <div cass="box" id="folder_box" style="display:none">
						<input placeholder="<?php echo lang('folder_name');?>" type="text" name="folder_name" id="folder_name">
						<input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&yp_id=' . $yp_id); ?>">
						<input type="hidden" name="path" id="path" value="<?php echo $refresh; ?>">
						<input type="button" name="create_folder" id="create_folder" value="<?php echo lang('create');?>">
					</div>
                </div>
				
        <?php if(count($images)>0){?>
		
			<ul id="boximggrid" class="list-unstyled bd-img-upload whitebox-img">
				<div class="gallery">
					<?php foreach ($images as $image) { ?>			 
						<li class="ui-state-default bd-dragimage" >
							<div class=" text-center eachImage">
							<?php if ($image['type'] == 'directory') {
								$imagePath = $image['path'];
							?>
								 <?php if($past_care_id == 0){ ?>
								<a href="javascript:;" title="<?php echo $image['name']; ?>" data-href="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($imagePath) . '&yp_id=' . $yp_id); ?>" class="directory" style="vertical-align: middle;">
									<div class="bd-file-minheight">
										<i class="fa fa-folder-ico"></i>
									</div>
									<label class="mar-tb0">
										<?php echo (strlen($image['name']) > 8) ? substr($image['name'], 0, 8) . '...' : $image['name']; ?>
									</label>
								</a>
								<?php }else{ ?>
									<a href="javascript:;" title="<?php echo $image['name']; ?>" data-href="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($imagePath) . '&yp_id=' . $yp_id.'&care_home_id='. $care_home_id .'&past_care_id=' .$past_care_id); ?>" class="directory" style="vertical-align: middle;">
									<div class="bd-file-minheight">
										<i class="fa fa-folder-ico"></i>
									</div>
									<label class="mar-tb0">
										<?php echo (strlen($image['name']) > 8) ? substr($image['name'], 0, 8) . '...' : $image['name']; ?>
									</label>
								</a>
								<?php } ?>

							
							<?php } ?>
					
							<?php if ($image['type'] == 'image') {
								
								$imagePath = $image['path'].''.$image['name'];
							?>
								<a class="lightbox" id="li_<?php echo $i; ?>" title="<?php echo $image['name']; ?>" href="<?php echo $image['href']; ?>" data-value="<?php echo $image['path']; ?>" data-name="<?php echo $image['name']; ?>" >
									<div class="preview"> 
										<?php if (in_array($image['ext'], array('jpg', 'png', 'jpeg','gif'))) { 
											
											if(strtolower($image['ext']) == 'gif'){
												$image['name'] = '';
											}
										?>
											<div class="bd-file-minheight">
												<img src="<?php echo $image['href']; ?>" class=" img-responsive thumbnail" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" />
											</div>
										<?php } else { ?>
											<div class="bd-file-minheight"> 
												<div class="image_ext">
													<img src="<?php echo base_url('/uploads/images/icons64/file-64.png'); ?>"  width="75"/>
														<p class="img_show">
															<?php echo $image['ext']; ?>
														</p>
												</div>
											</div>
							<?php } ?>
										<label class="mar-tb0">
											<?php echo (strlen($image['name']) > 8) ? substr($image['name'], 0, 8) . '...' : $image['name']; ?>
										</label>
									</div>
								</a>
							<?php } ?>
							<?php if($past_care_id == 0){ ?>
							<input type="checkbox" class="chkImage" name="chkImage" id="chkImage" value='<?= $imagePath;?>'/>
							<?php } ?>
						</div>	
					</li>
					<?php 
						$i++;
					} 
				?>
				<div class="clear"></div> 
				</div>   
			</ul>
         <?php }else {?>
			<div class="whitebox-img text-center bd-min-height p-t-20"><?php echo lang('NO_RECORD_FOUND');?></div>
         <?php }?>
    </div>
</div>

<script>
    
	$(document).ready(function () {
		
		var $gallery = $('.gallery li a.lightbox').simpleLightbox({'sourceAttr' : 'href'});	 // Load Lightbox
		
		// Start - Check box chcek , Uncheck
		$("#checkedAll").change(function() {
			if (this.checked) {
				$(".chkImage").each(function() {
					this.checked=true;
				});
			} else {
				$(".chkImage").each(function() {
					this.checked=false;
				});
			}
		});

		$(".chkImage").click(function () {
			
			if ($(this).is(":checked")) {
				var isAllChecked = 0;

				$(".chkImage").each(function() {
					if (!this.checked)
						isAllChecked = 1;
				});

				if (isAllChecked == 0) {
					$("#checkedAll").prop("checked", true);
				}     
			}
			else {
				$("#checkedAll").prop("checked", false);
			}
		});
	});

</script>