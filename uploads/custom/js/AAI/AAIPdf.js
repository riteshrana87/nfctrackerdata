/*Code by Dhara Start*/
$(document).ready(function() {
    check_incident_linked();
    var end = new Date();
    $("#yp_dob").datepicker({
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    });
    /* l1 initiate start */
    l1_location_change();
    check_l1_debrief();
    check_yp_treatment_l1();
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
	  $(".l4_date_yp_missing").datepicker({
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l4_date_yp_return').datepicker('setStartDate', maxDate);
        calculate_total_duration_l4();
    });
    $(".l4_date_yp_return").datepicker({
        endDate: end,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.l4_date_yp_missing').datepicker('setEndDate', maxDate);
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
    if ((typeof CREATE_MODE !== 'undefined' && CREATE_MODE == 1) || (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 1)) {
        $('#main_form_link').click();
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 2) {
        $('#incident_form_link').click();
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 3) {
        $('#incident_l1_form_link').click();
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 4) {
        $('#incident_l2_l3_form_link').click();
    }
    if (typeof EDIT_MODE !== 'undefined' && EDIT_MODE == 7) {
        $('#incident_l6_form_link').click();
    }
});
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
            alert("incident should not be conclude before incident started");
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
    check_l5_debrief();
    $(".l5_debrief_offer").change(function() {
        check_l5_debrief();
    });
    $(".l5_debrief_accept").change(function() {
        check_l5_debrief();
    });
});

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

function check_l5_debrief() {
    var l5_debrief_offer = $("input[name='l5_debrief_offer']:checked").val();
    if (l5_debrief_offer == 'Yes') {
        $('#div_l5_debrief_accept').show();
        var l5_debrief_accept = $("input[name='l5_debrief_accept']:checked").val();
        if (l5_debrief_accept == 'Yes') {
            $('#div_l5_debrief_comments').show();
        } else {
            $('#div_l5_debrief_comments').hide();
        }
    } else {
        $('#div_l5_debrief_accept').hide();
        $('#div_l5_debrief_comments').hide();
    }
}
/*ritesh rana start*/
/* l7 functions Starts */
check_yp_disclosure_or_concern_l7();
check_l6_debrief();
$(".l7_disclosure_or_concern").change(function() {
    check_yp_disclosure_or_concern_l7();
});

