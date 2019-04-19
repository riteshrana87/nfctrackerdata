
$(document).ready(function () {
	$('.tile .slimScroll-120').slimscroll({
                height: 120,
                size: 3,
                alwaysVisible: true,
                color: '#007c34'
            });
    $('.tile .slimScroll').slimscroll({
        height: 60,
        size: 3,
        alwaysVisible: true,
        color: '#007c34'
    });
    //check all validation filled
    $("#ksform").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
         var valid = false;
        form.parsley().validate();

        if (form.parsley().isValid()){
             var valid = true;
            $('button[type="submit"]').prop('disabled', true);
        }
        if (valid) this.submit();
    });
    
});
$('body').delegate('[data-toggle="ajaxModal"]', 'click',
        function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
            var $this = $(this)
                    , $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href')
                    , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal({backdrop: true});
            $modal.load($remote);
            $("body").css("padding-right", "0 !important");
        }

);

// user signoff script
function manager_request(YPId,KS_ID){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $("input[name='ks_signoff']").prop("checked", false);
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/KeySession/manager_review/' + YPId + '/' + KS_ID;
                    dialog.close();
                }
            }]
        });
}


function signoff_request(YPId,KS_ID){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $("input[name='signoff_data']").prop("checked", false);
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    var EMAIL = $("#email_data").val();
                    window.location.href = baseurl +'/KeySession/signoff_review_data/' + YPId + '/' + KS_ID + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}


function delete_ks(ks_id,yp_id)
{
    BootstrapDialog.show(
        {
            title: 'Confirm!',
            message: "<strong> Are you sure want to delete Key Session ? <strong>",
            buttons: [{
                    label: 'Cancel',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: 'Ok',
                    action: function (dialog) {
                        window.location.href = baseurl + "/KeySession/deletedata/" + ks_id + '/' + yp_id;
                        dialog.close();
                    }

                }]
        });
}
/* function added by Dhara Bhalala for conclude option */
function conclude_ks(ks_id,yp_id)
{
    BootstrapDialog.show(
        {
            title: 'Confirm!',
            message: "<strong> Are you sure want to Conclude Key Session ? <strong>",
            buttons: [{
                    label: 'Cancel',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: 'Ok',
                    action: function (dialog) {                        
                        dialog.close();
                        $.ajax({
                            type: "POST",
                            url: baseurl +'/KeySession/concludeKS',
                            data: {
                                ks_id: ks_id,yp_id: yp_id
                            },
                            success: function (html) {
                               $("#conclude_option_"+ks_id).hide();
                               $("#concluded_option_"+ks_id).show();
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                    alert('Try again');
                                    location.reload();
                                }
                        });
                    }

                }]
        });
}

//signoff chnage user type
$('body').on('change', '#user_type', function (e) {
    $("#submit_btn").attr("disabled", true);
    var id = $(this).val();
    var element = $(this).find('option:selected'); 
    var user_type = element.attr("user-type"); 
    $.ajax({
        type: "POST",
        url: baseurl +'/KeySession/getUserTypeDetail',
        data: {
            user_type: user_type,id: id,ypid:$('#ypid').val()
        },
        success: function (html) {
           $("#common_div").html(html);
           $("#submit_btn").removeAttr("disabled");
        },
        error: function (xhr, ajaxOptions, thrownError) {
                if (xhr.responseText) {
                    toastr.error(xhr.responseText, 'Inconceivable!')
                } else {
                    console.error("<div>Http status: " + xhr.status + " " + xhr.statusText + "</div>" + "<div>ajaxOptions: " + ajaxOptions + "</div>"
                        + "<div>thrownError: " + thrownError + "</div>");
                }
            }
    });
    
});
//resend mail
function resendMail(id,ypid,ksid){
    var delete_meg ="Are you sure you want to resend this External Approval?";
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
                    window.location.href = baseurl + 'KeySession/resend_external_approval/'+ id+'/'+ypid+'/'+ksid;
                    dialog.close();
                }
            }]
        });
}