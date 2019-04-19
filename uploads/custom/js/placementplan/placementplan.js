
$(document).ready(function () {
	
	$('#ppform').parsley();
	var today = new Date();
	 $('#datepicker_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
			
			
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
                    $("input[name='signoff_data']").prop("checked", false);
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





/*NIKUNJ
17-11-2018
FOR NEW PP MODULE
*/
    //alert(x);
	if(typeof x === 'undefined') {
		count = 1;
	}
	else{
		count = x;
	}
	
    function add_cpt_review_limit()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
       
        html += '<div class="row" id="item_new_' + count + '"><div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text " id="health_name[]" name="health_name[]" /></div><div class="col-sm-4"><h4>Placement Plan</h4><div class="form-group"><div id=""><textarea id="pre_placement_'+ count +'" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement[]"></textarea></div></div></div><div class="col-sm-4"><h4>Risk Assessment </h4><div class="form-group"><div id=""><textarea id="risk_assesment_'+ count +'" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment[]"></textarea></div></div></div><div class="col-sm-4"><h4>INDIVIDUAL STRATEGIES</h4><div class="form-group"><div id=""><textarea id="individual_strategies_'+ count +'" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies[]"></textarea></div></div></div> <div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_health(\'item_new_' + count + '\');"></span></a></div><div class="clearfix"></div></div>';
        
        html += '</div>';
        html += '</div>';
        count++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_cpt_review').click(function () {
			
            item_html = add_cpt_review_limit();

            $('#add_cpt_review').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
        /*end item code*/
      
    
    });
    //remove new row
    function delete_new_row(del_id)
    {
       
        var delete_meg = "Are you sure want to deletddddde?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                $('#' + del_id).remove();
                                dialog.close();
                            }

                        }]
                });
    }
    //remove new row
    function delete_cpt_review_row_health(del_id)
    {
		
		
		
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_id').val();
								
                                remove_id = del_id.split('item_new_');
                               
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_id').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}


if(typeof x === 'undefined') {
		count_education = 1;
	}
	else{
		count_education = x;
	}
    function add_cpt_review_limit_education()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
        html += '<div class="row" id="item_new_edu_' + count_education + '"><div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" name="title_education[]" /></div><div class="col-sm-4"><h4>Placement Plan</h4><div class="form-group"><div id=""><textarea id="pre_placement_'+ count_education +'" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_edu_sub[]"></textarea></div></div></div><div class="col-sm-4"><h4>Risk Assessment </h4><div class="form-group"><div id=""><textarea id="risk_assesment_'+ count_education +'" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_edu[]"></textarea></div></div></div><div class="col-sm-4"><h4>INDIVIDUAL STRATEGIES</h4><div class="form-group"><div id=""><textarea id="individual_strategies_'+ count_education +'" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_edu[]"></textarea></div></div></div><div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_edu(\'item_new_edu_' + count_education + '\');"></span></a></div><div class="clearfix"></div></div>';
         
        html += '</div>';
        html += '</div>';
        count_education++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_cpt_review_education').click(function () {
			
            item_html = add_cpt_review_limit_education();

            $('#add_cpt_review_education').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
       
    
    });
   
    //remove new row
    function delete_cpt_review_row_edu(del_id)
    {
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_edu_id').val();
                                remove_id = del_id.split('item_new_edu_');
                                
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_edu_id').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}



if(typeof x === 'undefined') {
		count_transport = 1;
	}
	else{
		count_transport = x;
	}
    function add_cpt_review_limit_transport()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
        //html += '<h3 contenteditable="true">Title</h3>';
        html += '<div class="row" id="item_new_tra_' + count_transport + '"><div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" name="title_tra[]" /></div><div class="col-sm-4"><h4>Placement Plan</h4><div class="form-group"><div id=""><textarea id="pre_placement_'+ count_transport +'" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_tra[]"></textarea></div></div></div><div class="col-sm-4"><h4>Risk Assessment </h4><div class="form-group"><div id=""><textarea id="risk_assesment_'+ count_transport +'" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_tra[]"></textarea></div></div></div><div class="col-sm-4"><h4>INDIVIDUAL STRATEGIES</h4><div class="form-group"><div id=""><textarea id="individual_strategies_'+ count_transport +'" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_tra[]"></textarea></div></div></div><div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_tra(\'item_new_tra_' + count_transport + '\');"></span></a></div><div class="clearfix"></div></div>';
        
        html += '</div>';
        html += '</div>';
        count_transport++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_cpt_review_transport').click(function () {
			
            item_html = add_cpt_review_limit_transport();

            $('#add_cpt_review_transport').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
        
    
    });
    
    //remove new row
    function delete_cpt_review_row_tra(del_id)
    {
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_tra_id').val();
                                remove_id = del_id.split('item_new_tra_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_tra_id').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}




