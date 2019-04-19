$(document).ready(function () {
$('#admin_report_start_date').datetimepicker({format: 'DD/MM/YYYY'});
    $('#admin_report_end_date').datetimepicker({format: 'DD/MM/YYYY',useCurrent: false });
    $('#date_received').datetimepicker({format: 'DD/MM/YYYY'});
    
     $("#admin_report_start_date").datetimepicker({
    format:'DD/MM/YYYY',
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() + 1);
 $("#admin_report_end_date").datetimepicker("option", "minDate", dt);
}                                 
});
  $("#admin_report_end_date").datetimepicker({
    format:'DD/MM/YYYY',
    onSelect: function (selected) {
      var dt1 = new Date(selected);
      dt1.setDate(dt1.getDate() - 1);
      $("#admin_report_start_date").datetimepicker("option", "maxDate", dt1);
    }
  });


    
    //  $("#admin_report_start_date").on("dp.change", function (e) { 
    //     $('#admin_report_end_date').data("DateTimePicker").minDate(e.date);
    // });
    // $("#admin_report_end_date").on("dp.change", function (e) {
    //     $('#admin_report_start_date').data("DateTimePicker").maxDate(e.date);
    // });
    // On Form Submit
    $("#generate_report_form").on('submit', function (e) {

        e.preventDefault();
        get_report_data();
    });

$(document).ready(function(){
   $("form#generate_report_form").submit();
});

    // On clcik generate excel file
    $(document).on('click', '#exportFile', function (evt, params) {
        $.ajax({
            type: 'POST',
            url: 'generateExcelFileUrl',
            data: $("#generate_report_form").serialize(),
        }).done(function (data) {
            window.open(data, '_blank');
        });

    });
});
function apply_report_sorting(sortfilter,sorttype)
{
    $("#sortfield").val(sortfilter);
    $("#sortby").val(sorttype);
    get_report_data();

}
/*Reset data*/
 function reset_report_data()
    {
        $('#example1_filter .input-group input,#example1_filter select').val("");
        $('.chosen-select').val('').trigger('chosen:updated');
        get_report_data();
    }
    /*get report data*/
    function get_report_data()
    {
        var form = $('form#generate_report_form');
        $.ajax({
            url: 'showReportList',
            data: form.serialize(),
            type: 'POST',
            success: function (data) {
                $('#common_div').html(data);
            },
            error: function (data) {
                //$("#error").show().fadeOut(20000);
            }
        });
    }

    //pagination
    $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {
        var url = $(this).attr('href');
        if(url != '#' && url != '')
        {
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type: 'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(), 
                yp_name : $('#yp_name').val(), admin_report_start_date : $('#admin_report_start_date >input').val(), admin_report_end_date: $('#admin_report_end_date > input').val(), reportType: $('#reportType').val(),
                communication_type : $('#communication_type').val(),created_by : $('#created_by').val(),medical_number : $('#medical_number').val(),date_received : $('#date_received > input').val(),staff : $('#staff').val(), professional_name: $("#professional_name").val(), search_date: $("#search_date").val(), search_time: $("#search_time").val()
            },
            beforeSend: function () {
                $('#common_div').block({message: 'Loading...'});
            },
            success: function (html) {
                $("#common_div").html(html);
                $.unblockUI();
            }
        });
        }
        return false;

    });