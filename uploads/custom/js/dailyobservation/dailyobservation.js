
$(document).ready(function () {
    //$(".chosen-select").chosen({ search_contains: true});
    //intialize scroll
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
    $("#doform").on('submit', function(e){

        $('#doform').parsley().destroy();
        $("span#DO_error").empty();
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

$(".do_adddate").datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
    
}).on('changeDate', function(e) {
    $('#doform').parsley().destroy();
    $("span#DO_error").empty();
    window.Parsley.addValidator('create_date', function (value, requirement) {
    var response = false;
    var form = $(this);
    var do_date = $("#create_date").val();
    var yp_id = $("#yp_id").val();
    if(yp_id){
        var ypId = yp_id
    }else{
        var ypId = "";
    }

    $.ajax({
        type: "POST",
        url: check_Do_url,
        data: {do_date:do_date,ypId:ypId}, // <--- THIS IS THE CHANGE
        async: false,
        success: function(result){
            if(result == 0){
                response = false;
            }else{
                response = true;
            }

//$("input[name=create_date]").parsley().reset();
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
    .addMessage('en', 'create_date', 'Entered create date is already exist.');
});

function manager_request(YPId,DO_ID){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $("input[name='do_signoff']").prop("checked", false);
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/DailyObservation/manager_review/' + YPId + '/' + DO_ID;
                    dialog.close();
                }
            }]
        });
}

function signoff_request(YPId,Doid){
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
                    window.location.href = baseurl +'/DailyObservation/signoff_review_data/' + YPId + '/' + Doid + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}




if (typeof Doid !== 'undefined') {
var do_url = window.location.href;
var url_do = baseurl +'DailyObservation/add_overview/' + Doid + '/' + YPId;
if(url_do == do_url){
function get_do_complete(){
    //var datetime = timefunction();
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "DailyObservation/update_slug/",
        data: {'url_data': url_do}
        
    }).done(function(){
        setTimeout(function(){get_do_complete();}, 10000);
    }).responseText;
}

$(function(){
    get_do_complete();
});
}
}

if (typeof Doid !== 'undefined') {
var food_url = window.location.href;
var url_food = baseurl +'DailyObservation/edit_do/' + Doid + '/' + YPId;
if(url_food == food_url){
  function get_food_complete(){
    //var datetime = timefunction();
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "DailyObservation/update_slug/",
        data: {'url_data': url_food}
        
    }).done(function(){
        setTimeout(function(){get_food_complete();}, 10000);
    }).responseText;
}

$(function(){
    get_food_complete();
});
}

}

function deleteArchiveDo(yp_id,do_archive_id,do_id){
    var delete_meg ="Are you sure you want to delete this Archive Daily Observation?";
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
                    window.location.href = baseurl + 'ArchiveDailyObservation/deleteArchiveDo/' + yp_id + '/' + do_archive_id + '/' + do_id;
                    dialog.close();
                }
            }]
        });
}


function deleteDo(yp_id,do_id){
    var delete_meg ="Are you sure you want to delete this Daily Observation?";
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
                    window.location.href = baseurl + 'DailyObservation/deleteDo/' + yp_id + '/' + do_id;
                    dialog.close();
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
        url: baseurl +'/DailyObservation/getUserTypeDetail',
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
function resendMail(id,ypid,doid){
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
                    window.location.href = baseurl + 'DailyObservation/resend_external_approval/'+ id+'/'+ypid+'/'+doid;
                    dialog.close();
                }
            }]
        });
}
function deleteStaff(do_staff_id,do_id,yp_id){
    var delete_meg ="Are you sure you want to delete this staff?";
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
                    window.location.href = baseurl + 'DailyObservation/delete_staff/' + do_staff_id + '/' + do_id + '/' + yp_id;
                    dialog.close();
                }
            }]
        });
}