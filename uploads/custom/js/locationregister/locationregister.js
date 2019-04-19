
$(document).ready(function () {
    
    /*datepicker code*/
 $("#left_date").datepicker({
        //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#returned_date').datepicker('setStartDate', minDate);
    });

    $("#returned_date").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#left_date').datepicker('setEndDate', maxDate);
            $("#returned_time").val('');
            $(".addtime").timepicker({
                defaultTime: '',
            });

    });

function reset_timepicker(){
     $(".addtime").timepicker({
                defaultTime: '',
            });
    }
    
$('#left_time').timepicker().on('hide.timepicker', function(e) {
     var left_time= $(this).val();
     openingHour = moment(left_time, "h:mm A").format("HH:mm");
     document.getElementById('returned_time').value = "";    
});

$('#returned_time').timepicker().on('hide.timepicker', function(e) {
     var returned_time= $(this).val();
     closingHour = moment(returned_time, "h:mm A").format("HH:mm");
     var left_date = $("#left_date").val();
     var returned_date = $("#returned_date").val();

    if(left_date == returned_date){
     if(closingHour < openingHour){
         document.getElementById('returned_time').value = "";
         BootstrapDialog.alert("Returned Time should be greater than left Time");

     }
 }
     
});



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
//signoff
function manager_request(YPId,LRId){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $("input[name='lr_signoff']").prop("checked", false);
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/LocationRegister/manager_review/' + YPId + '/' + LRId;
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
                    window.location.href = baseurl +'/LocationRegister/signoff_review_data/' + YPId + '/' + KS_ID + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}

//delete 
function delete_lr(lr_id,yp_id)
{
    BootstrapDialog.show(
        {
            title: 'Confirm!',
            message: "<strong> Are you sure want to delete Location Register ? <strong>",
            buttons: [{
                    label: 'Cancel',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: 'Ok',
                    action: function (dialog) {
                        window.location.href = baseurl + "/LocationRegister/deletedata/" + lr_id + '/' + yp_id;
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
        url: baseurl +'/LocationRegister/getUserTypeDetail',
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
function resendMail(id,ypid,lrid){
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
                    window.location.href = baseurl + 'LocationRegister/resend_external_approval/'+ id+'/'+ypid+'/'+lrid;
                    dialog.close();
                }
            }]
        });
}