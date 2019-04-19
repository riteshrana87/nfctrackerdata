
$(document).ready(function () {
	
	$('.boxdate').click(function(){
		$('.adddate').trigger('click');
		
	});
	
			var end = new Date();
     $(".adddate").datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        //endDate   : end,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#activity_date_to').datepicker('setStartDate', minDate);
    });

    $("#activity_date_to").datepicker({
        //endDate   : end,
        format: 'dd/mm/yyyy',
    })
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.adddate').datepicker('setEndDate', maxDate);
        });
		
		$('.datebox').click(function(){
            var id=$(this).attr('id');
           $('.'+id+'.adddate').click();
         });
    
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
    var end = new Date();
     $(".add_start_date").datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        //endDate   : end,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.add_end_date').datepicker('setStartDate', minDate);
    });

    $(".add_end_date").datepicker({
        //endDate   : end,
        format: 'dd/mm/yyyy',
    })
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.add_start_date').datepicker('setEndDate', maxDate);
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

function manager_request(YPId,KS_ID){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $("input[name='rmp_signoff']").prop("checked", false);
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/RiskManagementPlan/manager_review/' + YPId + '/' + KS_ID;
                    dialog.close();
                }
            }]
        });
}

function signoff_request(YPId,KS_ID){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $("input[name='signoff_data']").prop("checked", false);
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    var EMAIL = $("#email_data").val();
                    window.location.href = baseurl +'/RiskManagementPlan/signoff_review_data/' + YPId + '/' + KS_ID + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}
//delete RMP
function delete_ks(ks_id,yp_id)
{
    BootstrapDialog.show(
        {
            title: 'Confirm!',
            message: "<strong> Are you sure want to delete Risk Management Plan ? <strong>",
            buttons: [{
                    label: 'Cancel',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: 'Ok',
                    action: function (dialog) {
                        window.location.href = baseurl + "/RiskManagementPlan/deletedata/" + ks_id + '/' + yp_id;
                        dialog.close();
                    }

                }]
        });
}

//signoff chnage user type
$('body').on('change', '#user_type', function (e) {
    $("#submit_btn").attr("disabled", true);
    var id = $(this).val();
    var element = $(this).find('option:selected'); 
    var user_type = element.attr("user-type"); 
    $.ajax({
        type: "POST",
        url: baseurl +'/RiskManagementPlan/getUserTypeDetail',
        data: {
            user_type: user_type,id: id,ypid:$('#ypid').val()
        },
        success: function (html) {
           $("#common_div").html(html);
           $("#submit_btn").removeAttr("disabled");
        },
        error: function (xhr, ajaxOptions, thrownError) {
                if (xhr.responseText) {
                    toastr.error(xhr.responseText, 'Inconceivable!')
                } else {
                    console.error("<div>Http status: " + xhr.status + " " + xhr.statusText + "</div>" + "<div>ajaxOptions: " + ajaxOptions + "</div>"
                        + "<div>thrownError: " + thrownError + "</div>");
                }
            }
    });
    
});
//resend mail
function resendMail(id,ypid,ksid){
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
                    window.location.href = baseurl + 'RiskManagementPlan/resend_external_approval/'+ id+'/'+ypid+'/'+ksid;
                    dialog.close();
                }
            }]
        });
}


 //Search data
    function data_search_rmp(allflag)
    {
        $("#search").val('1');
        var ypid=$("#ypid").val();
        $("#professional_name_value").val($("#professional_name").val());
        $("#search_date_value").val($("#search_date").val());
        $("#search_time_value").val($("#search_time").val());
        $("#search_start_time_value").val($("#search_start_time").val());
        $("#search_end_time_value").val($("#search_end_time").val());
        
        var uri_segment = $("#uri_segment").val();
        var urisegment = $("#uri_segment").val();
        
        if(uri_segment == 0)
        {
            if($('#primaryId').length>0){
                var primaryId = $("#primaryId").val();
                  var urisegment =primaryId+'/'+uri_segment;
            }
            else
            {
                 var urisegment = '1'+'/'+uri_segment;
            }
           
            var uri = urisegment.split('/');

            var uri_segment = uri[1];
            
        }
        else
        {
           
             var uri = uri_segment.split('/');

             var uri_segment = uri[1];
        }
        
       
        /* Start Added By niral*/
        var request_url = '';
        if (uri_segment == 0)
        {
            request_url = baseurl + 'ArchiveRMP/index/'+ urisegment;
        } else {
            request_url = baseurl + 'ArchiveRMP/index/'+ urisegment;
        }
        /* End Added By niral*/

        $.ajax({
            type: "POST",
            url: request_url,
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), professional_name: $("#professional_name").val(), search_date: $("#search_date").val(), search_time: $("#search_time").val(),search_start_time: $("#search_start_time").val(),search_end_time: $("#search_end_time").val(),yp_list_type: $("#yp-listtype").val(), allflag: allflag
            },
            success: function (html) {
                $("#searchtextyp").val('');
                $("#common_div").html(html);
                $(".addtime").timepicker({
                 defaultTime: '',
                });

                var today = new Date();
                $('#datepicker_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                endDate: "today",
                maxDate: today
            });
            }
        });
        return false;
    }

     function reset_data_rmp()
    {

        $("#searchtext").val("");
         $('.chosen-select').val('').trigger('chosen:updated');
         $("#searchForm input").val("");
         $("#yp-listtype").val($('#searchForm .active').attr('id'));
         
        apply_sorting('', '');
        data_search_rmp('all');

    }