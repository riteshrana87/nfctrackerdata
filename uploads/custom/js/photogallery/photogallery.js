
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

$("body").on("focus", ".time-input", function () {
    var id = $(this).attr('id');
    $('.time-input').datepicker();
    //inline: true,
    // sideBySide: true
});

  function deleteGalleryData(photo_gallery_id,yp_id){
    var delete_meg ="Are you sure you want to delete this Photo?";
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
                    window.location.href = baseurl + '/PhotoGallery/deletePhoto/' + photo_gallery_id +'/'+ yp_id;
                    dialog.close();
                }
            }]
        });
}

