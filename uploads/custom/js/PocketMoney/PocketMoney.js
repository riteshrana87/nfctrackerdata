
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

function delete_ks(ks_id,yp_id)
{
    BootstrapDialog.show(
        {
            title: 'Confirm!',
            message: "<strong> Are you sure want to delete Pocket Money ? <strong>",
            buttons: [{
                    label: 'Cancel',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: 'Ok',
                    action: function (dialog) {
                        window.location.href = baseurl + "/PocketMoney/deletedata/" + ks_id + '/' + yp_id;
                        dialog.close();
                    }

                }]
        });
}

//set max mouny out 
$('#money_in').keyup(function(){
    var money_in = $(this).val();
    var new_blance = parseFloat(total_balance)+parseFloat(money_in);
    $('#money_out').attr('max',new_blance)
})
//$('#money_out').parent().parent().parent().parent('.col-sm-12').hide();
$('body').on('change', '#reason', function (e) {
     var id = $(this).val();

    if(id == 'Earnt from Banding')
    {
        $('#money_out').parent().parent().parent().parent('.col-sm-12').hide();
        $('#money_out').val('');
    }
    else
    {
        $('#money_out').parent().parent().parent().parent('.col-sm-12').show();
    }
     
});