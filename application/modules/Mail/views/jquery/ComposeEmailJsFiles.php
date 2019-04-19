
<script>
    function refreshCheckbox(){
        if ($("input[name='checkedIds[]']:checked").length > 1) {
            $('#replyEmail').hide();
            $('#replyAll').hide();
            $('#forwardEmail').hide();
            $('#ComposeMail').hide();
        } else {
            $('#replyEmail').show();
            $('#replyAll').show();
            $('#forwardEmail').show();
            $('#ComposeMail').show();
        }
        
    }

    $('#searchContact').keyup(function () {

        var inputVal = $(this).val();

        if (inputVal.length > 2) {
            searchContactList(inputVal);
        } else {
            searchContactList('');
        }
    });

    function searchContactList(inputVal) {
        var yp_id = $('.ypid').val();
        $.ajax({
            url: "<?php echo base_url('Mail/searchContacts'); ?>",
            data: {searchValue: inputVal,ypid:yp_id},
            type: "post",
            //dataType: "json",
            beforeSend: function () {
                //$.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> Please wait...'});
            },
            success: function (result)
            {
                $('#searchList').html(result);
            },
        });
    }

    $(document).ready(function () {
        $('#compose-form').parsley();
    });
    
    /*validation added by Dhara Bhalala to check valid email*/
    $(document).ready(function() {
       $('#to').blur(function() {
            checkemail();
        });

    $('#bcc').blur(function() {
            checkemail();
        });

    });
    
    function checkemail(){
        var tomail = $('#to').val();
        var bccmail = $('#bcc').val();
            var count1 = 0;
            var count2 = 0;
            var flag = '';

            var emails = tomail.split(";");
            if (emails !== '') {
                emails.forEach(function (email) {
                    if (email !== '') {
                        flag = validateEmail(email.trim());
                        if (flag == 'f') {
                            count1 = count1 + 1;
                        }
                    }
                });
            }

            var emails = bccmail.split(";");
            if (emails !== '') {
                emails.forEach(function (email) {
                    if (email !== '') {
                        flag = validateEmail(email.trim());
                        if (flag == 'f') {
                            count2 = count2 + 1;
                        }
                    }
                });
            }

            if (count1 > 0 || count2 > 0) {
                $('#sentmail').attr('disabled', true);
            }
            else {
                $('#sentmail').attr('disabled', false);
            }

            if (count1 > 0) {
                $('#email_error').css("display", "block");
            } else {
                $('#email_error').css("display", "none");
            }

            if (count2 > 0) {
                $('#bcc_error').css("display", "block");
            } else {
                $('#bcc_error').css("display", "none");
            }
        }

        function validateEmail(sEmail) {
             var filter = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

            //var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

            if (filter.test(sEmail)) {
                return 't';
            } else {
                return 'f';
            }
        }

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');

        BootstrapDialog.confirm("<?php echo lang('mail_delete_confirm'); ?>", function (result) {
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
                    if (parseInt(file.size / 1024) > 5120) {
                        //alert("File \"" + file.name + "\" is too big.Max allowed size is 20 MB.");
                        alert("<?= lang('mail_file'); ?> \"" + file.name + "\" <?= lang('mail_file_size'); ?>");
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
            if(/^[a-zA-Z0-9\-\_\.]*$/.test(imgName) == false) {
                BootstrapDialog.show(
                {
                    title: 'Information',
                    message: 'space not allowed in file name',
                    buttons: [{
                            label: 'ok',
                            action: function (dialog) {
                                window.location.href = window.location.href;              
                                dialog.close();
                            }

                        }]
                });
                return false;
            }
            var src = e.target.result;
            var xhr = new Array();
            xhr[rand] = new XMLHttpRequest();
//            console.log(xhr[rand]);

            var filename = file.name;
            var fileext = filename.split('.').pop();
//            console.log(fileext);
            xhr[rand].open("post", "<?php echo base_url('Mail/dragDropImgSave') ?>/" + imgName, true);
            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                }
                else {
                    alert("<?php echo lang('mail_file_compute_lenght'); ?>");
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
                        template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>&times;</a>';
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
        //$('.upload_recent').remove();
        var url = '<?php echo base_url(); ?>';
        var i = 0;
        $.each(input.files, function (a, b) {

            var rand = Math.floor((Math.random() * 100000) + 3);
            var arr1 = b.name.split('.');
            var arr = arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var string = b.name
            string = string.replace(/[&\/\\#,+()$~%'":*?<>{} ]/g,'_');
            var img = string;
            /* if(/^[a-zA-Z0-9\-\_\.]*$/.test(img) == false) {
                BootstrapDialog.show(
                {
                    title: 'Information',
                    message: 'space and special characters are not allowed in file name',
                    buttons: [{
                            label: 'ok',
                            action: function (dialog) {
                                window.location.href = window.location.href;              
                                dialog.close();
                            }

                        }]
                });
                return false;
            }*/
            
            filerdr.onload = function (e) {
                var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                var randtest = 'delete_row("' + rand + '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>&times;</a>';
                if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                    template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                } else {
                    template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="fileToUpload[]" value="' + img + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

            var file_data = $("#upl").prop("files")[i];  
            i++;
             // Getting the properties of file from file field
            var form_data = new FormData();                  // Creating object of FormData class
            form_data.append("file", file_data)
            console.log(file_data);
            $.ajax({
                url: '<?php echo base_url('Mail/dragDropImgSave'); ?>/' + img,
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
        var file_size = input.files[0].size / 1024;
        if(file_size > 5120){
             BootstrapDialog.show(
                {
                    title: 'Information',
                    message: 'Please upload the file with maximum size of 5MB',
                    buttons: [{
                            label: 'ok',
                            action: function (dialog) {
                                $('.upload_recent').empty();
                                //window.location.href = window.location.href;              
                                dialog.close();
                            }

                        }]
                });

        }else{
            var maximum = input.files[0].size / 1024;
        }
        
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
            emails += $('#' + typemail).val() + ';';
        }

        $('.cmail.active').each(function () {
            emails += $(this).attr('data-email') + ';';
        });

        emails = emails.slice(';', -1);

       var eamil_data_val = emails.replace('\\','');

       var eamil_data = eamil_data_val.replace('\\','');

    
        $('#' + typemail).val(eamil_data);
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
                    BootstrapDialog.alert("<?php echo lang('mail_save_concept_alert'); ?>");
                }
            });
        }
        return false;


    }
    $(document).ready(function () {
        //Set Editor For Estimate Content
        $('#message').summernote({
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            },
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            //['insert', ['link', 'picture', 'hr']],
            ['view', ['fullscreen'/*, 'codeview' */]],   // remove codeview button 
            ['help', ['help']]
          ],
            focus: true,
            
            onImageUpload: function (files, editor, $editable) {
                sendFile(files[0], editor, $editable);
            }
        });

        function sendFile(file, editor, welEditable) {
            data = new FormData();
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
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
                      $.unblockUI();
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
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['fullscreen'/*, 'codeview' */]],   // remove codeview button 
                ['help', ['help']]
              ],
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
    }
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });
</script>


