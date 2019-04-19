
<script>
//search Contact list
    $('#searchContact').keyup(function () {

        var inputVal = $(this).val();

        if (inputVal.length > 2) {
            searchContactList(inputVal);
        } else {
            searchContactList('');
        }
    });

//search Contact list
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
    
    //check mail id 
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

//delete image script
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

    function delete_row(rand) {
        jQuery('#' + rand).remove();
    }

    //image upload
    function showimagepreview(input)
    {
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
        });
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
</script>
<script>
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