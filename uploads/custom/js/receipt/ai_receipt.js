$(document).ready(function () {
    $('#email_template').parsley();
    tinymce.init({
        selector: '.tinyeditor_email_template',
        branding: false,
        init_instance_callback: function (editor) {            
            editor.on('KeyUp', function (e) {
                checkEditer();
            });
        }
    });
});
 $('#email').blur(function() {
            checkemail();
        });

		
    
    function checkemail(){
        var tomail = $('#email').val();

            var count1 = 0;
            var count2 = 0;
            var flag = '';

            var emails = tomail.split(";");
            if (emails !== '') {
                emails.forEach(function (email) {
                    if (email !== '') {
                        flag = validateEmail(email.trim());
                        if (flag == 'f') {
                            count1 = count1 + 1;
                        }
                    }
                });
            }

           

            if (count1 > 0) {
                $('#sentmail').attr('disabled', true);
            }
            else {
                $('#sentmail').attr('disabled', false);
            }

            if (count1 > 0) {
                $('#email_error').css("display", "block");
            } else {
                $('#email_error').css("display", "none");
            }

            
        }

        function validateEmail(sEmail) {
             var filter = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

            //var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

            if (filter.test(sEmail)) {
                return 't';
            } else {
                return 'f';
            }
        }
window.ParsleyConfig = {
  validators: {
     multipleemail: {
       fn: function (value) {
			
			var inputString = document.getElementById('email').value;
			var splitInput = inputString.split(',');

			var pattern = /(([a-zA-Z\-0-9\.]+@)([a-zA-Z\-0-9\.]+)[,]*)+/;

			var match = true;

			for(i=0;i<splitInput.length;i++) {
			  if(!splitInput.match(pattern)){
				match = false;
			  }
			}

return match;

       },
       priority: 32
     }
   }
};
/* $('#email').keyup(function () {
    var $email = this.value;
    validateEmails($email);
});
function validateEmails(email) {
    var emailReg =/^(\s?[^\s,]+@[^\s,]+\.[^\s,]+\s?,)*(\s?[^\s,]+@[^\s,]+\.[^\s,]+)$/;
    if (!emailReg.test(email)) {
        //alert('foo');
    } else {
        //alert('bar');
    }
} */
function checkEditer() {
    var email_body = tinymce.get('email_body').getContent();
    if (email_body == '') {
        $("#email_body_error").show();
    } else {
        $("#email_body_error").hide();
    }
}

//check all validation filled
$("#email_template").on('submit', function (e) {
    e.preventDefault();
    var form = $(this);
    var valid = false;
    form.parsley().validate();
    var email_body = tinymce.get('email_body').getContent();
    if (email_body == '') {
        $("#email_body_error").show();
        return false;
    } else {
        $("#email_body_error").hide();
    }
    if (form.parsley().isValid()) {
        valid = true;
        $('button[type="submit"]').prop('disabled', true);
    }
    if (valid)
        this.submit();
});

$(window).on('load resize', function () {
    $('.content-wrapper').css('min-height', $(window).height() - 51);
});
function delete_request(receipt_id) {
    var delete_meg = "Are You Sure Want to Delete This Email Template ?";
    BootstrapDialog.show(
            {
                title: 'Information',
                message: delete_meg,
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: 'ok',
                        action: function (dialog) {
                            window.location.href = baseurl + 'Admin/Receiptient/deletedata/' + receipt_id;
                            dialog.close();
                        }
                    }]
            });
}