<script>

    $("body").on('click', '#selectall', function () {
        var checkAll = $("#selectall").prop('checked');
        if (checkAll) {
            $("input[name='checkedIds[]']").prop("checked", true);
        } else {
            $("input[name='checkedIds[]']").prop("checked", false);
        }
        refreshCheckbox();
    });

    $("body").on('click', "input[name='checkedIds[]']", function () {
        refreshCheckbox();

            if ($("input[name='checkedIds[]']").length == $("input[name='checkedIds[]']:checked").length) {
                $("#selectall").prop("checked", true);
            } else {
                $("#selectall").prop("checked", false);
            }
        });



    $("body").on('click', '#flagMail', function () {

        var checkedList = [];
        $("input[name='checkedIds[]']:checked").each(function () {
            checkedList.push($(this).attr('id'));
        });

        var finalCheckedFlagList = checkedList.join();

        if (finalCheckedFlagList.length === 0) {
            BootstrapDialog.alert("<?php echo lang('mail_select_atleast_one'); ?>");
            return false;
        }

        BootstrapDialog.confirm("<?php echo lang('mail_select_flag'); ?>", function (result) {

            if (result) {
                $.ajax({
                    url: "<?php echo base_url('Mail/movetoImportant'); ?>",
                    data: {ids: finalCheckedFlagList},
                    type: "post",
                    dataType: "json",
                    beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
                    },
                    success: function (d)
                    {
                        if (d.status) {
                            BootstrapDialog.alert("<?= lang('mail_moved_important') ?>");

                            $("input[name='checkedIds[]']:checked").each(function () {
                                $(this).parent().parent().remove();
                            });
                            $.unblockUI();


                        }
                    }
                });
            }
        });

    });

    $("body").on('click', '#trashMail', function () {

        // $('#trashMail').click(function () {
        // $('#trashMail').click(function () {
       /* if ($('.Trash').hasClass('active'))
        {
            return false;
        }*/
        
        var checkedList = [];
        $("input[name='checkedIds[]']:checked").each(function () {
            checkedList.push($(this).attr('id'));
        });

        var finalCheckedList = checkedList.join();

        if (finalCheckedList.length === 0) {
            BootstrapDialog.alert("<?php echo lang('mail_select_atleast_one'); ?>");
            return false;
        }

        BootstrapDialog.confirm("<?= lang('mail_select_remove') ?>", function (result) {
            var yp_id = $('.yp_id').val();
            if (result) {
                $.ajax({
                    url: "<?php echo base_url('Mail/movetoTrash'); ?>"+'/'+yp_id,
                    data: {ids: finalCheckedList},
                    type: "post",
                    dataType: "json",
                    beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
                    },
                    success: function (d)
                    {
                        if (d.status) {
                            BootstrapDialog.alert("<?php echo lang('mail_moved_trash'); ?>");

                            $("input[name='checkedIds[]']:checked").each(function () {
                                $(this).parent().parent().remove();
                            });
                            refreshCheckbox();
                            //$('.Trash').trigger('click');
                            $.unblockUI();


                        }
                    }
                });
            }
        });

    });

    $("body").on('click', '.starred', function () {

        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/moveMessage'); ?>",
            data: {id: $(this).data('id'), 'path': 'starred'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                // window.location.href = window.location.href;
                if (d.status == 1)
                {
                    BootstrapDialog.alert("<?php echo lang('mail_marked_starred'); ?>");

                    $(el).removeClass('starred');
                    $(el).addClass('unstarred');
//                    window.location.href = window.location.href;
                    $('#star_' + id).addClass('fa-star');
                    $('#star_' + id).removeClass('fa-star-o');
                    $.unblockUI();
                }
            }
        });
    });
    $("body").on('click', '.unstarred', function () {
        var id = $(this).data('id');

        $.ajax({
            url: "<?php echo base_url('Mail/moveMessage'); ?>",
            data: {id: $(this).data('id'), 'path': 'unstarred'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
//                window.location.href = window.location.href;
                if (d.status == 1)
                {
                    BootstrapDialog.alert("<?php echo lang('mail_removed_starred'); ?>");
                    $(el).removeClass('unstarred');
                    $(el).addClass('starred');
                    $('#star_' + id).removeClass('fa-star-0');
                    $('#star_' + id).addClass('fa-star');

////                    alert("done");
//                    $('#' + $(this).data('id')).removeClass('fa-star');
//                    $('#' + $(this).data('id')).addClass('fa-star-0');
                    //  window.location.href = window.location.href;
                }
            }
        });
    });
    $("body").on('click', '.flagged', function () {

        //$('.flagged').click(function () {
        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/markasFlagged'); ?>",
            data: {id: $(this).data('id'), 'path': 'flagged'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                BootstrapDialog.alert("<?php echo lang('mail_marked_flag'); ?>");
                // window.location.href = window.location.href;
                if (d.status == 1)
                {
                    //$.unblockUI();
//                    alert("done");
                    $(el).addClass('unflagged');
                    $(el).removeClass('flagged');
                    $(el).parent('div').addClass('bd-in-mark');
                    $(el).closest('li').addClass('bd-in-mark');
                    $.unblockUI();
                    //  $(elm).parent('div').removeClass('bd-in-mark');

                }
            }
        });
    });
    $("body").on('click', '.unflagged', function () {

        //$('.flagged').click(function () {
        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/markasFlagged'); ?>",
            data: {id: $(this).data('id'), 'path': 'INBOX'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                BootstrapDialog.alert("<?php echo lang('mail_marked_unflag'); ?>");
                if (d.status == 1)
                {
                    $(el).removeClass('unflagged');
                    $(el).addClass('flagged');
                    $(el).parent('div').removeClass('bd-in-mark');
                    $(el).closest('li').removeClass('bd-in-mark');
//                    $(elm).parent('div').removeClass('bd-in-mark');
                    $.unblockUI();
//                    alert("done");
//                    $('#' + $(this).data('id')).removeClass('fa-star');
//                    $('#' + $(this).data('id')).addClass('fa-star-0');
                }
            }
        });
    });

    $("body").on('click', '.mail-tr', function () {
        //$('.mail-tr').click(function () {
        $('.mail-tr').removeClass("ActiveTr");
        $(this).addClass("ActiveTr");
    });

