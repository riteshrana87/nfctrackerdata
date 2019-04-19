$(document).ready(function () {
var formAction ="";
    $('#registration').parsley();

    $('.chosen-select').chosen();
    $('.chosen-select-salution').chosen();
    $('.chosen-select-status').chosen();
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
                response = true;
            }else{
                response = false;
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


function delete_request(loginId){
    var delete_meg ="Are You Sure Want to Delete This User from list ?";
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
                    window.location.href = baseurl +'/User/deletedata/' + loginId;
                    dialog.close();
                }
            }]
        });
}