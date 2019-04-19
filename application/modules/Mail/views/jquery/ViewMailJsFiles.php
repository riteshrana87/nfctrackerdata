<script>
    $(document).ready(function () {
        $('#compose-form').parsley();
    });

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');

        BootstrapDialog.confirm("Are your Sure to delete this item?", function (result) {
            if (result) {
                $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                $('#' + divId).remove();
            } else {

            }
            $('#confirm-id').on('hidden.bs.modal', function () {
                $('body').addClass('modal-open');
            });
        });
    });
    var config = {
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "/upload_file"               // Server side upload url
    }
    $(document).ready(function () {
        //initMultiUploader(config);
        var dropbox;
        var oprand = {
            dragClass: "active",
            on: {
                load: function (e, file) {
                    // check file size
                    if (parseInt(file.size / 1024) > 20480) {
                        alert("File \"" + file.name + "\" is too big.Max allowed size is 20 MB.");
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
//            console.log(xhr[rand]);

            var filename = file.name;
            var fileext = filename.split('.').pop();
//            console.log(fileext);
            xhr[rand].open("post", "<?php echo base_url('Mail/dragDropImgSave') ?>/" + fileext, true);
            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                }
                else {
                    alert("Failed to compute file upload length");
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
                            template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + filetype + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
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
    function delete_row(rand) {
        jQuery('#' + rand).remove();

    }


    //image upload
    function showimagepreview(input)
    {
        $('.upload_recent').remove();
        var url = '<?php echo base_url(); ?>';
        $.each(input.files, function (a, b) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var arr1 = b.name.split('.');
            var arr = arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function (e) {
                var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                var randtest = 'delete_row("' + rand + '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                    template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                } else {
                    template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" id="img_' + rand + '" name="fileToUpload[]" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

            var file_data = $("#upl").prop("files")[0];   // Getting the properties of file from file field
            var form_data = new FormData();                  // Creating object of FormData class
            form_data.append("file", file_data)
            $.ajax({
                url: '<?php echo base_url('Mail/dragDropImgSave'); ?>/' + arr,
                type: 'POST',
                processData: false, // important
                contentType: false, // important
                data: file_data,
                success: function (d)
                {
                    $('#img_' + rand + '').val(d);
                }
            });
//           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size / 1024;
        //alert(maximum);

    }



    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });
    function addselectedclass(id, emailaddress)
    {
        if ($('#' + id).hasClass('active'))
        {
            $('#' + id).removeClass('active');
        } else
        {
            $('#' + id).addClass('active');
        }
    }
    function addtomail(typemail)
    {
        var emails = '';
        if ($('#' + typemail).val() != '')
        {
            emails += $('#' + typemail).val() + ',';

        }
        $('.cmail.active').each(function () {
            emails += $(this).attr('data-email') + ',';
        });


        emails = emails.slice(',', -1);
        $('#' + typemail).val(emails);
        $('.cmail').removeClass('active');
    }
    function saveConcept()
    {
        $('#compose-form').parsley().validate();
        if ($('#compose-form').parsley().isValid()) {
            $.ajax({
                url: "<?php echo base_url('Mail/saveConcept'); ?>",
                data: $('#compose-form').serialize(),
                type: "POST",
                dataType: 'json',
                success: function (d)
                {
                    BootstrapDialog.alert("Email Saved as Concept!");
                }
            });
        }
        return false;


    }
    $(document).ready(function () {
        //Set Editor For Estimate Content
        var countImg =<?php echo count($mail_files); ?>;

        if (parseInt(countImg) > 10)
        {
            $('.bd-mail-detail').addClass('scrollDiv');
        }
        else
        {
            $('.bd-mail-detail').removeClass('scrollDiv');
        }

        $('#message').summernote({
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            },
            focus: true,
            onImageUpload: function (files, editor, $editable) {

                sendFile(files[0], editor, $editable);
            }
        });
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("file", file);
            $.ajax({
                url: "<?php echo base_url('Mail/uploadFromEditor'); ?>",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    editor.insertImage(welEditable, data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                }
            });
        }

    });
    function signatureBox()
    {
        $('#signaturemodal').modal('show');
        $('#signarea').summernote({
            disableDragAndDrop: true,
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
    }
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });
</script>