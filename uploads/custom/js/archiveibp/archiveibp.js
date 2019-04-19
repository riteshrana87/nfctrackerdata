
$(document).ready(function () {
	
	var today = new Date();
	 $('#datepicker_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });
			
			
//window.onbeforeunload = function () { return "If you want to go back to previous page Please use Previous step Button in below"; };

    $('.tile .slimScroll-120').slimscroll({
        height: 120,
        size: 3,
        alwaysVisible: true,
        color: '#007c34'
    });
    $('.tile .slimScroll').slimscroll({
        height: 60,
        size: 3,
        alwaysVisible: true,
        color: '#007c34'
    });
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
/*delete image*/
function delete_img(obj, img, inputfield)
{
    var input = $("#" + inputfield);
    BootstrapDialog.show(
            {
                title: 'Confirm!',
                message: "<strong> Are you sure want to delete file ? <strong>",
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: 'Ok',
                        action: function (dialog) {
                            input.val((input.val() != '') ? input.val() + "," + img : img);
                            $(obj).parent().remove();
                            dialog.close();
                        }

                    }]
            });
}




