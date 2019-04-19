/*
 @Description: CSE Report custom js
 @Author: Ishani Dave
 */

$(document).ready(function () {

    $('#created_date').datetimepicker({
        format: 'DD/MM/YYYY', //format,
        minDate: '0001',
        //useCurrent: false,
        maxDate: new Date()
    });
});

//get total score value
function getVal(formName)
{
    $.ajax({
        url: valurl,
        type: 'POST',
        data: $(formName).serialize() + '&' + 'result_type=' + 'ajax',
        success: function (value) {
            var data = value.split(",");
            $('#total_score_h').val(data[0]);
            $('#total_score_m').val(data[1]);
            $('#total_score_l').val(data[2]);
            $('#total_score_n').val(data[3]);
            $('#total_h').html(data[4]);
            $('#total_m').html(data[5]);
            $('#total_l').html(data[6]);
            $('#total_n').html(data[7]);
            $('#total').html(data[8]);

        }
    });
}
