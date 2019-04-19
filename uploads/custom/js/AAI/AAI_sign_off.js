
// for aai step bar start
;(function($) {
    "use strict";  
    
    //* Form js
    function verificationForm(){
        //jQuery time


        $(".next").click(function () {

            var nxtli=$('#progressbar li.active');
            var curcls=nxtli.attr('id');
            var oclassNames = nxtli.attr('class').split(' ');
        /*var newcls =nxtli.next().attr('class');    
            nxtli.next().addClass('active');
            $('#'+curcls).removeClass('active');
             $('#'+curcls).addClass('fdone');
             var nclassNames = newcls.split(' ');*/
             var newcls =nxtli.closest('div').parent().next(".slick-slide").find('li').attr('class');    
             nxtli.closest('div').parent().next(".slick-slide").find('li').addClass('active');
             $('#'+curcls).removeClass('active');
             $('#'+curcls).addClass('fdone');

             var nclassNames = newcls.split(' ');

             var oldclss=oclassNames[0];
             var nclass=nclassNames[0];
             console.log(oldclss);

             console.log(nclassNames.length);            
            //$('#'+oldclss).parent().hide();
            $('#'+oldclss).hide();
             //  $('#'+nclass).parent().show();
             $('#'+nclass).show();

            // removing next btn on last child
            if($('#progressbar li').last().hasClass('active')) {
                $('.next').attr('disabled', true );
            }
            else
            {
                $('.next').removeAttr('disabled', false);
                $('.previous').removeAttr('disabled', false);
            }

        });
        
        $(".previous").click(function () {




          var nxtli=$('#progressbar li.active');
          var curcls=nxtli.attr('id');
          var oclassNames = nxtli.attr('class').split(' ');
        /*var newcls =nxtli.prev().attr('class');    
            nxtli.prev().addClass('active');
            $('#'+curcls).removeClass('active');
             $('#'+curcls).addClass('fdone');
             var nclassNames = newcls.split(' ');*/
             var newcls =nxtli.closest('div').parent().prev(".slick-slide").find('li').attr('class');    
             nxtli.closest('div').parent().prev(".slick-slide").find('li').addClass('active');
             $('#'+curcls).removeClass('active');
             $('#'+curcls).addClass('fdone');
             var nclassNames = newcls.split(' ');


             var oldclss=oclassNames[0];
             var nclass=nclassNames[0];
             console.log(oldclss);

             console.log(nclassNames.length);            
            //$('#'+oldclss).parent().hide();
            $('#'+oldclss).hide();
             //  $('#'+nclass).parent().show();
             $('#'+nclass).show();

           // removing next btn on last child
          if($('#progressbar li').first().hasClass('active')) {
            $('.previous').attr('disabled', true );
            }
            else
            {
                $('.previous').removeAttr('disabled', false);
                $('.next').removeAttr('disabled', false);
            }    
    });

        // $(".previous1").click(function () {
        //     if (animating) return false;
        //     animating = true;

        //     current_fs = $(this).parent();
        //     previous_fs = $(this).parent().prev();

        //     //de-activate current step on progressbar
        //     $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //     //show the previous fieldset
        //     previous_fs.show();
        //     //hide the current fieldset with style
        //     current_fs.animate({
        //         opacity: 0
        //     }, {
        //         step: function (now, mx) {
        //             //as the opacity of current_fs reduces to 0 - stored in "now"
        //             //1. scale previous_fs from 80% to 100%
        //             scale = 0.8 + (1 - now) * 0.2;
        //             //2. take current_fs to the right(50%) - from 0%
        //             left = ((1 - now) * 50) + "%";
        //             //3. increase opacity of previous_fs to 1 as it moves in
        //             opacity = 1 - now;
        //             current_fs.css({
        //                 'left': left
        //             });
        //             previous_fs.css({
        //                 'transform': 'scale(' + scale + ')',
        //                 'opacity': opacity
        //             });
        //         },
        //         duration: 800,
        //         complete: function () {
        //             current_fs.hide();
        //             animating = false;
        //         },
        //         //this comes from the custom easing plugin
        //         easing: 'easeInOutBack'
        //     });
        // });


    }; 
    

    verificationForm();
})(jQuery); 

// for aai step bar over

