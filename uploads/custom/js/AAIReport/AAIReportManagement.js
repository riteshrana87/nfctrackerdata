$(document).ready(function () {
/*18-2-19 nikunj code for filter regarding aai report*/ 
var today = new Date(); 
$("#care_home_1").change(function(){
            comparison_of_number_of_incident_between_care_homes();
       
    
});

$("#care_home_3").change(function(){
	
            number_of_type_of_incident_by_yp_over();
       
    
});

$("#yp_id_3").change(function(){
            number_of_type_of_incident_by_yp_over();
       
    
});
$("#aai_report_from_date_3").datepicker({
        
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        
        var minDate = new Date(selected.date.valueOf());
        $('#aai_report_to_date_3').datepicker('setStartDate', minDate);
            number_of_type_of_incident_by_yp_over();
       
        
    });
	$("#aai_report_to_date_3").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            //alert('adf');
            var maxDate = new Date(selected.date.valueOf());
            $('#aai_report_from_date_3').datepicker('setEndDate', maxDate);
            number_of_type_of_incident_by_yp_over();
        

    });
	
$("#care_home_9").change(function(){
	
            number_of_complaints_by_carehome();
       
    
});

$("#aai_report_from_date_9").datepicker({
        
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        
        var minDate = new Date(selected.date.valueOf());
        $('#aai_report_to_date_9').datepicker('setStartDate', minDate);
            number_of_complaints_by_carehome();
       
        
    });
	$("#aai_report_to_date_9").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            //alert('adf');
            var maxDate = new Date(selected.date.valueOf());
            $('#aai_report_from_date_9').datepicker('setEndDate', maxDate);
            number_of_complaints_by_carehome();
        

    });
	
	$("#care_home_10").change(function(){
	
            numer_of_safeguarding_occurences_by_yp_and_carehome();
       
    
});

$("#yp_id_10").change(function(){
            numer_of_safeguarding_occurences_by_yp_and_carehome();
       
    
});
$("#aai_report_from_date_10").datepicker({
        
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        
        var minDate = new Date(selected.date.valueOf());
        $('#aai_report_to_date_10').datepicker('setStartDate', minDate);
            numer_of_safeguarding_occurences_by_yp_and_carehome();
       
        
    });
	$("#aai_report_to_date_10").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            //alert('adf');
            var maxDate = new Date(selected.date.valueOf());
            $('#aai_report_from_date_10').datepicker('setEndDate', maxDate);
            numer_of_safeguarding_occurences_by_yp_and_carehome();
        

    });
	
	
	$("#staff_name_4").change(function(){
				numner_and_level_of_incidents_by_staff_member_over_time();
	});


$("#aai_report_from_date_4").datepicker({
        
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        
        var minDate = new Date(selected.date.valueOf());
        $('#aai_report_to_date_4').datepicker('setStartDate', minDate);
            numner_and_level_of_incidents_by_staff_member_over_time();
       
        
    });
	$("#aai_report_to_date_4").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            //alert('adf');
            var maxDate = new Date(selected.date.valueOf());
            $('#aai_report_from_date_4').datepicker('setEndDate', maxDate);
            numner_and_level_of_incidents_by_staff_member_over_time();
        

    });
	
	$("#care_home_5").change(function(){
		
				getnumber_of_sactions();
	});
	
	$("#yp_id_5").change(function(){
				getnumber_of_sactions();
	});


$("#aai_report_from_date_5").datepicker({
        
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        
        var minDate = new Date(selected.date.valueOf());
        $('#aai_report_to_date_5').datepicker('setStartDate', minDate);
            getnumber_of_sactions();
       
        
    });
	$("#aai_report_to_date_5").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            //alert('adf');
            var maxDate = new Date(selected.date.valueOf());
            $('#aai_report_from_date_5').datepicker('setEndDate', maxDate);
            getnumber_of_sactions();
        

    });
	
	$("#care_home_8").change(function(){
		
				number_of_complaints_by_yp();
	});
	
	$("#yp_id_8").change(function(){
				number_of_complaints_by_yp();
	});


