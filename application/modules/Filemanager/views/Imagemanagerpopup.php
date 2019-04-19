<div class="mediagalleryPopupData-img">
    <div class="row">
        <div class="col-sm-12"><a data-href="<?php echo base_url("Filemanager/index/$yp_id/?dir=uploads/filemanager/Project0" . $yp_id . "/&modal=true");?>" data-toggle="tooltip" title="<?php echo lang('up');?>" id="button-parent" class="btn btn-default"><i class="fa fa-level-up"></i></a> 
            <a data-href="<?php echo base_url("Filemanager/index/$yp_id/?dir=uploads/filemanager/Project0" . $yp_id . "/&modal=true");?>" data-toggle="tooltip" title="<?php echo 'referesh'; ?>" id="button-refresh" class="btn btn-default"><i class="fa fa-refresh"></i></a>
            <button type="button" name="selectfiles" class="btn btn-default" id="selectfiles"><?php echo lang('use_selected'); ?></button> 

        </div>
        <div cass="box" id="folder_box" style="display:none">
            <input placeholder="<?php echo lang('folder_name');?>" type="text" name="folder_name" id="folder_name">
            <input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&yp_id=' . $yp_id); ?>">
            <input type="hidden" name="path" id="path" value="<?php echo base_url('Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&yp_id=' . $yp_id); ?>">
            <input type="button" name="create_folder" id="create_folder" value="<?php echo lang('create');?>">
        </div>
    </div>
    <hr />
    <ul id="selectable" class="list-unstyled bd-img-upload">
        <?php foreach ($images as $image) { ?>
            <li class="ui-state-default col-sm-3 col-lg-3 col-md-3 ">
                <div class=" text-center childImages">
                    <?php if ($image['type'] == 'directory') { ?>
                        <div class="text-center "><a title="<?php echo $image['name']; ?>" data-href="<?php echo base_url('Filemanager/index/'.$yp_id.'?dir=' .rawurlencode($image['path']) . '&modal=true&module='.$module); ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-5x"></i> <label class="mar-tb0">
                                    <?php echo (strlen($image['name']) > 15) ? substr($image['name'], 0, 15) . '...' : $image['name']; ?></label></a></div>

                    <?php } ?>
                    <?php if ($image['type'] == 'image') { ?>
                        <a title="<?php echo $image['name']; ?>" href="javascript:;" data-value="<?php echo $image['path']; ?>" data-name="<?php echo $image['name']; ?>" class="thumbnail">
                            <?php if (in_array($image['ext'], array('jpg', 'png', 'jpeg','JPG','PNG','JPEG'))) { ?>
                                <img src="<?php echo $image['href']; ?>" class="thumbnail-img" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" />
                            <?php } else { ?>
                                <i class="fa fa-file"></i>                   
                            <?php } ?>     <label class="mar-tb0">
                                <input type="hidden" name="path[]" id="selectFiles" value="<?php echo $image['path']; ?>" data-name="<?php echo $image['name']; ?>" />
                                <?php echo (strlen($image['name']) > 15) ? substr($image['name'], 0, 15) . '...' : $image['name']; ?></label>
                        </a>

                    <?php } ?>
                </div>

            </li>
        <?php } ?>
    </ul>
    <br />
</div>

<script>
    $(document).ready(function () {
        $("#selectable").selectable();
        $("#selectable").on("selectablestart", function (event, ui) {
            event.originalEvent.ctrlKey = false;

        });

    });
    var returnUrl = $.trim($('#returnUrl').val());
    var path = $.trim($('#path').val());
    $('a.directory').on('click', function (e) {
        e.preventDefault();

        $('#modbdy').load($(this).attr('data-href'));
    });

    $('.pagination a').on('click', function (e) {
        e.preventDefault();

        $('#modbdy').load($(this).attr('data-href'));
    });

    $('#button-parent').on('click', function (e) {
        e.preventDefault();

        $('#modbdy').load($(this).attr('data-href'));
    });

    $('#button-refresh').on('click', function (e) {
        e.preventDefault();

        $('#modbdy').load($(this).attr('data-href'));
    });
    $('#button-folder').on('click', function (e) {

        $('#folder_box').show();
        e.preventDefault();
    });
    $('#create_folder').click(function ()
    {
        var folderName = $.trim($('#folder_name').val());
        var path = $.trim($('#path').val());
        var returnUrl = $.trim($('#returnUrl').val());
        var re = /^[a-zA-Z].*/;

        if (folderName == '')
        {
             var delete_meg = "please input folder name";
            BootstrapDialog.show(
                    {
                        title: 'Information',
                        message: delete_meg,
                        buttons: [{
                                label: 'ok',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                    });
            return false;
        }
        else
        {
            if (re.test(folderName) == false)
            {
                 var delete_meg = "Folder name should be Alphanumeric";
            BootstrapDialog.show(
                    {
                        title: 'Information',
                        message: delete_meg,
                        buttons: [{
                                label: 'ok',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                    });
                return false;
            }
        }
        $.ajax({
            url: "Filemanager/makeDir",
            data: {'name': folderName, 'path': path},
            dataType: 'json',
            type: "POST",
            success: function (d)
            {
                if (d.status == '1')
                {
                    $('#modbdy').load(returnUrl);
                }
                else
                {
                 var delete_meg = "Problem while creating folder";
                  BootstrapDialog.show(
                    {
                        title: 'Information',
                        message: delete_meg,
                        buttons: [{
                                label: 'ok',
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

    $('#button-upload').on('click', function () {
        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" id="uploadFileId" name="file[]" multiple="true" value="" /></form>');

        $('#form-upload #uploadFileId').trigger('click');


        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function () {
            if ($('#form-upload #uploadFileId').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: "<?php echo base_url('Filemanager/upload/?path='); ?>" + path,
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                        $('#button-upload').prop('disabled', true);
                    },
                    complete: function () {
                        $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                        $('#button-upload').prop('disabled', false);
                    },
                    success: function (json) {
                        if (json['error']) {
                            alert(json['error']);
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $('#button-refresh').trigger('click');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    $('#selectfiles').on('click', function () {
        var checkedlen = $('li.ui-selected > .childImages').length;
        var html = '';
        if (checkedlen > 0)
        {
            $("li.ui-selected > .childImages").each(function () {
                console.log($(this));
                $(this).addClass("foo");

                var name = $(this).find('a').attr('data-name');
                var path = $(this).find('a').attr('data-value');


                if (parseInt(path.length) > 0 && parseInt(name.length) > 0) {
                    html += "<p>" + name + "</p><input type='hidden' name='gallery_files[]' id='gallery_files' value='" + name + "'><input type='hidden' name='gallery_path[]' id='gallery_path' value='" + path + "'>";
                }
            });
            $('.mediaGalleryImg').empty();
            $('.mediaGalleryImg').prepend(html);
            $('#modalGallery').modal('hide');

        }

        return false;
    });
    function getImgUrlToForm(url, name, path)
    {
        var imgpath = path + '/' + name;
        var html = "<p>" + name + "</p><input type='hidden' name='gallery_files[]' id='gallery_files' value='" + name + "'><input type='hidden' name='gallery_path[]' id='gallery_path' value='" + path + "'>";
        $('.mediaGalleryImg').empty();
        $('.mediaGalleryImg').prepend(html);
        $('#modalGallery').modal('hide');
    }
    
    $(".selectable").click(function (e) {
        $(this).toggleClass("selected");
    });

</script>

