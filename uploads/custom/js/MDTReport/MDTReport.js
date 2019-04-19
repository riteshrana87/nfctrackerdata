$(document).ready(function () {
    $('#registration').parsley(); //parsley validation
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
    //$(".chosen-select").chosen();
    
    $(".add_start_date_mdt").datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        endDate   : '+3m',
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.add_end_date_mdt').datepicker('setStartDate', minDate);
    });

    $(".add_end_date_mdt").datepicker({
        endDate   : '+3m',
        format: 'dd/mm/yyyy',
    })
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.add_start_date_mdt').datepicker('setEndDate', maxDate);
        });

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
    var end = new Date();
    $(".checkdate").datepicker({
                format: 'dd/mm/yyyy',
                endDate   : end,
                autoclose: true
            });
    
     $(".add_start_date").datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        endDate   : end,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.add_end_date').datepicker('setStartDate', minDate);
    });

    $(".add_end_date").datepicker({
        endDate   : end,
        format: 'dd/mm/yyyy',
    })
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.add_start_date').datepicker('setEndDate', maxDate);
        });
});

// save as draft functionality - 2-4-2018 -niral patel
var mdt_url = window.location.href;
var url_add = baseurl +'MDTReviewReport/add/' + YPId;
var url_edit = baseurl +'MDTReviewReport/edit_draft/'+mdt_report_id+'/' + YPId;

if(url_add == mdt_url || url_edit == mdt_url){
var isOnIOS = navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPhone/i);
console.log(isOnIOS);
var eventName = isOnIOS ? "pagehide" : "beforeunload";
console.log(eventName);
    var needToConfirm = true;
  $(window).bind(eventName, function (e) {
    setTimeout(function(){
        $("#draft_data").val("1");
       
 if(needToConfirm){    
        $.ajax({
        type: "POST",
        url: baseurl + "MDTReviewReport/insert",
        data: $('#mdtform').serialize(),
        success: function (data) {
           console.log(data);
        }
    });
         }  
    },0);
});
}
//mouse over content
screen_width = document.documentElement.clientWidth;
screen_heght = document.documentElement.clientHeight;

if(screen_width > 770)
{
  $('.form-group').each(function() {

      var $this = $(this);
      if($this.find('.slimScroll-120').html())
      {
        var d = $(this).find('.slimScroll-120').css('height').split('px');
        if(d[0] > 60){
           $this.popover({
              trigger: 'manual',
              placement: 'auto',
              html: true,   
              container: 'body',
              content: $this.find('.slimScroll-120').html()  
            }).on("mouseenter", function () {
          var _this = this;
          $(this).popover("show");
          $(".popover").on("mouseleave", function () {
              $(_this).popover('hide');
          });
      }).on("mouseleave", function () {
          var _this = this;
          setTimeout(function () {
              if (!$(".popover:hover").length) {
                  $(_this).popover("hide");
              }
          }, 0);
  });
        }
      }
  });
}
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

function manager_request(YPId,mdt_report_id){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $( ".ims_signoff" ).prop( "checked", false );
                    $("input[name='is_signoff']").prop("checked", false);
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/MDTReviewReport/manager_review/' + YPId +'/'+ mdt_report_id;
                    dialog.close();
                }
            }]
        });
}


function signoff_request(YPId,mdt_report_id){
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
                    window.location.href = baseurl +'/MDTReviewReport/signoff_review_data/' + YPId + '/' + mdt_report_id + '/' + EMAIL;
                    dialog.close();
                }
            }]
        });
}


