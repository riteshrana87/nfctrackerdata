/*
 @Description: CSE Report custom js
 @Author: Ishani Dave
 */

$(document).ready(function () {

    $('#created_date').datetimepicker({
        format: 'DD/MM/YYYY', //format,
        minDate: '0001',
        useCurrent: false,
        maxDate: new Date()
    });

    $('.main-content').css('min-height', $(window).height() + 100);
    Highcharts.setOptions({
        colors: ['#d9534f', '#FA8128', '#5cb85c', '#4f9b4f', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
    });
    var getVal = 'year';
    getLineGraphData(getVal); // Added By Ritesh Rana
    getTotalData();
    getHealthData();
    getBehaviourData();
    getGroomData();
    getLookChildData();
    getFamilySocialdData();
    getEsafetyData();


    $('#cse_year_record').datetimepicker({
        format: "YYYY",
        minDate: '0001',
        viewMode: "years"
    }).on("dp.change", function (e) {
        var currentYear = $('#cse_year').val();
        $('#get_Year').val(currentYear);
        var submitVal = "Export PDF SDQ Record Report " + currentYear;
        $('#submit').val(submitVal);

        getCommentData();
        getTotalData();
        getHealthData();
        getBehaviourData();
        getGroomData();
        getLookChildData();
        getFamilySocialdData();
        getEsafetyData();
        var getVal = 'month';
        getLineGraphData(getVal);

    });

    $('#cse_month_record').datetimepicker({
        format: "MM",
        minDate: '0001',
        viewMode: "months"
    }).on("dp.change", function (e) {
        var currentMonth = $('#cse_month').val();
        $('#get_Month').val(currentMonth);
        getCommentData();
        getTotalData();
        getHealthData();
        getBehaviourData();
        getGroomData();
        getLookChildData();
        getFamilySocialdData();
        getEsafetyData();
    });

});

function getCommentData()
{
  var selectedYear = $('#cse_year').val();
  var selectedMonth = $('#cse_month').val();
  var ypId = $('#yp_id').val();

  /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 25/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();


   $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getCommentData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        
        success: function (html) {
         $('#comment_section').html(html);
        }
    });
}
//get Line Graph Data
function getLineGraphData(getVal)
{
    var selectedYear = $('#cse_year').val();
    var selectedMonth = $('#cse_month').val();
    var ypId = $('#yp_id').val();

 /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 25/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();

    $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getLineGraphData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        beforeSend: function () {

        },
        success: function (data) {
            var newData = $.parseJSON(data);
            lineGraphData(newData, getVal);
        }
    });
}

function lineGraphData(data, getVal) {
    if (getVal == 'month') {
        Highcharts.chart('line_container', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'CSE Report Graph'
            },
            credits: {
                enabled: false
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {// don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Numbers'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y:.2f}'
            },
            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },
            series: data.series,
        });

    } else {
        Highcharts.chart('line_container', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'CSE Report Graph'
            },
            credits: {
                enabled: false
            },
            xAxis: {
                type: 'year',
                dateTimeLabelFormats: {// don't display the dummy year
                    year: '%b'
                },
                title: {
                    text: 'Year'
                }
            },
            yAxis: {
                title: {
                    text: 'Numbers'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.y:.2f}'
            },
            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },
            series: data.series
        });
    }


}
//get total data
function getTotalData()
{
    var selectedYear = $('#cse_year').val();
    var selectedMonth = $('#cse_month').val();

    /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();


    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getTotalData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        beforeSend: function () {
        },
        success: function (value) {

            var data = value.split(",");
            var high = parseFloat(data[0]);
            var medium = parseFloat(data[1]);
            var low = parseFloat(data[2]);
            var no_knownVal = parseFloat(data[3]);
            TotalGraph(high, medium, low, no_knownVal);

        }
    });
}

//total indicators graph
function TotalGraph(high, med, low, no_knownVal) {

    $('#total_container').highcharts({
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
            text: 'Total Risk'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'High Risk',
                        y: high
                    }, {
                        name: 'Medium Risk',
                        y: med
                    }, {
                        name: 'Low Risk',
                        y: low
                    }, {
                        name: 'No Know Risk',
                        y: no_knownVal
                    }]
            }]
    });
}

//get Health yearly data
function getHealthData()
{
    var selectedYear = $('#cse_year').val();
    var selectedMonth = $('#cse_month').val();

      /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();

    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getHealthData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var high = parseFloat(data[0]);
            var medium = parseFloat(data[1]);
            var low = parseFloat(data[2]);
            var no_knownVal = parseFloat(data[3]);
            var total = parseFloat(data[4]);
            HealthGraph(high, medium, low, no_knownVal);

        }
    });
}

//health indicators graph
function HealthGraph(high, med, low, no_knownVal) {

    $('#health_container').highcharts({
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
            text: 'Health'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'High Risk',
                        y: high
                    }, {
                        name: 'Medium Risk',
                        y: med
                    }, {
                        name: 'Low Risk',
                        y: low
                    }, {
                        name: 'No Know Risk',
                        y: no_knownVal
                    }]
            }]
    });
}

