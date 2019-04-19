$(document).ready(function () {
	var totalcheckbox;
	$('.selecctall').click(function(e){
		totalcheckbox = $('input[type="checkbox"]').length;
	});
	$('.checkbox1').click(function(e){
		var totalcheckbox1 = $('.checkbox1:checkbox:checked').length;
		
		if(totalcheckbox-1 != totalcheckbox1){
		
			$(".selecctall").prop('checked', false);
		}else{ 		
		
			$(".selecctall").prop('checked', true);
		}

	});
	
});
function customer_bulk_delete(){

        var allVals = [];
            $('#customer_ids :checked').each(function() {
              allVals.push($(this).val());
            });
            
            if(allVals != ""){                
                
            var delete_url = customerDeleteurl+"?customerid=" + allVals;
            var delete_meg = "Are you sure you want to delete selected Customers ?";
            BootstrapDialog.show(
                    {
                        title: 'Customer Delete Confrim',
                        message: delete_meg,
                        buttons: [{
                                label: 'Cancel',
                                action: function (dialog) {
                                    dialog.close();

                                }
                            }, {
                                label: 'Ok',
                                action: function (dialog) {
                                    window.location.href = delete_url;
                                    dialog.close();
                                }

                            }]
                    });
        }else{
		
			BootstrapDialog.show(
                    {
                        title: 'Customer Delete Confrim',
                        message: "Please Select Customer",
                        buttons: [{
                                label: 'Cancel',
                                action: function (dialog) {
                                    dialog.close();

                                }
                            }, {
                                label: 'Ok',
                                action: function (dialog) {
                                   
                                    dialog.close();
                                }

                            }]
                    });
		}
}
function delete_customers(id){
	if(id != ""){                
                
            var delete_url = customerDeleteurl+"?customerid=" + id;
            var delete_meg = "Are you sure you want to delete selected Customers ?";
            BootstrapDialog.show(
                    {
                        title: 'Customer Delete Confrim',
                        message: delete_meg,
                        buttons: [{
                                label: 'Cancel',
                                action: function (dialog) {
                                    dialog.close();

                                }
                            }, {
                                label: 'Ok',
                                action: function (dialog) {
                                    window.location.href = delete_url;
                                    dialog.close();
                                }

                            }]
                    });
        }else{
		
			BootstrapDialog.show(
                    {
                        title: 'Customer Delete Confrim',
                        message: "Please Select Customer",
                        buttons: [{
                                label: 'Cancel',
                                action: function (dialog) {
                                    dialog.close();

                                }
                            }, {
                                label: 'Ok',
                                action: function (dialog) {
                                   
                                    dialog.close();
                                }

                            }]
                    });
		}
}
