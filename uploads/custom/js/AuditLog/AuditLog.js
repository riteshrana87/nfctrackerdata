$(document).ready(function () {
	//alert("x");
    var today = new Date();
		$('#audit_log_datepicker_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
			$('#audit_log_datepicker_end_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
			$('#search_end_date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
    
});

 function apply_sorting_auditlog(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search_auditlog('changesorting');
    }
    
    function apply_sortings_auditlog(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search_auditlog('changesorting');
    }
    //pagination
    $('body').on('click', '#common_tb_audit ul.pagination a.ajax_paging', function (e) {
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data:{'staff_name':$('#staff_name').val(),
			 'yp_name':$('#yp_name').val(),
			 'search_start_date':$('#search_start_date').val(),
			 'search_end_date':$('#search_end_date').val(),
			 'module_name':$('#module_name').val(),
			 'result_type':'ajax'
			 },
            /*
             beforeSend: function () {
             $('#common_div').block({message: 'Loading...'});
             },
             */
            success: function (html) {
                $("#common_div").html(html);
				  var today = new Date();
		$('#audit_log_datepicker_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
			$('#audit_log_datepicker_end_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
			
                //    $.unblockUI();
            }
        });
        
	return false;
    });
	
	 //Search data
    function data_search_auditlog(allflag)
    {
		var uri_segment = $("#uri_segment").val();
        
		request_url = alogurl + uri_segment;
        $.ajax({
            type: "POST",
            url: request_url,
             data:{'staff_name':$('#staff_name').val(),
			 'yp_name':$('#yp_name').val(),
			 'search_start_date':$('#search_start_date').val(),
			 'search_end_date':$('#search_end_date').val(),
			 'module_name':$('#module_name').val(),
			 'result_type':'ajax'
			 },
            success: function (html) {
				//alert(alogurl);
                //$("#common_div").html(html);
                $(".audit_table").html(html);
				  var today = new Date();
		$('#audit_log_datepicker_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
			
			$('#audit_log_datepicker_end_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
			
			;$('#search_end_date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
		
            }
        });
        return false;
    }
