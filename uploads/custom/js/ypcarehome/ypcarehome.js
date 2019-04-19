$(document).ready(function () {
    $('#registration').parsley(); //parsley validation
    $('.tile .slimScroll').slimscroll({
        height: 60,
        size: 3,
        alwaysVisible: true,
        color: '#007c34'
    });
    //$(".chosen-select").chosen();

    $('#display-list').on('click', function () {
        $(this).addClass('active');
        $('#display-table').removeClass('active');
        $('#table-view').slideUp();
        $('#list-view').slideDown();
    });
    $('#display-table').on('click', function () {
        $(this).addClass('active');
        $('#display-list').removeClass('active');
        $('#list-view').slideUp();
        $('#table-view').slideDown();
    });
     $('.time-input').datepicker({
        format: 'dd/mm/yyyy',
        startDate: new Date(),
        autoclose: true,
    });
    $('.time-input1').datepicker({
        format: 'dd/mm/yyyy',
        endDate: new Date(),
        autoclose: true,
    });
    //add current list type
    $('.yp-listtype').click(function(){
        $('#yp-listtype').val($(this).attr('id'));
    })
});
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


