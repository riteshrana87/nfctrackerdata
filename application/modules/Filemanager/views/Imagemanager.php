<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>


<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }
        ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Filemanager <small>New Forest Care</small>
            <div class="pull-right">
                <div class="btn-group">
                    <?php if($past_care_id == 0){ ?>
                     <a href="<?=base_url('YoungPerson/view/'.$yp_id); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                    <?php }else{ ?>        
                     <a href="<?=base_url('ArchiveCarehome/view/'.$yp_id.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                            </a>    
                    <?php } ?>
                </div>
            </div>
			<div class="clearfix"></div>
        </h1>
        <h1 class="page-title">
           <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>

<div class="box mediagalleryPopupData-img">

    <div class="">

        <?php $this->load->view('Ajaxview'); ?>
    </div>
</div>

<div class="modal fade modal-image" id="folderCreation" tabindex="-1" role="dialog" data-refresh="true" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#folder_name').val('');
                        $('#folderCreation').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo lang('create_folder'); ?></h4>
            </div>
            <form method="post" id="fldrfrm">
                <div class="modal-body" id="modbdy">
                    <div class="form-group">
                        <input placeholder="<?php echo lang('folder_name'); ?>" type="text" name="folder_name" class="form-control folder_name" id="folder_name">
                        <ul class="parsley-errors-list filled hidden" id="flderErr" ><li class="parsley-required"><?php echo lang('folder_name_alpha'); ?></li></ul>
                        <input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&yp_id=' . $yp_id); ?>">
                        <input type="hidden" name="path" id="path" value="<?php echo $refresh; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" name="create_folder" id="create_folder" value="<?php echo lang('create_folder'); ?>">
                    <button type="button" class="btn btn-danger" onClick="$('#folder_name').val('');
                            $('#folderCreation').modal('hide');"><?php echo lang('close'); ?></button>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


      
    </div>
