/* Remove File if file already Exists*/
//    $(".delete-confirm").on('click', function () {
//        alert("test");
//        var imageHolderElement = $(this).parent("#image-holder");
//
//        var delete_msg = "Are You Sure Want to Delete Attachment from list ?";
//        BootstrapDialog.show(
//                {
//                    title: 'Information',
//                    message: delete_msg,
//                    buttons: [{
//                            label: 'Cancel',
//                            action: function (dialog) {
//                                dialog.close();
//                            }
//                        }, {
//                            label: 'Ok',
//                            action: function (dialog) {
//                                imageHolderElement.html('');
//                                $('#removeFile').val('1');
//                                dialog.close();
//                            }
//                        }]
//                });
//    });