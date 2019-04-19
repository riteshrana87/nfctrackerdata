$(document).ready(function() {
        $("#addrole").on('submit', function(e){
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