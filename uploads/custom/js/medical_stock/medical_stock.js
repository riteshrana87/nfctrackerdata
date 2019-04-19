
$(document).ready(function () {
	
	$('select').on('change', function() {
	
	$("#ypid").val(this.value);
})	
	
	
	var today = new Date();
	 $('#datepicker_search').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
				endDate: "today",
				maxDate: today
            });

	var ypid=$('#print_value').val();
	
	$('.export').click(function(e){
		
		var professional_name=$('#ypid').val();
		var search_date=$('#search_date').val();
		var search_time=$('#search_time').val();
		var search=$('#search').val();
		var targetAttr = $(this).attr('target');
		var linkHref = $(this).attr('href');
		
		var url=baseurl +'MedicationStock/generateExcelFile/?care_home_id='+care_home_id+'&ypid='+professional_name;
		$(this).attr('href',url);	
		$.ajax({
        type: "GET",
        url: url,
        data: {
            professional_name: professional_name,search_date : search_date,search_time : search_time,search:search
        },
        success: function (res) {
			//alert(targetAttr);
			$(this).trigger('click');
				 //window.open(url, '_blank');
			
			//alert(res);
         
        },
        
    });
		
	});
	
	$('.print').click(function(e){
		
		
		var professional_name=$('#professional_name').val();
		var search_date=$('#search_date').val();
		var search_time=$('#search_time').val();
		var search=$('#search').val();
		var targetAttr = $(this).attr('target');
		var linkHref = $(this).attr('href');
		
		var url=baseurl +'Medical/DownloadAdministerMedication/'+ypid+'/print/?professional_name='+professional_name+'&search_date='+search_date+'&search_time='+search_time+'&search='+search;
		
		$.ajax({
        type: "GET",
        url: url,
        data: {
            professional_name: professional_name,search_date : search_date,search_time : search_time,search:search
        },
        success: function (res) {
			//alert(targetAttr);
			if ( targetAttr === '_blank' ) {
				 window.open(url, '_blank');
			}
			//alert(res);
         
        },
        
    });
		
	});
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
            $('#repeat_appointment_time').timepicker({defaultTime: '',minuteStep: 5});
            $('#repeat_appointment_time').click(function(){
                var id=$(this).attr('id');
                
               $('.repeatappointment.input-group-addon').click();
             });
            var end = new Date();
            end.setDate(end.getDate() + 60);
            
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
                    window.location.href = baseurl +'/Medical/manager_review/' + YPId;
                    dialog.close();
                }
            }]
        });
}


if (typeof Miid !== 'undefined') {
var ypinfo_url = window.location.href;
var url_ypinfo = baseurl +'Medical/editMi/' + YPId + '/' + Miid;
if(url_ypinfo == ypinfo_url){
function get_mi_complete(){
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Medical/update_slug/",
        data: {'url_data': url_ypinfo}
        
    }).done(function(){
        setTimeout(function(){get_mi_complete();}, 10000);
    }).responseText;
}

$(function(){
    get_mi_complete();
});

}
}

if (typeof Miid !== 'undefined') {
var allergies_url = window.location.href;
var url_allergies = baseurl +'Medical/add_edit_allergies/' + YPId + '/' + Miid;
if(url_allergies == allergies_url){
  function get_allergies_complete(){
    //var datetime = timefunction();
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Medical/update_slug/",
        data: {'url_data': url_allergies}
        
    }).done(function(){
        setTimeout(function(){get_allergies_complete();}, 10000);
    }).responseText;
}
$(function(){
    get_allergies_complete();
});
}
}

var mac_url = window.location.href;
var url_mac = baseurl +'Medical/add_mac/' + YPId;
if(url_mac == mac_url){
function get_mac_complete(){
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Medical/update_slug/",
        data: {'url_data': url_mac}
    }).done(function(){
        setTimeout(function(){get_mac_complete();}, 10000);
    }).responseText;
}
$(function(){
    get_mac_complete();
});

}


if (typeof Appointment_id !== 'undefined') {
var appointment_url = window.location.href;
var url_appointment = baseurl +'Medical/appointment_edit/' + Appointment_id + '/' + YPId;
if(url_appointment == appointment_url){
  function get_appointment_complete(){
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Medical/update_slug/",
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


var omi_url = window.location.href;
var url_omi = baseurl +'Medical/add_omi/' + YPId;
if(url_omi == omi_url){
   function get_omi_complete(){
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Medical/update_slug/",
        data: {'url_data': url_omi}
    }).done(function(){
        setTimeout(function(){get_omi_complete();}, 10000);
    }).responseText;
}
$(function(){
    get_omi_complete();
});
}


var mi_url = window.location.href;
var url_mi = baseurl +'Medical/add_mi/' + YPId;
if(url_mi == mi_url){
   function get_mi_complete(){
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Medical/update_slug/",
        data: {'url_data': url_mi}
    }).done(function(){
        setTimeout(function(){get_mi_complete();}, 10000);
    }).responseText;
}
$(function(){
    get_mi_complete();
});
}

