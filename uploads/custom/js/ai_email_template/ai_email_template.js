$(document).ready(function () {
    $('#email_template').parsley();
    tinymce.init({
        selector: '.tinyeditor_email_template',
        branding: false,
        init_instance_callback: function (editor) {            
            editor.on('KeyUp', function (e) {
                checkEditer();
            });
        }
    });
});
function checkEditer() {
    var email_body = tinymce.get('email_body').getContent();
    if (email_body == '') {
        $("#email_body_error").show();
    } else {
        $("#email_body_error").hide();
    }
}

//check all validation filled
$("#email_template").on('submit', function (e) {
    e.preventDefault();
    var form = $(this);
    var valid = false;
    form.parsley().validate();
    var email_body = tinymce.get('email_body').getContent();
    if (email_body == '') {
        $("#email_body_error").show();
        return false;
    } else {
        $("#email_body_error").hide();
    }
    if (form.parsley().isValid()) {
        valid = true;
        $('button[type="submit"]').prop('disabled', true);
    }
    if (valid)
        this.submit();
});

$(window).on('load resize', function () {
    $('.content-wrapper').css('min-height', $(window).height() - 51);
});
function delete_request(template_id) {
    var delete_meg = "Are You Sure Want to Delete This Email Template ?";
    BootstrapDialog.show(
            {
                title: 'Information',
                message: delete_meg,
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: 'ok',
                        action: function (dialog) {
                            window.location.href = baseurl + 'Admin/AIEmailTemplate/deletedata/' + template_id;
                            dialog.close();
                        }
                    }]
            });
}