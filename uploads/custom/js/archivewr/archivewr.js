
$(document).ready(function () {
//window.onbeforeunload = function () { return "If you want to go back to previous page Please use Previous step Button in below"; };

    $('.tile .slimScroll-120').slimscroll({
        height: 60,
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

function GeneratePrint(yp_id)
{
    $('#printDIV').html("");
    send_url = base_url + 'PlacementPlan/DownloadPrint/' + yp_id + '/print';
    window.open(send_url, '_blank');
}

function SavePlacementPlanForm(val)
{
    var ShowMsg = ''; //Set Msg for Bootstrap 
    var hdn_submit_status = ""; //Set Value for hidden Status
    var action = ''; //Set value for its related with Bootstrap or Just save the information
    if (val == 'print')
    {
        ShowMsg = 'Before make Print you have to save the current Placement Plan, Are you sure want to continue?';
        hdn_submit_status = "print"; //2 Value for Store as a Draft
        action = '1';
    } else {
        action = '0';
    }
    if (action == '1')
    {
        BootstrapDialog.show(
                {
                    title: 'Placement Plan Module',
                    message: ShowMsg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                tinyMCE.triggerSave();
                                $("#hdn_submit_status").val(hdn_submit_status);
                                $("#HdnSubmitBtnVlaue").val(val);
                                if ($('#ppform').parsley().validate() == true)
                                {
                                    $("#ppform").submit();
                                }
                                dialog.close();
                            }
                        }]
                });
    } else {
        $("#hdn_submit_status").val($("#estStatus").val());
        $("#HdnSubmitBtnVlaue").val(val);
    }
    return false;
}
function manager_request(YPId,PP_ID){
    var delete_meg ="Please select OK to authorise this document.";
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
                    window.location.href = baseurl +'/PlacementPlan/manager_review/' + YPId + '/' + PP_ID;
                    dialog.close();
                }
            }]
        });
}

function signoff_request(YPId,PP_ID){
    var delete_meg ="Please select OK to authorise this document.";
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
                    var EMAIL = $("#email_data").val();
                    window.location.href = baseurl +'/PlacementPlan/signoff_review_data/' + YPId + '/' + PP_ID + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}

function manager_request_ibp(YPId){
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
                    window.location.href = baseurl +'/Ibp/manager_review/' + YPId;
                    dialog.close();
                }
            }]
        });
}

var pathname = window.location.href;
var url_data = baseurl +'PlacementPlan/edit/' + YPId;
if(url_data == pathname){
function get_pp_complete(){
   // var datetime = timefunction();
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "PlacementPlan/update_slug/",
        data: {'url_data': url_data}
        
    }).done(function(){
        setTimeout(function(){get_pp_complete();}, 10000);
    }).responseText;
}

$(function(){
    get_pp_complete();
});

}
  