if (typeof MedicationId !== 'undefined') {
var me_url = window.location.href;
var url_me = baseurl +'Medical/edit_medication/' + MedicationId + '/' + YPId;
if(url_me == me_url){
   function get_me_complete(){
    var feedback = $.ajax({
        type: "POST",
         url: baseurl + "Medical/update_slug/",
        data: {'url_data': url_me}
    }).done(function(){
        setTimeout(function(){get_me_complete();}, 10000);
    }).responseText;
}
$(function(){
    get_me_complete();
});
}
}

 
 window.Parsley.addValidator('quantity_left', function (value, requirement) {
    var response = false;
    var form = $(this);
    var medication_id = $('#select_medication :selected').val()
    if(medication_id){
    var quantity_left = $("#quantity_left").val();
    var remain = $("#quantity_left").attr('max');
    var yp_id = $("#yp_id").val();
    if(yp_id){
        var ypId = yp_id
    }else{
        var ypId = "";
    }

    $.ajax({
        type: "POST",
        url: check_medication_qut,
        data: {medication_id:medication_id,ypId:ypId,quantity_left:quantity_left}, // <--- THIS IS THE CHANGE
        async: false,
        success: function(result){
            if(result == "true"){
                response = true;
            }else{
                response = false;
            }
        },
        error: function() {

            var delete_meg ="Error posting feed";
            BootstrapDialog.show(
                {
                    title: 'Information',
                    message: delete_meg,
                    buttons: [{
                        label: 'ok',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }]
                });
        }
    });
}else{
   /* var delete_meg ="select Medication";
            BootstrapDialog.show(
                {
                    title: 'Information',
                    message: delete_meg,
                    buttons: [{
                        label: 'ok',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }]
                });*/
}
    return response;
}, 46)
    .addMessage('en', 'quantity_left', 'Stock not available.');
//checked stock
function checkStock(el,amid,stock,care_home_id){
    
        var ele = $(el);
    var delete_meg ="Quantity Remaining : "+stock;
    BootstrapDialog.show(
        {
            title: 'Stock Check',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: 'Check',
                action: function(dialog) {
                    window.location.href = baseurl +'/YoungPerson/checkedStock/' + amid+'/'+care_home_id;
                    dialog.close();
                }
            }]
        });
    
}

//get medical detail
var administer_medication_id='';
if(administer_medication_id != '')
{
    $('.medication_detail').show();
    var id = $('#select_medication').val();
     
     var element = $('#select_medication').find('option:selected'); 
     $.ajax({
        type: "POST",
        url: baseurl +'/Medical/getMedicalDetail',
        dataType: "json",
        data: {
            medication_id: id
        },
        success: function (res) {
          $('#doseage').html(res.doseage);
          $('#reason').html(res.reason);
          $('#med_quantity').html(res.stock);
          $('#medication_type').html(res.medication_type);
          $('#date_prescribed').html(res.date_prescribed);
          $('#length_of_treatment').html(res.length_of_treatment);
          $('#quantity_left').attr('max',res.stock);

        },
        error: function (xhr, ajaxOptions, thrownError) {
                if (xhr.responseText) {
                    toastr.error(xhr.responseText, 'Inconceivable!')
                } else {
                    console.error("<div>Http status: " + xhr.status + " " + xhr.statusText + "</div>" + "<div>ajaxOptions: " + ajaxOptions + "</div>"
                        + "<div>thrownError: " + thrownError + "</div>");
                }
            }
    });
}
else
{
    $('.medication_detail').hide();
}
$('body').on('change', '#select_medication', function (e) {
     var id = $(this).val();
     $('.medication_detail').show();
     
     var element = $(this).find('option:selected'); 
     $.ajax({
        type: "POST",
        url: baseurl +'/Medical/getMedicalDetail',
        dataType: "json",
        data: {
            medication_id: id
        },
        success: function (res) {
          $('#doseage').html(res.doseage);
          $('#reason').html(res.reason);
          $('#med_quantity').html(res.stock);
          $('#medication_type').html(res.medication_type);
          $('#date_prescribed').html(res.date_prescribed);
          $('#length_of_treatment').html(res.length_of_treatment);
          $('#quantity_left').attr('max',res.stock)
        },
        error: function (xhr, ajaxOptions, thrownError) {
                if (xhr.responseText) {
                    toastr.error(xhr.responseText, 'Inconceivable!')
                } else {
                    console.error("<div>Http status: " + xhr.status + " " + xhr.statusText + "</div>" + "<div>ajaxOptions: " + ajaxOptions + "</div>"
                        + "<div>thrownError: " + thrownError + "</div>");
                }
            }
    });
});


function StockArchive(medical_care_home_id,care_home_id){
    var delete_meg ="Are you sure this medication is being archived? This cannot be undone!";
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
                    window.location.href = baseurl + 'MedicationStock/createArchive/' + medical_care_home_id + '/' + care_home_id;
                    dialog.close();
                }
            }]
        });
}
//delete administer medication

function administer_medication_deletepopup(administer_medication_id,select_medication,yp_id,redirect_flag){
    var delete_meg ="Are you sure you want to delete this resord?";
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
                    window.location.href = baseurl + '/Medical/delete_administer_medication/' + administer_medication_id + '/'+select_medication+'/'+ yp_id+'/'+redirect_flag;
                    dialog.close();
                }
            }]
        });
}