if(typeof x === 'undefined') {
		count_contact = 1;
	}
	else{
		count_contact = x;
	}
    function add_cpt_review_limit_contact()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
       
        html += '<div class="row" id="item_new_con_' + count_contact + '"><div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" name="title_con[]" /></div><div class="col-sm-4"><h4>Placement Plan</h4><div class="form-group"><div id=""><textarea id="pre_placement_'+ count_contact +'" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_con[]"></textarea></div></div></div><div class="col-sm-4"><h4>Risk Assessment </h4><div class="form-group"><div id=""><textarea id="risk_assesment_'+ count_contact +'" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_con[]"></textarea></div></div></div><div class="col-sm-4"><h4>INDIVIDUAL STRATEGIES</h4><div class="form-group"><div id=""><textarea id="individual_strategies_'+ count_contact +'" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_con[]"></textarea></div></div></div><div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_con(\'item_new_con_' + count_contact + '\');"></span></a></div><div class="clearfix"></div></div>';
        
        html += '</div>';
        html += '</div>';
        count_contact++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_cpt_review_contact').click(function () {
			
            item_html = add_cpt_review_limit_contact();

            $('#add_cpt_review_contact').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
       
    
    });
   
    //remove new row
    function delete_cpt_review_row_con(del_id)
    {
		
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_con_id').val();
                                remove_id = del_id.split('item_new_con_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_con_id').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}


if(typeof x === 'undefined') {
		count_ft = 1;
	}
	else{
		count_ft = x;
	}
    function add_cpt_review_limit_free_time()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
        
        html += '<div class="row" id="item_new_ft_' + count_ft + '"><div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text"  name="title_ft[]" /></div><div class="col-sm-4"><h4>Placement Plan</h4><div class="form-group"><div id=""><textarea id="pre_placement_'+ count_ft +'" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_ft[]"></textarea></div></div></div><div class="col-sm-4"><h4>Risk Assessment </h4><div class="form-group"><div id=""><textarea id="risk_assesment_'+ count_ft +'" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_ft[]"></textarea></div></div></div><div class="col-sm-4"><h4>INDIVIDUAL STRATEGIES</h4><div class="form-group"><div id=""><textarea id="individual_strategies_'+ count_ft +'" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_ft[]"></textarea></div></div></div><div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_ft(\'item_new_ft_' + count_ft + '\');"></span></a></div><div class="clearfix"></div></div>';
        
        html += '</div>';
        html += '</div>';
        count_ft++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_cpt_review_free_time').click(function () {
			
            item_html = add_cpt_review_limit_free_time();

            $('#add_cpt_review_free_time').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
        
    
    });
    
    //remove new row
    function delete_cpt_review_row_ft(del_id)
    {
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_ft_id').val();
                                remove_id = del_id.split('item_new_ft_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_ft_id').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}



if(typeof x === 'undefined') {
		count_mgi = 1;
	}
	else{
		count_mgi = x;
	}
    function add_cpt_review_limit_mgi()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
        
        html += '<div class="row" id="item_new_mgi_' + count_mgi + '"><div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" name="title_mgi[]" /></div><div class="col-sm-4"><h4>Placement Plan</h4><div class="form-group"><div id=""><textarea id="pre_placement_'+ count_mgi +'" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_mgi[]"></textarea></div></div></div><div class="col-sm-4"><h4>Risk Assessment </h4><div class="form-group"><div id=""><textarea id="risk_assesment_'+ count_mgi +'" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_mgi[]"></textarea></div></div></div><div class="col-sm-4"><h4>INDIVIDUAL STRATEGIES</h4><div class="form-group"><div id=""><textarea id="individual_strategies_'+ count_mgi +'" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_mgi[]"></textarea></div></div></div><div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_mgi(\'item_new_mgi_' + count_mgi + '\');"></span></a></div><div class="clearfix"></div></div>';
        
        html += '</div>';
        html += '</div>';
        count_mgi++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_cpt_review_mgi').click(function () {
			
            item_html = add_cpt_review_limit_mgi();

            $('#add_cpt_review_mgi').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
        
    
    });
    
    //remove new row
    function delete_cpt_review_row_mgi(del_id)
    {
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_mgi_id').val();
                                remove_id = del_id.split('item_new_mgi_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_mgi_id').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}



