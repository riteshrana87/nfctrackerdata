//load calandar
$(document).ready(function() {
   $("#event_date").datepicker({
                 format: 'dd/mm/yyyy',
            });
   $('#Appointments').parsley();

           // $('#event_time1').timepicker({defaultTime: '',minuteStep: 5});
           $('#event_time').datetimepicker({
                    format: 'LT'
                });
          var week_start_date=  getFormattedYearDate(new Date());
        calnderload(60,week_start_date);
        
    });
//calandar option
function calnderload(duration,week_start_date)
{
  var calendar =$('#calendar').fullCalendar({
  header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      firstDay : 1, // 0- Sunday, 1- Monday, 2- Tuesday
      defaultDate: new Date(),
      allDaySlot: false,
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      contentHeight:'auto',
      //selectable: true,
      //selectHelper: true,
      slotDuration: "00:60:00",
      displayEventTime: false,
      eventLimit: true, // allow "more" link when too many events
      events: {
          url: baseurl+'Planner/getEvents',
          type: 'POST',
          data: {
                  yp_id: yp_id,
                  care_home_id: care_home_id,
                },
          success: function (data) {
            //$('div#calendar').unblock(); 
          },
          error: function () {
          }
      },
      eventRender: function (event, element) {
          element.attr("data-title", event.description);
           element.find('.fc-title').html(event.title);
          if(accessEdit == true)
          {

              element.attr("data-toggle", "ajaxModal");
              var hr = element.attr('href');
              element.removeAttr("href");
              element.attr("data-href", hr);
          }
          else{element.removeAttr("href");}
      },
      select: function(start, end, jsEvent) {
               
                var minutes = end.diff(start,"minutes");

                currentDate = getFormattedTimeDate(new Date());
                datestart = $.fullCalendar.formatDate(start, "DD/MM/YYYY");
                start = $.fullCalendar.formatDate(start, "hh:mm A");
                end = $.fullCalendar.formatDate(end, "hh:mm A");
                /*if(datestart+' '+start >= currentDate)
                { */
                  /*if(minutes == duration)
                  {*/
                       $('#ajaxModal #event_date').val(datestart);
                       $('#ajaxModal #event_time input').val(start);
                       $('#ajaxModal #start_time').val(start);
                       $('#ajaxModal #end_time').val(end);
                       $('#ajaxModal #delete').remove();
                       $('#ajaxModal #date').val(datestart);
                       $('#ajaxModal #total_minute').val(minutes);
                       $('#ajaxModal #total_duration').val(duration);
                       
                       $('#ajaxModal #event_id').val('');
                      
                       if(accessAdd == true)
                       {
                          $('#ajaxModal').modal('show');
                       }
                  /*}
                  else
                  {
                      calendar.fullCalendar('unselect');
                  }*/
                /*}
                else
                {
                  calendar.fullCalendar('unselect');
                }*/
            
            },
            selectConstraint:{
              start: '00:00', 
              end: '24:00', 
            },
    });
}
  
//convert to date format
   function getFormattedDate(date,sign) {
      var year = date.getFullYear();

      var month = (1 + date.getMonth()).toString();
      month = month.length > 1 ? month : '0' + month;

      var day = date.getDate().toString();
      day = day.length > 1 ? day : '0' + day;
      
      return month + '/' + day + '/' + year;
    }
    //convert to date format
   function getFormattedYearDate(date,sign) {
      var year = date.getFullYear();

      var month = (1 + date.getMonth()).toString();
      month = month.length > 1 ? month : '0' + month;

      var day = date.getDate().toString();
      day = day.length > 1 ? day : '0' + day;
      
      return year + '-' + month + '-' + day;
    }
    function getFormattedTimeDate(date,sign) {
      var year = date.getFullYear();

      var month = (1 + date.getMonth()).toString();
      month = month.length > 1 ? month : '0' + month;

      var day = date.getDate().toString();
      day = day.length > 1 ? day : '0' + day;

      var hour = date.getHours().toString();
      hour = hour.length > 1 ? hour : '0' + hour;

      var min = date.getMinutes().toString();
      min = min.length > 1 ? min : '0' + min;
      
      return month + '/' + day + '/' + year+ ' '+hour+':'+min;
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
    //Delete time slot
    function deletepopup(id)
    {   
        BootstrapDialog.show(
        {
            title: 'Confirm!',
            message: "<strong> Are you sure want to delete this? <strong>",
            buttons: [{
                    label: 'Cancel',
                    action: function (dialog) {
                        dialog.close();

                    }
                }, {
                    label: 'Ok',
                    action: function (dialog) {
                        $.ajax({
  
                          type: "POST",
                          url: baseurl+'Planner/deleteEvent',
                          //dataType: 'json',
                          data: {'id':id},
                          success: function(data){
                            if(data == 1)
                             {
                                 $("#calendar").fullCalendar("refetchEvents");
                             }
                             else{
                                $.alert({
                                    title: 'Alert!',
                                    //backgroundDismiss: false,
                                    content: "<strong> Something went wrong.<strong>",
                                    confirm: function(){
                                    }
                                });
                                calendar.fullCalendar('unselect');
                             }
                          }
                        });
                        dialog.close();
                    }

                }]
        });
    } 