$("#aai_report_from_date_8").datepicker({
        
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        
        var minDate = new Date(selected.date.valueOf());
        $('#aai_report_to_date_5').datepicker('setStartDate', minDate);
            number_of_complaints_by_yp();
       
        
    });
	$("#aai_report_to_date_8").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            //alert('adf');
            var maxDate = new Date(selected.date.valueOf());
            $('#aai_report_from_date_5').datepicker('setEndDate', maxDate);
            number_of_complaints_by_yp();
        

    });

$("#aai_report_from_date_1").datepicker({
        
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        
        var minDate = new Date(selected.date.valueOf());
        $('#aai_report_to_date_1').datepicker('setStartDate', minDate);
            comparison_of_number_of_incident_between_care_homes();
       
        
    });
	
	

    $("#aai_report_to_date_1").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            //alert('adf');
            var maxDate = new Date(selected.date.valueOf());
            $('#aai_report_from_date_1').datepicker('setEndDate', maxDate);
            comparison_of_number_of_incident_between_care_homes();
        

    });
    
    $('.main-content').css('min-height', $(window).height() + 100);
        Highcharts.setOptions({
            colors: ['#264e6e', '#63b1f0', '#2E8B57', '#808000', '#008B8B', '#2F4F4F', '#A0522D', '#9E69AF', '#778899']
        });
		
		
        comparison_of_number_of_incident_between_care_homes();
        number_of_type_of_incident_by_yp_over();
        numner_and_level_of_incidents_by_staff_member_over_time();
        getnumber_of_sactions();
        number_of_complaints_by_yp();
        number_of_complaints_by_carehome();
        numer_of_safeguarding_occurences_by_yp_and_carehome();
        
        
});

