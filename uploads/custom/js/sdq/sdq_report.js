/*
 @Description: SDQ Report custom js
 @Author: Ishani Dave
 */

$(document).ready(function () {
    $('.main-content').css('min-height', $(window).height() + 100);
        Highcharts.setOptions({
            colors: ['#2196f3', '#63b1f0', '#1766a6', '#264e6e', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
        });
    getTotalDifData();
    getEmoSymData();
    getConScale();
    getHypScale();
    getPeerScale();
    getProScale();
    
    $('#total_diff_year').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getTotalDifData();
        var currentYear = $('#total_dif_report').val();
        $('#get_Year').val(currentYear);
        var submitVal = "Export PDF SDQ Record Report " + currentYear;
       $('#submit').val(submitVal);
       $('#emo_sympt_report').val(currentYear);
       getEmoSymData();
       $('#con_scale_report').val(currentYear);
       getConScale();
       $('#hyp_scale_report').val(currentYear);
       getHypScale();
       $('#peer_scale_report').val(currentYear);
       getPeerScale();
       $('#pro_scale_report').val(currentYear);
       getProScale();
    });
    $('#emo_sympt_year').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getEmoSymData();
    });
    $('#con_scale_year').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getConScale();
    });
    $('#hyp_scale_year').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getHypScale();
    });
    $('#peer_scale_year').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getPeerScale();
    });
    $('#pro_scale_year').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getProScale();
    });
    
    
    
});

//get total difficulties yearly data
function getTotalDifData()
{
    var selectedYear = $('#total_dif_report').val();
    $('#get_Year').val(selectedYear);
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getTotalDiffData",
        data: {year: selectedYear, yp_id: ypId},
        beforeSend: function () {
        },
        success: function (data) {
            var newData = $.parseJSON(data);
            TotalDifGraph(newData);
        }
    });
}
//total difficulties graph
function TotalDifGraph(data) {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Total Difficulties'
        },
        xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Score'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:1f} </b></td></tr>',
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
        series: data.seriesdata
    });
}
//get emotional symptoms yearly data
function getEmoSymData()
{
    var selectedYear = $('#emo_sympt_report').val();
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getEmoSymData",
        data: {year: selectedYear, yp_id: ypId},
        beforeSend: function () {
        },
        success: function (data) {
            var newData = $.parseJSON(data);
            EmoSymGraph(newData);
        }
    });
}
//emotional symptoms scale graph
function EmoSymGraph(data) {
    $('#emotional_symp').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Emotional Symptoms Scale'
        },
        xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Score'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:1f} </b></td></tr>',
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
        series: data.seriesdata
    });
}
//get conductproblem scale yearly data
function getConScale()
{
    var selectedYear = $('#con_scale_report').val();
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getConScaleData",
        data: {year: selectedYear, yp_id: ypId},
        beforeSend: function () {
        },
        success: function (data) {
            var newData = $.parseJSON(data);
            ConScaleGraph(newData);
        }
    });
}
//conduct problem scale graph
function ConScaleGraph(data) {
    $('#con_scale').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Conduct Problem Scale'
        },
        xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Score'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:1f} </b></td></tr>',
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
        series: data.seriesdata
    });
}
//get hyperactivity scale yearly data
function getHypScale()
{
    var selectedYear = $('#hyp_scale_report').val();
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getHypScaleData",
        data: {year: selectedYear, yp_id: ypId},
        beforeSend: function () {
        },
        success: function (data) {
            var newData = $.parseJSON(data);
            HypScaleGraph(newData);
        }
    });
}
//hyperavtivity scale graph
function HypScaleGraph(data) {
    $('#hyperactivity').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Hyperactivity Scale'
        },
        xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Score'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:1f} </b></td></tr>',
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
        series: data.seriesdata
    });
}
//get peer problem scale yearly data
function getPeerScale()
{
    var selectedYear = $('#peer_scale_report').val();
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getPeerScaleData",
        data: {year: selectedYear, yp_id: ypId},
        beforeSend: function () {
        },
        success: function (data) {
            var newData = $.parseJSON(data);
            PeerScaleGraph(newData);
        }
    });
}
// peer problem scale graphd
function PeerScaleGraph(data) {
    $('#peer_problem').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Peer Problems Scale'
        },
        xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Score'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:1f} </b></td></tr>',
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
        series: data.seriesdata
    });
}
//get prosocial behavior yearly data
function getProScale()
{
    var selectedYear = $('#pro_scale_report').val();
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/getProScaleData",
        data: {year: selectedYear, yp_id: ypId},
        beforeSend: function () {
        },
        success: function (data) {
            var newData = $.parseJSON(data);
            ProScaleGraph(newData);
        }
    });
}
// peer prosocial behavior graphd
function ProScaleGraph(data) {
    $('#pro_behav').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Prosocial Scale'
        },
        xAxis: {
            categories: data.xaxisdata,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Score'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:1f} </b></td></tr>',
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
        series: data.seriesdata
    });
}

//get total score value
function getVal(formName)
{
    $.ajax({
        url: valurl,
        type: 'POST',
        data: $(formName).serialize() + '&' + 'result_type=' + 'ajax',
        success: function (data) {
            $('#total_score').val(data);
        }
    });
}

// export pdf 
function generate_pdf()
{  
    var selectedYear = $('#total_dif_report').val();
    var ypId = $('#yp_id').val();
    $.ajax({
        type: "POST",
        url: baseurl + "SdqReport/generatePDF",
        data: {year: selectedYear, yp_id: ypId},
        success: function (data) {
        }
    });
}
