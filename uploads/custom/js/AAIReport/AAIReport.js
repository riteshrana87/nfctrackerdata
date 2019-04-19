$(document).ready(function () {
/*18-2-19 nikunj code for filter regarding aai report*/ 
var today = new Date(); 
$("#care_home").change(function(){
            getTotalDifData();
        getCountIncident();
        getRelatedToL2Form();
        getRelatedToL2AndL3Form();
        getRelatedToL3Form();
        getRelatedToL1Form();
        getRelatedToPoliceInvolvement();
        getRelatedToREG40Form();
        getRelatedTol7ladoForm();
        getRelatedTostatus_panding_for_each_incident_Form();
    
});

$("#yp_id").change(function(){
            getTotalDifData();
        getCountIncident();
        getRelatedToL2Form();
        getRelatedToL2AndL3Form();
        getRelatedToL3Form();
        getRelatedToL1Form();
        getRelatedToPoliceInvolvement();
        getRelatedToREG40Form();
        getRelatedTol7ladoForm();
        getRelatedTostatus_panding_for_each_incident_Form();
    
});

$("#location").change(function(){
            getTotalDifData();
        getCountIncident();
        getRelatedToL2Form();
        getRelatedToL2AndL3Form();
        getRelatedToL3Form();
        getRelatedToL1Form();
        getRelatedToPoliceInvolvement();
        getRelatedToREG40Form();
        getRelatedTol7ladoForm();
        getRelatedTostatus_panding_for_each_incident_Form();
    
});
$("#aai_report_from_date").datepicker({
        
         format: 'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {
        
        var minDate = new Date(selected.date.valueOf());
        $('#aai_report_to_date').datepicker('setStartDate', minDate);
            getTotalDifData();
        getCountIncident();
        getRelatedToL2Form();   
        getRelatedToL2AndL3Form();
        getRelatedToL3Form();
        getRelatedToL1Form();
        getRelatedToPoliceInvolvement();
        getRelatedToREG40Form();
        getRelatedTol7ladoForm();
        getRelatedTostatus_panding_for_each_incident_Form();
        
    });

    $("#aai_report_to_date").datepicker({
     //todayBtn:  1,
         format: 'dd/mm/yyyy',
        autoclose: true,
    })
        .on('changeDate', function (selected) {
            //alert('adf');
            var maxDate = new Date(selected.date.valueOf());
            $('#aai_report_from_date').datepicker('setEndDate', maxDate);
            getTotalDifData();
        getCountIncident();
        getRelatedToL2Form();
        getRelatedToL2AndL3Form();
        getRelatedToL3Form();
        getRelatedToL1Form();
        getRelatedToPoliceInvolvement();
        getRelatedToREG40Form();
        getRelatedTol7ladoForm();
        getRelatedTostatus_panding_for_each_incident_Form();

    });
    
    $('.main-content').css('min-height', $(window).height() + 100);
        Highcharts.setOptions({
            colors: ['#264e6e', '#63b1f0', '#2E8B57', '#808000', '#008B8B', '#2F4F4F', '#A0522D', '#9E69AF', '#778899']
        });
        getTotalDifData();
        getCountIncident();
        getRelatedToL2Form();
        getRelatedToL2AndL3Form();
        getRelatedToL3Form();
        getRelatedToL1Form();
        getRelatedToPoliceInvolvement();
        getRelatedToREG40Form();
        getRelatedTol7ladoForm();
        getRelatedTostatus_panding_for_each_incident_Form();
        
});