</script>

<script>
    $(document).ready(function () {
        //serch by enter
        $('#searchtext').keyup(function (event)
        {
            if (event.keyCode == 13) {
                data_search('changesearch');
            }

        });
    });
    function markasUnread(uid)
    {

        $.ajax({
            url: "<?php echo base_url('Mail/markasRead'); ?>",
            type: "POST",
            data: {'uid': uid},
            success: function (d)
            {
                $('span#' + uid).removeClass('font-bold');
            }

        });

    }

    //Search data
    function data_search(allflag)
    {
        
        var uri_segment = $("#uri_segment").val();
        var request_url = '';
        if (uri_segment == 0)
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index/' + uri_segment;
        } else
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/' + uri_segment;
        }
        var boxtype = $('#refreshBn').attr('data-boxtype');

        $.ajax({
            type: "POST",
            url: request_url,
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: allflag, 'boxtype': boxtype
            },
            success: function (html) {
                $("#common_div").html(html);
            }
        });
        return false;
    }
    function reset_data()
    {
        $("#searchtext").val("");
        apply_sorting('', '');
        data_search('all');
    }

    /*function reset_data_list(data)
     {
     $("#searchtext").val(data);
     apply_sorting('', '');
     data_search('all');
     }*/

    function changepages()
    {
        data_search('');
    }

    function apply_sorting(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);

        if (sortfilter != '' || sorttype != '') {
            data_search('changesorting');
        }
    }
    //pagination
    $('body').on('click', '#common_tb ul.bd-inbox-pagin a.ajax_paging', function (e) {
        var boxtype = $('#refreshBn').attr('boxtype');
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), 'boxtype': boxtype
            },
            /*
             beforeSend: function () {
             $('#common_div').block({message: 'Loading...'});
             },
             */
            success: function (html) {
                $("#common_div").html(html);
                //    $.unblockUI();
            }
        });
        return false;

    });
    function forwardEmail()
    {

        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('forward');
                window.location.href = url;
            });
        }
         else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function replyEmail()
    {

        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('reply');
                window.location.href = url;
            });
        }
         else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function replyAll()
    {
        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('replyall');
                window.location.href = url;
            });
        }
         else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function updateEmails(type)
    {
        if (type != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            $.ajax({
                url: "<?php echo base_url('Mail/getEmails'); ?>?type=full",
                type: "GET",
                success: function (d)
                {
                    BootstrapDialog.alert("<?php echo lang('mail_inbox_update'); ?>");
                    window.location.href = window.location.href;
                    if (d == 'done')
                    {
                        $.ajax({
                            url: "<?php echo base_url('Mail/leftBarCount'); ?>",
                            type: "GET",
                            success: function (d)
                            {
                                $('#leftbar').html(d);
                                $.unblockUI();
                            }
                        });
                    }
                }

            });
        }

    }
    $("body").on('click', '#refreshBn', function () {
//    $('#refreshBn').click(function () {
        var type = $(this).data('boxtype');
        var yp_id = $('.yp_id').val();
        //console.log(type);
        if (type != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            $.ajax({
                url: "<?php echo base_url('Mail/getEmails'); ?>?type=full",
                type: "POST",
                data: {'manualSync': 'yes', 'boxtype': type, 'folderName': type, 'yp_id':yp_id},
                success: function (d)
                {

                    if (d == 'done')
                    {
                        $.ajax({
                            url: "<?php echo base_url('Mail/Index'); ?>" +'/'+ yp_id + "?type=full",
                            type: "POST",
                            data: {'result_type': 'ajax', 'boxtype': type, 'folderName': type},
                            success: function (d)
                            {
                                $('#main_div').html(d);
                                $(this).data('boxtype', type);
                                BootstrapDialog.alert(type + " Updated!");

                                $('#refereshLeftbox').trigger('click');
                                refreshCheckbox();
                            }

                        });
                    }
                }

            });
        }


    });
    function getMailBoxData(boxtype, id)
    {
        if (boxtype != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            var yp_id = $('.yp_id').val();
            $.ajax({
                url: "<?php echo base_url('Mail/getEmails_data'); ?>",
                type: "POST",
                data: {'boxtype': boxtype, 'folderName': boxtype,'yp_id':yp_id},
                success: function (d)
                {
                    $('#refreshBn').data('boxtype', boxtype);
                    //BootstrapDialog.alert(boxtype+" Updated!");
                    // window.location.href = window.location.href;
                    if (d == 'done')
                    {
                        $.ajax({
                            url: "<?php echo base_url('Mail/Index'); ?>" +'/'+ yp_id + "?type=full",
                            type: "POST",
                            data: {'result_type': 'ajax', 'boxtype': boxtype, 'folderName': boxtype},
                            success: function (d)
                            {
                                $('#refreshBn').data('boxtype', boxtype);
                                $('.leftbx').removeClass('active');
                                $('#' + id).addClass('active');
//                                $('#refereshCode li button').attr('onClick', "updateEmails(" + boxtype + ")");
                                $('#main_div').html(d);
                                var currBoxType = boxtype.charAt(0).toUpperCase() + boxtype.slice(1).toLowerCase();
                                $('#currentBoxType').html(currBoxType);
                                refreshCheckbox();
                                $.unblockUI();
                                //  $('#refereshLeftbox').trigger('click');

                            }

                        });

                    }
                }

            });
        }

    }