/*
      @Author : Niral Patel
      @Desc   : Add cpt
      @Input    : Post record from cpt
      @Output   : Update data in database and redirect
      @Date   : 26/03/2018
     */

    count = 0;
    function add_cpt_review_limit()
    {
        var html = '';
        html += '<div class="newrow item_row item_new_' + count + '" id="item_new_' + count + '">';
        html += '<div class="col-lg-4 col-md-4 col-sm-3 form-group add_items_field mb44">';
        html += '<textarea class="form-control" name="cpt_title[]" placeholder="" id=""></textarea>';
        html += '</div>';
        html += '<div class="col-lg-3 col-md-3 col-sm-3 form-group add_items_field mb44">';
        html += '<select class="chosen-select form-control" name="cpt_select[]" id=""><option value="">Select</option><option value="Achieved">Achieved</option><option value="Ongoing">Ongoing</option><option value="Outstanding">Outstanding</option></select>';
        html += '</div>';
        html += '<div class="col-lg-4 col-md-4 col-sm-4 form-group add_items_field mb44">';
        html += '<textarea class="form-control" name="cpt_reason[]" placeholder="" id=""></textarea>';
        html += '</div>';
        html += '<div class="col-md-1 col-sm-2 form-group add_items_field">';
        html += '<a class="btn btn-default btn_border">';
        html += '<span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row(\'item_new_' + count + '\');"></span>';
        html += '</a>';
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
            $('#from-model').parsley();
            $(".chosen-select").chosen();
            tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
            
        });
        /*end item code*/
        if (care_plan_target == '') {
            //            //Append first time item
            item_html = add_cpt_review_limit();

            $('#add_cpt_review').append(item_html);
            $(".chosen-select").chosen();
             tinymce.init ({
                selector: '.tinyeditor',
               branding: false
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
    function delete_cpt_review_row(del_id)
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
                                remove_id = del_id.split('cpt_review_edit_');
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
 

    tinymce.init({ 
        selector:'.tinyeditor',
        branding: false, 
        plugins: "textcolor colorpicker",
        toolbar: "forecolor backcolor",
        color_picker_callback: function(callback, value) {
            callback('#FF00FF');
        }
    });

var ra_url = window.location.href;
var url_is = baseurl +'MDTReviewReport/edit/' + YPId;
if(url_is == ra_url){
 function get_is_complete(){
    //var datetime = timefunction();
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "MDTReviewReport/update_slug/",
        data: {'url_data': url_is}
        
    }).done(function(){
        setTimeout(function(){get_is_complete();}, 10000);
    }).responseText;
}

$(function(){
    get_is_complete();
});

}
/*
      @Author : Niral Patel
      @Desc   : Add/delete Hobbies
      @Input    : Post record from Amendments
      @Output   : Update data in database and redirect
      @Date   : 27/03/2018
*/
function delete_hobbies_row(del_id)
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
                        var del_ids = $('#delete_hobbies_id').val();
                        remove_id = del_id.split('hobbies_edit_');
                        if($.isNumeric( remove_id[1]))
                        {
                        $('#delete_hobbies_id').val(del_ids + remove_id[1] + ',');
                        }
                        $('#' + del_id).remove();
                        //count current item
                        dialog.close();
                    }

                }]
        });
}

//add hobbies html
count = 0;
function add_hobbies_limit()
{
    var html = '';
    
        html += '<div class="row"><div class="form-group" item_new_' + count + '" id="item_new_' + count + '">';
        html += '<div class="col-md-6 col-sm-5">';
        html += '<input class="form-control col-sm-4 mb30" name="regular_hobbies[]" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-md-5 col-sm-5">';
        html += '<input class="form-control col-sm-4" name="regular_hobbies_duration[]" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-sm-2 col-md-1 add_items_field mb44">';
        html += '<a class="btn btn-default btn_border">';
        html += '<span class="glyphicon glyphicon-trash" onclick="delete_hobbies_row(\'item_new_' + count + '\');"></span>';
        html += '</a>';
        html += '</div>';

        html += '</div></div>';
        count++;
    
    return html;
}

$(function () {
    //Add more item
    $('#add_new_hobbies').click(function () {
        item_html = add_hobbies_limit();

        $('#add_hobbies').append(item_html);
        $('#from-model').parsley();
        tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
        
    });
    /*end item code*/
    if (hobbies_data == '') {
            //            //Append first time item
            item_html = add_hobbies_limit();

            $('#add_hobbies').append(item_html);
             tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
    }

});
/*start physical excercise*/
function delete_physical_exercise_row(del_id)
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
                        var del_ids = $('#delete_physical_exercise_id').val();
                        remove_id = del_id.split('physical_exercise_edit_');
                        if($.isNumeric( remove_id[1]))
                        {
                        $('#delete_physical_exercise_id').val(del_ids + remove_id[1] + ',');
                        }
                        $('#' + del_id).remove();
                        //count current item
                        dialog.close();
                    }

                }]
        });
}

//add physical_exercise html
count = 0;
function add_physical_exercise_limit()
{
    var html = '';
    
        html += '<div class="row"><div class="form-group" item_new_' + count + '" id="item_new_' + count + '">';
        html += '<div class="col-md-6 col-sm-5">';
        html += '<input class="form-control col-sm-4" name="physical_exercise[]" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-md-5 col-sm-5 mb30">';
        html += '<input class="form-control col-sm-4" name="physical_exercise_duration[]" placeholder="" value="">';
        html += '</div>';
        html += '<div class="col-sm-2 col-md-1 add_items_field mb44">';
        html += '<a class="btn btn-default btn_border">';
        html += '<span class="glyphicon glyphicon-trash" onclick="delete_physical_exercise_row(\'item_new_' + count + '\');"></span>';
        html += '</a>';
        html += '</div>';

        html += '</div></div>';
        count++;
    
    return html;
}

