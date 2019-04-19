$(document).ready(function () {
    $('#mail_config').parsley();
});

$(window).on('load resize', function () {
    $('.content-wrapper').css('min-height', $(window).height() - 51);
});

/*$(document).ready(function () {
        $('body').on('click', '#camp_submit_btn', function (event) {
        	if ($('#mail_config').parsley().isValid()) {

                event.preventDefault();
                var formData = $("#mail_config").serialize();
                $.ajax({
                	url: base_url +'/Admin/MailConfig/updatedata',
                    data: formData,
                    type: "POST",
                    beforeSend: function () {
                    	loder_url = base_url + 'uploads/images/ajax-loader.gif';
                        //$.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> Please wait...'});
                        $.blockUI({message: "<img src="+loder_url+"> Please wait..."});
                    },
                    success: function (result)
                    {
                    	$.unblockUI();
                        var arr = JSON.parse(result);
                        BootstrapDialog.alert(arr.message);
                        if (arr.status) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                });
                return false;
            }
        });

    });*/