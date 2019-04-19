/* created common function by Dhara Bhalala to delete image on 01/10/2018  */
function delete_img(obj,img,inputfield)
{
    var input = $("#"+inputfield);
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
                        input.val((input.val() != '')?input.val()+","+img:img);
                        $(obj).parent().remove();
                        dialog.close();
                    }

                }]
        });
}
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
