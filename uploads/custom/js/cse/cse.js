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

//add more
$(document).ready(function() {
    var max_fields      = 30; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="form-group"><label for="que1" class="control-label col-sm-3 required">Question </label><div class="col-sm-8"><div class="input-group"><input class="form-control" name="sub_que[]" placeholder="" type="text" value="" data-parsley-required  minlength="3"  maxlength="200"/><div class="input-group-btn"><a href="#" class="remove_field btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a></div></div></div></div>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div.input-group-btn').parent('div.input-group').parent('div').parent('div.form-group').remove(); x--;
    })
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



function deleteCse(id){
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
                    window.location.href = baseurl + '/Cse/delete/' + id;
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
