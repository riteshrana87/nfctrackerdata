$(document).ready(function () {
            //intialize scroll
            $('.tile .slimScroll-110').slimscroll({
                height: 110,
                size: 3,
                alwaysVisible: true,
                color: '#a94442'
            });
            //intialize date
             
            $('#datepicker').datetimepicker({format: 'DD/MM/YYYY'});
            $('#appointment_time').timepicker({defaultTime: '',minuteStep: 5});
        });
//dynamic time format intialize
$(function () {
    $(".addtime").timepicker({
        defaultTime: '',
        minuteStep: 5
    });
  
});
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

 