</div>
<script>

    var returnUrl = $.trim($('#returnUrl').val());
    var path = $.trim($('#path').val());

    $("body").delegate(".directory", "click", function (e) {

        e.preventDefault();

        $('#image-filemanger').load($(this).attr('data-href'));
    });

    $("body").delegate("#button-parent", "click", function (e) {
        e.preventDefault();
        $('#image-filemanger').load($(this).attr('data-href'));
    });

    $("body").delegate("#button-refresh", "click", function (e) {
        e.preventDefault();

        $('#image-filemanger').load($(this).attr('data-href'));
    });
	
    $("body").delegate("#button-folder", "click", function (e) {

        $('.folder_name').val('');
        $('#folderCreation').modal('show');
        e.preventDefault();
    });

	// Create Folder
    $("body").delegate("#create_folder", "click", function (e) {
		
        var returnUrl = $.trim($('#returnUrl').val());
        var path = $.trim($('#path').val());
        var folderName = $.trim($('.folder_name').val());
        var re = /^\w+( \w+)*$/;
        if (folderName == null || folderName == "")
        {
            $('.parsley-required').html('<?php echo lang('input_folder'); ?>');
            $('#flderErr').removeClass('hidden');
            return false;
        }
        else
        {
            if (re.test(folderName) == false)
            {
                $('.parsley-required').html('<?php echo lang('folder_name_alpha'); ?>');

                $('#flderErr').removeClass('hidden');
                return false;
            }
            else
            {
                $('#flderErr').addClass('hidden');
            }
        }
		
        $.ajax({
            url: "<?php echo base_url('Filemanager/makeDir'); ?>",
            data: {'name': folderName, 'path': path},
            dataType: 'json',
            type: "POST",
			beforeSend: function () {
				$("#loading").show();
			},
            success: function (d)
            {
				$("#loading").hide();
                if (d.status == '1')
                {
                    $('#image-filemanger').load(returnUrl);
                    $('.folder_name').val();
                    $('#folderCreation').modal('hide');
                    $('#image-filemanger').load(returnUrl);

                }
                else
                {
                    var delete_meg = "<?php echo $this->lang->line('problem_creating_folder'); ?>";
                    BootstrapDialog.show(
                            {
                                title: '<?php echo $this->lang->line('Information'); ?>',
                                message: delete_meg,
                                buttons: [{
                                        label: '<?php echo $this->lang->line('ok'); ?>',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            });
                    return false;
                }
            }

        });
    });
	
	//Upload File
    $("body").delegate("#button-upload", "click", function (e) {
		
        $('#form-upload').remove();
		
        var returnUrl = $.trim($('#returnUrl').val());
		
        var path = $.trim($('#path').val());
		
        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" id="uploadFileId" name="file[]" multiple="true" value="" /></form>');
		
        $('#form-upload #uploadFileId').trigger('click');
		
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function () {
			
            if ($('#form-upload #uploadFileId').val() != '') {
				
                clearInterval(timer);
				
					//
				for (var i = 0; i < $("#uploadFileId").get(0).files.length; ++i) {
						
						var file1=$("#uploadFileId").get(0).files[i].name;
						
						if(file1){        
							
							var file_size=$("#uploadFileId").get(0).files[i].size;
							
								var ext = file1.split('.').pop().toLowerCase();
								
								if($.inArray(ext,['jpg','jpeg','gif', 'png'])===-1){
							 		BootstrapDialog.show({
										title: 'Invalid',
										message: 'Invalid file extension',
										buttons: [{
												label: '<?php echo $this->lang->line('ok'); ?>',
												action: function (dialog) {
													dialog.close();
												}
											}]
									});
									
									return false;
								}

						}
				}
				
                $.ajax({
                    url: "<?php echo base_url('Filemanager/upload/?path='); ?>" + path,
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
						$("#loading").show();
                        $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                        $('#button-upload').prop('disabled', true);
                    },
					
                    success: function (json) {
						$("#loading").hide();
                        if (json['error']) {
                            var delete_meg = json['error'];
                            BootstrapDialog.show(
							{
								title: '<?php echo $this->lang->line('Information'); ?>',
								message: delete_meg,
								buttons: [{
										label: '<?php echo $this->lang->line('ok'); ?>',
										action: function (dialog) {
											dialog.close();
										}
									}]
							});
                        }

                        if (json['success']) {
                            var delete_meg = json['success'];
                            BootstrapDialog.show(
							{
								title: '<?php echo $this->lang->line('Information'); ?>',
								message: delete_meg,
								buttons: [{
										label: '<?php echo $this->lang->line('ok'); ?>',
										action: function (dialog) {
											dialog.close();
										}
									}]
							});

                            $('#image-filemanger').load(returnUrl);
                        }
                    },
					complete: function () {
                        $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                        $('#button-upload').prop('disabled', false);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
	
	// Delete Images
    $("body").delegate("#button-delete", "click", function (e) {
		
        var fileArr = [];
		var checkedlen = $('.chkImage:checked').length;
        if (checkedlen > 0)
        {
            $('.chkImage:checked').each(function () {
                fileArr.push("" + $(this).val() + "");
            });
			
            var delete_meg = "<?php echo lang('common_delete_file'); ?>";
            BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information'); ?>',
                        message: delete_meg,
                        buttons: [{
                                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }, 
							{
                                label: '<?php echo $this->lang->line('ok'); ?>',
                                action: function (dialog) {
									
                                    $.ajax({
                                        url: "<?php echo base_url('Filemanager/deleteImage'); ?>",
                                        data: {name: fileArr},
                                        type: "POST",
                                        dataType: "json",
										beforeSend: function () {
											$("#loading").show();
											$('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
											$('#button-upload').prop('disabled', true);
										},
                                        success: function (data)
                                        {
											$("#loading").hide();
                                            if (data.status == 1)
                                            {
                                                $('#image-filemanger').load($('#button-refresh').attr('data-href'));
                                                var delete_meg = "<?php echo lang('file_delete_message'); ?>";
                                                BootstrapDialog.show(
                                                        {
                                                            title: '<?php echo $this->lang->line('Information'); ?>',
                                                            message: delete_meg,
                                                            buttons: [{
                                                                    label: '<?php echo $this->lang->line('ok'); ?>',
                                                                    action: function (dialog) {
                                                                        dialog.close();
                                                                    }
                                                                }]
                                                        });

                                            }
                                            else
                                            {
                                                var delete_meg = data.error;
                                                BootstrapDialog.show(
                                                        {
                                                            title: '<?php echo $this->lang->line('Information'); ?>',
                                                            message: delete_meg,
                                                            buttons: [{
                                                                    label: '<?php echo $this->lang->line('ok'); ?>',
                                                                    action: function (dialog) {
                                                                        dialog.close();
                                                                    }
                                                                }]
                                                        });
                                            }
                                        },
                                        error: function ()
                                        {
                                            console.log('Error in call');
                                        }
                                    });
                                    dialog.close();
                                }
                            }]
                    });

        }
        else
        {
            var delete_meg = "<?php echo lang('no_file_error'); ?>";
            BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information'); ?>',
                        message: delete_meg,
                        buttons: [{
                                label: '<?php echo $this->lang->line('ok'); ?>',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                    });
        }
//common_delete_file
    });
	
    $("body").delegate("#list", "click", function (e) {
        $('.boximg').removeClass('hidden');
        $('#boximglist').removeClass('hidden');
        $('#boximggrid').addClass('hidden');

    });
    $("body").delegate("#grid", "click", function (e) {
        $('.boximg').removeClass('hidden');
        $('#boximglist').addClass('hidden');
        $('#boximggrid').removeClass('hidden');

    });
</script>