$(function () {
    //Add more item
    $('#add_new_physical_exercise').click(function () {
        item_html = add_physical_exercise_limit();

        $('#add_physical_exercise').append(item_html);
        $('#from-model').parsley();
        tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
        
    });
    /*end item code*/
    if (physical_exercise_data == '') {
            //            //Append first time item
            item_html = add_physical_exercise_limit();

            $('#add_physical_exercise').append(item_html);
             tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
    }

});
    
/*start incident*/   
function delete_incident_row(del_id)
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
                        var del_ids = $('#delete_incident_id').val();
                        remove_id = del_id.split('incident_edit_');
                        if($.isNumeric( remove_id[1]))
                        {$('#delete_incident_id').val(del_ids + remove_id[1] + ',');}
                        

                        $('#' + del_id).remove();
                        //count current item
                        dialog.close();
                    }

                }]
        });
}

//add incident html
count = 0;
level = 0;
function add_incident_limit()
{
    var html = '';
    html += '<div class="form-group" item_new_' + count + '" id="item_new_' + count + '">';
    html += '<div class="col-md-3 col-sm-5 mb30">';
    html += '<textarea rows="4" class="form-control" name="incident_summary[]" placeholder="">';
    html += '</textarea> ';
    html += '</div>';
    html += '<div class="col-md-8 col-sm-5"><div class="row"><div class="col-md-6 col-sm-12"><lable class="radio-inline">';
    html += '<input class="levelradio" type="radio" name="level' + level + '" value="1"> Level 1(incident requiring no physical intervention)';
    html += '</label></div>';
    html += '<div class="col-md-6 col-sm-12"><lable class="radio-inline">';
    html += '<input class="levelradio" type="radio" name="level' + level + '" value="2"> Level 2(incident requiring physical intervention up to and including seated holds)';
    html += '</label></div>';
    html += '<div class="col-md-6 col-sm-12"><lable class="radio-inline">';
    html += '<input class="levelradio" type="radio" name="level' + level + '" value="3"> Level 3(incident requiring physical intervention including ground holds)';
    html += '</label></div>';
    html += '<div class="col-md-6 col-sm-12"><lable class="radio-inline">';
    html += '<input class="levelradio" type="radio" name="level' + level + '" value="4"> Level 4(Missing from care / absent without authority)';
    html += '</label></div></div></div>';
    html += '<div class="col-sm-1 add_items_field mb44">';
    html += '<a class="btn btn-default btn_border">';
    html += '<span class="glyphicon glyphicon-trash" onclick="delete_incident_row(\'item_new_' + count + '\');"></span>';
    html += '</a>';
    html += '</div>';
    html += '<div class="clearfix"></div>';
    count++;
    level++;
    return html;
}

$(function () {
    //Add more item
    $('#add_new_incident').click(function () {
        item_html = add_incident_limit();

        $('#add_incident').append(item_html);
        $('#from-model').parsley();
        tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
        
    });
    /*end item code*/
    if (incident_data == '') {
            //            //Append first time item
            item_html = add_incident_limit();

            $('#add_incident').append(item_html);
             tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
    }

});
/*start sanction*/
function delete_sanction_row(del_id)
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
                        var del_ids = $('#delete_sanction_id').val();
                        remove_id = del_id.split('sanction_edit_');
                        if($.isNumeric( remove_id[1]))
                        {$('#delete_sanction_id').val(del_ids + remove_id[1] + ',');}

                        $('#' + del_id).remove();
                        //count current item
                        dialog.close();
                    }

                }]
        });
}

//add sanction html
count = 0;
function add_sanction_limit()
{
    var html = '';
    html += '<div class="row"><div class="form-group" item_new_' + count + '" id="item_new_' + count + '">';
    html += '<div class="col-md-4 col-sm-3">';
    html += '<input class="form-control col-sm-4" name="reason_sanction[]" placeholder="" value="">';
    html += '</div>';
    html += '<div class="col-md-3 col-sm-3">';
    html += '<div class="input-group input-append date checkdate">';
    html += '<input type="text" class="form-control checkdate " name="date_sanction[]" placeholder="" value="" readonly="" >';
    html += '<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-4 col-sm-4 mb30">';
    html += '<input class="form-control col-sm-4" name="imposed_sanction[]" placeholder="" value="">';
    html += '</div>';
    html += '<div class="col-md-1 col-sm-2 add_items_field mb44">';
    html += '<a class="btn btn-default btn_border">';
    html += '<span class="glyphicon glyphicon-trash" onclick="delete_sanction_row(\'item_new_' + count + '\');"></span>';
    html += '</a>';
    html += '</div>';
    html += '<div class="clearfix"></div>';
    count++;
        
    return html;
}

