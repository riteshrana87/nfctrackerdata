<?php
if (isset($editRecord) && $editRecord == "updatedata") {
    $record = "updatedata";
} else {
    $record = "insertdata";
}
?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'edit' : 'registration';

$path = $form_action_path;
if (isset($readonly)) {
    $disable = $readonly['disabled'];
} else {
    $disable = "";
}
$main_user_data = $this->session->userdata('LOGGED_IN');
$main_user_id = $main_user_data['ID'];
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Add Care Handover</h4>
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
            </div>
        </div>
         <?php
            $attributes = array("name" => "personal_info", "id" => "registration", "data-parsley-validate" => "true", "onload" => "loadit('registration')");

            echo form_open_multipart($path, $attributes);
            ?>
        <div class="modal-body">
           
            <div class="form-group">
                <label for="recipient-name" class="control-label">Title <span class="astrick">*</span></label>
                <input type="text" required="" name="title" data-parsley-pattern='/^[A-Za-z\d\s]+$/' data-parsley-pattern-message="Please enter only alphanumeric." minlength="4" maxlength="255"  class="form-control" id="recipient-name" data-parsley-trigger="keyup">
            </div>
            <div class="form-group">
                <label for="message-text" class="control-label">Notice <span class="astrick">*</span></label>
                <textarea name="notice" required="" class="form-control" id="message-text" rows="5"></textarea>
            </div>

        <div class="form-group dropzone">
                            <!-- new code-->
          <div id="dragAndDropFiles" class="uploadArea bd-dragimage upload_btn_modal">
            <div class="image_part" style="height: 100px;">
              <label name="fileUpload[]">
              <h1 style="top: -162px;"> <i class="fa fa-cloud-upload"></i>
               Select Files Here
              </h1>
              <div class="up_limit_label">Allow file size min. 1 KB and max. 20 MB</div>
              <input type="file" onchange="showimagepreview(this)" name="fileUpload[]" style="display: none" id="upl" multiple />
              </label>
            </div>
            <?php

                            if (!empty($image_data)){
                                if (count($image_data) > 0) {
                                    $i = 15482564;
                                    foreach ($image_data as $image) {
                                        $path = $image['file_path'];
                                        $name = $image['file_name'];
                                        $arr_list = explode('.', $name);
                                        $arr = $arr_list[1];
                                        if (file_exists($path . '/' . $name)) { ?>
            <div id="img_<?php echo $image['file_id']; ?>" class="eachImage"> 
              <a id="delete_row" class="btn delimg remove_drag_img" href="javascript:;" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['file_id']; ?>" data-path="<?php echo $path; ?>">x</a> <span id="<?php echo $i; ?>" class="preview"> <a href='<?php echo base_url('Marketingcampaign/download/' . $image['file_id']); ?>' target="_blank">
              <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
              <img src="<?= base_url($path . '/' . $name); ?>"  width="75"/>
              <?php } else { ?>
              <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/>
                
              </div>
              <?php } ?>
              </a>
              <p class="img_name"><?php echo $name; ?></p>
              <span class="overlay" style="display: none;"> <span class="updone">100%</span></span> 
              </span> </div>
            <?php } ?>
            <?php
                                        $i++;
                                    }
                                    ?>
            <div id="deletedImagesDiv"></div>
            <?php }  }?>
          </div>
          <div class="clearfix"></div>
     
        <!-- end new code --> 
                        </div>
        
        </div>
        <div class="modal-footer">
                <div id="deletediamgesdiv"></div>
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                <input type="submit" class="btn btn-default" name="submit_btn" id="submit_btn" value="Submit" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>

    </div>