if(typeof x === 'undefined') {
		count_pr = 1;
	}
	else{
		count_pr = x;
	}
    function add_cpt_review_limit_pr()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
        
        html += '<div class="row" id="item_new_pr_' + count_pr + '"><div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" name="title_pr[]" /></div><div class="col-sm-4"><h4>Placement Plan</h4><div class="form-group"><div id=""><textarea id="pre_placement_'+ count_pr +'" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_pr[]"></textarea></div></div></div><div class="col-sm-4"><h4>Risk Assessment </h4><div class="form-group"><div id=""><textarea id="risk_assesment_'+ count_pr +'" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_pr[]"></textarea></div></div></div><div class="col-sm-4"><h4>INDIVIDUAL STRATEGIES</h4><div class="form-group"><div id=""><textarea id="individual_strategies_'+ count_pr +'" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_pr[]"></textarea></div></div></div><div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_pr(\'item_new_pr_' + count_pr + '\');"></span></a></div><div class="clearfix"></div></div>';
        
        html += '</div>';
        html += '</div>';
        count_pr++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_cpt_review_pr').click(function () {
			
			
            item_html = add_cpt_review_limit_pr();

            $('#add_cpt_review_pr').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
       
    });
    //remove new row
    function delete_new_row_pr(del_id)
    {
       
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                $('#' + del_id).remove();
                                dialog.close();
                            }

                        }]
                });
    }
    //remove new row
    function delete_cpt_review_row_pr(del_id)
    {
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_pr_id').val();
                                remove_id = del_id.split('item_new_pr_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_pr_id').val(del_ids + remove_id[1] + ',');
                                }
                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}


if(typeof x === 'undefined') {
		count_bc = 1;
	}
	else{
		count_bc = x;
	}
    function add_cpt_review_limit_bc()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
        
        html += '<div class="row " id="item_new_bc_' + count_bc + '"><div class="col-md-12 pp_title_1"><h4>Title</h4><input type="text" name="title_bc[]" /></div><div class="col-sm-4"><h4>Placement Plan</h4><div class="form-group"><div id=""><textarea id="pre_placement_'+ count_bc +'" class="form-control"  placeholder="What are you trying to achieve?"  name="pre_placement_bc[]"></textarea></div></div></div><div class="col-sm-4"><h4>Risk Assessment </h4><div class="form-group"><div id=""><textarea id="risk_assesment_'+ count_bc +'" class="form-control"  placeholder="What are the risks associated with this?"  name="risk_assesment_bc[]"></textarea></div></div></div><div class="col-sm-4"><h4>INDIVIDUAL STRATEGIES</h4><div class="form-group"><div id=""><textarea id="individual_strategies_'+ count_bc +'" class="form-control"  placeholder="What are the strategies being used to minimise the risk?"  name="individual_strategies_bc[]"></textarea></div></div></div><div class="col-md-1 col-sm-2 add_items_field mb44"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash " data-id="'+count_bc+'" onclick="delete_cpt_review_row_bc(\'item_new_bc_' + count_bc + '\');"></span></a></div><div class="clearfix"></div></div>';
        
        html += '</div>';
        html += '</div>';
        count_bc++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_cpt_review_bc').click(function () {
			
			
            item_html = add_cpt_review_limit_bc();

            $('#add_cpt_review_bc').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
       
    
    });
    //remove new row
    function delete_new_row_bc(del_id)
    {
       
        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                $('#' + del_id).remove();
                                dialog.close();
                            }

                        }]
                });
    }
    //remove new row
    function delete_cpt_review_row_bc(del_id)
    {
            

        var delete_meg = "Are you sure want to delete?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_bc_id').val();
                                remove_id = del_id.split('item_new_bc_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_bc_id').val(del_ids + remove_id[1] + ',');
                                }
                                
                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}

	

