$(document).ready(function () {
    $('#care_home_date').datetimepicker({format: 'DD/MM/YYYY'});
var formAction ="";
    $('#registration').parsley();


    if(formAction == "updatedata"){
        $("#password").keyup(function() {
            var password = $("#password").val();
            password = password.trim();
            if(password != ""){
                $("#cpassword").attr("data-parsley-required","true");
            }else{
                $("#cpassword").attr("data-parsley-required","false");
            }
        });
    }
});

$(window).on('load resize', function () {
    $('.content-wrapper').css('min-height', $(window).height() - 51);
});


window.Parsley.addValidator('email', function (value, requirement) {
    var response = false;
    var form = $(this);
    var email = $("#email").val();
    var login_id = $("#login_id").val();
    if(login_id){
        var userId = login_id
    }else{
        var userId = "";
    }

    $.ajax({
        type: "POST",
        url: check_email_url,
        data: {emailID:email,userID:userId}, // <--- THIS IS THE CHANGE
        async: false,
        success: function(result){
            if(result == "true"){
                response = '1';
            }else{
                response = '0';
            }

        },
        error: function() {
            // alert("Error posting feed.");
            var delete_meg ="Error posting feed";
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
    });

    return response;
}, 46)
    .addMessage('en', 'email', 'Entered EmailID is already exist.');


function delete_request(care_home_id){
    var delete_meg ="Are You Sure Want to Delete This Care home  from list ?";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'Admin/CareHome/deletedata/' + care_home_id;
                    dialog.close();
                }
            }]
        });
}

    var image_upload_url = "<?php echo base_url('CareHome/upload_file'); ?>";
    var url_data = "<?php echo base_url(); ?>";

    /* image upload */

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg = "Are your Sure to delete this item?";

        BootstrapDialog.show(
                {
                    title: 'Information',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls' value='" + dataPath + '/' + imgName + "'>");
                                $('#' + divId).remove();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }

                        }]
                });

    });
//image upload
    function showimagepreview(input)
    {
        console.log(input);
        $('.upload_recent').remove();
        var url = '<?php echo base_url(); ?>';
        $.each(input.files, function (a, b) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var arr1 = b.name.split('.');
            var arr = arr1[1].toLowerCase();
            if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function (e) {
                var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                var randtest = 'delete_row("' + rand + '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                    template += '<span class="preview" id="' + rand + '"><img style="max-width: 200px;" src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                } else {
                    template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);
            }
            else
            {
                BootstrapDialog.show(
                {
                    title: 'Information',
                    message: 'Please upload jpg, jpeg, png and gif image.',
                    buttons: [{
                            label: 'Ok',
                            action: function (dialog) {
                                $('#upl').val("");
                                dialog.close();
                            }

                        }]
                });
            }

            //           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size / 20480;
        //alert(maximum);
    }
    function delete_row(rand) {
		
        jQuery('#' + rand).remove();
		$('#upl').val('');
		
    }
	
	/*
      @Author : Ritesh Rana
      @Desc   : Check Duplicate Care home name allow
      @Input 	:
      @Output	:
      @Date   : 6th Dec 2017
    */
	window.Parsley.addValidator('care_home_name', function (value, requirement) {
   
		var response = false;
		var care_home_name = $("#care_home_name").val();
		
		$.ajax({
			type: "POST",
			url: care_home_name_duplicate_link,
			data: {care_home_name: care_home_name, edit_id: edit_id },
			async: false,
			success: function (result) {
				//response = result;
				if (result == 1) {
					response = false;
				} else {
					response = true;
				}
			}        
		});
		return response;
	}, 32).addMessage('en', 'care_home_name', 'Care home name is already used.');