//get Behaviour yearly data
function getBehaviourData()
{
    var selectedYear = $('#cse_year').val();
    var selectedMonth = $('#cse_month').val();
    var ypId = $('#yp_id').val();


      /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();

    $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getBehaviourData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var high = parseFloat(data[0]);
            var medium = parseFloat(data[1]);
            var low = parseFloat(data[2]);
            var no_knownVal = parseFloat(data[3]);
            var total = parseFloat(data[4]);
            BehaviourGraph(high, medium, low, no_knownVal);

        }
    });
}
//behaviour indicators graph
function BehaviourGraph(high, med, low, no_knownVal) {

    $('#behaviour_container').highcharts({
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
            text: 'Behaviour'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'High Risk',
                        y: high
                    }, {
                        name: 'Medium Risk',
                        y: med
                    }, {
                        name: 'Low Risk',
                        y: low
                    }, {
                        name: 'No Known Risk',
                        y: no_knownVal
                    }]
            }]
    });
}
//get Grooming yearly data
function getGroomData()
{
    var selectedYear = $('#cse_year').val();
    var selectedMonth = $('#cse_month').val();
    var ypId = $('#yp_id').val();

      /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();

    $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getGroomData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var high = parseFloat(data[0]);
            var medium = parseFloat(data[1]);
            var low = parseFloat(data[2]);
            var no_knownVal = parseFloat(data[3]);
            var total = parseFloat(data[4]);
            GroomGraph(high, medium, low, no_knownVal);

        }
    });
}
//Grooming indicators graph
function GroomGraph(high, med, low, no_knownVal) {

    $('#groom_container').highcharts({
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
            text: 'Grooming'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'High Risk',
                        y: high
                    }, {
                        name: 'Medium Risk',
                        y: med
                    }, {
                        name: 'Low Risk',
                        y: low
                    }, {
                        name: 'No Known Risk',
                        y: no_knownVal
                    }]
            }]
    });
}

//get looked after children yearly data
function getLookChildData()
{
    var selectedYear = $('#cse_year').val();
    var selectedMonth = $('#cse_month').val();
    var ypId = $('#yp_id').val();

         /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();

    $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getLookChildData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var high = parseFloat(data[0]);
            var medium = parseFloat(data[1]);
            var low = parseFloat(data[2]);
            var no_knownVal = parseFloat(data[3]);
            var total = parseFloat(data[4]);
            LookChildGraph(high, medium, low, no_knownVal);

        }
    });
}
//looked after children indicators graph
function LookChildGraph(high, med, low, no_knownVal) {

    $('#child_container').highcharts({
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
            text: 'Looked After Children'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'High Risk',
                        y: high
                    }, {
                        name: 'Medium Risk',
                        y: med
                    }, {
                        name: 'Low Risk',
                        y: low
                    }, {
                        name: 'No Known Risk',
                        y: no_knownVal
                    }]
            }]
    });
}

//get family & social yearly data
function getFamilySocialdData()
{
    var selectedYear = $('#cse_year').val();
    var selectedMonth = $('#cse_month').val();

    var ypId = $('#yp_id').val();

          /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();


    $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getFamilySocialdData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var high = parseFloat(data[0]);
            var medium = parseFloat(data[1]);
            var low = parseFloat(data[2]);
            var no_knownVal = parseFloat(data[3]);
            var total = parseFloat(data[4]);
            FamilyAndSocialGraph(high, medium, low, no_knownVal);

        }
    });
}
//Family and Social indicators graph
function FamilyAndSocialGraph(high, med, low, no_knownVal) {

    $('#soc_container').highcharts({
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
            text: 'Family and Social'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'High Risk',
                        y: high
                    }, {
                        name: 'Medium Risk',
                        y: med
                    }, {
                        name: 'Low Risk',
                        y: low
                    }, {
                        name: 'No Known Risk',
                        y: no_knownVal
                    }]
            }]
    });
}


//get E Safety yearly data
function getEsafetyData()
{
    var selectedYear = $('#cse_year').val();
    var selectedMonth = $('#cse_month').val();
    var ypId = $('#yp_id').val();

             /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
 */
    var PastCareId = $('#past_care_id').val();
    var movedate = $('#movedate').val();
    var created_date = $('#CreatedDate').val();


    $.ajax({
        type: "POST",
        url: baseurl + "CseReport/getEsafetyData",
        data: {year: selectedYear, month: selectedMonth, yp_id: ypId,PastCareId: PastCareId,movedate: movedate,CreatedDate: created_date},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var high = parseFloat(data[0]);
            var medium = parseFloat(data[1]);
            var low = parseFloat(data[2]);
            var no_knownVal = parseFloat(data[3]);
            var total = parseFloat(data[4]);
            EsafetyGraph(high, medium, low, no_knownVal);

        }
    });
}
//looked after children indicators grapah
function EsafetyGraph(high, med, low, no_knownVal) {

    $('#safety_container').highcharts({
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
            text: 'E Safety'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                name: 'Score',
                colorByPoint: true,
                data: [{
                        name: 'High Risk',
                        y: high
                    }, {
                        name: 'Medium Risk',
                        y: med
                    }, {
                        name: 'Low Risk',
                        y: low
                    }, {
                        name: 'No Kown Risk',
                        y: no_knownVal
                    }]
            }]
    });
}

//get total score value
function getVal(formName)
{
    $.ajax({
        url: valurl,
        type: 'POST',
        data: $(formName).serialize() + '&' + 'result_type=' + 'ajax',
        success: function (value) {
            var data = value.split(",");
            $('#total_score_h').val(data[0]);
            $('#total_score_m').val(data[1]);
            $('#total_score_l').val(data[2]);
            $('#total_score_n').val(data[3]);
            $('#total_h').html(data[4]);
            $('#total_m').html(data[5]);
            $('#total_l').html(data[6]);
            $('#total_n').html(data[7]);
            $('#total').html(data[8]);

        }
    });
}
