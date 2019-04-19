$(document).ready(function () {
    window.ParsleyValidator
        .addValidator('fileextension', function (value, requirement) {
            // the value contains the file path, so we can pop the extension
            var fileExtension = value.split('.').pop();
            var multipleFileType = requirement.split('|');

            if ($.inArray(fileExtension, multipleFileType) != -1)
            {
                return true;
            }else
            {
                return false;
            }

        }, 32)
        .addMessage('en', 'fileextension', 'Upload Only .png, .jpeg, .jpg Format.');
    $("#update_myprofile").parsley();

});

function change_password()
{
    var $form = $('#update_password');
    $form.parsley().validate();
    if ($('#update_password').parsley().isValid())
    {
        //$('input[type="button"]').prop('disabled', true);
        var delete_meg ="Are You Sure Want To Change Password ?";
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
                        $('#update_password').submit();
                        dialog.close();
                    }
                }]
            });


    }
}
$('#password').attr("autocomplete", "off");
setTimeout('$("#password").val("");', 2000);

$(document).ready(function() {
    $("#update_password").parsley();
});