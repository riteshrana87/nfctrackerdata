function reset_data_archive_is()
    {
		//alert('aaa');

        $("#searchtext").val("");
         $('.chosen-select').val('').trigger('chosen:updated');
         $("#searchForm input").val("");
         $("#yp-listtype").val($('#searchForm .active').attr('id'));
         
        apply_sorting('', '');
        //data_search('all');
		//return false;
    }
	
	$('body').on('click', '#common_tb_archive_is ul.pagination a.ajax_paging', function (e) {
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
             data:{
			 'search_date':$('#search_date').val(),
			 'search_start_time':$('#search_start_time').val(),
			 'search_end_time':$('#search_end_time').val(),
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
		$('#datepicker_search').datepicker({
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
	
	