$(function () {
    //Add more item
    $('#add_new_sanction').click(function () {
        item_html = add_sanction_limit();

        $('#add_sanction').append(item_html);
         var end = new Date();
        $(".checkdate").datepicker({
                    format: 'dd/mm/yyyy',
                    endDate   : end,
                    autoclose: true
                });
        $('#from-model').parsley();
        tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
        
    });
    /*end item code*/
    if (sanction_data == '') {
            //            //Append first time item
            item_html = add_sanction_limit();

            $('#add_sanction').append(item_html);
            var end = new Date();
            $(".checkdate").datepicker({
                        format: 'dd/mm/yyyy',
                        endDate   : end,
                        autoclose: true
                    });
             tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
    }

});
   /*start like skills*/
   function delete_life_skills_row(del_id)
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
                        var del_ids = $('#delete_life_skills_id').val();
                        remove_id = del_id.split('life_skills_edit_');
                        if($.isNumeric( remove_id[1]))
                        {
                        $('#delete_life_skills_id').val(del_ids + remove_id[1] + ',');
                        }

                        $('#' + del_id).remove();
                        //count current item
                        dialog.close();
                    }

                }]
        });
}

//add life_skills html
count = 0;
function add_life_skills_limit()
{
    var html = '';
    html += '<div class="form-group" item_new_' + count + '" id="item_new_' + count + '">';
    html += '<div class="col-sm-5 mb30">';
    html += '<textarea class="form-control col-sm-5" name="area_of_development[]" placeholder="">';
    html += '</textarea> ';
    html += '</div>';
    html += '<div class="col-sm-5 col-md-6 mb30">';
    html += '<textarea class="form-control col-sm-5" name="progress_achieved[]" placeholder="">';
    html += '</textarea> ';
    html += '</div>';
    html += '<div class="col-sm-2 col-md-1 add_items_field mb44">';
    html += '<a class="btn btn-default btn_border">';
    html += '<span class="glyphicon glyphicon-trash" onclick="delete_life_skills_row(\'item_new_' + count + '\');"></span>';
    html += '</a>';
    html += '</div>';
    html += '<div class="clearfix"></div>';
    count++;
        
    return html;
}

$(function () {
    //Add more item
    $('#add_new_life_skills').click(function () {
        item_html = add_life_skills_limit();

        $('#add_life_skills').append(item_html);
        $('#from-model').parsley();
        tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
        
    });
    /*end item code*/
    if (life_skills_data == '') {
            //            //Append first time item
            item_html = add_life_skills_limit();

            $('#add_life_skills').append(item_html);
             tinymce.init ({
                selector: '.tinyeditor',
               branding: false
            });
    }

});
   /*end like skills*/
   /*start cpt weeks*/
   //remove new row
    function delete_cpt_weeks_row(del_id)
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
                                var del_ids = $('#delete_cpt_weeks_id').val();
                                remove_id = del_id.split('cpt_weeks_edit_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_weeks_id').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}
count = 0;
function add_cpt_weeks_limit()
{
    var html = '';
    html += '<div class="newrow item_row item_new_' + count + '" id="item_new_' + count + '">';
    html += '<div class="col-lg-4 col-md-4 col-sm-3 form-group add_items_field mb44">';
    html += '<textarea class="form-control" name="cpt_week_title[]" placeholder="" id=""></textarea>';
    html += '</div>';
    html += '<div class="col-lg-3 col-md-3 col-sm-3 form-group add_items_field mb44">';
    html += '<select class="chosen-select form-control" name="cpt_week_select[]" id=""><option value="">Select</option><option value="Achieved">Achieved</option><option value="Ongoing">Ongoing</option><option value="Outstanding">Outstanding</option></select>';
    html += '</div>';
    html += '<div class="col-lg-4 col-md-4 col-sm-4 form-group add_items_field mb44">';
    html += '<textarea class="form-control" name="cpt_week_reason[]" placeholder="" id=""></textarea>';
    html += '</div>';
    html += '<div class="col-md-1 col-sm-2 form-group add_items_field">';
    html += '<a class="btn btn-default btn_border">';
    html += '<span class="glyphicon glyphicon-trash" onclick="delete_cpt_weeks_row(\'item_new_' + count + '\');"></span>';
    html += '</a>';
    html += '</div>';
    html += '</div>';
    count++;
    
    return html;
}

