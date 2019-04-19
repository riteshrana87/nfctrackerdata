/*
 @Description: SDQ Report custom js
 @Author: Ishani Dave
 */

$(document).ready(function () {
    $('.main-content').css('min-height', $(window).height() + 100);
    Highcharts.setOptions({
        colors: ['#5cb85c', '#FA8128', '#d9534f', '#264e6e', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
    });
    getLineGraphData();
    getTotalDifTrendData();
    getEmoSymTrendData();
    getConScaleTrend();
    getHypScaleTrend();
    getPeerScaleTrend();
    getProScaleTrend();

});

//get Line Graph Data
function getLineGraphData()
{
    var ypId = $('#yp_id').val();

    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getLineGraphData",
        data: {yp_id: ypId},
        beforeSend: function () {

        },
        success: function (data) {
            var newData = $.parseJSON(data);
            lineGraphData(newData);
        }
    });
}
// line chart
function lineGraphData(data) {

Highcharts.chart('line_container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'SDQ Report Graph'
    },
    credits: {
                enabled: false
            },
    xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: { // don't display the dummy year
            month: '%e. %b',
            year: '%b'
        },
        title: {
            text: 'Month'
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
    series: data.series
});
}
//get total difficulties trend data
function getTotalDifTrendData()
{

    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getTotalDifTrendData",
        data: {yp_id: ypId},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var notTrueTot = parseFloat(data[0]);
            var someTrueTot = parseFloat(data[1]);
            var certainlyTrueTot = parseFloat(data[2]);
            TotalDifTrendGraph(notTrueTot, someTrueTot, certainlyTrueTot);
        }
    });
}
//total difficulties graph
function TotalDifTrendGraph(notTrueTot, someTrueTot, certainlyTrueTot) {
    $('#pie_container').highcharts({
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
            text: 'Total Difficulties'
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
                        name: 'Normal',
                        y: notTrueTot
                    }, {
                        name: 'Borderline',
                        y: someTrueTot
                    }, {
                        name: 'Abnormal',
                        y: certainlyTrueTot
                    }]
            }]
    })
}
//get emotional symptoms trend data
function getEmoSymTrendData()
{
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getEmoSymTrendData",
        data: {yp_id: ypId},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var notTrueEmo = parseFloat(data[0]);
            var someTrueEmo = parseFloat(data[1]);
            var certainlyTrueEmo = parseFloat(data[2]);
            EmoSymGraphTrend(notTrueEmo, someTrueEmo, certainlyTrueEmo);
        }
    });
}
//emotional symptoms scale graph
function EmoSymGraphTrend(notTrueEmo, someTrueEmo, certainlyTrueEmo) {
    $('#pie_emotional_symp').highcharts({
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
            text: 'Emotional Symptoms Scale'
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
                        name: 'Not True',
                        y: notTrueEmo
                    }, {
                        name: 'Some What True',
                        y: someTrueEmo
                    }, {
                        name: 'Certainly True',
                        y: certainlyTrueEmo
                    }]
            }]
    });
}
//get conductproblem scale trend data
function getConScaleTrend()
{
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getConScaleTrend",
        data: {yp_id: ypId},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var notTrueCon = parseFloat(data[0]);
            var someTrueCon = parseFloat(data[1]);
            var certainlyTrueCon = parseFloat(data[2]);
            ConScaleGraphTrend(notTrueCon, someTrueCon, certainlyTrueCon);
        }
    });
}
//conduct problem scale graph
function ConScaleGraphTrend(notTrueCon, someTrueCon, certainlyTrueCon) {
    $('#pie_con_scale').highcharts({
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
            text: 'Conduct Problem Scale'
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
                        name: 'Not True',
                        y: notTrueCon
                    }, {
                        name: 'Some What True',
                        y: someTrueCon
                    }, {
                        name: 'Certainly True',
                        y: certainlyTrueCon
                    }]
            }]
    });
}
//get hyperactivity scale trend data
function getHypScaleTrend()
{
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getHypScaleTrend",
        data: {yp_id: ypId},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var notTrueHyp = parseFloat(data[0]);
            var someTrueHyp = parseFloat(data[1]);
            var certainlyTrueHyp = parseFloat(data[2]);
            HypScaleGraphTrend(notTrueHyp, someTrueHyp, certainlyTrueHyp);
        }
    });
}
//hyperavtivity scale graph
function HypScaleGraphTrend(notTrueHyp, someTrueHyp, certainlyTrueHyp) {
    $('#pie_hyperactivity').highcharts({
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
            text: 'Hyperactivity Scale'
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
                        name: 'Not True',
                        y: notTrueHyp
                    }, {
                        name: 'Some What True',
                        y: someTrueHyp
                    }, {
                        name: 'Certainly True',
                        y: certainlyTrueHyp
                    }]
            }]
    });
}
//get peer problem scale trend data
function getPeerScaleTrend()
{
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getPeerScaleTrend",
        data: {yp_id: ypId},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var notTruePeer = parseFloat(data[0]);
            var someTruePeer = parseFloat(data[1]);
            var certainlyTruePeer = parseFloat(data[2]);
            PeerScaleGraphTrend(notTruePeer, someTruePeer, certainlyTruePeer);
        }
    });
}
// peer problem scale graphd
function PeerScaleGraphTrend(notTruePeer, someTruePeer, certainlyTruePeer) {
    $('#pie_peer_problem').highcharts({
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
            text: 'Peer Problems Scale'
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
                        name: 'Not True',
                        y: notTruePeer
                    }, {
                        name: 'Some What True',
                        y: someTruePeer
                    }, {
                        name: 'Certainly True',
                        y: certainlyTruePeer
                    }]
            }]
    });
}
//get prosocial behavior trend data
function getProScaleTrend()
{
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getProScaleTrend",
        data: {yp_id: ypId},
        beforeSend: function () {
        },
        success: function (value) {
            var data = value.split(",");
            var notTruePro = parseFloat(data[0]);
            var someTruePro = parseFloat(data[1]);
            var certainlyTruePro = parseFloat(data[2]);
            ProScaleGraphTrend(notTruePro, someTruePro, certainlyTruePro);
        }
    });
}
// peer prosocial behavior graphd
function ProScaleGraphTrend(notTruePro, someTruePro, certainlyTruePro) {
    $('#pie_pro_behav').highcharts({
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
            text: 'Prosocial Scale'
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
                        name: 'Not True',
                        y: notTruePro
                    }, {
                        name: 'Some What True',
                        y: someTruePro
                    }, {
                        name: 'Certainly True',
                        y: certainlyTruePro
                    }]
            }]
    });
}