function comparison_of_number_of_incident_between_care_homes()
{
    var care_home=$("#care_home_1").val();
    var from_date=$("#from_date_1").val();
    var to_date=$("#to_date_1").val();
    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/comparison_of_number_of_incident_between_care_homes",
        data: {'care_home': care_home, 'from_date': from_date, 'to_date': to_date},
        beforeSend: function () {
        },
        success: function (datacoloum) {
            
            var newData = $.parseJSON(datacoloum);
            Totalcomparison_of_number_of_incident_between_care_homes(newData);
            
        }
    });
}
function Totalcomparison_of_number_of_incident_between_care_homes(data) {
    
    $('#comparison_of_number_of_incident_between_care_homes').highcharts({
        chart: {
        type: 'column'
    },
    title: {
        text: 'Numbers Of Incidents by Type'
    },
    subtitle: {
        //text: 'Source: WorldClimate.com'
    },
     // xAxis: {
        // categories: [
            // 'Jan',
            // 'Feb',
            // 'Mar',
            // 'Apr',
            // 'May',
            // 'Jun',
            // 'Jul',
            // 'Aug',
            // 'Sep',
            // 'Oct',
            // 'Nov',
            // 'Dec'
        // ],
        // crosshair: true
    // } 
      xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        } ,
    yAxis: {
        min: 0,
        title: {
            text: 'Rainfall (mm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
     // series: [{
        // name: 'Jan',
        // data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]


    // }] 
    series: data.seriesdata
    });
}

function number_of_type_of_incident_by_yp_over()
{
    var care_home=$("#care_home_3").val();
    var from_date=$("#from_date_3").val();
    var to_date=$("#to_date_3").val();
    var yp_id=$("#yp_id_3").val();
    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/number_of_type_of_incident_by_yp_over",
        data: {'care_home': care_home, 'from_date': from_date, 'yp_id': yp_id, 'to_date': to_date},
        beforeSend: function () {
        },
        success: function (datacoloum) {
            
            var newData = $.parseJSON(datacoloum);
            Totalnumber_of_type_of_incident_by_yp_over(newData);
            
        }
    });
}
function Totalnumber_of_type_of_incident_by_yp_over(data) {
    
    $('#number_of_type_of_incident_by_yp_over').highcharts({
        chart: {
        type: 'column'
    },
    title: {
        text: 'Numbers Of Incidents by Type'
    },
    subtitle: {
        //text: 'Source: WorldClimate.com'
    },
     // xAxis: {
        // categories: [
            // 'Jan',
            // 'Feb',
            // 'Mar',
            // 'Apr',
            // 'May',
            // 'Jun',
            // 'Jul',
            // 'Aug',
            // 'Sep',
            // 'Oct',
            // 'Nov',
            // 'Dec'
        // ],
        // crosshair: true
    // } 
      xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        } ,
    yAxis: {
        min: 0,
        title: {
            text: 'Rainfall (mm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
     // series: [{
        // name: 'Jan',
        // data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]


    // }] 
    series: data.seriesdata
    });
}

function numner_and_level_of_incidents_by_staff_member_over_time()
{
    var staff_member=$("#staff_name_4").val();
    var from_date=$("#from_date_4").val();
    var to_date=$("#to_date_4").val();
    
    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/numner_and_level_of_incidents_by_staff_member_over_time",
        data: {'staff_member': staff_member, 'from_date': from_date, 'to_date': to_date},
        beforeSend: function () {
        },
        success: function (datacoloum) {
            
            var newData = $.parseJSON(datacoloum);
            Totalnumner_and_level_of_incidents_by_staff_member_over_time(newData);
            
        }
    });
}
function Totalnumner_and_level_of_incidents_by_staff_member_over_time(data) {
    
    $('#numner_and_level_of_incidents_by_staff_member_over_time').highcharts({
        chart: {
        type: 'column'
    },
    title: {
        text: 'Numbers Of Incidents by Type'
    },
    subtitle: {
        //text: 'Source: WorldClimate.com'
    },
     // xAxis: {
        // categories: [
            // 'Jan',
            // 'Feb',
            // 'Mar',
            // 'Apr',
            // 'May',
            // 'Jun',
            // 'Jul',
            // 'Aug',
            // 'Sep',
            // 'Oct',
            // 'Nov',
            // 'Dec'
        // ],
        // crosshair: true
    // } 
      xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        } ,
    yAxis: {
        min: 0,
        title: {
            text: 'Rainfall (mm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
     // series: [{
        // name: 'Jan',
        // data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]


    // }] 
    series: data.seriesdata
    });
}



function getnumber_of_sactions()
{
    
    var care_home=$("#care_home_5").val();
    var from_date=$("#from_date_5").val();
    var to_date=$("#to_date_5").val();
    var yp_id=$("#yp_id_5").val();
    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/number_of_sactions",
		 dataType: 'json',
        data: {'care_home': care_home, 'from_date': from_date, 'yp_id': yp_id, 'to_date': to_date},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            if(value.care_home_name){
                var care_home_data = value.care_home_name;
            }
            number_of_sactions(inc_data,care_home,care_home_data);
        }
    });
}

//Count Incident graph
function number_of_sactions(inc_data,care_home,care_home_data) {
	//alert(inc_data);
    $('#number_of_sactions').highcharts({

    /*     chart: {
        styledMode: true
    },

    title: {
        text: 'NUMBER OF INCIDENTS BY TYPE'
    },
    series: [{
        type: 'pie',
        allowPointSelect: false,
        keys: ['name', 'y', 'selected', 'sliced'],
        data: [
            ['Other', inc_data, false],
            ['Ridge Farm Crisis', care_home, false],
        ],
        showInLegend: true
    }]

    */

    chart: {
        //plotBackgroundColor: null,
        //plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Number of incidents by Care home'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'Other',
            y: inc_data,
            sliced: true,
            selected: true
        }, {
            name: care_home_data,
            y: care_home
        }]
    }]
 });
}



