
$(document).ready(function () {
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

//date
var end = new Date();
 $(".add_start_date").datepicker({
    todayBtn:  1,
    format: 'dd/mm/yyyy',
    autoclose: true,
    endDate   : end,
}).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('.add_end_date').datepicker('setStartDate', minDate);
});

$(".add_end_date").datepicker({
    endDate   : end,
    format: 'dd/mm/yyyy',
})
    .on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.add_start_date').datepicker('setEndDate', maxDate);
    });



/*delete mdt*/
function deleteMDT(yp_id,do_id){
    var delete_meg ="Are you sure you want to delete this MDT Report?";
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
                    window.location.href = baseurl + 'MDTReviewReport/deleteMDT/' + yp_id + '/' + do_id;
                    dialog.close();
                }
            }]
        });
}
/*resend mail*/
function resendMail(id,ypid){
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
                    window.location.href = baseurl + 'MDTReviewReport/resend_external_approval/'+ id+'/'+ypid;
                    dialog.close();
                }
            }]
        });
}