/*Code by Dhara Start*/
$(document).ready(function() {


    if($('#progressbar li').first().hasClass('active')) {
        $('.previous').attr('disabled', true );
    }
    else
    {
        $('.previous').removeAttr('disabled', false);
    }



    console.log(lstcld);

    if ((typeof CREATE_MODE !== 'undefined' && CREATE_MODE == 'main') || (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 'main')) {        
        openForm('mef');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 2) {
        openForm('toi');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 3) {        
        openForm('tl1');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 4) {        
        openForm('tl23');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 5) {
        openForm('tl4');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 6) {
        openForm('tl5');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 7) {
        openForm('tl6');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 8) {
        openForm('tl7');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 9) {
        openForm('tl8');
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 10) {
        openForm('tl9');
    }

    check_incident_linked();
    var end = new Date();

    $(".aaitime").timepicker({
        defaultTime: '',
        minuteStep: 1,
        showMeridian: false
    });		

    $("#yp_dob").datepicker({
        startDate: '-125y',
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    });
    /* l1 initiate start */
    l1_location_change();
    change_incident_type();
    check_l1_debrief();
    check_yp_treatment_l1();
    check_l1_anyone_else_injured();
    check_l1_staff_member_injured();
    $(".l1_yp_treatment").change(function() {
        check_yp_treatment_l1();
    });
    $(".l1_yp_treatment_accept").change(function() {
        check_yp_treatment_l1();
    });
    $(".l1_debrief_offer").change(function() {
        check_l1_debrief();
    });
    $(".l1_debrief_accept").change(function() {
        check_l1_debrief();
    });

    $(".l1_was_a_staff_member_injured").change(function() {
        check_l1_staff_member_injured();
    });

    $(".l1_was_a_anyone_else_injured").change(function() {
        check_l1_anyone_else_injured();
    });

    var obj = document.getElementsByClassName("check_l1_involved_incident");
    $.each(obj, function(key, value) {
        l1_involve_change(value);
    });
    $(".check_l1_involved_incident").change(function() {
        l1_involve_change(this);
    });
    $(".l1_start_date").datepicker({
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function(selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l1_end_date').datepicker('setStartDate', maxDate);
        calculate_total_duration_l1();
    });
    $(".l1_end_date").datepicker({
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function(selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l1_start_date').datepicker('setEndDate', maxDate);
        calculate_total_duration_l1();
    });
    $('#l1_start_time').on('changeTime.timepicker', function() {
        calculate_total_duration_l1();
    });
    $('#l1_conclude_time').on('changeTime.timepicker', function() {
        calculate_total_duration_l1();
    });
    /* l1 initiate end */
    /* l2 initiate start */
    l2_location_change();
    check_l2_debrief();
    check_yp_treatment_l2();
    check_l2_staff_member_injured();
    check_l2_anyone_else_injured();

    $(".l2_yp_treatment").change(function() {
        check_yp_treatment_l2();
    });
    $(".l2_yp_treatment_accept").change(function() {
        check_yp_treatment_l2();
    });
    $(".l2_debrief_offer").change(function() {
        check_l2_debrief();
    });
    $(".l2_debrief_accept").change(function() {
        check_l2_debrief();
    });


    $(".l2_was_staff_member_injured").change(function() {
        check_l2_staff_member_injured();
    });

    $(".l2_was_anyone_else_injured").change(function() {
        check_l2_anyone_else_injured();
    });

    var obj = document.getElementsByClassName("check_l2_involved_incident");
    $.each(obj, function(key, value) {
        l2_involve_change(value);
    });
    $(".check_l2_involved_incident").change(function() {
        l2_involve_change(this);
    });
    $(".l2_start_date").datepicker({
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function(selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l2_end_date').datepicker('setStartDate', maxDate);
        calculate_total_duration_l2();
    });
    $(".l2_end_date").datepicker({
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function(selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l2_start_date').datepicker('setEndDate', maxDate);
        calculate_total_duration_l2();
    });
    $('#l2_start_time').on('changeTime.timepicker', function() {
        calculate_total_duration_l2();
    });
    $('#l2_conclude_time').on('changeTime.timepicker', function() {
        calculate_total_duration_l2();
    });
    /* l2 initiate end */
    /*l4 time calculate*/
    var yp_missing_date = $("#l4_date_yp_missing").val();
    if(yp_missing_date === ''){
        yp_missing_date = YP_PLACEMENT_DATE;
    }
    $(".l4_date_yp_missing").datepicker({
        startDate: YP_PLACEMENT_DATE,
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l4_date_yp_return').datepicker('setStartDate', maxDate);
        $('#l4_date_informed_pi').datepicker('setStartDate', maxDate);
        $('.after_yp_missing_date').datepicker('setStartDate', maxDate);
    });
    $(".l4_date_yp_return").datepicker({
        startDate: YP_PLACEMENT_DATE,
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l4_date_yp_missing').datepicker('setEndDate', maxDate);
    });
    $("#l4_date_informed_pi").datepicker({
        startDate: yp_missing_date,
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l4_date_yp_missing').datepicker('setEndDate', maxDate);
    });
    $(".after_yp_missing_date").datepicker({
        startDate: yp_missing_date,
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l4_date_yp_missing').datepicker('setEndDate', maxDate);
    });
    $('#ail4formupdate').submit(function() {
        calculate_total_duration_l4();
    })
    $('#l4_date_yp_missing').on('change', function () {
        calculate_total_duration_l4();
    });
    $('#l4_date_yp_return').on('change', function () {
        calculate_total_duration_l4();
    });
    $('#l4_time_yp_missing').on('changeTime.timepicker', function () {
        calculate_total_duration_l4();
    });
    $('#l4_time_yp_return').on('changeTime.timepicker', function () {
        calculate_total_duration_l4();
    });
    
    /*end*/
    /* l6 initiate start */
    check_yp_treatment_l6();
    check_l6_debrief();
    $(".l6_yp_treatment").change(function() {
        check_yp_treatment_l6();
    });
    $(".l6_yp_treatment_accept").change(function() {
        check_yp_treatment_l6();
    });
    $(".l6_debrief_offer").change(function() {
        check_l6_debrief();
    });
    $(".l6_debrief_accept").change(function() {
        check_l6_debrief();
    });
    $("#l6_date_of_complaint").datepicker({
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    });
    /* l6 initiate end */

});

function openForm(form_id) {
    $.blockUI({message: LOADER});
    var nxtli = $('#progressbar li.active');
    var curcls = nxtli.attr('id');
    var oclassNames = nxtli.attr('class').split(' ');
    var newcls = $('#' + form_id).attr('class');
    $('#' + form_id).addClass('active');
    $('#' + curcls).removeClass('active');
    var nclassNames = newcls.split(' ');
    var oldclss = oclassNames[0];
    var nclass = nclassNames[0];
    $('#' + oldclss).hide();
    $('#' + nclass).show();
    $.unblockUI();
}
/* main entry and before functions start */
function check_incident_type() {
    if ($('#educheck').is(':checked')) {
        $('#careIncident').hide();
        $('#eduIncident').show();
    } else {
        $('#careIncident').show();
        $('#eduIncident').hide();
    }
}

function change_incident_link() {
	
    var value = $("#yp_name option:selected").val();
    var isCareIncident = $("#isCareIncident").val();

    if (value > 0) {
        $('#errormsg').hide();
        $('#createIncidentLink').attr("href", baseurl + "AAI/create/" + isCareIncident + "/" + value);
    } else {
        $('#errormsg').show();
        $('#createIncidentLink').attr("href", 'javascript:void(0);');
    }
}

function check_incident_linked() {
    if ($('#yescheck').is(':checked')) {
        $('#yp_incidents').show();
    } else {
        $('#yp_incidents input:checked').removeAttr('checked');
        $('#yp_incidents').hide();
        $('#related_incident').val('');
    }
}

function incident_check_list() {
    var relatedIncident = $('.incidentCheckbox:checked').map(function() {
        return this.value;
    }).get().join(',');
    $("#related_incident").val(relatedIncident);
}
function change_incident_type() {
    var is_pi = $("input[name='is_pi']:checked").val();
    var is_yp_injured = $("input[name='is_yp_injured']:checked"). val();
    var is_yp_safeguarding = $("input[name='is_yp_safeguarding']:checked"). val();
    var is_other_injured = $("input[name='is_other_injured']:checked"). val();
    var is_yp_missing = $("input[name='is_yp_missing']:checked"). val();
    var is_yp_complaint = $("input[name='is_yp_complaint']:checked"). val();
    var is_staff_injured = $("input[name='is_staff_injured']:checked"). val();
    if (is_pi === '1') {
        $(".div_l1_creation").hide();
    } else if(is_yp_injured === '1' || is_yp_safeguarding === '1' || is_other_injured === '1' || is_yp_missing === '1' || is_yp_complaint === '1' || is_staff_injured === '1') {
        $(".div_l1_creation").show();
    }
}

function change_user(obj) {
    var selectId = obj.value;
    var fname = $("#" + selectId).attr("data-fname");
    var lname = $("#" + selectId).attr("data-lname");
    var email = $("#" + selectId).attr("data-email");
    var job_title = $("#" + selectId).attr("data-jobtitle");
    var job_location = $("#" + selectId).attr("data-location");
    $("#reporter_fname").val(fname);
    $("#reporter_surname").val(lname);
    $("#reporter_email").val(email);
    $("#reporter_jobtitle").val(job_title);
    $("#reporter_work_location").val(job_location);
    $("#reporter_line_manager").val('');
    $("#reporting_title").val('');
    $("#aimainformupdate").parsley().validate();
}
/* main entry and before functions end */
/* l1 functions start */
function calculate_total_duration_l1() {
    var startDate = $("#l1_start_date").val();
    var endDate = $("#l1_conclude_date").val();
    var startTime = $("#l1_start_time").val();
    var endTime = $("#l1_conclude_time").val();
    /*alert('result is '+startDate + ' ' + endDate + ' ' + startTime + ' ' + endTime);*/
    if (startDate !== '' && endDate !== '' && startTime !== '' && endTime !== '') {
        var dateAr = startDate.split('/');
        var startDate = dateAr[1] + '-' + dateAr[0].slice(-2) + '-' + dateAr[2];
        var dateAr2 = endDate.split('/');
        var endDate = dateAr2[1] + '-' + dateAr2[0].slice(-2) + '-' + dateAr2[2];
        var start_actual_time = new Date(startDate + ' ' + startTime);
        var end_actual_time = new Date(endDate + ' ' + endTime);
        var diff = end_actual_time - start_actual_time;
        var diffSeconds = diff / 1000;
        if (diffSeconds < 0) {
            alert("incident should not be conclude before incident started");
            $("#l1_total_duration_main").val('');
            $("#l1_total_duration").val('');
            if (startDate == endDate) $('#l1_conclude_time').timepicker('setTime', startTime);
            return false;
        }
        var HH = Math.floor(diffSeconds / 3600);
        var MM = Math.floor(diffSeconds % 3600) / 60;
        var formatted = ((HH < 10) ? ("0" + HH) : HH) + ":" + ((MM < 10) ? ("0" + MM) : MM);
        $("#l1_total_duration_main").val(formatted);
        $("#l1_total_duration").val(formatted);
    }
}

function calculate_total_duration_l4() {
    var startDate = $("#l4_date_yp_missing").val();
    var endDate = $("#l4_date_yp_return").val();
    var startTime = $("#l4_time_yp_missing").val();
    var endTime = $("#l4_time_yp_return").val();
    /*alert('result is '+startDate + ' ' + endDate + ' ' + startTime + ' ' + endTime);*/
    if (startDate !== '' && endDate !== '' && startTime !== '' && endTime !== '') {
        var dateAr = startDate.split('/');
        var startDate = dateAr[1] + '-' + dateAr[0].slice(-2) + '-' + dateAr[2];
        var dateAr2 = endDate.split('/');
        var endDate = dateAr2[1] + '-' + dateAr2[0].slice(-2) + '-' + dateAr2[2];
        
        var start_actual_time = new Date(startDate + ' ' + startTime);
        var end_actual_time = new Date(endDate + ' ' + endTime);
        var diff = end_actual_time - start_actual_time;
        var diffSeconds = diff / 1000;
        if (diffSeconds < 0) {
            alert("Return date and time can not before of YP missing date and time");
            $("#l4_total_duration_main").val('');
            $("#l4_total_duration").val('');
            if(startDate == endDate)
                $('#l4_time_yp_return').timepicker('setTime', startTime);
            return false;
        }
        var HH = Math.floor(diffSeconds / 3600);
        var MM = Math.floor(diffSeconds % 3600) / 60;

        var formatted = ((HH < 10) ? ("0" + HH) : HH) + ":" + ((MM < 10) ? ("0" + MM) : MM);
        $("#l4_total_duration_main").val(formatted);
        $("#l4_total_duration").val(formatted);
    }
}

function check_yp_treatment_l1() {
    var l1_yp_treatment = $("input[name='l1_yp_treatment']:checked").val();
    if (l1_yp_treatment == 'Yes') {
        $('#div_l1_treatment_not_accepted_comments').removeClass('col-md-6');
        $('#div_l1_treatment_not_accepted_comments').addClass('col-md-12');
        $('#div_l1_yp_treatment_accept').show();
        var l1_yp_treatment_accept = $("input[name='l1_yp_treatment_accept']:checked").val();
        if (l1_yp_treatment_accept == 'No') {
            $('#div_l1_treatment_not_accepted_comments').show();
        } else {
            $('#div_l1_treatment_not_accepted_comments').hide();
        }
    } else {
        $('#div_l1_yp_treatment_accept').hide();
        $('#div_l1_treatment_not_accepted_comments').hide();
    }
}

function check_l1_debrief() {
    var l1_debrief_offer = $("input[name='l1_debrief_offer']:checked").val();

    if (l1_debrief_offer == 'Yes') {
        $('#div_l1_debrief_comments').removeClass('col-md-4');
        $('#div_l1_debrief_comments').addClass('col-md-12');
        $('#div_l1_debrief_accept').show();
        var l1_debrief_accept = $("input[name='l1_debrief_accept']:checked").val();
        if (l1_debrief_accept == 'Yes') {
            $('#div_l1_debrief_comments').show();
        } else {
            $('#div_l1_debrief_comments').hide();
        }
    } else {
        $('#div_l1_debrief_accept').hide();
        $('#div_l1_debrief_comments').hide();
    }
}

function check_l1_staff_member_injured() {
    var l1_staff_member_injured = $("input[name='l1_was_a_staff_member_injured']:checked").val();
    if (l1_staff_member_injured == 'Yes') {
        $('#div_l1_staff_member_list').show();
    } else {
        $('#div_l1_staff_member_list').hide();
    }
}


function check_l1_anyone_else_injured() {
    var l1_anyone_else_injured = $("input[name='l1_was_a_anyone_else_injured']:checked").val();
    if (l1_anyone_else_injured == 'Yes') {

        $('#div_l1_other_injured').show();
    } else {
        $('#div_l1_other_injured').hide();
    }
}

function l1_location_change() {
    var selectId = $("#l1_location_incident").val();
    var location_value = $("#location_" + selectId).attr("data-detail");
    if (location_value == 'Other') {
        $('#div_l1_location_other').show();
    } else {
        $('#div_l1_location_other').hide();
    }
}

function l1_involve_change(obj) {
    if (obj.value == 'Employee') {
        if ($(obj).is(":checked")) {
            $('#div_l1_involved_employee').show();
        } else {
            $('#div_l1_involved_employee').hide();
        }
    }
    if (obj.value == 'Outside Agency') {
        if ($(obj).is(":checked")) {
            $('#div_l1_involved_agency').show();
        } else {
            $('#div_l1_involved_agency').hide();
        }
    }
    if (obj.value == 'Other') {
        if ($(obj).is(":checked")) {
            $('#div_l1_involved_other').show();
        } else {
            $('#div_l1_involved_other').hide();
        }
    }
}


//Police informed
police_informed_pi_l1();
$(".l1_police_informed_pi").change(function() {
    police_informed_pi_l1();
});

function police_informed_pi_l1() {
    var l1_police_informed = $("input[name='l1_police_informed_pi']:checked").val();
    if (l1_police_informed == 'Yes') {
        $('#div_l1_informed_by_pi').show();
        $('#div_l1_how_the_information_was_send_pi').show();
        $('#div_l1_who_was_informed_pi').show();
        $('#div_l1_police_reference_number_pi').show();
        $('#div_l1_date_informed_pi').show();
        $('#div_l1_time_informed_pi').show();
    } else {
        $('#div_l1_informed_by_pi').hide();
        $('#div_l1_how_the_information_was_send_pi').hide();
        $('#div_l1_who_was_informed_pi').hide();
        $('#div_l1_police_reference_number_pi').hide();
        $('#div_l1_date_informed_pi').hide();
        $('#div_l1_time_informed_pi').hide();
    }
}
/*end*/

/* l1 functions end */
/* l2 functions start */
function calculate_total_duration_l2() {
    var startDate = $("#l2_start_date").val();
    var endDate = $("#l2_conclude_date").val();
    var startTime = $("#l2_start_time").val();
    var endTime = $("#l2_conclude_time").val();
    //    alert('result is '+startDate + ' ' + endDate + ' ' + startTime + ' ' + endTime);
    if (startDate !== '' && endDate !== '' && startTime !== '' && endTime !== '') {
        var dateAr = startDate.split('/');
        var startDate = dateAr[1] + '-' + dateAr[0].slice(-2) + '-' + dateAr[2];
        var dateAr2 = endDate.split('/');
        var endDate = dateAr2[1] + '-' + dateAr2[0].slice(-2) + '-' + dateAr2[2];
        var start_actual_time = new Date(startDate + ' ' + startTime);
        var end_actual_time = new Date(endDate + ' ' + endTime);
        var diff = end_actual_time - start_actual_time;
        var diffSeconds = diff / 1000;
        if (diffSeconds < 0) {
            alert("incident should not be conclude before incident started");
            $("#l2_total_duration_main").val('');
            $("#l2_total_duration").val('');
            if (startDate == endDate) $('#l2_conclude_time').timepicker('setTime', startTime);
            return false;
        }
        var HH = Math.floor(diffSeconds / 3600);
        var MM = Math.floor(diffSeconds % 3600) / 60;
        var formatted = ((HH < 10) ? ("0" + HH) : HH) + ":" + ((MM < 10) ? ("0" + MM) : MM);
        $("#l2_total_duration_main").val(formatted);
        $("#l2_total_duration").val(formatted);
    }
}

function check_yp_treatment_l2() {
    var l2_yp_treatment = $("input[name='l2_yp_treatment']:checked").val();
    if (l2_yp_treatment == 'Yes') {
        $('#div_l2_yp_treatment_accept').show();
        var l2_yp_treatment_accept = $("input[name='l2_yp_treatment_accept']:checked").val();
        if (l2_yp_treatment_accept == 'No') {
            $('#div_l2_treatment_not_accepted_comments').removeClass('col-md-6');
            $('#div_l2_treatment_not_accepted_comments').addClass('col-md-12');
            $('#div_l2_treatment_not_accepted_comments').show();
        } else {
            $('#div_l2_treatment_not_accepted_comments').hide();
        }
    } else {
        $('#div_l2_yp_treatment_accept').hide();
        $('#div_l2_treatment_not_accepted_comments').hide();
    }
}

function check_l2_debrief() {
    var l2_debrief_offer = $("input[name='l2_debrief_offer']:checked").val();
    if (l2_debrief_offer == 'Yes') {
        $('#div_l2_debrief_accept').show();
        var l2_debrief_accept = $("input[name='l2_debrief_accept']:checked").val();
        if (l2_debrief_accept == 'Yes') {
            $('#div_l2_debrief_comments').show();
        } else {
            $('#div_l2_debrief_comments').hide();
        }
    } else {
        $('#div_l2_debrief_accept').hide();
        $('#div_l2_debrief_comments').hide();
    }
}

function l2_location_change() {
    var selectId = $("#l2_location_incident").val();
    var location_value = $("#location_" + selectId).attr("data-detail");
    if (location_value == 'Other') {
        $('#div_l2_location_other').show();
    } else {
        $('#div_l2_location_other').hide();
    }
}

function l2_involve_change(obj) {
    if (obj.value == 'Employee') {
        if ($(obj).is(":checked")) {
            $('#div_l2_involved_employee').show();
        } else {
            $('#div_l2_involved_employee').hide();
        }
    }
    if (obj.value == 'Outside Agency') {
        if ($(obj).is(":checked")) {
            $('#div_l2_involved_agency').show();
        } else {
            $('#div_l2_involved_agency').hide();
        }
    }
    if (obj.value == 'Other') {
        if ($(obj).is(":checked")) {
            $('#div_l2_involved_other').show();
        } else {
            $('#div_l2_involved_other').hide();
        }
    }
}

function check_l2_staff_member_injured() {
    var l2_staff_member_injured = $("input[name='l2_was_staff_member_injured']:checked").val();
    if (l2_staff_member_injured == 'Yes') {
        $('#div_l2_staff_member_list').show();
    } else {
        $('#div_l2_staff_member_list').hide();
    }
}


function check_l2_anyone_else_injured() {
    var l2_anyone_else_injured = $("input[name='l2_was_anyone_else_injured']:checked").val();
    if (l2_anyone_else_injured == 'Yes') {
        $('#div_l2_other_injured').show();
    } else {
        $('#div_l2_other_injured').hide();
    }
}


//Police informed
police_informed_pi_l2();
$(".l2_police_informed_pi").change(function() {
    police_informed_pi_l2();
});

function police_informed_pi_l2() {
    var l2_police_informed = $("input[name='l2_police_informed_pi']:checked").val();
    if (l2_police_informed == 'Yes') {
        $('#div_l2_informed_by_pi').show();
        $('#div_l2_how_the_information_was_send_pi').show();
        $('#div_l2_who_was_informed_pi').show();
        $('#div_l2_police_reference_number_pi').show();
        $('#div_l2_date_informed_pi').show();
        $('#div_l2_time_informed_pi').show();
    } else {
        $('#div_l2_informed_by_pi').hide();
        $('#div_l2_how_the_information_was_send_pi').hide();
        $('#div_l2_who_was_informed_pi').hide();
        $('#div_l2_police_reference_number_pi').hide();
        $('#div_l2_date_informed_pi').hide();
        $('#div_l2_time_informed_pi').hide();
    }
}
/*end*/

/* l2 functions end */
/* l6 functions Starts */
function check_yp_treatment_l6() {
    var l6_yp_treatment = $("input[name='l6_yp_treatment']:checked").val();
    if (l6_yp_treatment == 'Yes') {
        $('#div_l6_yp_treatment_accept').show();
        var l6_yp_treatment_accept = $("input[name='l6_yp_treatment_accept']:checked").val();
        if (l6_yp_treatment_accept == 'No') {
            $('#div_l6_treatment_not_accepted_comments').show();
        } else {
            $('#div_l6_treatment_not_accepted_comments').hide();
        }
    } else {
        $('#div_l6_yp_treatment_accept').hide();
        $('#div_l6_treatment_not_accepted_comments').hide();
    }
}

function check_l6_debrief() {
    var l6_debrief_offer = $("input[name='l6_debrief_offer']:checked").val();
    if (l6_debrief_offer == 'Yes') {
        $('#div_l6_debrief_accept').show();
        var l6_debrief_accept = $("input[name='l6_debrief_accept']:checked").val();
        if (l6_debrief_accept == 'Yes') {
            $('#div_l6_debrief_comments').show();
        } else {
            $('#div_l6_debrief_comments').hide();
        }
    } else {
        $('#div_l6_debrief_accept').hide();
        $('#div_l6_debrief_comments').hide();
    }
}
/* l6 functions end */
/*function check_l2_type(obj) {
    if ($('#seated').is(':checked')) {
        $('.sequence-events').hide();
    } else {
        $('.sequence-events').show();
    }
}*/
/*Code by Dhara End*/
/*Code by Nikunj Start*/
$(document).ready(function() {
    check_l4_debrief();
    $(".l4_debrief_offer").change(function() {
        check_l4_debrief();
    });
    $(".l4_debrief_accept").change(function() {
        check_l4_debrief();
    });

    check_yp_treatment_l4();
    $(".l4_was_yp_offered_treatment").change(function() {
        check_yp_treatment_l4();
    });
    $(".l4_yp_treatment_accept").change(function() {
        check_yp_treatment_l4();
    });

    check_l4_anyone_else_injured();
    check_l4_staff_member_injured();
    $(".l4_was_staff_member_injured").change(function() {
        check_l4_staff_member_injured();
    });


    $(".l4_was_anyone_else_injured").change(function() {
        check_l4_anyone_else_injured();
    });

    police_informed_pi_l4();
    $(".l4_police_informed_pi").change(function() {
        police_informed_pi_l4();
    });


    where_did_accident_other();
    $("#where_did_accident").change(function() {
        where_did_accident_other();
    });
    

    check_l6_anyone_else_injured();
    check_l6_staff_member_injured();

    $(".l6_was_staff_member_injured").change(function() {
        check_l6_staff_member_injured();
    });

    $(".l6_was_anyone_else_injured").change(function() {
        check_l6_anyone_else_injured();
    });

});

function check_l6_staff_member_injured() {
    var l6_staff_member_injured = $("input[name='l6_was_staff_member_injured']:checked").val();
    if (l6_staff_member_injured == 'Yes') {
        $('#div_l6_staff_member_list').show();
    } else {
        $('#div_l6_staff_member_list').hide();
    }
}


function check_l6_anyone_else_injured() {
    var l6_anyone_else_injured = $("input[name='l6_was_anyone_else_injured']:checked").val();
    if (l6_anyone_else_injured == 'Yes') {
        $('#div_l6_other_injured').show();
    } else {
        $('#div_l6_other_injured').hide();
    }
}

function check_l4_staff_member_injured() {
    var l4_staff_member_injured = $("input[name='l4_was_staff_member_injured']:checked").val();
    if (l4_staff_member_injured == 'Yes') {
        $('#div_l4_staff_member_list').show();
    } else {
        $('#div_l4_staff_member_list').hide();
    }
}


function check_l4_anyone_else_injured() {
    var l4_anyone_else_injured = $("input[name='l4_was_anyone_else_injured']:checked").val();
    if (l4_anyone_else_injured == 'Yes') {
        $('#div_l4_other_injured').show();
    } else {
        $('#div_l4_other_injured').hide();
    }
}


function check_yp_treatment_l4() {
    var l4_yp_treatment = $("input[name='l4_was_yp_offered_treatment']:checked").val();
    if (l4_yp_treatment == 'Yes') {
        $('#div_l4_yp_treatment_accept').show();
        var l4_yp_treatment_accept = $("input[name='l4_yp_treatment_accept']:checked").val();
        if (l4_yp_treatment_accept == 'No') {
            $('#div_l4_treatment_not_accepted_comments').show();
        } else {
            $('#div_l4_treatment_not_accepted_comments').hide();
        }
    } else {
        $('#div_l4_yp_treatment_accept').hide();
        $('#div_l4_treatment_not_accepted_comments').hide();
    }
}


function check_l4_debrief() {
    var l4_debrief_offer = $("input[name='l4_debrief_offer']:checked").val();
    if (l4_debrief_offer == 'Yes') {
        $('#div_l4_debrief_accept').show();
        var l1_debrief_accept = $("input[name='l4_debrief_accept']:checked").val();
        if (l1_debrief_accept == 'Yes') {
            $('#div_l4_debrief_comments').show();
        } else {
            $('#div_l4_debrief_comments').hide();
        }
    } else {
        $('#div_l4_debrief_accept').hide();
        $('#div_l4_debrief_comments').hide();
    }
}

function police_informed_pi_l4() {
    var l4_police_informed = $("input[name='l4_police_informed_pi']:checked").val();
    if (l4_police_informed == 'Yes') {
        $('#div_l4_informed_by_pi').show();
        $('#div_l4_how_the_information_was_send_pi').show();
        $('#div_l4_who_was_informed_pi').show();
        $('#div_l4_police_reference_number_pi').show();
        $('#div_l4_date_informed_pi').show();
        $('#div_l4_time_informed_pi').show();
    } else {
        $('#div_l4_informed_by_pi').hide();
        $('#div_l4_how_the_information_was_send_pi').hide();
        $('#div_l4_who_was_informed_pi').hide();
        $('#div_l4_police_reference_number_pi').hide();
        $('#div_l4_date_informed_pi').hide();
        $('#div_l4_time_informed_pi').hide();
    }
}
/*end*/


/*ritesh rana start*/
/*l5 function starts*/   
function where_did_accident_other() {
    var l5_accident_other = $('select#where_did_accident option:selected').val();
    if (l5_accident_other == other_option) {
        $('#div_l5_where_did_accident_other').show();
    } else {
        $('#div_l5_where_did_accident_other').hide();
    }
}

l5_first_aid_informed();
$(".first_aid").change(function() {
    l5_first_aid_informed();
});

function l5_first_aid_informed() {
    var first_aid = $("input[name='first_aid']:checked").val();
    if (first_aid == 'Yes') {
        $('#div_l5_detail_why').hide();
        $('#div_l5_details_of_first_aid_provided').show();
    } else {
        $('#div_l5_detail_why').show();
        $('#div_l5_details_of_first_aid_provided').hide();
    }
}


l5_reffered__doctors();
$(".reffered_doctore").change(function() {
    l5_reffered__doctors();
});

function l5_reffered__doctors() {
    var reffered__doctors = $("input[name='reffered_doctore']:checked").val();
    if (reffered__doctors == 'Yes') {
        $('#div_l5_give_details').show();
    } else {
        $('#div_l5_give_details').hide();
    }
}



l5_debrief_offered_informed();
$(".l5_debrief_offer").change(function() {
    l5_debrief_offered_informed();
});

function l5_debrief_offered_informed() {
    var debrief_offered = $("input[name='l5_debrief_offer']:checked").val();
    if (debrief_offered == 'Yes') {
        $('#div_l5_debrief_details_why').hide();
        $('#div_l5_debrief_details').show();
    } else {
        $('#div_l5_debrief_details_why').show();
        $('#div_l5_debrief_details').hide();
    }
}




/*end l5 function end*/

/* l7 functions Starts */

l7_location_disclosure();
$("#l7_location_disclosure").change(function() {
    l7_location_disclosure();
});


function l7_location_disclosure() {
    var location_disclosure = $('select#l7_location_disclosure option:selected').val();
    if (location_disclosure == other_option) {
        $('#div_enter_location').show();
    } else {
        $('#div_enter_location').hide();
    }
} 

l7_external_agency();
$("#witness_disclosure").change(function() {
    l7_external_agency();
});


function l7_external_agency() {
    var witness_disclosure = $('select#witness_disclosure option:selected').val();
    if (witness_disclosure == external_agency_other) {
        $('#div_l7_enter_other_person').show();
    } else {
        $('#div_l7_enter_other_person').hide();
    }
}




check_yp_disclosure_or_concern_l7();
check_l6_debrief();
$(".l7_disclosure_or_concern").change(function() {
    check_yp_disclosure_or_concern_l7();
});

$(".l7_restricted_access").change(function() {
    check_yp_disclosure_or_concern_l7();
});


function check_yp_disclosure_or_concern_l7() {
    var l7_yp_treatment = $("input[name='is_disclosure_staff']:checked").val();
    if (l7_yp_treatment == 'Yes') {
        $('#div_l7_restricted_access').show();
        var l7_restricted_access = $("input[name='l7_restricted_access[]']:checked").val();
        if (l7_restricted_access == 'Yes') {
            $('#div_allowed_access').show();
        } else {
            $('#div_allowed_access').hide();
        }
    } else {
        $('#div_l7_restricted_access').hide();
        $('#div_allowed_access').hide();
    }
}
evidence_information_passed_l7();
$(".is_evidence_information_passed_to_call").change(function() {
    evidence_information_passed_l7();
});

function evidence_information_passed_l7() {
    var l7_information_passed_to_call = $("input[name='is_evidence_information_passed_to_call[]']:checked").val();
    if (l7_information_passed_to_call == 'Yes') {
        $('#div_detail_evidence').show();
    } else {
        $('#div_detail_evidence').hide();
    }
}
//social_worker_informed
social_worker_informed_l7();
$(".l7_social_worker_informed").change(function() {
    social_worker_informed_l7();
});

function social_worker_informed_l7() {
    var l7_social_worker_informed = $("input[name='l7_social_worker_informed']:checked").val();
    if (l7_social_worker_informed == 'Yes') {
        $('#div_l7_who_was_informed_social_worker').show();
        $('#div_l7_how_the_information_was_send_sw').show();
        $('#div_l7_date_lnformed_social_worker').show();
        $('#div_l7_lnformed_by_social_worker').show();
        $('#div_l7_time_informed_sw').show();
        $('#div_is_evidence_information_passed_to_call').show();
        var l7_information_passed_to_call = $("input[name='is_evidence_information_passed_to_call[]']:checked").val();
        if (l7_information_passed_to_call == 'Yes') {
            $('#div_detail_evidence').show();
        } else {
            $('#div_detail_evidence').hide();
        }

    } else {
        $('#div_l7_lnformed_by_social_worker').hide();
        $('#div_l7_who_was_informed_social_worker').hide();
        $('#div_l7_how_the_information_was_send_sw').hide();
        $('#div_l7_date_lnformed_social_worker').hide();
        $('#div_l7_time_informed_sw').hide();
        $('#div_is_evidence_information_passed_to_call').hide();
        $('#div_detail_evidence').hide();
    }
}
/*end*/
//EDT informed
edt_informed_l7();
$(".l7_edt_informed").change(function() {
    edt_informed_l7();
});

function edt_informed_l7() {
    var l7_edt_informed = $("input[name='l7_edt_informed']:checked").val();
    if (l7_edt_informed == 'Yes') {
        $('#div_l7_informed_by_edt').show();
        $('#div_l7_how_the_information_was_send_edt').show();
        $('#div_l7_who_was_informed_edt').show();
        $('#div_l7_date_informed_edt').show();
        $('#div_l7_time_informed_edt').show();
    } else {
        $('#div_l7_informed_by_edt').hide();
        $('#div_l7_how_the_information_was_send_edt').hide();
        $('#div_l7_who_was_informed_edt').hide();
        $('#div_l7_date_informed_edt').hide();
        $('#div_l7_time_informed_edt').hide();
    }
}
/*end*/
//Parents / Carers informed
parents_carers_informed_l7();
$(".l7_parents_carers_informed_pci").change(function() {
    parents_carers_informed_l7();
});

function parents_carers_informed_l7() {
    var l7_parents_carers_informed = $("input[name='l7_parents_carers_informed_pci']:checked").val();
    if (l7_parents_carers_informed == 'Yes') {
        $('#div_l7_informed_by_pci').show();
        $('#div_l7_how_the_information_was_send_pci').show();
        $('#div_l7_who_was_informed_pci').show();
        $('#div_l7_date_informed_pci').show();
        $('#div_l7_time_informed_pci').show();
    } else {
        $('#div_l7_informed_by_pci').hide();
        $('#div_l7_how_the_information_was_send_pci').hide();
        $('#div_l7_who_was_informed_pci').hide();
        $('#div_l7_date_informed_pci').hide();
        $('#div_l7_time_informed_pci').hide();
    }
}
/*end*/
//Police informed
police_informed_pi_l7();
$(".l7_police_informed_pi").change(function() {
    police_informed_pi_l7();
});

function police_informed_pi_l7() {
    var l7_police_informed = $("input[name='l7_police_informed_pi']:checked").val();
    if (l7_police_informed == 'Yes') {
        $('#div_l7_informed_by_pi').show();
        $('#div_l7_how_the_information_was_send_pi').show();
        $('#div_l7_who_was_informed_pi').show();
        $('#div_l7_police_reference_number_pi').show();
        $('#div_l7_date_informed_pi').show();
        $('#div_l7_time_informed_pi').show();
    } else {
        $('#div_l7_informed_by_pi').hide();
        $('#div_l7_how_the_information_was_send_pi').hide();
        $('#div_l7_who_was_informed_pi').hide();
        $('#div_l7_police_reference_number_pi').hide();
        $('#div_l7_date_informed_pi').hide();
        $('#div_l7_time_informed_pi').hide();
    }
}
/*end*/
//LADO informed
lado_informed_li_l7();
$(".l7_lado_informed_li").change(function() {
    lado_informed_li_l7();
});

function lado_informed_li_l7() {
    var l7_police_informed = $("input[name='l7_lado_informed_li']:checked").val();
    if (l7_police_informed == 'Yes') {
        $('#div_l7_informed_by_li').show();
        $('#div_l7_how_the_information_was_send_li').show();
        $('#div_l7_who_was_informed_li').show();
        $('#div_l7_date_informed_li').show();
        $('#div_l7_time_informed_li').show();
    } else {
        $('#div_l7_informed_by_li').hide();
        $('#div_l7_how_the_information_was_send_li').hide();
        $('#div_l7_who_was_informed_li').hide();
        $('#div_l7_date_informed_li').hide();
        $('#div_l7_time_informed_li').hide();
    }
}
/*end*/
//LSCB informed
lscb_informed_lscb_l7();
$(".l7_lscb_informed_lscb").change(function() {
    lscb_informed_lscb_l7();
});

function lscb_informed_lscb_l7() {
    var l7_police_informed = $("input[name='l7_lscb_informed_lscb']:checked").val();
    if (l7_police_informed == 'Yes') {
        $('#div_l7_informed_by_lscb').show();
        $('#div_l7_how_the_information_was_send_lscb').show();
        $('#div_l7_who_was_informed_lscb').show();
        $('#div_l7_police_reference_number_lscb').show();
        $('#div_l7_date_informed_lscb').show();
        $('#div_l7_time_informed_lscb').show();
    } else {
        $('#div_l7_informed_by_lscb').hide();
        $('#div_l7_how_the_information_was_send_lscb').hide();
        $('#div_l7_who_was_informed_lscb').hide();
        $('#div_l7_police_reference_number_lscb').hide();
        $('#div_l7_date_informed_lscb').hide();
        $('#div_l7_time_informed_lscb').hide();
    }
}
/*end*/
//LSCB informed
other_placing_authorities_informed_opai_l7();
$(".l7_other_placing_authorities_informed_opai").change(function() {
    other_placing_authorities_informed_opai_l7();
});

function other_placing_authorities_informed_opai_l7() {
    var l7_opai = $("input[name='l7_other_placing_authorities_informed_opai']:checked").val();
    if (l7_opai == 'Yes') {
        $('#div_l7_informed_by_opai').show();
        $('#div_l7_how_the_information_was_send_opai').show();
        $('#div_l7_who_was_informed_opai').show();
        $('#div_l7_police_reference_number_opai').show();
        $('#div_l7_date_informed_opai').show();
        $('#div_l7_time_informed_opai').show();
    } else {
        $('#div_l7_informed_by_opai').hide();
        $('#div_l7_how_the_information_was_send_opai').hide();
        $('#div_l7_who_was_informed_opai').hide();
        $('#div_l7_police_reference_number_opai').hide();
        $('#div_l7_date_informed_opai').hide();
        $('#div_l7_time_informed_opai').hide();
    }
}
/*end*/
//Reg 40 Ofsted informed
Reg_40_Ofsted_informed_l7();
$(".l7_reg_40_ofsted_informed_reg_40").change(function() {
    Reg_40_Ofsted_informed_l7();
});

function Reg_40_Ofsted_informed_l7() {
    var l7_reg_ofsted = $("input[name='l7_reg_40_ofsted_informed_reg_40']:checked").val();
    if (l7_reg_ofsted == 'Yes') {
        $('#div_l7_informed_by_reg_40').show();
        $('#div_l7_how_the_information_was_send_reg_40').show();
        $('#div_l7_who_was_informed_reg_40').show();
        $('#div_l7_date_informed_reg_40').show();
        $('#div_l7_time_informed_reg_40').show();
    } else {
        $('#div_l7_informed_by_reg_40').hide();
        $('#div_l7_how_the_information_was_send_reg_40').hide();
        $('#div_l7_who_was_informed_reg_40').hide();
        $('#div_l7_date_informed_reg_40').hide();
        $('#div_l7_time_informed_reg_40').hide();
    }
}
/*end*/
//Education informed
education_informed_l7();
$(".l7_education_informed_ei").change(function() {
    education_informed_l7();
});

function education_informed_l7() {
    var l7_education_informed = $("input[name='l7_education_informed_ei']:checked").val();
    if (l7_education_informed == 'Yes') {
        $('#div_l7_informed_by_ei').show();
        $('#div_l7_how_the_information_was_send_ei').show();
        $('#div_l7_who_was_informed_ei').show();
        $('#div_l7_date_informed_ei').show();
        $('#div_l7_time_informed_ei').show();
    } else {
        $('#div_l7_informed_by_ei').hide();
        $('#div_l7_how_the_information_was_send_ei').hide();
        $('#div_l7_who_was_informed_ei').hide();
        $('#div_l7_date_informed_ei').hide();
        $('#div_l7_time_informed_ei').hide();
    }
}
/*end*/
//Therapy informed
therapy_informed_l7();
$(".l7_therapy_informed_ti").change(function() {
    therapy_informed_l7();
});

function therapy_informed_l7() {
    var l7_therapy_informed = $("input[name='l7_therapy_informed_ti']:checked").val();
    if (l7_therapy_informed == 'Yes') {
        $('#div_l7_informed_by_ti').show();
        $('#div_l7_how_the_information_was_send_ti').show();
        $('#div_l7_who_was_informed_ti').show();
        $('#div_l7_date_informed_ti').show();
        $('#div_l7_time_informed_ti').show();
    } else {
        $('#div_l7_informed_by_ti').hide();
        $('#div_l7_how_the_information_was_send_ti').hide();
        $('#div_l7_who_was_informed_ti').hide();
        $('#div_l7_date_informed_ti').hide();
        $('#div_l7_time_informed_ti').hide();
    }
}
/*end*/
//Other informed
other_informed_l7();
$(".l7_other_informed_oi").change(function() {
    other_informed_l7();
});

function other_informed_l7() {
    var l7_therapy_informed = $("input[name='l7_other_informed_oi']:checked").val();
    if (l7_therapy_informed == 'Yes') {
        $('#div_l7_informed_by_oi').show();
        $('#div_l7_how_the_information_was_send_oi').show();
        $('#div_l7_who_was_informed_oi').show();
        $('#div_l7_date_informed_oi').show();
        $('#div_l7_time_informed_oi').show();
    } else {
        $('#div_l7_informed_by_oi').hide();
        $('#div_l7_how_the_information_was_send_oi').hide();
        $('#div_l7_who_was_informed_oi').hide();
        $('#div_l7_date_informed_oi').hide();
        $('#div_l7_time_informed_oi').hide();
    }
}
/*end*/
if (typeof l4sq === 'undefined') {
    countl4sq = 1;
} else {
    countl4sq = l4sq;
}

function l4add_sequence_event() {
    var html = '';
    html += '<div class=""></div>';

    html += '<div class="col-md-6 dynamic-div" id="item_new_sq_' + countl4sq + '"><div class="form-group"><div class="col-md-6"><label>Sequence Number</label></div><div class="col-md-6"><input readonly="true" name="l4seq_sequence_number[]" value="S'+countl4sq+'" class="red form-control input-textar-style" type="text" /></div></div>';

    html += '<div class="form-group"><div class="col-md-6"><label>Who(staff full name)</label></div>';
    html += '<div class="col-md-6 ">';
    html += '<select data-parsley-errors-container="#errors_l4_who-' + countl4sq + '" class="form-control chosen-select" id="' + countl4sq + '" name="l4_sequence_who[]"><option value=""> Select user </option>';
    $.each(bambooNfcUsers, function(i, elem) {
        html += '<option value="' + elem['user_type'] + '_' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select><span id="errors_l4_who-'+ countl4sq +'"></span></div></div>';

    html+='<div class="form-group"><div class="col-md-6">';
    html+='<label>What Happned / what was done(include senior cover instruction)</label>';
    html+='</div><div class="col-md-6">';
    html+='<textarea id="what_happned'+ countl4sq +'" minlength="2" maxlength="500" class="form-control input-textar-style"  placeholder="Attend Health appointments"  name="l4seq_what_happned[]"></textarea>';
    html+='</div></div>';

    html += '<div class="form-group"><div class="col-md-6"><label>Date</label><span class="astrick">*</span></div>';
    html += '<div class="col-md-6 m-t-3">';
    html += '<div class="input-group input-append">';
    html += '<input type="text" autocomplete="off" class="form-control sq_addtime_data timer-sty" data-parsley-errors-container="#errors-container-l4seq_date_event' + countl4sq + '" required="true" name="l4seq_date_event[]" id="date_event" value="">';
    html += '<span class="input-group-addon add-on"><i class="fa fa-calendar" id="l7date_safeguarding_lbl"></i></span>';
    html += '</div><span id="errors-container-l4seq_date_event'+ countl4sq +'"></span>';
    html += '</div>';
    html += '</div>';


    html+='<div class="form-group"><div class="col-md-6"><label>Time</label></div>';
    html+='<div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html+='<input type="text" required="true" class="red form-control l4_addtime_sequence addtime_data1 timer-sty" name="l4seq_time_event[]" id="l4time_event' + countl4sq + '" placeholder="" value="" data-parsley-errors-container="#errors-container-l4time_event' + countl4sq + '" readonly="">';
    html+='<span class="input-group-addon add-on l4time_event' + countl4sq + '"><i class="fa fa-clock-o"></i></span>';
    html+='</div><span id="errors-container-l4time_event'+ countl4sq +'"></span></div></div><div class="form-group"><div class="col-md-6">';
    html+='<label>All communication details</label></div>';
    html+='<div class="col-md-6"><textarea id="communication'+ countl4sq +'" minlength="2" maxlength="500" class="form-control input-textar-style" placeholder="All Communication Detail"  name="l4seq_communication[]"></textarea>';
    html+='</div></div>';
    html+='<div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border">';
    html+='<span class="glyphicon glyphicon-trash" onclick="delete_sq_review_row(\'item_new_sq_' + countl4sq + '\');"></span>';
    html+='</a></div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    countl4sq++;
    return html;
}
$(function() {
    //Add more item
    $('#l4add_new_seq_event').click(function() {
        var yp_missing_date = $("#l4_date_yp_missing").val();
        if (yp_missing_date === '') {
            yp_missing_date = YP_PLACEMENT_DATE;
        }
        item_html = l4add_sequence_event();
        $('#l4add_seqevent').append(item_html);
        $('.l4_addtime_sequence').timepicker({
            defaultTime: '',
            minuteStep: 1,
            showMeridian: false
        });
        $('.addtime_data1').click(function(){
            var id=$(this).attr('id');
            $('.'+id+'.input-group-addon').click();
        });
        $('input.sq_addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            startDate: yp_missing_date,
            autoclose: true,
            endDate: '+0d'
        });
        $('#ail4formupdate').parsley();
        $(".chosen-select").chosen();
        tinymce.init({
            selector: '.tinyeditor',
            branding: false
        });
    });
    /*end item code*/
});
//remove new row

/*//remove new row
function delete_repeat_block(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                var del_ids = $('#delete_cpt_review_id').val();
                remove_id = del_id.split('item_new_');
                if ($.isNumeric(remove_id[1])) {
                    $('#delete_cpt_review_id').val(del_ids + remove_id[1] + ',');
                }
                $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}*/

//remove new row l4 Sequence Number data
function delete_sq_review_row(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}
/**************************************/
/*nikunj code*/
$('#send_worker_notification').click(function() {
    var l4_form_id  = $('#l4_form_id').val();
    var incident_id  = $('#incident_id').val();
    $.blockUI({message: LOADER});
    $.ajax({
        type: "POST",
        url: baseurl + 'AAI/send_notification_social_worker/',
        data: {
            l4_form_id: l4_form_id,
            incident_id: incident_id
        },
        success: function (data) {
            var newData = $.parseJSON(data); 
            $.unblockUI();
            if (newData.status == 'success') {
                BootstrapDialog.show({
                    title: 'Success',
                    message: 'Notification has been sent successfully',
                    buttons: [{
                        label: 'Ok',
                        action: function (dialog) {
                            dialog.close();
                            $('#calculate_notification_worker').val(newData.notification_date);
                            $('#calculate_notification_worker_label').html(newData.notification_date);
                            $('#l4_form_id').val(newData.l4_form_id);
                        }
                    }]
                });
            }
            
            
        }
    });
});
$('#send_notification_missing_team').click(function() { 
    var l4_form_id  = $('#l4_form_id').val();
    var incident_id  = $('#incident_id').val();
    $.blockUI({message: LOADER});
    $.ajax({
        type: "POST",
        url: baseurl + 'AAI/send_notification_missing_team/',
        data: {
            l4_form_id: l4_form_id,
            incident_id: incident_id
        },
        success: function(data) {
            var newData = $.parseJSON(data);            
            $.unblockUI();
            if (newData.status == 'success') {
                BootstrapDialog.show({
                    title: 'Success',
                    message: 'Notification has been sent successfully',
                    buttons: [{
                        label: 'Ok',
                        action: function (dialog) {
                            dialog.close();
                            $('#calculate_notification_missing').val(newData.notification_date);
                            $('#calculate_notification_missing_label').html(newData.notification_date);
                            $('#l4_form_id').val(newData.l4_form_id);
                        }
                    }]
                });
            }
            
            
        }
    });
});
if (typeof total_missing === 'undefined') {
    count_missing = 1;
} else {
    count_missing = total_missing;
}

function add_new_person_informed_yp_missing() {
    var html = '';
    html += '<div class=""></div>';
    html += '<div class="col-md-6 dynamic-div" id="item_new_' + count_missing + '">';
    html += '<div class="form-group"><div class="col-md-6"><label>Persons Informed</label><span class="astrick">*</span></div>';
    html += '<div class="col-md-6">';
    html += '<select class="chosen-select form-control input-textar-style" name="person_informed_missing_team[]" id="person_informed_missing_team" required="true" data-parsley-errors-container="#errors-container-person_informed_missing_team_'+ count_missing +'">';
    html += '<option value="">Select Persons Infromed</option>';
    $.each(persons_infromed, function(i, elem) {
        html += '<option value="' + elem['value'] + '">' + elem['label'] + '</option>';
    });
    html += '</select><span id="errors-container-person_informed_missing_team_'+ count_missing +'"></span></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Name Of Person Informed</label></div>';
    html += '<div class="col-md-6"><input name="name_of_person_informed_missing[]" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="50" class="red form-control input-textar-style" type="text" />';
    html += '</div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Badge Number</label></div>';
    html += '<div class="col-md-6"><input class="red form-control input-textar-style" name="badge_number_person_missing[]" type="number" max="9999999999" />';
    html += '</div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Contact Number</label></div>';
    html += '<div class="col-md-6"><input class="red form-control input-textar-style" name="contact_number_person_missing[]" type="number" max="9999999999999" />';
    html += '</div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Contact Email</label></div>';
    html += '<div class="col-md-6"><input class="red form-control input-textar-style" name="contact_email_person_missing[]" type="email" />';
    html += '</div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Informed By</label></div>';
    html += '<div class="col-md-6">';
    
    html += '<select class="chosen-select form-control input-textar-style" name="informed_by_person_missing[]" id="informed_by_person_missing">';
    html += '<option value="">Select Informed By</option>';
    $.each(bambooNfcUsers, function(i, elem) {
        html += '<option value="' + elem['user_type'] + '_' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select>';    
//            <input class="red form-control input-textar-style" name="informed_by_person_missing[]" type="text" />';
html += '</div></div>';

html += '<div class="form-group"><div class="col-md-6"><label>Date</label><span class="astrick">*</span></div>';
html += '<div class="col-md-6 m-t-3">';
html += '<div class="input-group input-append">';
html += '<input type="text" autocomplete="off" class="form-control seq_adddate seq_addtime_data timer-sty" data-parsley-errors-container="#errors-container-l4date_event'+ count_missing +'" required="true" name="date_event[]" id="date_event" value="">';
html += '<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>';
html += '</div><span id="errors-container-l4date_event'+ count_missing +'"></span>';
html += '</div>';
html += '</div>';


html += '<div class="form-group"><div class="col-md-6"><label>Time</label><span class="astrick">*</span></div>';
html += '<div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
html += '<input type="text" class="red form-control aaitime addtime_data1 timer-sty" name="time_event[]" id="l4persons_missing_time'+ count_missing +'" placeholder="" value="" required="true" data-parsley-errors-container="#errors-container-l4persons_missing_time'+ count_missing +'" readonly="">';
html += '<span class="input-group-addon add-on l4persons_missing_time'+ count_missing +'"><i class="fa fa-clock-o"></i></span></div><span id="errors-container-l4persons_missing_time'+ count_missing +'"></span></div></div>';
html += '<div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_repeat_block(\'item_new_' + count_missing + '\');"></span></a></div>';
html += '</div></div>';
html += '</div>';
html += '</div>';
count_missing++;
return html;
}
$(function() {
    //Add more item
    $('#add_new_person_informed_yp_missing').click(function() {
        var yp_missing_date = $("#l4_date_yp_missing").val();
        if (yp_missing_date === '') {
            yp_missing_date = YP_PLACEMENT_DATE;
        }
        item_html = add_new_person_informed_yp_missing();
        $('#add_person_yp_missing').append(item_html);
        $('.aaitime').timepicker({
            defaultTime: '',
            minuteStep: 1,
            showMeridian: false
        });
        $('.addtime_data1').click(function(){
            var id=$(this).attr('id');
            $('.'+id+'.input-group-addon').click();
        });
        $('input.seq_addtime_data').datepicker({
            startDate: yp_missing_date,
            format: 'dd/mm/yyyy',
            autoclose: true,
            endDate: '+0d'
        });
        $('#ail4formupdate').parsley();
        $(".chosen-select").chosen();
        tinymce.init({
            selector: '.tinyeditor',
            branding: false
        });
    });
    /*end item code*/
});

//remove new row
function delete_person_yp_missing(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                var del_ids = $('#delete_person_yp_missing').val();
                remove_id = del_id.split('item_new_');
                if ($.isNumeric(remove_id[1])) {
                    $('#delete_person_yp_missing').val(del_ids + remove_id[1] + ',');
                }
                $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}
if (typeof person_re === 'undefined') {
    count_pr = 1;
} else {
    count_pr = person_re;
}

function add_new_person_informed_yp_return() {
    var html = '';
    html += '<div class=""></div>';
    html += '<div class="col-md-6 dynamic-div" id="item_new_pret' + count_pr + '">';
    html += '<div class="form-group"><div class="col-md-6"><label>Persons Informed</label><span class="astrick">*</span></div>';
    html += '<div class="col-md-6">';
    html += '<select class="chosen-select form-control input-textar-style" name="person_informed_return_team[]" id="person_informed_return_team" required="true" data-parsley-errors-container="#errors-container-person_informed_return_team'+ count_pr +'">';
    html += '<option value="">Select Persons Infromed</option>';
    $.each(persons_infromed, function(i, elem) {
        html += '<option value="' + elem['value'] + '">' + elem['label'] + '</option>';
    });
    html += '</select><span id="errors-container-person_informed_return_team'+ count_pr +'"></span></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Name Of Person Informed</label></div>';
    html += '<div class="col-md-6">';
    html += '<input name="name_of_person_informed_return[]" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="50" class="red form-control input-textar-style" type="text" />';
    html += '</div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Badge Number</label></div>';
    html += '<div class="col-md-6"><input class="red form-control input-textar-style" name="badge_number_person_return[]" type="number" max="9999999999" />';
    html += '</div></div><div class="form-group"><div class="col-md-6"><label>Contact Number</label></div>';
    html += '<div class="col-md-6">';
    html += '<input class="red form-control input-textar-style" name="contact_number_person_return[]" type="number" max="9999999999999" />';
    html += '</div></div><div class="form-group"><div class="col-md-6"><label>Contact Email</label></div>';
    html += '<div class="col-md-6">';
    html += '<input class="red form-control input-textar-style" name="contact_email_person_return[]" type="email" />';
    html += '</div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Informed By</label></div>';
    html += '<div class="col-md-6">';
    
    html += '<select class="chosen-select form-control input-textar-style" name="informed_by_person_return[]" id="informed_by_person_return">';
    html += '<option value="">Select Informed By</option>';
    $.each(bambooNfcUsers, function(i, elem) {
        html += '<option value="' + elem['user_type'] + '_' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select>';  
    
//    html += '<input class="red form-control input-textar-style" name="informed_by_person_return[]" type="text" />';
html += '</div></div>';

html += '<div class="form-group"><div class="col-md-6"><label>Date</label><span class="astrick">*</span></div>';
html += '<div class="col-sm-6 m-t-3">';
html += '<div class="input-group input-append">';
html += '<input type="text" autocomplete="off" class="form-control seq_addtime_data timer-sty" required="true" name="person_return_date_event[]" id="date_event_yp_return'+count_pr+'" value="" data-parsley-errors-container="#errors-container-date_event_yp_return'+ count_pr +'">';
html += '<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>';
html += '</div><span id="errors-container-date_event_yp_return'+ count_pr +'"></span>';
html += '</div>';
html += '</div>';

html += '<div class="form-group"><div class="col-md-6"><label>Time</label></div>';
html += '<div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
html += '<input type="text" class="red form-control l4_addtime_yp_return addtime_data1 timer-sty" required="true" name="person_return_time_event[]" id="return_time_event'+count_pr+'" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event'+ count_pr +'" readonly="">';
html += '<span class="input-group-addon add-on return_time_event'+count_pr+'""><i class="fa fa-clock-o"></i></span></div>';
html += '<span id="errors-containertime_event'+ count_pr +'"></span></div></div>';
html += '<div class="col-md-12 add_items_field mb44 del-btn-form">';
html += '<a class="btn btn-default btn_border">';
html += '<span class="glyphicon glyphicon-trash" onclick="delete_repeat_block(\'item_new_pret' + count_pr + '\');"></span></a>';
html += '</div><div class="clearfix"></div></div></div>';
html += '</div>';
html += '</div>';
count_pr++;
return html;
}
$(function() {
    //Add more item
    $('#add_new_person_informed_yp_return').click(function() {
        var yp_missing_date = $("#l4_date_yp_missing").val();
        if (yp_missing_date === '') {
            yp_missing_date = YP_PLACEMENT_DATE;
        }
        item_html = add_new_person_informed_yp_return();
        $('#add_person_yp_return').append(item_html);
        $('.l4_addtime_yp_return').timepicker({
            defaultTime: '',
            minuteStep: 1,
            showMeridian: false
        });
        $('.addtime_data1').click(function(){
            var id=$(this).attr('id');
            $('.'+id+'.input-group-addon').click();
        });
        $('input.seq_addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            endDate: '+0d',
            startDate: yp_missing_date,
        });
        $('#ail4formupdate').parsley();
        $(".chosen-select").chosen();
        tinymce.init({
            selector: '.tinyeditor',
            branding: false
        });
    });
    /*end item code*/
});

//remove new row
function delete_person_yp_return(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                var del_ids = $('#delete_person_yp_return').val();
                remove_id = del_id.split('item_new_');
                if ($.isNumeric(remove_id[1])) {
                    $('#delete_person_yp_return').val(del_ids + remove_id[1] + ',');
                }
                $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}
/**********************************/
/*Safeguarding updates start*/
if (typeof xsu === 'undefined') {
    count_data = 1;
} else {
    count_data = xsu;
}

function add_safeguarding_updates() {
    //console.log(bambooNfcUsers);
    var html = '';
    html += '<div class=""></div>';
    html += '<div class="col-md-6 dynamic-div" id="item_new_safeguarding_' + count_data + '">';
    html += '<div class="form-group"><div class="col-md-6"><label>Sequence Number</label></div>';
    html += '<div class="col-md-6"><input name="l7sequence_number[]" value="S'+count_data+'" class="red form-control input-textar-style" type="text" /></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Update By</label></div>';
    html += '<div class="col-md-6">';
    html += '<select data-parsley-errors-container="#errors-' + count_data + '" class="form-control chosen-select" id="' + count_data + '" name="l7update_by[]"><option value=""> Select user </option>';
    $.each(bambooNfcUsers, function(i, elem) {
        console.log(elem);
        html += '<option value="' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Daily action taken</label></div>';
    html += '<div class="col-md-6"><textarea id="what_happned" class="form-control input-textar-style"  placeholder="Daily action outcome"  name="l7daily_action_taken[]"></textarea></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Daily action outcome</label></div>';
    html += '<div class="col-md-6"><textarea id="what_happned" class="form-control input-textar-style"  placeholder="Daily action outcome" name="l7daily_action_outcome[]"></textarea></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Supporting documents</label></div>';
    html += '<div class="col-md-6"><input multiple="true" type="file" id="l7supporting_documents" class="form-control file-attch" placeholder="Daily action outcome" name="l7supporting_documents' + count_data + '[]" /></div></div>';
    
    html += '<div class="form-group"><div class="col-md-6"><label>Date</label></div>';
    html += '<div class="col-md-6 m-t-3">';
    html += '<div class="input-group input-append">';
    html += '<input type="text" autocomplete="off" class="form-control addtime_data timer-sty" required="true" name="l7date_safeguarding[]" id="safe_addtime_data" value="" readonly="">';
    html += '<span class="input-group-addon add-on"><i class="fa fa-calendar" id="l7date_safeguarding_lbl"></i></span>';
    html += '</div><span id="errors-containerdate_of_disclosure"></span>';
    html += '</div>';
    html += '</div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Time</label></div>';
    html += '<div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control aaiaddtime addtime_data1 l7time_safeguard_data timer-sty" name="l7time_safeguard[]" id="l7time_safeguard'+count_data+'" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on l7time_safeguard'+count_data+'"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="col-md-12  add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_safeguard_row(\'item_new_safeguarding_' + count_data + '\');"></span></a></div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    count_data++;
    return html;
}
$(function() {
    //Add more item
    $('#add_new_safe_updates').click(function() {
        item_html = add_safeguarding_updates();
        $('#add_safeguarding').append(item_html);
        $('.l7time_safeguard_data').timepicker({
            defaultTime: '',
            minuteStep: 1,
            showMeridian: false
        });
        $('.addtime_data1').click(function(){
            var id=$(this).attr('id');
            $('.'+id+'.input-group-addon').click();
        });
        $('input.addtime_data').datepicker({
         format: 'dd/mm/yyyy',
         autoclose: true,
         endDate: '+0d',
         startDate: new Date("01/01/1970"),
     });
        
        $('#ail7formupdate').parsley();
        $(".chosen-select").chosen();
        tinymce.init({
            selector: '.tinyeditor',
            branding: false
        });
    });
    /*end item code*/
});
//remove new row
function delete_safeguard_row(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                /* var del_ids = $('#delete_safeguard_review_id').val();

                            remove_id = del_id.split('item_new_safeguarding_');
                            if ($.isNumeric(remove_id[1]))
                            {
                                $('#delete_safeguard_review_id').val(del_ids + remove_id[1] + ',');
                            }
                            */
                            $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}
/*Safeguarding updates end*/
/* ritesh rana end*/
if (typeof x === 'undefined') {
    count = 1;
} else {
    count = x;
}

function add_sequence_event() {
    var html = '';
    html += '<div class=""></div>';
    html += '<div class="col-md-6 dynamic-div" id="item_new_' + count + '"><div class="form-group"><div class="col-md-6"><label>Sequence Number</label></div><div class="col-md-6"><input name="sequence_number[]" class="red form-control input-textar-style" type="text" /></div></div><div class="form-group"><div class="col-md-6"><label>Who(staff full name)</label></div><div class="col-md-6"><input class="red form-control input-textar-style" name="who[]"  type="text" /></div></div><div class="form-group"><div class="col-md-6"><label>What Happned / what was done(include senior cover instruction)</label></div><div class="col-md-6"><textarea id="what_happned" class="form-control input-textar-style"  placeholder="Attend Health appointments"  name[]="what_happned"></textarea></div></div><div class="form-group"><div class="col-md-6"><label>Date</label></div><div class="col-md-6"><input class="form-control adddate addtime_data input-textar-style" name="date_event[]" id="date_event" type="text" /></div></div><div class="form-group"><div class="col-md-6"><label>Time</label></div><div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker"><input type="text" class="red form-control  aaiaddtime addtime_data1 timer-sty" name="time_event" id="time_event" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly=""><span class="input-group-addon add-on time_event"><i class="fa fa-clock-o"></i></span></div></div></div><div class="form-group"><div class="col-md-6"><label>What Happned / what was done(include senior cover instruction)</label></div><div class="col-md-6"><textarea id="communication" class="form-control input-textar-style"  placeholder="All Communication Detail"  name="communication[]"></textarea></div></div><div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_repeat_block(\'item_new_' + count + '\');"></span></a></div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    count++;
    return html;
}
$(function() {
    //Add more item
    $('#add_new_seq_event').click(function() {
        item_html = add_sequence_event();
        $('#add_seqevent').append(item_html);
        $('#time_event').timepicker({
            defaultTime: '',
            minuteStep: 1,
            showMeridian: false
        });
        $('input.seq_addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            startDate: YP_PLACEMENT_DATE,
        });
        $('#ail4formupdate').parsley();
        $(".chosen-select").chosen();
        tinymce.init({
            selector: '.tinyeditor',
            branding: false
        });
    });
    /*end item code*/
});
//remove new row
/*function delete_new_row(del_id) {
    var delete_meg = "Are you sure want to deletddddde?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'ok',
            action: function(dialog) {
                $('#' + del_id).remove();
                dialog.close();
            }
        }]
    });
}*/
//remove new row
function delete_repeat_block(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                var del_ids = $('#delete_cpt_review_id').val();
                remove_id = del_id.split('item_new_');
                if ($.isNumeric(remove_id[1])) {
                    $('#delete_cpt_review_id').val(del_ids + remove_id[1] + ',');
                }
                $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}



/*Code by Nikunj End*/
/*code by Ritesh Ranan*/
function manager_request(YPId, incidentId) {
    var delete_meg = "Please select OK to authorise this document.";
    BootstrapDialog.show({
        title: 'Information',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                $(".aai_signoff").prop("checked", false);
                dialog.close();
            }
        }, {
            label: 'ok',
            action: function(dialog) {
                window.location.href = baseurl + 'AAI/manager_review/' + YPId + '/' + incidentId;
                dialog.close();
            }
        }]
    });
}

$('body').delegate('[data-toggle="ajaxModal"]', 'click', function(e) {
    $('#ajaxModal').remove();
    e.preventDefault();
    var $this = $(this),
    $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href'),
    $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
    $('body').append($modal);
    $modal.modal({
        backdrop: true
    });
    $modal.load($remote);
    $("body").css("padding-right", "0 !important");
});

//signoff chnage user type
$('body').on('change', '#user_type', function(e) {
    $("#submit_btn").attr("disabled", true);
    var id = $(this).val();
    var element = $(this).find('option:selected');
    var user_type = element.attr("user-type");
    $.ajax({
        type: "POST",
        url: baseurl + '/AAi/getUserTypeDetail',
        data: {
            user_type: user_type,
            id: id,
            ypid: $('#ypid').val()
        },
        success: function(html) {
            $("#common_div").html(html);
            $("#submit_btn").removeAttr("disabled");
        },
        error: function(xhr, ajaxOptions, thrownError) {
            if (xhr.responseText) {
                toastr.error(xhr.responseText, 'Inconceivable!')
            } else {
                console.error("<div>Http status: " + xhr.status + " " + xhr.statusText + "</div>" + "<div>ajaxOptions: " + ajaxOptions + "</div>" + "<div>thrownError: " + thrownError + "</div>");
            }
        }
    });
});

function signoff_request(YPId, signoff_approval_id) {
    var delete_meg = "Please select OK to authorise this document.";
    BootstrapDialog.show({
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
                window.location.href = baseurl + 'AAI/signoff_review_data/' + YPId + '/' + signoff_approval_id + '/' + EMAIL;
                dialog.close();
            }
        }]
    });
}
/*L2 & L3 sequence events updates start*/
if (typeof l2xsu === 'undefined') {
    l2_count_data = 1;
} else {
    l2_count_data = l2xsu;
}
edit_Who_was_involved();
$("#l2_involved_other").change(function() {
    //alert(l2_count_data);
    edit_Who_was_involved();
    return true;
});
$("#l2_involved_agency").change(function() {
    //alert(l2_count_data);
    edit_Who_was_involved();
    return true;
});
$("#l2_involved_employee").change(function() {
    //alert(l2_count_data);
    edit_Who_was_involved();
    return true;
});

function edit_Who_was_involved() {
    var employee = [];
    var involved = [];
    var other = '';
    var favorite = [];
    $.each($("input[name='l2_involved_incident[]']:checked"), function() {
        favorite.push($(this).val());
    });
    var str = favorite.join(",");
    emp = str.split(",");
    if (jQuery.inArray('Employee', emp) !== -1) {
        $.each($("#l2_involved_employee option:selected"), function() {
            employee.push($(this).val());
        });
    }
    if (jQuery.inArray('Outside Agency', emp) !== -1) {
        $.each($("#l2_involved_agency option:selected"), function() {
            involved.push($(this).val());
        });
    }
    if (jQuery.inArray('Other', emp) !== -1) {
        other = $('#l2_involved_other').val();
    }
    var row_count = l2_count_data - 1;
    for (isq = 1; isq <= row_count; isq++) {
        var pid=$('#sqpid'+isq).val();
        var url_inv = baseurl + 'AAI/who_was_involved_in_incident/' + isq+'/'+pid;
        $.ajax({
            type: "POST",
            url: url_inv,
            dataType: 'json',
            data: {
                incident_id: $("#incident_id").val(),
                involved_employee: employee,
                involved_agency: involved,
                other: other
            },
            success: function(response) {
                //console.log(response);
                $('.l2Who_was_involved_in_incident' + response.squ_num).empty();
                $(".l2Who_was_involved_in_incident" + response.squ_num).append(response.table);
                $(".l2Who_was_involved_in_incident" + response.squ_num).chosen('destroy');
                $(".l2Who_was_involved_in_incident" + response.squ_num).chosen();
            }
        });
    }
}

function l2_add_sequence_events() {
    var employee = [];
    var involved = [];
    var other = '';
    var favorite = [];
    $.each($("input[name='l2_involved_incident[]']:checked"), function() {
        favorite.push($(this).val());
    });
    var str = favorite.join(",");
    emp = str.split(",");
    if (jQuery.inArray('Employee', emp) !== -1) {
        $.each($("#l2_involved_employee option:selected"), function() {
            employee.push($(this).val());
        });
    }
    if (jQuery.inArray('Outside Agency', emp) !== -1) {
        $.each($("#l2_involved_agency option:selected"), function() {
            involved.push($(this).val());
        });
    }
    if (jQuery.inArray('Other', emp) !== -1) {
        other = $('#l2_involved_other').val();
    }
    var html = '';
    html += '<div class=""></div>';
    html += '<div class="col-md-6 dynamic-div" id="l2item_new_sequence_' + l2_count_data + '">';
    html += '<div class="form-group"><div class="col-md-6"><label>Sequence Number</label></div>';
    html += '<div class="col-md-6"><input name="l2sequence_number[]" value="S'+l2_count_data+'" class="red form-control" type="text" /></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Who was involved in Incident</label></div>';
    html += '<div class="col-md-6 m-t-3">';
    html += '<select multiple data-parsley-errors-container="#errors-' + l2_count_data + '" class="form-control chosen-select l2Who_was_involved_in_incident' + l2_count_data + '" id="' + l2_count_data + '" name="l2Who_was_involved_in_incident' + l2_count_data + '[]"><option value=""> Select user </option>';
    var str = employee.join(",");
    res = str.split(",");
    //console.log(res);
    $.each(bambooNfcUsers, function(i, elem) {
        if (jQuery.inArray(elem['user_type'] + '_' + elem['user_id'], res) !== -1) {
            html += '<option class="mydd" value="' + elem['user_type'] + '_' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
        }
    });
    var str_inv = involved.join(",");
    inv = str_inv.split(",");
    //console.log(res);
    $.each(pre_outside_agency, function(e, elm) {
        if (jQuery.inArray(elm['value'], inv) !== -1) {
            html += '<option class="mydd" value="' + elm['value'] + '">' + elm['label'] + '</option>';
        }
    });
    if (other != '') {
        html += '<option class="mydd" value="' + other + '">' + other + '</option>';
    }
    html += '</select></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Position</label></div>';
    html += '<div class="col-md-6">';
    html += '<select data-parsley-errors-container="#errors-' + l2_count_data + '" class="form-control chosen-select" id="' + l2_count_data + '" name="l2position[]"><option value=""> Select Position </option>';
    $.each(position_of_yp, function(i, elem) {
        html += '<option value="' + elem['value'] + '">' + elem['label'] + '</option>';
    });
    html += '</select></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Type</label></div>';
    html += '<div class="col-md-6">';
    html += '<select data-parsley-errors-container="#errors-' + l2_count_data + '" class="form-control chosen-select" id="' + l2_count_data + '" name="l2type[]"><option value=""> Select Type </option>';
    $.each(type, function(i, elem) {
        html += '<option value="' + elem['value'] + '">' + elem['label'] + '</option>';
    });
    html += '</select></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Comments</label></div>';
    html += '<div class="col-md-6"><textarea id="comments" class="form-control input-textar-style" placeholder="Comments"  name="l2comments[]"></textarea></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Time</label></div>';
    html += '<div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control aaiaddtime addtime_data1 l2time_sequence_data timer-sty" name="l2time_sequence[]" id="l2time_sequence'+ l2_count_data +'" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on l2time_sequence'+ l2_count_data +'"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_sequence_row(\'l2item_new_sequence_' + l2_count_data + '\');"></span></a></div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    l2_count_data++;
    return html;
}
$(function() {
    //Add more item
    $('#l2add_new_sequence_of_events_updates').click(function() {
        item_html = l2_add_sequence_events();
        $('#l2add_sequence_of_events').append(item_html);
        $('.l2time_sequence_data').timepicker({
            defaultTime: '',
            minuteStep: 1,
            showMeridian: false
        });

        $('.addtime_data1').click(function(){
         var id=$(this).attr('id');
         $('.'+id+'.input-group-addon').click();
     });

        $('input.addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        });
        $('#ail2formupdate').parsley();
        $(".chosen-select").chosen();
        tinymce.init({
            selector: '.tinyeditor',
            branding: false
        });
    });
    /*end item code*/
});
//remove new row
function delete_sequence_row(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}
/*sequence events updates end*/
/*L2 & L3 Medical Observations updates start*/
if (typeof xmds === 'undefined') {
    l2_count_meds = 1;
} else {
    l2_count_meds = xmds;
}

function l2_add_medical_observation() {
    var html = '';
    html += '<div class=""></div>';
    html += '<div class="col-md-6 dynamic-div" id="item_new_medical_observation_' + l2_count_meds + '">';
    html += '<div class="form-group"><div class="col-md-6"><label>Medical observation taken</label></div>';
    html += '<div class="col-md-6"><div class="radio-group"><div class="radio-inline">';
    html += '<label><input name="l2_medical_observation_taken' + l2_count_meds + '[]" class="" value="Yes" checked="checked" type="radio" data-parsley-multiple="l2_sanction_required">Yes</label></div>';
    html += '<div class="radio-inline"><label><input name="l2_medical_observation_taken' + l2_count_meds + '[]" class="" value="No" type="radio" data-parsley-multiple="l2_sanction_required">No</label>';
    html += '</div></div></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Medical observations after xx minutes</label></div>';
    html += '<div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control aaiaddtime addtime_data1 l2time_sequence_medical timer-sty" name="l2medical_observations_after_minutes[]" id="medical_observation'+ l2_count_meds +'" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on medical_observation'+ l2_count_meds +'"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Time</label></div>';
    html += '<div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control aaiaddtime addtime_data1 l2time_sequence_medical timer-sty" name="l2time_medical[]" id="time_medical'+l2_count_meds+'" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on time_medical'+l2_count_meds+'"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Observation taken by</label></div>';
    html += '<div class="col-md-6 ">';
    html += '<select data-parsley-errors-container="#errors-' + l2_count_meds + '" class="form-control chosen-select" id="' + l2_count_meds + '" name="l2Observation_taken_by[]"><option value=""> Select user </option>';
    $.each(bambooNfcUsers, function(i, elem) {
        console.log(elem);
        html += '<option value="' + elem['user_type'] + '_' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Comments</label></div>';
    html += '<div class="col-md-6 m-t-3"><textarea id="comments" class="form-control timer-sty" placeholder="Comments" name="l2comments_mo[]"></textarea></div></div>';
    html += '<div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_medical_observation(\'item_new_medical_observation_' + l2_count_meds + '\');"></span></a></div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    l2_count_meds++;
    return html;
}
$(function() {
    //Add more item
    $('#l2add_new_medical_observations').click(function() {
        item_html = l2_add_medical_observation();
        $('#add_medical_observation').append(item_html);
        $('.l2time_sequence_medical').timepicker({
            defaultTime: '',
            minuteStep: 1,
            showMeridian: false
        });

        $('.addtime_data1').click(function(){
         var id=$(this).attr('id');
         $('.'+id+'.input-group-addon').click();
     });

        $('input.addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        });
        $('#ail2formupdate').parsley();
        $(".chosen-select").chosen();
        tinymce.init({
            selector: '.tinyeditor',
            branding: false
        });
    });
    /*end item code*/
});
//remove new row
function delete_medical_observation(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}
/*Medical Observations updates end*/
/*Sequence of events updates start*/
if (typeof xsix === 'undefined') {
    count_data_six = 1;
} else {
    count_data_six = xsix;
}

function l6_add_sequence_of_events() {
    //console.log(bambooNfcUsers);
    var html = '';
    html += '<div class=""></div>';
    html += '<div class="col-md-6 dynamic-div" id="item_new_sequence_six_' + count_data_six + '">';
    html += '<div class="form-group"><div class="col-md-6"><label>Sequence Number</label></div>';
    html += '<div class="col-md-6"><input value="S'+count_data_six+'" name="l6sequence_number[]" class="red form-control" type="text" /></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>Who raised Complaint</label></div>';
    html += '<div class="col-md-6">';
    html += '<select data-parsley-errors-container="#errors-' + count_data_six + '" class="form-control chosen-select input-textar-style" id="' + count_data_six + '" name="l6who_raised_complaint[]"><option value=""> Select user </option>';
    $.each(bambooNfcUsers, function(i, elem) {
        console.log(elem);
        html += '<option value="' + elem['user_type'] + '_' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select></div></div>';
    html += '<div class="form-group"><div class="col-md-6"><label>What happened / what was done (include Senior Cover instructions)</label></div>';
    html += '<div class="col-md-6 m-t-3"><textarea id="what_happned" class="form-control timer-sty input-textar-style"  placeholder="Daily action outcome"  name="l6what_happened[]"></textarea></div></div>';

    html += '<div class="form-group"><div class="col-md-6"><label>Date</label></div>';
    html += '<div class="col-md-6 m-t-3">';
    html += '<div class="input-group input-append">';
    html += '<input type="text" autocomplete="off" class="form-control addtime_data timer-sty" required="true" name="l6sequence_date[]" id="safe_addtime_data" value="">';
    html += '<span class="input-group-addon add-on"><i class="fa fa-calendar" id="l7date_safeguarding_lbl"></i></span>';
    html += '</div><span id="errors-containerdate_of_disclosure"></span>';
    html += '</div>';
    html += '</div>';


    html += '<div class="form-group"><div class="col-md-6"><label>Time</label></div>';
    html += '<div class="col-md-6 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control aaiaddtime addtime_data1 l6time_safeguard_data timer-sty" name="l6time_sequence[]" id="l6time_safeguard'+count_data_six+'" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on l6time_safeguard'+count_data_six+'"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border del-btn-form"><span class="glyphicon glyphicon-trash" onclick="delete_safeguard_row(\'item_new_sequence_six_' + count_data_six + '\');"></span></a></div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    count_data_six++;
    return html;
}
$(function() {
    //Add more item
    $('#l6_add_new_sequence_of').click(function() {
        item_html = l6_add_sequence_of_events();
        $('#l6_add_sequence_data').append(item_html);
        $('.l6time_safeguard_data').timepicker({
            defaultTime: '',
            minuteStep: 1,
            showMeridian: false
        });
        $('.addtime_data1').click(function(){
            var id=$(this).attr('id');
            $('.'+id+'.input-group-addon').click();
        });
        $('input.addtime_data').datepicker({
           format: 'dd/mm/yyyy',
           autoclose: true,
           endDate: '+0d',
           startDate: new Date("01/01/1970"),
       });
        $('#ail7formupdate').parsley();
        $(".chosen-select").chosen();
        tinymce.init({
            selector: '.tinyeditor',
            branding: false
        });
    });
    /*end item code*/
});
//remove new row
function delete_sequence_six_row(del_id) {
    var delete_meg = "Are you sure want to delete?";
    BootstrapDialog.show({
        title: 'Alert',
        message: delete_meg,
        buttons: [{
            label: 'Cancel',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: 'Ok',
            action: function(dialog) {
                $('#' + del_id).remove();
                //count current item
                dialog.close();
            }
        }]
    });
}
/*Sequence of events updates end*/
/*nikunj ghelani for body map l5 process*/

$(document).ready(function(){
	
	$(".hmb").click(function(){
		$(this).toggleClass("active");
	});


	$(".hmb").each(function(){ 
		var classArray = $(this).attr('class').split(" ");
		var clas = classArray[1];
		$(this).click(function(){
			if($(this).hasClass('active')){
				$("li[data-hmb='"+ clas +"']").addClass('active');
			} else {
				$("li[data-hmb='"+ clas +"']").removeClass('active');
			}
		});
		
	});

	

});


/*************END HERE*****************************/