</script>
<script>
    $(document).ready(function () {
        $('body').delegate('[data-toggle="ajaxModal"]', 'click',
                function (e) {
                    $('#ajaxModal').remove();
                    e.preventDefault();
                    var $this = $(this)
                            , $remote = $this.data('remote') || $this.attr('data-href')
                            , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                    $('body').append($modal);
                    $modal.modal();
                    $modal.load($remote);
                    //$("body").removeClass("modal-open");
                    //$("body").css("padding-right", "0 !important");

                }
        // $('#ajaxModal').css({height:"350px", overflow:"auto"});
        );

    });
    $('body').on('click', '#refereshLeftbox', function () {
        var id = $('.leftbx.active').attr('id');
        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
        $.ajax({
            url: "<?php echo base_url('Mail/leftBarCount'); ?>",
            type: "GET",
            success: function (d)
            {
                $('#leftbar').html(d);
                $('.leftbx').removeClass('active');
                $('#' + id).addClass('active');
                $.unblockUI();
            }
        });

    });

    $('body').on('click', '.note-image-btn', function () {
        var urldata = $('.note-image-url').val();
        var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
        if (pattern.test(urldata)) {
            return true;
        } else{
             BootstrapDialog.alert("Url is not valid!");
            return false;
        }

    });
</script>
<script>
  $(window).on('load', function(){
    //$('.bd-select-box > ul.nav > li > a.cmail').removeClass('active');
  });

$( document ).ready(function() {
    $('.bd-select-box > ul.nav > li > a.cmail').removeClass('active');
});
  
</script>