function check_yp_disclosure_or_concern_l7() {
    var l7_yp_treatment = $("input[name='is_disclosure_staff']:checked").val();
    if (l7_yp_treatment == 'Yes') {
       // $('#div_l7_restricted_access').show();
        //$('#div_allowed_access').show();
    } else {
        //$('#div_l7_restricted_access').remove();
       // $('#div_allowed_access').remove();
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
    } else {
        $('#div_l7_lnformed_by_social_worker').hide();
        $('#div_l7_who_was_informed_social_worker').hide();
        $('#div_l7_how_the_information_was_send_sw').hide();
        $('#div_l7_date_lnformed_social_worker').hide();
        $('#div_l7_time_informed_sw').hide();
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
    count = 1;
} else {
    count = l4sq;
}

function l4add_sequence_event() {
    var html = '';
    html += '<div class="clearfix"></div>';
    html += '<div class="row adding-form-box" id="item_new_' + count + '"><div class="col-md-12"><div class="col-md-3"><label>Sequence Number</label></div><div class="col-md-4"><input name="l4seq_sequence_number[]" class="red form-control input-textar-style" type="text" /></div></div><div class="col-md-12"><div class="col-md-3"><label>Who(staff full name)</label></div><div class="col-md-4"><input class="red form-control input-textar-style" name="l4seq_who[]"  type="text" /></div></div><div class="col-md-12"><div class="col-md-3"><label>What Happned / what was done(include senior cover instruction)</label></div><div class="col-sm-4"><textarea id="what_happned" class="form-control input-textar-style"  placeholder="Attend Health appointments"  name="l4seq_what_happned[]"></textarea></div></div><div class="col-md-12"><div class="col-md-3"><label>Date</label></div><div class="col-md-4"><input class="form-control seq_adddate sq_addtime_data input-textar-style" name="l4seq_date_event[]" id="date_event" type="text" /></div></div><div class="col-md-12"><div class="col-md-3"><label>Time</label></div><div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker"><input type="text" class="red form-control  l4_addtime_sequence addtime_data1 timer-sty" name="l4seq_time_event[]" id="" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly=""><span class="input-group-addon add-on time_event"><i class="fa fa-clock-o"></i></span></div></div></div><div class="col-md-12"><div class="col-md-3"><label>What Happned / what was done(include senior cover instruction)</label></div><div class="col-md-4"><textarea id="communication" class="form-control input-textar-style"  placeholder="All Communication Detail"  name="l4seq_communication[]"></textarea></div></div><div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_sq_review_row(\'item_new_sq_' + count + '\');"></span></a></div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    count++;
    return html;
}
$(function() {
    //Add more item
    $('#l4add_new_seq_event').click(function() {
        item_html = l4add_sequence_event();
        $('#l4add_seqevent').append(item_html);
        $('.l4_addtime_sequence').timepicker({
            defaultTime: '',
            minuteStep: 5
        });
        $('input.sq_addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
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
function delete_cpt_review_row_health(del_id) {
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
    var dt = new Date();
    var twoDigitMonth = ((dt.getMonth().length + 1) === 1) ? (dt.getMonth() + 1) : '0' + (dt.getMonth() + 1);
    var data_mail = '1';
    $.ajax({
        type: "POST",
        url: baseurl + 'AAI/send_notification_social_worker/',
        data: {
            data_mail: data_mail
        },
        success: function(html) {
            alert('done');
            $('#calculte_notification').val(dt.getDate() + "/" + twoDigitMonth + "/" + dt.getFullYear() + ":" + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds());
        }
    });
});
$('#send_notification_missing_team').click(function() {
    var dt = new Date();
    var twoDigitMonth = ((dt.getMonth().length + 1) === 1) ? (dt.getMonth() + 1) : '0' + (dt.getMonth() + 1);
    var data_mail = '1';
    $.ajax({
        type: "POST",
        url: baseurl + 'AAI/send_notification_missing_team/',
        data: {
            data_mail: data_mail
        },
        success: function(html) {
            alert('done');
            $('#calculte_notification').val(dt.getDate() + "/" + twoDigitMonth + "/" + dt.getFullYear() + ":" + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds());
        }
    });
});
if (typeof x === 'undefined') {
    count = 1;
} else {
    count = x;
}

function add_new_person_informed_yp_missing() {
    var html = '';
    html += '<div class="clearfix"></div>';
    html += '<div class="row adding-form-box" id="item_new_' + count + '">';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Persons Informed</label></div>';
    html += '<div class="col-md-4">';
    html += '<select class="chosen-select form-control input-textar-style" name="person_informed_missing_team[]" id="person_informed_missing_team">';
    html += '<option value="">Select Persons Infromed</option>';
    $.each(persons_infromed, function(i, elem) {
        html += '<option value="' + elem['value'] + '">' + elem['label'] + '</option>';
    });
    html += '</select></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Name Of Person Informed</label></div>';
    html += '<div class="col-md-4"><input name="name_of_person_informed_missing[]" class="red form-control input-textar-style" type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Badge Number</label></div>';
    html += '<div class="col-md-4"><input class="red form-control input-textar-style" name="badge_number_person_missing[]" type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Contact Number</label></div>';
    html += '<div class="col-md-4"><input class="red form-control input-textar-style" name="contact_number_person_missing[]" type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Contact Email</label></div>';
    html += '<div class="col-md-4"><input class="red form-control input-textar-style" name="contact_email_person_missing[]" type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Informed By</label></div>';
    html += '<div class="col-md-4"><input class="red form-control input-textar-style" name="informed_by_person_missing[]" type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Date</label></div>';
    html += '<div class="col-md-4"><input class="form-control seq_adddate seq_addtime_data timer-sty" name="date_event[]" id="date_event" type="text" />';
    html += '</div></div><div class="col-md-12"><div class="col-md-3"><label>Time</label></div>';
    html += '<div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control  l4_addtime_yp_missing addtime_data1 timer-sty" name="time_event[]" id="" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on time_event"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_health(\'item_new_' + count + '\');"></span></a></div>';
    html += '<div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    count++;
    return html;
}
$(function() {
    //Add more item
    $('#add_new_person_informed_yp_missing').click(function() {
        item_html = add_new_person_informed_yp_missing();
        $('#add_person_yp_missing').append(item_html);
        $('.l4_addtime_yp_missing').timepicker({
            defaultTime: '',
            minuteStep: 5
        });
        $('input.seq_addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
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
    html += '<div class="clearfix"></div>';
    html += '<div class="row adding-form-box" id="item_new_pret' + count_pr + '">';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Persons Informed</label></div>';
    html += '<div class="col-md-4">';
    html += '<select class="chosen-select form-control input-textar-style" name="person_informed_return_team[]" id="person_informed_return_team">';
    html += '<option value="">Select Persons Infromed</option>';
    $.each(persons_infromed, function(i, elem) {
        html += '<option value="' + elem['value'] + '">' + elem['label'] + '</option>';
    });
    html += '</select></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Name Of Person Informed</label></div>';
    html += '<div class="col-md-4">';
    html += '<input name="name_of_person_informed_return[]" class="red form-control input-textar-style" type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Badge Number</label></div>';
    html += '<div class="col-md-4"><input class="red form-control input-textar-style" name="badge_number_person_return[]" type="text" />';
    html += '</div></div><div class="col-md-12"><div class="col-md-3"><label>Contact Number</label></div>';
    html += '<div class="col-md-4">';
    html += '<input class="red form-control input-textar-style" name="contact_number_person_return[]"  type="text" />';
    html += '</div></div><div class="col-md-12"><div class="col-md-3"><label>Contact Email</label></div>';
    html += '<div class="col-md-4">';
    html += '<input class="red form-control input-textar-style" name="contact_email_person_return[]"  type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Informed By</label></div>';
    html += '<div class="col-md-4">';
    html += '<input class="red form-control input-textar-style" name="informed_by_person_return[]" type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Date</label></div>';
    html += '<div class="col-md-4">';
    html += '<input class="form-control seq_adddate seq_addtime_data input-textar-style" name="person_return_date_event[]" id="date_event" type="text" />';
    html += '</div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Time</label></div>';
    html += '<div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control  l4_addtime_yp_return addtime_data1 timer-sty" name="person_return_time_event[]" id="" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on time_event"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="col-md-12 add_items_field mb44 del-btn-form">';
    html += '<a class="btn btn-default btn_border">';
    html += '<span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_health(\'item_new_pret' + count_pr + '\');"></span></a>';
    html += '</div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    count++;
    return html;
}
$(function() {
    //Add more item
    $('#add_new_person_informed_yp_return').click(function() {
        item_html = add_new_person_informed_yp_return();
        $('#add_person_yp_return').append(item_html);
        $('.l4_addtime_yp_return').timepicker({
            defaultTime: '',
            minuteStep: 5
        });
        $('input.seq_addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
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
    html += '<div class="clearfix"></div>';
    html += '<div class="row adding-form-box" id="item_new_safeguarding_' + count_data + '">';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Sequence Number</label></div>';
    html += '<div class="col-md-4"><input name="l7sequence_number[]" class="red form-control input-textar-style" type="text" /></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Update By</label></div>';
    html += '<div class="col-md-4">';
    html += '<select data-parsley-errors-container="#errors-' + count_data + '" class="form-control chosen-select" id="' + count_data + '" name="l7update_by[]"><option value=""> Select user </option>';
    $.each(bambooNfcUsers, function(i, elem) {
        console.log(elem);
        html += '<option value="' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Daily action taken</label></div>';
    html += '<div class="col-md-4"><textarea id="what_happned" class="form-control input-textar-style"  placeholder="Daily action outcome"  name="l7daily_action_taken[]"></textarea></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Daily action outcome</label></div>';
    html += '<div class="col-md-4"><textarea id="what_happned" class="form-control input-textar-style"  placeholder="Daily action outcome" name="l7daily_action_outcome[]"></textarea></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Supporting documents</label></div>';
    html += '<div class="col-md-4"><input multiple="true" type="file" id="l7supporting_documents" class="form-control file-attch" placeholder="Daily action outcome" name="l7supporting_documents' + count_data + '[]" /></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Date</label></div>';
    html += '<div class="col-md-4 m-t-3"><input class="form-control adddate addtime_data timer-sty" name="l7date_safeguarding[]" id="safe_addtime_data" type="text" /></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Time</label></div>';
    html += '<div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control addtime addtime_data1 time_safeguard timer-sty" name="l7time_safeguard[]" id="time_safeguard" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span></div></div></div>';
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
        $('.time_safeguard').timepicker({
            defaultTime: '',
            minuteStep: 5
        });
        $('input.addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
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
    html += '<div class="clearfix"></div>';
    html += '<div class="row adding-form-box" id="item_new_' + count + '"><div class="col-md-12"><div class="col-md-3"><label>Sequence Number</label></div><div class="col-md-4"><input name="sequence_number[]" class="red form-control input-textar-style" type="text" /></div></div><div class="col-md-12"><div class="col-md-3"><label>Who(staff full name)</label></div><div class="col-md-4"><input class="red form-control input-textar-style" name="who[]"  type="text" /></div></div><div class="col-md-12"><div class="col-md-3"><label>What Happned / what was done(include senior cover instruction)</label></div><div class="col-md-4"><textarea id="what_happned" class="form-control input-textar-style"  placeholder="Attend Health appointments"  name[]="what_happned"></textarea></div></div><div class="col-md-12"><div class="col-md-3"><label>Date</label></div><div class="col-md-4"><input class="form-control adddate addtime_data input-textar-style" name="date_event[]" id="date_event" type="text" /></div></div><div class="col-md-12"><div class="col-md-3"><label>Time</label></div><div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker"><input type="text" class="red form-control  addtime addtime_data1 timer-sty" name="time_event" id="time_event" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly=""><span class="input-group-addon add-on time_event"><i class="fa fa-clock-o"></i></span></div></div></div><div class="col-md-12"><div class="col-md-3"><label>What Happned / what was done(include senior cover instruction)</label></div><div class="col-md-9"><textarea id="communication" class="form-control input-textar-style"  placeholder="All Communication Detail"  name="communication[]"></textarea></div></div><div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border"><span class="glyphicon glyphicon-trash" onclick="delete_cpt_review_row_health(\'item_new_' + count + '\');"></span></a></div><div class="clearfix"></div></div></div>';
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
            minuteStep: 5
        });
        $('input.seq_addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
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
function delete_cpt_review_row_health(del_id) {
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
        var url_inv = baseurl + 'AAI/who_was_involved_in_incident/' + isq;
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
    html += '<div class="clearfix"></div>';
    html += '<div class="row adding-form-box" id="l2item_new_sequence_' + l2_count_data + '">';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Sequence Number</label></div>';
    html += '<div class="col-md-4"><input name="l2sequence_number[]" class="red form-control" type="text" /></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Who was involved in Incident</label></div>';
    html += '<div class="col-md-4 m-t-3">';
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
    html += '<div class="col-md-12"><div class="col-md-3"><label>Position</label></div>';
    html += '<div class="col-md-4">';
    html += '<select data-parsley-errors-container="#errors-' + l2_count_data + '" class="form-control chosen-select" id="' + l2_count_data + '" name="l2position[]"><option value=""> Select Position </option>';
    $.each(position_of_yp, function(i, elem) {
        html += '<option value="' + elem['value'] + '">' + elem['label'] + '</option>';
    });
    html += '</select></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Type</label></div>';
    html += '<div class="col-md-4">';
    html += '<select data-parsley-errors-container="#errors-' + l2_count_data + '" class="form-control chosen-select" id="' + l2_count_data + '" name="l2type[]"><option value=""> Select Type </option>';
    $.each(type, function(i, elem) {
        html += '<option value="' + elem['value'] + '">' + elem['label'] + '</option>';
    });
    html += '</select></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Comments</label></div>';
    html += '<div class="col-md-4"><textarea id="comments" class="form-control input-textar-style" placeholder="Comments"  name="l2comments[]"></textarea></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Time</label></div>';
    html += '<div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control addtime addtime_data1 l2time_sequence timer-sty" name="l2time_sequence[]" id="time_safeguard" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span></div></div></div>';
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
        $('.l2time_sequence').timepicker({
            defaultTime: '',
            minuteStep: 5
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
    html += '<div class="clearfix"></div>';
    html += '<div class="row adding-form-box" id="item_new_medical_observation_' + l2_count_meds + '">';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Medical observation taken</label></div>';
    html += '<div class="col-md-4"><div class="radio-group"><div class="radio-inline">';
    html += '<label><input name="l2_medical_observation_taken' + l2_count_meds + '[]" class="" value="Yes" checked="checked" type="radio" data-parsley-multiple="l2_sanction_required">Yes</label></div>';
    html += '<div class="radio-inline"><label><input name="l2_medical_observation_taken' + l2_count_meds + '[]" class="" value="No" type="radio" data-parsley-multiple="l2_sanction_required">No</label>';
    html += '</div></div></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Medical observations after xx minutes</label></div>';
    html += '<div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control addtime addtime_data1 l2time_sequence timer-sty" name="l2medical_observations_after_minutes[]" id="time_safeguard" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Time</label></div>';
    html += '<div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control addtime addtime_data1 l2time_sequence timer-sty" name="l2time_medical[]" id="time_safeguard" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Observation taken by</label></div>';
    html += '<div class="col-md-4 ">';
    html += '<select data-parsley-errors-container="#errors-' + l2_count_meds + '" class="form-control chosen-select" id="' + l2_count_meds + '" name="l2Observation_taken_by' + l2_count_meds + '[]"><option value=""> Select user </option>';
    $.each(bambooNfcUsers, function(i, elem) {
        console.log(elem);
        html += '<option value="' + elem['user_type'] + '_' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Comments</label></div>';
    html += '<div class="col-md-4 m-t-3"><textarea id="comments" class="form-control timer-sty" placeholder="Comments" name="l2comments_mo[]"></textarea></div></div>';
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
        $('.l2time_sequence').timepicker({
            defaultTime: '',
            minuteStep: 5
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
    html += '<div class="clearfix"></div>';
    html += '<div class="row adding-form-box" id="item_new_sequence_six_' + count_data_six + '">';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Sequence Number</label></div>';
    html += '<div class="col-md-4"><input name="l6sequence_number[]" class="red form-control" type="text" /></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Who raised Complaint</label></div>';
    html += '<div class="col-md-4">';
    html += '<select data-parsley-errors-container="#errors-' + count_data_six + '" class="form-control chosen-select input-textar-style" id="' + count_data_six + '" name="l6who_raised_complaint[]"><option value=""> Select user </option>';
    $.each(bambooNfcUsers, function(i, elem) {
        console.log(elem);
        html += '<option value="' + elem['user_type'] + '_' + elem['user_id'] + '"> ' + elem['first_name'] + ' ' + elem['last_name'] + '-' + elem['email'] + ' </option>';
    });
    html += '</select></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>What happened / what was done (include Senior Cover instructions)</label></div>';
    html += '<div class="col-md-4 m-t-3"><textarea id="what_happned" class="form-control timer-sty input-textar-style"  placeholder="Daily action outcome"  name="l6what_happened[]"></textarea></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Date</label></div>';
    html += '<div class="col-md-4"><input class="form-control adddate addtime_data input-textar-style" name="l6sequence_date[]" id="safe_addtime_data" type="text" /></div></div>';
    html += '<div class="col-md-12"><div class="col-md-3"><label>Time</label></div>';
    html += '<div class="col-md-4 m-t-3"><div class="input-group input-append bootstrap-timepicker">';
    html += '<input type="text" class="red form-control addtime addtime_data1 time_safeguard timer-sty" name="l6time_sequence[]" id="time_safeguard" placeholder="" value="" data-parsley-errors-container="#errors-containertime_event" readonly="">';
    html += '<span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span></div></div></div>';
    html += '<div class="col-md-12 add_items_field mb44 del-btn-form"><a class="btn btn-default btn_border del-btn-form"><span class="glyphicon glyphicon-trash" onclick="delete_safeguard_row(\'item_new_sequence_six_' + count_data_six + '\');"></span></a></div><div class="clearfix"></div></div></div>';
    html += '</div>';
    html += '</div>';
    count_data++;
    return html;
}
$(function() {
    //Add more item
    $('#l6_add_new_sequence_of').click(function() {
        item_html = l6_add_sequence_of_events();
        $('#l6_add_sequence_data').append(item_html);
        $('.time_safeguard').timepicker({
            defaultTime: '',
            minuteStep: 5
        });
        $('input.addtime_data').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
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