function getTotalDifData()
{
    var care_home=$("#care_home").val();
    var locationdata=$("#location").val();
    var yp_id=$("#yp_id").val();
    var from_date=$("#from_date").val();
    var to_date=$("#to_date").val();
   
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/DashboardReport",
        data: {'care_home': care_home, 'yp_id': yp_id, 'locationdata': locationdata, 'from_date': from_date, 'to_date': to_date},
        beforeSend: function () {
        },
        success: function (datacoloum) {
            
            var newData = $.parseJSON(datacoloum);
            TotalDifGraph(newData);
            TotalDifstatusGraph(newData);
        }
    });
}
function TotalDifGraph(data) {
    
    $('#container').highcharts({
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
            text: 'Type of Incidents'
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
function TotalDifstatusGraph(data) {
    
    $('#container_status').highcharts({
        chart: {
        type: 'column'
    },
    title: {
        text: 'Numbers Of Incidents by Status'
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
            text: 'Status'
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



/*
    @Author : Rana Ritesh
    @Desc   : AAI Report graph start
*/

//get Count Incident data
function getCountIncident()
{
    
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getNumberOfIncident",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            if(value.care_home_name){
                var care_home_data = value.care_home_name;
            }
            incGraph(inc_data,care_home,care_home_data);
        }
    });
}

//Count Incident graph
function incGraph(inc_data,care_home,care_home_data) {
    $('#count_incident').highcharts({

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
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Number of Incidents by Care Home'
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




//get Count Incident data
function getRelatedToL2Form()
{
    
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getRelatedToL2",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            var care_home_data = value.care_home_name;
                
            forml2Graph(inc_data,care_home,care_home_data);
        }
    });
}

//form l2 graph
function forml2Graph(inc_data,care_home,care_home_data) {
    $('#related_to_l2_form').highcharts({
chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Total Lenth of Time of PI excluding Laying'
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




//get Related To L2 & L3 Form
function getRelatedToL2AndL3Form()
{
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getRelatedToL2AndL3",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            var care_home_data = value.care_home_name;
            forml2AndL3Graph(inc_data,care_home,care_home_data);
        }
    });
}

//form l2 & L3 graph
function forml2AndL3Graph(inc_data,care_home,care_home_data) {
    $('#related_to_l2AndL3_form').highcharts({

    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Total Incident Duration (PI)'
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




//get Related To L3 Form
function getRelatedToL3Form()
{
    
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getRelatedToL3",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            var care_home_data = value.care_home_name;
            formL3Graph(inc_data,care_home,care_home_data);
        }
    });
}

//form l2 & L3 graph
function formL3Graph(inc_data,care_home,care_home_data) {
    $('#related_to_L3_form').highcharts({

     chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Total Laying Duration (PI)'
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



//get Related To L1 Form
function getRelatedToL1Form()
{
    
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getRelatedToL1",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            var care_home_data = value.care_home_name;
            formL1Graph(inc_data,care_home,care_home_data);
        }
    });
}

//form l1 graph
function formL1Graph(inc_data,care_home,care_home_data) {
    $('#related_to_L1_form').highcharts({
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Total Time without PI (in release)'
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


//get Related To REG40 Form
function getRelatedToREG40Form()
{
    
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getRelatedToREG40",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            var care_home_data = value.care_home_name;
            formREG401Graph(inc_data,care_home,care_home_data);
        }
    });
}

//form REG40 graph
function formREG401Graph(inc_data,care_home,care_home_data) {
    $('#related_to_REG40_form').highcharts({
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Number of Incidents requiring REG40 Notification'
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


//get Related To Police Involvement Form
function getRelatedToPoliceInvolvement()
{
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getRelatedToPoliceInvolvement",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            var care_home_data = value.care_home_name;
            formPoliceInvolvementGraph(inc_data,care_home,care_home_data);
        }
    });
}

//form REG40 graph
function formPoliceInvolvementGraph(inc_data,care_home,care_home_data) {
    $('#related_to_police_involvement').highcharts({
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Number of Incidents requiring Police involvement'
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


/*
    @Author : Rana Ritesh
    @Desc   : AAI report graph End
*/

function getRelatedTol7ladoForm()
{
    
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getRelatedTol7lado",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            forml7ladoGraph(inc_data,care_home);
        }
    });
}

//form data l7lado notification
function forml7ladoGraph(inc_data,care_home) {
    $('#related_to_l7lado').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Related To L2 And L3 Form'
        },
        // tooltip: {
        //     pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        // },
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'Total',
                        y: inc_data
                    },{
                        name: 'Care Home Data',
                        y: care_home
                    }]
            }]
    });
}

//get Related To related_to_status_panding_for_each_incident
function getRelatedTostatus_panding_for_each_incident_Form()
{
    
    var selectedToDate = $('#to_date').val();
    var selectedFromDate = $('#from_date').val();
    var selectedCarehome = $('#care_home').val();
    var selectedLocation = $('#location').val();
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "AAIReport/getRelated_status_panding_for_each_incident",
        dataType: 'json',
        data: {to_date: selectedToDate, from_date: selectedFromDate, yp_id: ypId,care_home: selectedCarehome,location: selectedLocation},
        beforeSend: function () {
        },
        success: function (value) {
                console.log(value);
            var inc_data = parseFloat(value.count_data);
            var care_home = parseFloat(value.care_home);
            formstatus_panding_for_each_incident_Graph(inc_data,care_home);
        }
    });
}

//form data l7lado notification
function formstatus_panding_for_each_incident_Graph(inc_data,care_home) {
    $('#related_to_status_panding_for_each_incident').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Number Of Incidents requiring Review'
        },
        // tooltip: {
        //     pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        // },
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'Total',
                        y: inc_data
                    },{
                        name: 'Care Home Data',
                        y: care_home
                    }]
            }]
    });
}