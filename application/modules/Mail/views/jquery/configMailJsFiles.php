
<script>
    $(document).ready(function () {
        $('body').on('click', '#camp_submit_btn', function (event) {

            if ($('#frm_addemail').parsley().isValid()) {

                event.preventDefault();
                var formData = $("#frm_addemail").serialize();
                $.ajax({
                    url: "<?php echo base_url('Mail/validateMailConfig'); ?>",
                    data: formData,
                    type: "POST",
                    beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> Please wait...'});
                    },
                    success: function (result)
                    {
                        $.unblockUI();
                        var arr = JSON.parse(result);
                        BootstrapDialog.alert(arr.message);
                        if (arr.status) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                });
                return false;
            }
        });

    });
</script>