$(function () {
    //Add more item
    $('#add_new_cpt_weeks').click(function () {
        item_html = add_cpt_weeks_limit();

        $('#add_cpt_weeks').append(item_html);
        $('#from-model').parsley();
        $(".chosen-select").chosen();
        tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
        
    });
    /*end item code*/
    if (care_plan_target_week == '') {
        //            //Append first time item
        item_html = add_cpt_weeks_limit();

        $('#add_cpt_weeks').append(item_html);
        $(".chosen-select").chosen();
         tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
}

});
   /*end cpt weeks*/
/*start cpt previous*/
   //remove new row
    function delete_cpt_previous_row(del_id)
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
                                var del_ids = $('#delete_cpt_previous_id').val();
                                remove_id = del_id.split('cpt_previous_edit_');
                                if($.isNumeric( remove_id[1]))
                                {
                                $('#delete_cpt_previous_id').val(del_ids + remove_id[1] + ',');
                                }

                                $('#' + del_id).remove();
                                //count current item
                                dialog.close();
                            }

                        }]
                });
}
count = 0;
function add_cpt_previous_limit()
{
    var html = '';
    html += '<div class="newrow item_row item_new_' + count + '" id="item_new_' + count + '">';
    html += '<div class="col-lg-4 col-md-4 col-sm-3 form-group add_items_field mb44">';
    html += '<textarea class="form-control" name="cpt_previous_title[]" placeholder="" id=""></textarea>';
    html += '</div>';
    html += '<div class="col-lg-3 col-md-3 col-sm-3 form-group add_items_field mb44">';
    html += '<select class="chosen-select form-control" name="cpt_previous_select[]" id=""><option value="">Select</option><option value="Achieved">Achieved</option><option value="Ongoing">Ongoing</option><option value="Outstanding">Outstanding</option></select>';
    html += '</div>';
    html += '<div class="col-lg-4 col-md-4 col-sm-4 form-group add_items_field mb44">';
    html += '<textarea class="form-control" name="cpt_previous_reason[]" placeholder="" id=""></textarea>';
    html += '</div>';
    html += '<div class="col-md-1 col-sm-2 form-group add_items_field">';
    html += '<a class="btn btn-default btn_border">';
    html += '<span class="glyphicon glyphicon-trash" onclick="delete_cpt_previous_row(\'item_new_' + count + '\');"></span>';
    html += '</a>';
    html += '</div>';
    html += '</div>';
    count++;
    
    return html;
}

$(function () {
    //Add more item
    $('#add_new_cpt_previous').click(function () {
        item_html = add_cpt_previous_limit();

        $('#add_cpt_previous').append(item_html);
        $('#from-model').parsley();
        $(".chosen-select").chosen();
        tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
        
    });
    /*end item code*/
    if (care_plan_target_previous == '') {
        //            //Append first time item
        item_html = add_cpt_previous_limit();

        $('#add_cpt_previous').append(item_html);
        $(".chosen-select").chosen();
         tinymce.init ({
            selector: '.tinyeditor',
           branding: false
        });
}

});
   /*end cpt previous*/
    //enter numeric
function isNumberPerKey(evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 37 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

//signoff chnage user type
$('body').on('change', '#user_type', function (e) {
    $("#submit_btn").attr("disabled", true);
    var id = $(this).val();
    var element = $(this).find('option:selected'); 
    var user_type = element.attr("user-type"); 
    $.ajax({
        type: "POST",
        url: baseurl +'/MDTReviewReport/getUserTypeDetail',
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
$('body').on('click', '.levelradio', function (e) {
    var id = $(this).val();
    var total_level = $('.level'+id+' span').text();
    var cur_level = parseInt(total_level)+ 1;
    
    var level1 =0;
    var level2 =0;
    var level3 =0;
    var level4 =0;
    $( "#add_incident input[type='radio']:checked" ).each(function( index ) {
      var radio_value = $(this).val();
      if(radio_value == 1){level1++;}
      else if(radio_value == 2){level2++;}
      else if(radio_value == 3){level3++;}
      else if(radio_value == 4){level4++;}
    });
    
    $('.level1').text('There '+((level1 > 1)?'are':'is')+' '+level1+' incident of level1.');
    $('.level2').text('There '+((level2 > 1)?'are':'is')+' '+level2+' incident of level2.');
    $('.level3').text('There '+((level3 > 1)?'are':'is')+' '+level3+' incident of level3.');
    $('.level4').text('There '+((level4 > 1)?'are':'is')+' '+level4+' incident of level4.');
});


    
   

    
   
    
    
    
   
