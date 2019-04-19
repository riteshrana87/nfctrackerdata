$(document).ready(function () {
    $('#registration').parsley(); //parsley validation
    $('.tile .slimScroll').slimscroll({
        height: 60,
        size: 3,
        alwaysVisible: true,
        color: '#007c34'
    });
    //$(".chosen-select").chosen();
    $('.parent-profile .slimScroll').slimscroll({
        height: 300,
        size: 3,
        alwaysVisible: true,
        color: '#007c34'
    });
    $('#display-list').on('click', function () {
        $(this).addClass('active');
        $('#display-table').removeClass('active');
        $('#table-view').slideUp();
        $('#list-view').slideDown();
    });
    $('#display-table').on('click', function () {
        $(this).addClass('active');
        $('#display-list').removeClass('active');
        $('#list-view').slideUp();
        $('#table-view').slideDown();
    });
     $('.time-input').datepicker({
        format: 'dd/mm/yyyy',
        startDate: new Date(),
        autoclose: true,
    });
    $('.time-input1').datepicker({
        format: 'dd/mm/yyyy',
        endDate: new Date(),
        autoclose: true,
    });
    //add current list type
    $('.yp-listtype').click(function(){
        $('#yp-listtype').val($(this).attr('id'));
    })
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


function deletePreviousCareHome(past_carehome_id,yp_id){
    var delete_meg ="Are you sure you want to delete this Care Home?";
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
                    window.location.href = baseurl + '/YoungPerson/delete_past_carehome/' + past_carehome_id + '/' + yp_id;
                    dialog.close();
                }
            }]
        });
}

function deleteParent(yp_id,parent_carer_id){
    var delete_meg ="Are you sure you want to delete this parents / carer’s details?";
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
                    window.location.href = baseurl + 'ParentsCarerDetails/removeParentCarerInformation/' + yp_id + '/' + parent_carer_id;
                    dialog.close();
                }
            }]
        });
}
/*
function saveYPDetail()
    {
        $('#registration').parsley().validate();
        if ($('#registration').parsley().isValid()) {
            $.ajax({
                url: baseurl + 'YoungPerson/edit/' + yp_id,
                data: $('#registration').serialize(),
                type: "POST",
                dataType: 'json',
                 beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> please wait...'});
                    },
                success: function (d)
                {
                    $.unblockUI();
                    BootstrapDialog.alert("Young Person Moved Successfully!!");
                }
            });
        }
        return false;
    }
    */