</div>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<script>
    var image_upload_url = "<?php echo base_url('Dashboard/upload_file'); ?>";
    var url_data = "<?php echo base_url(); ?>";
    
     /* image upload */

        $('.delimg').on('click', function () {

            var divId = ($(this).attr('data-id'));
            var imgName = ($(this).attr('data-name'));
            var dataUrl = $(this).attr('data-href');
            var dataPath = $(this).attr('data-path');
            var str1 = divId.replace(/[^\d.]/g, '');
            var delete_meg ="Are your Sure to delete this item?";

            BootstrapDialog.show(
                {
                    title: 'Information',
                    message: delete_meg,
                    buttons: [{
                        label: 'Cancel',
                        action: function(dialog) {
                            dialog.close();
                            $('#confirm-id').on('hidden.bs.modal', function () {
                                $('body').addClass('modal-open');
                            });
                        }
                    }, {
                        label: 'ok',
                        action: function(dialog) {
                            $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                            $('#' + divId).remove();
                            $('#confirm-id').on('hidden.bs.modal', function () {
                                $('body').addClass('modal-open');
                            });
                            dialog.close();
                        }

                    }]
                });

        });

        var config = {
            support: "*", // Valid file formats
            form: "demoFiler", // Form ID
            dragArea: "dragAndDropFiles", // Upload Area ID
            uploadUrl: "<?php echo base_url(); ?>Dashboard/upload_file"				// Server side upload url
        }
        $(document).ready(function () {
            //initMultiUploader(config);
            var dropbox;
            var oprand = {
                dragClass: "active",
                on: {
                    load: function (e, file) {
                        // check file size
                        if (parseInt(file.size / 256) > 20480) {
                            var delete_meg ="=File \"" + file.name + "\is too big.Max allowed size is 20 MB." ;
                            BootstrapDialog.show(
                                {
                                    title: 'Information',
                                    message: delete_meg,
                                    buttons: [{
                                        label: 'ok',
                                        action: function(dialog) {
                                            dialog.close();
                                        }
                                    }]
                                });


                            return false;
                        }

                        create_box(e, file);
                    },
                }
            };
            FileReaderJS.setupDrop(document.getElementById('dragAndDropFiles'), oprand);
            var fileArr = [];

            create_box = function (e, file) {
                var rand = Math.floor((Math.random() * 100000) + 3);
                var imgName = file.name; // not used, Irand just in case if user wanrand to print it.
                var src = e.target.result;
                var xhr = new Array();
                xhr[rand] = new XMLHttpRequest();
                var filename = file.name;
                var fileext = filename.split('.').pop();
                xhr[rand].open("post", "<?php echo base_url('/Dashboard/upload_file') ?>/" + fileext, true);

                xhr[rand].upload.addEventListener("progress", function (event) {
                    if (event.lengthComputable) {
                        $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                        $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                    }
                    else {
                        var delete_meg ="Failed to compute file upload length";
                        BootstrapDialog.show(
                            {
                                title: 'Information',
                                message: delete_meg,
                                buttons: [{
                                    label: 'ok',
                                    action: function(dialog) {
                                        dialog.close();
                                    }
                                }]
                            });
                    }
                }, false);

                xhr[rand].onreadystatechange = function (oEvent) {
                    var img = xhr[rand].response;
                    var url = '<?php echo base_url(); ?>';
                    if (xhr[rand].readyState === 4) {
                        var filetype = img.split(".")[1];
                        if (xhr[rand].status === 200) {
                            var template = '<div class="eachImage" id="' + rand + '">';
                            var randtest = 'delete_row("' + rand + '")';
                            template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                            if (filetype == 'jpg' || filetype == 'jpeg' || filetype == 'png' || filetype == 'gif') {
                                template += '<span class="preview" id="' + rand + '"><img src="' + src + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                            } else {
                                template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                            }
                            template += '<input type="hidden" name="fileToUpload[]" value="' + img + '">';
                            template += '</span>';
                            $("#dragAndDropFiles").append(template);
                        }
                    }
                };

                xhr[rand].setRequestHeader("Content-Type", "multipart/form-data");
                xhr[rand].setRequestHeader("X-File-Name", file.fileName);
                xhr[rand].setRequestHeader("X-File-Size", file.fileSize);
                xhr[rand].setRequestHeader("X-File-Type", file.type);

                // Send the file (doh)
                xhr[rand].send(file);

            }
            upload = function (file, rand) {
            }

        });


        //image upload
        function showimagepreview(input)
        {
            console.log(input);
            //$('.upload_recent').remove();
            var url = '<?php echo base_url();?>';
            $.each(input.files, function(a,b){
                var rand = Math.floor((Math.random()*100000)+3);
                var arr1 = b.name.split('.');
                var arr= arr1[1].toLowerCase();
                var filerdr = new FileReader();
                var img = b.name;
                filerdr.onload = function(e) {
                    var template = '<div class="eachImage upload_recent" id="'+rand+'">';
                    var randtest = 'delete_row("' +rand+ '")';
                    template += '<a id="delete_row" class="remove_drag_img" onclick='+randtest+'>×</a>';
                    if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif'){
                        template += '<span class="preview" id="'+rand+'"><img src="'+e.target.result+'"><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                    }else{
                        template += '<span class="preview" id="'+rand+'"><div class="image_ext"><img src="'+url+'/uploads/images/icons64/file-64.png"></div><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                    }
                    template += '<input type="hidden" name="file_data[]" value="'+b.name+'" id="img_'+rand+'">';
                    template += '</span>';
                    $('#dragAndDropFiles').append(template);
                }
                filerdr.readAsDataURL(b);
            });
            var maximum = input.files[0].size/20480;
        }
        function delete_row(rand) {
            var image_name=jQuery('#img_' + rand).val();
                        jQuery('#deletediamgesdiv').append('<input type="hidden" id="deleted_images" name="deleted_images[]" value="'+image_name+'" >');
            jQuery('#' + rand).remove();
        }

    
</script>

