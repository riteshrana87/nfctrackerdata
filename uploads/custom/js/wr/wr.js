
$(document).ready(function () {
//window.onbeforeunload = function () { return "If you want to go back to previous page Please use Previous step Button in below"; };
    
    $('.tile .slimScroll-120').slimscroll({
        height: 60,
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
    
    $(".health_lac_date").datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            //endDate: '+0d',
            startDate: '-125y',
            autoclose: true
    });
    
    $('#date_picker_start_date').datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            //endDate: '-7d',
            endDate: new Date(),
            startDate: '-125y',
            autoclose: true
    }).on('change', function (e) {            
        var tt = $('#start_date').val();
        var newDate = tt.split("/");
        var date = new Date(newDate[1] +"/"+ newDate[0] +"/"+ newDate[2]);
        
        date.setDate(date.getDate() + 7);
    
        var dd = date.getDate().toString();
        var mm = (date.getMonth() + 1).toString();
        var y = date.getFullYear().toString();
        var finalDate = (dd[1]?dd:"0"+dd[0]) + '/' + (mm[1]?mm:"0"+mm[0]) + '/' + y;
       
        $("#end_date").val(finalDate);         
    });
    
    window.ParsleyValidator.addValidator('fileextension', function (value, requirement) {
        // the value contains the file path, so we can pop the extension
        var fileExtension = value.split('.').pop().toLowerCase().toString();
        
        if($.inArray(fileExtension, ["jpg","png","jpeg"]) == -1){
            return false;
        }
    }, 32).addMessage('en', 'fileextension', '<span class="parsley-type">Only jpg, png, jpeg files are allowed.</span>');
    
    $("#wrform").on("submit", function(){

        var $return = [];
        $("[id^=errors-container]").html("");
        if($("#health_concern:checked").val() == 'Yes'){
            var healthArr = ["health_appointments", "health_lac_date", "health_comments"];
            $return.push(checkRequired(healthArr));            
        } 
        if($("#education_concern:checked").val() == 'Yes'){
            var educationArr = ["education_attendance", "education_attendance_percent", "education_comments"];
            $return.push(checkRequired(educationArr));
        } 
        if($("#emotional_concern:checked").val() == 'Yes'){
            var emotionalArr = ["emotional_incidents", "emotional_incident_number", "emotional_comments"];
            $return.push(checkRequired(emotionalArr));
        } 
        if($("#identity_concern:checked").val() == 'Yes'){
            var identityArr = ["identity_comments"];
            $return.push(checkRequired(identityArr));
        } 
        if($("#family_social_concern:checked").val() == 'Yes'){
            var family_socialArr = ["family_social_contacts", "family_social_comments"];
            $return.push(checkRequired(family_socialArr));
        }
        if($("#social_skill_concern:checked").val() == 'Yes'){
            var social_skillArr = ["social_skill_comments"];
            $return.push(checkRequired(social_skillArr));            
        }
        if($("#self_care_concern:checked").val() == 'Yes'){
            var self_careArr = ["self_care_comments", "self_care_child_comments"];
            $return.push(checkRequired(self_careArr));            
        }
        var cnt = 0;
        $.each($return, function(i, j){
            if(j == false){
                cnt++;
            }
        });
        if(cnt > 0)
        {
            $('input[type="submit"]').removeAttr('disabled');
            $('button[type="submit"]').removeAttr('disabled');
        }
        else
        {
            $('input[type="submit"]').prop('disabled', true);
            $('button[type="submit"]').prop('disabled', true);
            $('input[type="submit"]').unbind();
            $('button[type="submit"]').unbind();
        }
        if(cnt){
            return false;
        }

    });
    $("input, textarea").on("keyup change", function(){
       $(this).removeClass("parsley-error");
       $(this).next(".text-danger").html("");
       $(this).parent().next(".text-danger").html("");
    });
});

function checkRequired($arr){
    var $return = true;
    $.each($arr, function(index, value){
        if($("#"+value).val().trim() == ""){
            $("#"+value).addClass("parsley-error");
            $("#errors-container"+value).html("This field is required");
            $return = false;
        }
    });
    return $return;
}


 $('body').delegate('[data-toggle="ajaxModal"]', 'click',
        function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
            var $this = $(this)
                , $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href')
                , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal();
            var url=$remote;
            $modal.load(url);
            $("body").css("padding-right", "0 !important");
        }
    );


function manager_request_wr(YPId,WR_ID){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    $( ".wr_signoff" ).prop( "checked", false );
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/WeeklyReport/manager_review/' + YPId + '/' + WR_ID;
                    dialog.close();
                }
            }]
        });
}

var wr_url = window.location.href;
var url_wr = baseurl +'WeeklyReport/edit/' + YPId;
if(url_wr == wr_url){

function get_wr_complete(){

    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "WeeklyReport/update_slug/",
        data: {'url_data': url_wr }
        
    }).done(function(){
        setTimeout(function(){get_wr_complete();}, 10000);
    }).responseText;
}
$(function(){
    get_wr_complete();
});
}
function delete_wr(wr_id,yp_id)
{
    BootstrapDialog.show(
        {
            title: 'Confirm!',
            message: "<strong> Are you sure want to delete Weekly Report ? <strong>",
            buttons: [{
                    label: 'Cancel',
                    action: function (dialog) {
                        dialog.close();
                    }
                }, {
                    label: 'Ok',
                    action: function (dialog) {
                        window.location.href = baseurl + "/WeeklyReport/deletedata/" + wr_id + '/' + yp_id;
                        dialog.close();
                    }

                }]
        });
}
function getStaffData(id,yp_id){ //console.log(id);
    $("#submit_btn").attr("disabled", true);
    var user_type = $("#user_type option:selected").attr("user-type");
    $.ajax({
        type: "POST",
        url: baseurl + "WeeklyReport/getStaffData/",
        data: {'yp_id': yp_id, id: id, user_type: user_type},
        success: function(html){
            $("#common_div").html(html);
            $("#submit_btn").removeAttr("disabled");
        }
    });
}



$('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });


