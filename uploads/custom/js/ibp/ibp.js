
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
//signoff review data
function signoff_request(YPId,IBP_ID){
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
                    window.location.href = baseurl +'/Ibp/signoff_review_data/' + YPId + '/' + IBP_ID + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}

//signoff ibp
function manager_request_ibp(YPId,IBP_ID){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $( ".ibp_signoff" ).prop( "checked", false );
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/Ibp/manager_review/' + YPId + '/' + IBP_ID;;
                    dialog.close();
                }
            }]
        });
}

var ibp_url = window.location.href;
var url_ibp = baseurl +'Ibp/edit/' + YPId;
if(url_ibp == ibp_url){

function get_ibp_complete(){

    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Ibp/update_slug/",
        data: {'url_data': url_ibp }
        
    }).done(function(){
        setTimeout(function(){get_ibp_complete();}, 10000);
    }).responseText;
}
$(function(){
    get_ibp_complete();
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
        url: baseurl +'/Ibp/getUserTypeDetail',
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
                    window.location.href = baseurl + 'Ibp/resend_external_approval/'+ id+'/'+ypid+'/'+ksid;
                    dialog.close();
                }
            }]
        });
}




