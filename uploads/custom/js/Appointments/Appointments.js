$(document).ready(function () {
            //intialize scroll
            $('.tile .slimScroll-110').slimscroll({
                height: 110,
                size: 3,
                alwaysVisible: true,
                color: '#a94442'
            });
            //intialize date
             
              $("#appointment_date").datepicker({
                 format: 'dd/mm/yyyy',
            });

            $('#appointment_time').timepicker({defaultTime: '',minuteStep: 5});
            $('#appointment_time').click(function(){
                var id=$(this).attr('id');
                
               $('.appointment.input-group-addon').click();
             });
            $('#repeat_appointment_time').timepicker({defaultTime: '',minuteStep: 5});
            $('#repeat_appointment_time').click(function(){
                var id=$(this).attr('id');
                
               $('.repeatappointment.input-group-addon').click();
             });
            $('#record_medication_offered_but_refused').click(function(){
                    if ($(this).is(':checked')){
                        $('#quantity_left').attr('min',0);
                        $('#quantity_left').val(0);
                    }
                    else
                    {
                        $('#quantity_left').attr('min',0);
                        $('#quantity_left').val('');
                    }
            });
            /*date validation*/
            var end = new Date();
            end.setDate(end.getDate() + 90);
            
            $(".add_start_date").datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                endDate   : end,
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('.add_end_date').datepicker('setStartDate', minDate);
            });

            $(".add_end_date").datepicker({
                endDate   : end,
                format: 'dd/mm/yyyy',
            })
                .on('changeDate', function (selected) {
                    var maxDate = new Date(selected.date.valueOf());
                    $('.add_start_date').datepicker('setEndDate', maxDate);
                });
            $('#with_repeat').hide();
            /*repeat checkbox on click*/
            $('body').delegate('#repeat_apt', 'click',
            function (e) {

                if($(this).prop('checked'))
                {
                    $('#appointment_date input').removeAttr('required');
                    $('#appointment_time').removeAttr('required');
                    $('#without_repeat').hide();
                    $('#with_repeat').show();
                    $('#repeat_appointment_time').attr('required','true');
                    $('#appointment_start_date').attr('required','true');
                    $('#appointment_end_date').attr('required','true');
                    
                }
                else
                {
                    $('#repeat_appointment_time').removeAttr('required');
                    $('#appointment_start_date').removeAttr('required');
                    $('#appointment_end_date').removeAttr('required');
                    $('#with_repeat').hide();
                    $('#without_repeat').show();
                    $('#appointment_date input').attr('required','true');
                    $('#appointment_time').attr('required','true');
                    
                }
            });
        });

     $(document).ready(function() {
        $("#docsform").on('submit', function(e){
            e.preventDefault();
            var form = $(this);
             var valid = false;
            form.parsley().validate();

            if (form.parsley().isValid()){
                 var valid = true;
                $('input[type="submit"]').prop('disabled', true);
                $('button[type="submit"]').prop('disabled', true);
            }
            if (valid) this.submit();
        });
    });
function setdefaultdata() {
// phone validation ends
    if ($('#docsform').parsley().isValid()) {
        $('input[type="submit"]').prop('disabled', true);
        $('#docsform').submit();
    }
}
//ajax model popup
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

 function manager_request(YPId){
    var delete_meg ="Please select OK to authorise this document.";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/Appointments/manager_review/' + YPId;
                    dialog.close();
                }
            }]
        });
}



if (typeof Appointment_id !== 'undefined') {
var appointment_url = window.location.href;
var url_appointment = baseurl +'Appointments/appointment_edit/' + Appointment_id + '/' + YPId;
if(url_appointment == appointment_url){
  function get_appointment_complete(){
    //var datetime = timefunction();
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Appointments/update_slug/",
        data: {'url_data': url_appointment}
    }).done(function(){
        setTimeout(function(){get_appointment_complete();}, 10000);
    }).responseText;
}
$(function(){
    get_appointment_complete();
});
}
}