function number_of_complaints_by_yp()
{
    var staff_member=$("#staff_name_4").val();
    var from_date=$("#from_date_4").val();
    var to_date=$("#to_date_4").val();
    
    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/number_of_complaints_by_yp",
        data: {'staff_member': staff_member, 'from_date': from_date, 'to_date': to_date},
        beforeSend: function () {
        },
        success: function (datacoloum) {
            
            var newData = $.parseJSON(datacoloum);
            Totalnumber_of_complaints_by_yp(newData);
            
        }
    });
}
function Totalnumber_of_complaints_by_yp(data) {
    
    $('#number_of_complaints_by_yp').highcharts({
        chart: {
        type: 'column'
    },
    title: {
        text: 'Numbers Of Complaints by YP'
    },
    subtitle: {
        //text: 'Source: WorldClimate.com'
    },
     // xAxis: {
        // categories: [
            // 'Jan',
            // 'Feb',
            // 'Mar',
            // 'Apr',
            // 'May',
            // 'Jun',
            // 'Jul',
            // 'Aug',
            // 'Sep',
            // 'Oct',
            // 'Nov',
            // 'Dec'
        // ],
        // crosshair: true
    // } 
      xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        } ,
    yAxis: {
        min: 0,
        title: {
            text: 'Rainfall (mm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
     // series: [{
        // name: 'Jan',
        // data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]


    // }] 
    series: data.seriesdata
    });
}

function number_of_complaints_by_carehome()
{
    var care_home=$("#care_home_9").val();
    var from_date=$("#from_date_4").val();
    var to_date=$("#to_date_4").val();
    
    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/number_of_complaints_by_carehome",
        data: {'care_home': care_home, 'from_date': from_date, 'to_date': to_date},
        beforeSend: function () {
        },
        success: function (datacoloum) {
            
            var newData = $.parseJSON(datacoloum);
            Totalnumber_of_complaints_by_carehome(newData);
            
        }
    });
}
function Totalnumber_of_complaints_by_carehome(data) {
	
	
    
    $('#number_of_complaints_by_carehome').highcharts({
        chart: {
        type: 'column'
    },
    title: {
        text: 'Numbers Of Complaints by CareHome'
    },
    subtitle: {
        //text: 'Source: WorldClimate.com'
    },
     // xAxis: {
        // categories: [
            // 'Jan',
            // 'Feb',
            // 'Mar',
            // 'Apr',
            // 'May',
            // 'Jun',
            // 'Jul',
            // 'Aug',
            // 'Sep',
            // 'Oct',
            // 'Nov',
            // 'Dec'
        // ],
        // crosshair: true
    // } 
      xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        } ,
    yAxis: {
        min: 0,
        title: {
            text: 'Rainfall (mm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
     // series: [{
        // name: 'Jan',
        // data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]


    // }] 
    series: data.seriesdata
    });
}

function numer_of_safeguarding_occurences_by_yp_and_carehome()
{
    var care_home=$("#care_home_10").val();
    var yp_id=$("#yp_id_10").val();
    var from_date=$("#from_date_10").val();
    var to_date=$("#to_date_10").val();
    
    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/numer_of_safeguarding_occurences_by_yp_and_carehome",
        data: {'care_home': care_home,'yp_id': yp_id, 'from_date': from_date, 'to_date': to_date},
        beforeSend: function () {
        },
        success: function (datacoloum) {
            
            var newData = $.parseJSON(datacoloum);
            Totalnumer_of_safeguarding_occurences_by_yp_and_carehome(newData);
            
        }
    });
}
function Totalnumer_of_safeguarding_occurences_by_yp_and_carehome(data) {
	
	
    
    $('#numer_of_safeguarding_occurences_by_yp_and_carehome').highcharts({
        chart: {
        type: 'column'
    },
    title: {
        text: 'Numbers Of safeguarding occurences by yp and carehome'
    },
    subtitle: {
        //text: 'Source: WorldClimate.com'
    },
     // xAxis: {
        // categories: [
            // 'Jan',
            // 'Feb',
            // 'Mar',
            // 'Apr',
            // 'May',
            // 'Jun',
            // 'Jul',
            // 'Aug',
            // 'Sep',
            // 'Oct',
            // 'Nov',
            // 'Dec'
        // ],
        // crosshair: true
    // } 
      xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        } ,
    yAxis: {
        min: 0,
        title: {
            text: 'Rainfall (mm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
     // series: [{
        // name: 'Jan',
        // data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]


    // }] 
    series: data.seriesdata
    });
}