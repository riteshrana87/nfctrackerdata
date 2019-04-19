$(document).ready(function () {
    getYPYearData();
    getDoYearData();
    getKsYearData();
    getPPYearData();
    getIbpYearData();
    getRaYearData();
    getDocsYearData();
    getMedsYearData();
    getComsYearData();
    /*$('#admin_report_data').datetimepicker({
     format: "YYYY",
     viewMode: "years",
     }).on('dp.change', getYPYearData);*/

    $('#admin_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getYPYearData();
    });
    
    
    $('#do_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getDoYearData();
    });
    
    $('#ks_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getKsYearData();
    });
    
    $('#pp_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getPPYearData();
    });
    
    $('#ibp_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getIbpYearData();
    });
    
    $('#ra_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getRaYearData();
    });
    $('#docs_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getDocsYearData();
    });
    
    $('#meds_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getMedsYearData();
    });
    
    $('#coms_report_data').datetimepicker({
        format: "YYYY",
        viewMode: "years"
    }).on("dp.change", function (e) {
        getComsYearData();
    });
    
});

function getYPYearData() {
    var selectedYear = $('#admin_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getYpChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            YpGraph(updateData);
        },
        error: function (result) {

        }
    });
}

function YpGraph(monthly_total_yp) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_yp, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#3c8dbc'
    }
    $.plot('#bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f3f3f3',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}

/*Daily Observation Chart Report*/

function getDoYearData() {
    
    var selectedYear = $('#do_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getDoChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            DoGraph(updateData);
        },
        error: function (result) {

        }
    });
}


function DoGraph(monthly_total_do) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_do, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#00a65a'
    }
    $.plot('#do-bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f56954',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}
/*End Daily Observation Chart Report*/

/*Daily Observation Chart Report*/

function getKsYearData() {
    
    var selectedYear = $('#ks_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getKsChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            KsGraph(updateData);
        },
        error: function (result) {

        }
    });
}


function KsGraph(monthly_total_do) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_do, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#f56954'
    }
    $.plot('#ks-bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#3c8dbc',
            tickColor: '#00a65a'
            
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}
/*End Daily Observation Chart Report*/



/*Daily Placement Plan Chart Report*/

function getPPYearData() {
    
    var selectedYear = $('#pp_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getPPChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            PPGraph(updateData);
        },
        error: function (result) {

        }
    });
}


function PPGraph(monthly_total_do) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_do, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#00a65a'
    }
    $.plot('#pp-bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f56954',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}
/*End Placement Plan Chart Report*/


/*Daily Individual Behaviour Plan Chart Report*/

function getIbpYearData() {
    
    var selectedYear = $('#ibp_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getIbpChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            IbpGraph(updateData);
        },
        error: function (result) {

        }
    });
}


function IbpGraph(monthly_total_do) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_do, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#00a65a'
    }
    $.plot('#ibp-bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f56954',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}
/*End Individual Behaviour Plan Chart Report*/

/*Daily Risk Assessment Chart Report*/

function getRaYearData() {
    
    var selectedYear = $('#ibp_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getRaChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            RaGraph(updateData);
        },
        error: function (result) {

        }
    });
}


function RaGraph(monthly_total_do) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_do, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#00a65a'
    }
    $.plot('#ra-bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f56954',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}
/*End Risk Assessment Plan Chart Report*/


/*Daily Medical Authorisations & Consents Chart Report*/
function getMedsYearData() {
    var selectedYear = $('#meds_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getMedsChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            MedsGraph(updateData);
        },
        error: function (result) {

        }
    });
}


function MedsGraph(monthly_total_do) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_do, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#00a65a'
    }
    $.plot('#meds-bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f56954',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}
/*End Medical Authorisations & Consents Chart Report*/

/*start Documents Chart Report*/
function getDocsYearData() {
    
    var selectedYear = $('#docs_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getDocsChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            docsGraph(updateData);
        },
        error: function (result) {

        }
    });
}


function docsGraph(monthly_total_do) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_do, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#00a65a'
    }
    $.plot('#docs-bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f56954',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}
/*End Documents Chart Report*/



/*start Communication Chart Report*/
function getComsYearData() {
    
    var selectedYear = $('#coms_report').val();
    $.ajax({
        type: "POST",
        url: baseurl + "Admin/Dashboard/getComsChartData",
        data: {year: selectedYear},
        beforeSend: function () {
        },
        success: function (result) {
            var updateData = $.parseJSON(result);
            ComsGraph(updateData);
        },
        error: function (result) {

        }
    });
}


function ComsGraph(monthly_total_do) {
    var monthdata = [];
    var temp = [];
    
    $.each(monthly_total_do, function (key, value) {
        temp = [];
        temp.push(key);
        temp.push(value);
        monthdata.push(temp);
    });
    var bar_data = {
        data: monthdata,
        color: '#00a65a'
    }
    $.plot('#coms-bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f56954',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        }
    })
}
/*End Communication Chart Report*/

/*
 * Custom Label formatter
 * ----------------------
 */
function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
            + label
            + '<br>'
            + Math.round(series.percent) + '%</div>'
}
