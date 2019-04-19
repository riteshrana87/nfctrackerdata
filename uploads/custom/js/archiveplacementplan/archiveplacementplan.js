
$(document).ready(function () {
	
	var today = new Date();
	 $('#datepicker_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
// for saperate archive placement plan data search and pagination this is not working now from comman modal

function apply_sorting_archive_placement_plan(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search_auditlog('changesorting');
    }
    
    function apply_sortings_archive_placement_plan(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search_auditlog('changesorting');
    }
    //pagination
    $('body').on('click', '#common_tb_archive_placement_plan ul.pagination a.ajax_paging', function (e) {
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
	
	 //Search data
   	
	
			
			
//window.onbeforeunload = function () { return "If you want to go back to previous page Please use Previous step Button in below"; };

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
/*delete image*/
function delete_img(obj, img, inputfield)
{
    var input = $("#" + inputfield);
    BootstrapDialog.show(
            {
                title: 'Confirm!',
                message: "<strong> Are you sure want to delete file ? <strong>",
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: 'Ok',
                        action: function (dialog) {
                            input.val((input.val() != '') ? input.val() + "," + img : img);
                            $(obj).parent().remove();
                            dialog.close();
                        }

                    }]
            });
}

function GeneratePrint(yp_id)
{
    $('#printDIV').html("");
    send_url = base_url + 'PlacementPlan/DownloadPrint/' + yp_id + '/print';
    window.open(send_url, '_blank');
}

function SavePlacementPlanForm(val)
{
    var ShowMsg = ''; //Set Msg for Bootstrap 
    var hdn_submit_status = ""; //Set Value for hidden Status
    var action = ''; //Set value for its related with Bootstrap or Just save the information
    if (val == 'print')
    {
        ShowMsg = 'Before make Print you have to save the current Placement Plan, Are you sure want to continue?';
        hdn_submit_status = "print"; //2 Value for Store as a Draft
        action = '1';
    } else {
        action = '0';
    }
    if (action == '1')
    {
        BootstrapDialog.show(
                {
                    title: 'Placement Plan Module',
                    message: ShowMsg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                tinyMCE.triggerSave();
                                $("#hdn_submit_status").val(hdn_submit_status);
                                $("#HdnSubmitBtnVlaue").val(val);
                                if ($('#ppform').parsley().validate() == true)
                                {
                                    $("#ppform").submit();
                                }
                                dialog.close();
                            }
                        }]
                });
    } else {
        $("#hdn_submit_status").val($("#estStatus").val());
        $("#HdnSubmitBtnVlaue").val(val);
    }
    return false;
}
function manager_request(YPId,PP_ID){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $( ".pp_signoff" ).prop( "checked", false );
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'PlacementPlan/manager_review/' + YPId + '/' + PP_ID;
                    dialog.close();
                }
            }]
        });
}

function signoff_request(YPId,signoff_id){
    var delete_meg ="Please select OK to authorise this document.";
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
                    var EMAIL = $("#email_data").val();
                    window.location.href = baseurl +'/PlacementPlan/signoff_review_data/' + YPId + '/' + signoff_id + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}

function manager_request_ibp(YPId){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $( ".ibp_signoff" ).prop( "checked", false );
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/Ibp/manager_review/' + YPId;
                    dialog.close();
                }
            }]
        });
}

var pathname = window.location.href;
var url_data = baseurl +'PlacementPlan/edit/' + YPId;
if(url_data == pathname){
function get_pp_complete(){
   // var datetime = timefunction();
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "PlacementPlan/update_slug/",
        data: {'url_data': url_data}
        
    }).done(function(){
        setTimeout(function(){get_pp_complete();}, 10000);
    }).responseText;
}

$(function(){
    get_pp_complete();
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
        url: baseurl +'/PlacementPlan/getUserTypeDetail',
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
function resendMail(id,ypid,ppid){
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
                    window.location.href = baseurl + 'PlacementPlan/resend_external_approval/'+ id+'/'+ypid+'/'+ppid;
                    dialog.close();
                }
            }]
        });
}



