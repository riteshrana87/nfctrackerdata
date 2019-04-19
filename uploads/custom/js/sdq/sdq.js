/*
  @Description: SDQ custom js
  @Author: Ishani Dave
 */

$(window).on('load resize', function () {
    $('.content-wrapper').css('min-height', $(window).height() - 51);
});


$(document).ready(function () {
    $('#addsdq').parsley();
});

function myFunc(id) {
    $('#myModal').modal('show');
    $('#tem_id').val(id);
}

function apply_sorting(sortfilter, sorttype)
{

    $("#sortfield").val(sortfilter);
    $("#sortby").val(sorttype);
    data_search('changesorting');

}
function data_search(allflag)
{
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type: 'ajax', sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: allflag
        },
        success: function (html) {
            $("#common_div").html(html);
        }
    });
    return false;

}
//pagination
$('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {


    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type: 'ajax', perpage: $("#perpage").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val()
        },
        success: function (html) {
            $("#common_div").html(html);
        }
    });
    return false;

});



function deleteSdq(id){
    var delete_msg ="Are you sure you want to delete this question?";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_msg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl + '/Sdq/delete/' + id;
                    dialog.close();
                }
            }]
        });
}

function submitform($formID)
{
    var $form = $($formID), url = $form.attr('action');
    var formData = new FormData($($formID)[0]);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        async: false,
        success: function (data) {
            $('#total_score').val(data);
        },
        cache: false,
        contentType: false,
        processData: false
    });

}