/*
nikunj ghelani
27-2-19
pp aims of placement code start here*/

if(typeof x === 'undefined') {
		count_aims = 1;
	}
	else{
		count_aims = x;
	}
	
    function add_pp_aims_of_placement()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
       
        html += '<div class="row" id="item_new_aims_' + count_aims + '"><div class="form-group col-md-11 col-sm-2"><div id=""><textarea id="" class="form-control" name="aims_of_placement_data[]"></textarea></div></div>';
		 html += '<div class="col-md-1 col-sm-2 add_items_field mb44">';
        html += '<a class="btn btn-default btn_border">';
        html += '<span class="glyphicon glyphicon-trash" onclick="delete_aims_of_placement(\'item_new_aims_' + count_aims + '\');"></span></a>';
        html += '</div><div class="clearfix"></div></div></div>';
        
        html += '</div>';
        html += '</div>';
        count_aims++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_pp_aims_of_placement').click(function () {
			
            item_html = add_pp_aims_of_placement();

            $('#add_aims').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
        /*end item code*/
      
    
    });
    //remove new row
    function delete_new_row(del_id)
    {
       
        var delete_meg = "Are you sure want to deletddddde?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                $('#' + del_id).remove();
                                dialog.close();
                            }

                        }]
                });
    }
    //remove new row
   function delete_aims_of_placement(del_id)
    {

		
        var delete_meg = "Are you sure want to delete this?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_aims_of_placement').val();
								
                                remove_id = del_id.split('item_new_aims_');
								//alert(remove_id);
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_aims_of_placement').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}

/*nikunj ghelani pp aims of placement code completed*/

/*
nikunj ghelani
28-2-19
pp lac of placement code start here*/

if(typeof x === 'undefined') {
		count_lac = 1;
	}
	else{
		count_lac = x;
	}
	
    function add_pp_lac_of_placement()
    {
		
        var html = '';
        html += '<div class="clearfix"></div>';
       
        html += '<div class="row" id="item_new_lac_' + count_lac + '"><div class="form-group col-md-11 col-sm-2"><div id=""><textarea id="" class="form-control" name="lac_review_data[]"></textarea></div></div>';
		 html += '<div class="col-md-1 col-sm-2 add_items_field mb44">';
        html += '<a class="btn btn-default btn_border">';
        html += '<span class="glyphicon glyphicon-trash" onclick="delete_lac_of_placement(\'item_new_lac_' + count_lac + '\');"></span></a>';
        html += '</div><div class="clearfix"></div></div></div>';
        
        html += '</div>';
        html += '</div>';
        count_lac++;
		
        
        return html;
    }

    $(function () {
        //Add more item
        $('#add_pp_lac_of_placement').click(function () {
			
            item_html = add_pp_lac_of_placement();

            $('#add_lac').append(item_html);
            $('#ppform').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
        /*end item code*/
      
    
    });
    //remove new row
    function delete_new_row(del_id)
    {
       
        var delete_meg = "Are you sure want to deletddddde?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'ok',
                            action: function (dialog) {
                                $('#' + del_id).remove();
                                dialog.close();
                            }

                        }]
                });
    }
    //remove new row
   function delete_lac_of_placement(del_id)
    {

		
        var delete_meg = "Are you sure want to delete this?";
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_lac_of_placement').val();
                                remove_id = del_id.split('item_new_lac_');
								
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_lac_of_placement').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}

/*nikunj ghelani pp lac of placement code completed*/

/*
$(".remove_bc").on("click", function(e){

        var delete_meg = "Are you sure want to delete?";
        var del_id =$(this).attr('data-id');
        var $this=$(this);
        BootstrapDialog.show(
                {
                    title: 'Alert',
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                var del_ids = $('#delete_cpt_review_bc_id').val();
                                remove_id = del_id.split('item_new_bc_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_review_bc_id').val(del_ids + remove_id[1] + ',');
                                }
                                console.log($($this).closest('.new_bccls'));
                                $($this).closest('.new_bccls').remove();
                               // $('#' + del_id).remove();
                                //count current item
                               dialog.close();
                            }

                        }]
                });
});
*/