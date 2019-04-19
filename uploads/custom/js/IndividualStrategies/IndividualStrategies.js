$(document).ready(function () {
    $('#registration').parsley(); //parsley validation
    $('.tile .slimScroll').slimscroll({
        height: 60,
        size: 3,
        alwaysVisible: true,
        color: '#007c34'
    });
    //$(".chosen-select").chosen();

    $('#display-list').on('click', function () {
        $(this).addClass('active');
        $('#display-table').removeClass('active');
        $('#table-view').slideUp();
        $('#list-view').slideDown();
    });
    $('#display-table').on('click', function () {
        $(this).addClass('active');
        $('#display-list').removeClass('active');
        $('#list-view').slideUp();
        $('#table-view').slideDown();
    });
     $('.time-input').datepicker({
        format: 'dd/mm/yyyy',
        startDate: new Date(),
        autoclose: true,
    });
    $('.time-input1').datepicker({
        format: 'dd/mm/yyyy',
        endDate: new Date(),
        autoclose: true,
    });
    //add current list type
    $('.yp-listtype').click(function(){
        $('#yp-listtype').val($(this).attr('id'));
    })
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

function manager_request(YPId,individual_strategies_id){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $( ".ims_signoff" ).prop( "checked", false );
                    $( ".ra_signoff" ).prop( "checked", false );
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/IndividualStrategies/manager_review/' + YPId +'/'+ individual_strategies_id;
                    dialog.close();
                }
            }]
        });
}


function signoff_request(YPId,IS_ID){
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
                    window.location.href = baseurl +'/IndividualStrategies/signoff_review_data/' + YPId + '/' + IS_ID + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}


/*
      @Author : Ritesh rana
      @Desc   : Add Amendments code
      @Input    : Post record from Amendments
      @Output   : Update data in database and redirect
      @Date   : 30/01/2018
     */

    count = 0;
    function add_item_limit()
    {
        var html = '';
        html += '<div class="newrow item_row item_new_' + count + '" id="item_new_' + count + '">';
        html += '<div class="col-sm-10 form-group add_items_field mb44">';
        html += '<textarea class="tinyeditor" name="amendments[]" placeholder="" id=""></textarea>';
        html += '</div>';
        html += '<div class="col-sm-2 form-group add_items_field">';
        html += '<a class="pull-right btn btn-default ">';
        html += '<span class="glyphicon glyphicon-trash" onclick="delete_new_row(\'item_new_' + count + '\');"></span>';
        html += '</a>';
        html += '</div>';
        html += '</div>';
        count++;
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_item').click(function () {
            item_html = add_item_limit();

            $('#add_items').append(item_html);
            $('#from-model').parsley();

        tinymce.init({ 
        selector:'.tinyeditor',
        branding: false, 
        plugins: "textcolor colorpicker",
        toolbar: "forecolor backcolor",
        color_picker_callback: function(callback, value) {
            callback('#FF00FF');
        }
    });
            
        });
        /*end item code*/
  if (edit_data == '') {
            //            //Append first time item
            item_html = add_item_limit();

            $('#add_items').append(item_html);

            tinymce.init({ 
        selector:'.tinyeditor',
        branding: false, 
        plugins: "textcolor colorpicker",
        toolbar: "forecolor backcolor",
        color_picker_callback: function(callback, value) {
            callback('#FF00FF');
        }
    });
    }
    
    });
    //remove new row
    function delete_new_row(del_id)
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
    function delete_item_row(del_id)
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
                                var del_ids = $('#delete_item_id').val();
                                remove_id = del_id.split('item_edit_');
                                $('#delete_item_id').val(del_ids + remove_id[1] + ',');

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
    }
    

    /*
      @Author : Ritesh rana
      @Desc   : Add current protocols in place code
      @Input    : Post record from current protocols in place code
      @Output   : Update data in database and redirect
      @Date   : 30/01/2018
     */

    count = 0;
    function add_protocols()
    {
        var html = '';
        html += '<div class="newrow item_row protocols_new_' + count + '" id="protocols_new_' + count + '">';
        html += '<div class="col-sm-10 form-group add_items_field mb44">';
        html += '<textarea class="tinyeditor" name="current_protocols_in_place[]" placeholder="" id=""></textarea>';
        html += '</div>';
        html += '<div class="col-sm-2 form-group add_items_field ">';
        html += '<a class="pull-right btn btn-default ">';
        html += '<span class="glyphicon glyphicon-trash" onclick="delete_protocols_new_row(\'protocols_new_' + count + '\');"></span>';
        html += '</a>';
        html += '</div>';
        html += '</div>';
        count++;
        return html;
    }

    $(function () {
        //Add more item
        $('#add_new_protocols').click(function () {
            protocols_html = add_protocols();

            $('#add_protocols').append(protocols_html);
            $('#from-model').parsley();

            tinymce.init({ 
        selector:'.tinyeditor',
        branding: false, 
        plugins: "textcolor colorpicker",
        toolbar: "forecolor backcolor",
        color_picker_callback: function(callback, value) {
            callback('#FF00FF');
        }
    });
            
        });
        /*end item code*/
  if (edit_protocols_details == '') {
            //            //Append first time item
            protocols_html = add_protocols();

            $('#add_protocols').append(protocols_html);

            tinymce.init({ 
        selector:'.tinyeditor',
        branding: false, 
        plugins: "textcolor colorpicker",
        toolbar: "forecolor backcolor",
        color_picker_callback: function(callback, value) {
            callback('#FF00FF');
        }
    });
    }
    
    });
    //remove new row
    function delete_protocols_new_row(del_id)
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
    function delete_protocols_item_row(del_id)
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
                                var del_ids = $('#delete_protocols_id').val();
                                remove_id = del_id.split('protocols_edit_');
                                $('#delete_protocols_id').val(del_ids + remove_id[1] + ',');

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
    }

jQuery(document).on("ready page:load", function ($) {
    tinymce.init({ 
        selector:'.tinyeditor',
        branding: false, 
        plugins: "textcolor colorpicker",
        toolbar: "forecolor backcolor",
        color_picker_callback: function(callback, value) {
            callback('#FF00FF');
        }
    });
});

var ra_url = window.location.href;
var url_is = baseurl +'IndividualStrategies/edit/' + YPId;
if(url_is == ra_url){
 function get_is_complete(){
    //var datetime = timefunction();
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "IndividualStrategies/update_slug/",
        data: {'url_data': url_is}
        
    }).done(function(){
        setTimeout(function(){get_is_complete();}, 10000);
    }).responseText;
}

$(function(){
    get_is_complete();
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
        url: baseurl +'/IndividualStrategies/getUserTypeDetail',
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
function resendMail(id,ypid,isid){
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
                    window.location.href = baseurl + 'IndividualStrategies/resend_external_approval/'+ id+'/'+ypid+'/'+isid;
                    dialog.close();
                }
            }]
        });
} 

   
   
    



    
   

    
   
    
    
    
   
