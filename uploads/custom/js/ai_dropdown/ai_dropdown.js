$(document).ready(function () {
    $('#dropdown_form').parsley();
    $('#dropdown_option_form').parsley();
});

//check all validation filled
$("#dropdown_form").on('submit', function (e) {
    e.preventDefault();
    var form = $(this);
    var valid = false;
    form.parsley().validate();    
    if (form.parsley().isValid()) {
        valid = true;
        $('button[type="submit"]').prop('disabled', true);
    }
    if (valid)
        this.submit();
});
$("#dropdown_option_form").on('submit', function (e) {
    e.preventDefault();
    var form = $(this);
    var valid = false;
    form.parsley().validate();    
    if (form.parsley().isValid()) {
        valid = true;
        $('button[type="submit"]').prop('disabled', true);
    }
    if (valid)
        this.submit();
});

$(window).on('load resize', function () {
    $('.content-wrapper').css('min-height', $(window).height() - 51);
});
function delete_request(dropdown_id) {
    var delete_meg = "Are You Sure Want to Delete This Dropdown ?";
    BootstrapDialog.show(
            {
                title: 'Information',
                message: delete_meg,
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: 'ok',
                        action: function (dialog) {
                            window.location.href = baseurl + 'Admin/DropDownController/deletedata/' + dropdown_id;
                            dialog.close();
                        }
                    }]
            });
}
function delete_request_option(dropdown_id,option_id) {
    var delete_meg = "Are You Sure Want to Delete This Option ?";
    BootstrapDialog.show(
            {
                title: 'Information',
                message: delete_meg,
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: 'ok',
                        action: function (dialog) {
                            window.location.href = baseurl + 'Admin/DropDownOptionController/deletedata/' + dropdown_id + '/' + option_id;
                            dialog.close();
                        }
                    }]
            });
}