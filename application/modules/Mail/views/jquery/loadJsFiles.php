<script>
    function refreshCheckbox(){
        if ($("input[name='checkedIds[]']:checked").length > 1) {
            $('#replyEmail').hide();
            $('#replyAll').hide();
            $('#forwardEmail').hide();
            $('#ComposeMail').hide();
            
        } else {
            $('#replyEmail').show();
            $('#replyAll').show();
            $('#forwardEmail').show();
            $('#ComposeMail').show();
        }
        
    }

    function getMailBoxData(boxtype, id)
    {
        if (boxtype != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});

             var yp_id = $('.yp_id').val();
            $.ajax({
                url: "<?php echo base_url('Mail/getEmails_data'); ?>",
                type: "POST",
                data: {'boxtype': boxtype, 'folderName': boxtype,'yp_id':yp_id},
                success: function (d)
                {
                    $('#refreshBn').data('boxtype', boxtype);
                    $('#refreshBn').attr('data-boxtype', boxtype);
                    $("#searchtext").val("");
                    //BootstrapDialog.alert(boxtype+" Updated!");
                    // window.location.href = window.location.href;
                    if (d == 'done')
                    {
                        $.ajax({
                            url: "<?php echo base_url('Mail/Index'); ?>" +'/'+ yp_id,
                            type: "POST",
                            data: {'result_type': 'ajax', 'boxtype': boxtype, 'folderName': boxtype, 'allflag':'changesearch'},
                            success: function (d)
                            {
                                $('#refreshBn').data('boxtype', boxtype);
                                $('.leftbx').removeClass('active');
                                $('#' + id).addClass('active');
//                                $('#refereshCode li button').attr('onClick', "updateEmails(" + boxtype + ")");
                                $('#mailTable').html(d);
                                var currBoxType = boxtype.charAt(0).toUpperCase() + boxtype.slice(1).toLowerCase();
                                $('#currentBoxType').html(currBoxType);
                                refreshCheckbox();
                                $.unblockUI();
                                //$('#refereshLeftbox').trigger('click');
                            }

                        });

                    }
                }

            });
        }

    }

    $(document).ready(function () {

        //serch by enter

      
        $('#searchtext').keyup(function (event)
        {
            if (event.keyCode == 13) {
                data_search('changesearch');
            }

        });

    });
    function markasUnread(uid)
    {
        var yp_id = $('.yp_id').val();
        $.ajax({
            url: "<?php echo base_url('Mail/markasRead'); ?>",
            type: "POST",
            data: {'uid': uid,'ypid':yp_id},
            success: function (d)
            {
                $('span#' + uid).removeClass('font-bold');
                // $('#refereshLeftbox').trigger('click');
            }

        });

    }

    //Search data
    function data_search(allflag)
    {
        var uri_segment = $("#uri_segment").val();
        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
        var request_url = '';
        if (uri_segment == 0)
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index/' + uri_segment;
        } else
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index/' + uri_segment;
        }
        var boxtype = $('#refreshBn').attr('data-boxtype');
        $.ajax({
            type: "POST",
            url: request_url,
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: allflag, boxtype: boxtype
            },
            success: function (html) {
                $.unblockUI();
                $("#common_div").html(html);
            }
        });
        return false;
    }
    function reset_data()
    {
        $("#searchtext").val("");
        apply_sorting('', '');
        data_search('all');
    }

    /*function reset_data_list(data)
     {
     $("#searchtext").val(data);
     apply_sorting('', '');
     data_search('all');
     }*/

    function changepages()
    {
        data_search('');
    }

    function apply_sorting(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);

        if (sortfilter != '' || sorttype != '') {
            data_search('changesorting');
        }
    }
    //pagination
    $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {
        var boxtype = $('#refreshBn').attr('data-boxtype');
        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), 'boxtype': boxtype
            },
            /*
             beforeSend: function () {
             $('#common_div').block({message: 'Loading...'});
             },
             */
            success: function (html) {
                $("#common_div").html(html);
                $.unblockUI();
            }
        });
        return false;

    });


    function forwardEmail(ypid)
    {

        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('forward');
                window.location.href = url +'/'+ ypid;
            });
        }
        else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function replyEmail(ypid)
    {

        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('reply');
                window.location.href = url +'/'+ ypid;
            });
        }
        else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function replyAll(ypid)
    {
        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('replyall');
                window.location.href = url +'/'+ ypid;
            });
        }
        else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function updateEmails(type)
    {
        if (type != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            $.ajax({
                url: "<?php echo base_url('Mail/getEmails'); ?>",
                type: "GET",
                success: function (d)
                {
                    BootstrapDialog.alert("<?= lang('mail_inbox_update') ?>");
                    window.location.href = window.location.href;
                    if (d == 'done')
                    {
                        $.ajax({
                            url: "<?php echo base_url('Mail/leftBarCount'); ?>",
                            type: "GET",
                            success: function (d)
                            {
                                $('#leftbar').html(d);
                                $.unblockUI();
                            }
                        });
                    }
                }

            });
        }

    }
    $("body").on('click', '#refreshBn', function () {
        var type = $(this).data('boxtype');
        var inbox_box = $('.inbox_box').val();
        var sent_box = $('.sent_box').val();
        var yp_id = $('.yp_id').val();

        //console.log(type);
        if (type != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            $.ajax({
                url: "<?php echo base_url('Mail/getEmailsRefresh'); ?>",
                type: "POST",
                data: {'boxtype': type, 'folderName': type,'inbox_box':inbox_box,'sent_box':sent_box, 'new': 'new','yp_id':yp_id},
                success: function (d)
                {
                    if (d == 'done')
                    {
                        var url = 
                        $.ajax({
                            url: "<?php echo base_url('Mail/Index'); ?>" +'/'+ yp_id,
                            type: "POST",
                            data: {'result_type': 'ajax', 'boxtype': type, 'folderName': type},
                            success: function (d)
                            {
                                $('#mailTable').html(d);
                                refreshCheckbox();
                                /*$(this).data('boxtype', type);
                                BootstrapDialog.alert(type + " Updated!");
                                */
                                var id = $('.leftbx.active').attr('id');
                                // $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
                                $.ajax({
                                    url: "<?php echo base_url('Mail/leftBarCount'); ?>"+'/'+yp_id,
                                    type: "GET",
                                    success: function (d)
                                    {
                                        $('#leftbar').html(d);
                                        $('.leftbx').removeClass('active');
                                        $('#' + id).addClass('active');
                                        $.unblockUI();
                                        var type = $('#refreshBn').data('boxtype');
                                        BootstrapDialog.alert("Mailbox successfully updated!");
                                    }
                                });
                                //$('#refereshLeft').trigger('click');
                            }

                        });
                    }
                }

            });
        }


    });

</script>

<!--MailAjaxList script-->

<script>

    $("body").on('click', '#selectall', function () {
        var checkAll = $("#selectall").prop('checked');
        if (checkAll) {
            $("input[name='checkedIds[]']").prop("checked", true);
        } else {
            $("input[name='checkedIds[]']").prop("checked", false);
        }
        refreshCheckbox();        
    });

    $("body").on('click', "input[name='checkedIds[]']", function () {
        if ($("input[name='checkedIds[]']").length == $("input[name='checkedIds[]']:checked").length) {
            $("#selectall").prop("checked", true);
        } else {
            $("#selectall").prop("checked", false);
        }
        refreshCheckbox();        
    }
    );
    $("body").on('click', '#flagMail', function () {

        var checkedList = [];
        $("input[name='checkedIds[]']:checked").each(function () {
            checkedList.push($(this).attr('id'));
        });

        var finalCheckedFlagList = checkedList.join();

        if (finalCheckedFlagList.length === 0) {
            BootstrapDialog.alert("<?= lang('mail_select_atleast_one') ?>");
            return false;
        }

        BootstrapDialog.confirm("<?php echo lang('mail_select_flag'); ?>", function (result) {

            if (result) {
                $.ajax({
                    url: "<?php echo base_url('Mail/movetoImportant'); ?>",
                    data: {ids: finalCheckedFlagList},
                    type: "post",
                    dataType: "json",
                    beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
                    },
                    success: function (d)
                    {
                        if (d.status) {
                            BootstrapDialog.alert("<?= lang('mail_moved_important') ?>");

                            $("input[name='checkedIds[]']:checked").each(function () {
                                $(this).parent().parent().remove();
                            });

                            $.unblockUI();
                            //window.location.href = "<?php //echo base_url('Mail/');                                                              ?>";

                        }
                    }
                });
            }
        });

    });

    $("body").on('click', '#trashMail', function () {

        // $('#trashMail').click(function () {
       /* if ($('.Trash').hasClass('active'))
        {
            return false;
        }
        */
        var click_box = $(".bd-mailbox-menu li a.active").attr('data_leftbox');
        var checkedList = [];
        $("input[name='checkedIds[]']:checked").each(function () {
            checkedList.push($(this).attr('id'));
        });

        var finalCheckedList = checkedList.join();

        if (finalCheckedList.length === 0) {
            BootstrapDialog.alert("<?= lang('mail_select_atleast_one') ?>");
            return false;
        }

        BootstrapDialog.confirm("<?= lang('mail_select_remove') ?>", function (result) {
            var yp_id = $('.yp_id').val();
            if (result) {
                $.ajax({
                    url: "<?php echo base_url('Mail/movetoTrash'); ?>"+'/'+yp_id,
                    data: {ids: finalCheckedList},
                    type: "post",
                    dataType: "json",
                    beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
                    },
                    success: function (d)
                    {

                        if (d.status) {
                            BootstrapDialog.alert("<?php echo lang('mail_moved_trash'); ?>");
                            
                            $("input[name='checkedIds[]']:checked").each(function () {
                                $(this).parent().parent().remove();
                            });
                            $('.' + click_box).trigger('click');
                            refreshCheckbox();
                            //$.unblockUI();
                        
                            //return true;
                        }
                    }
                });
            }
            $("input[name='checkedIds[]']:checked").prop("checked", false);
        });
        
    });

    $("body").on('click', '.starred', function () {

        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/moveMessage'); ?>",
            data: {id: $(this).data('id'), 'path': 'starred'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                // window.location.href = window.location.href;
                if (d.status == 1)
                {
                    BootstrapDialog.alert("<?php echo lang('mail_marked_starred'); ?>");
                    $(el).removeClass('starred');
                    $(el).addClass('unstarred');
//                    window.location.href = window.location.href;
                    $('#star_' + id).addClass('fa-star');
                    $('#star_' + id).removeClass('fa-star-o');
                    $.unblockUI();
                }
            }
        });
    });
    $("body").on('click', '.unstarred', function () {
        var id = $(this).data('id');

        $.ajax({
            url: "<?php echo base_url('Mail/moveMessage'); ?>",
            data: {id: $(this).data('id'), 'path': 'unstarred'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
//                window.location.href = window.location.href;
                if (d.status == 1)
                {
                    BootstrapDialog.alert("<?php echo lang('mail_removed_starred'); ?>");
                    $(el).removeClass('unstarred');
                    $(el).addClass('starred');
                    $('#star_' + id).removeClass('fa-star-0');
                    $('#star_' + id).addClass('fa-star');

////                    alert("done");
//                    $('#' + $(this).data('id')).removeClass('fa-star');
//                    $('#' + $(this).data('id')).addClass('fa-star-0');
                    //  window.location.href = window.location.href;
                }
            }
        });
    });
    $("body").on('click', '.flagged', function () {

        //$('.flagged').click(function () {
        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/markasFlagged'); ?>",
            data: {id: $(this).data('id'), 'path': 'flagged'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                BootstrapDialog.alert("<?php echo lang('mail_marked_flag'); ?>");
                // window.location.href = window.location.href;
                if (d.status == 1)
                {
                    //$.unblockUI();
//                    alert("done");
                    $(el).addClass('unflagged');
                    $(el).removeClass('flagged');
                    $(el).parent('div').addClass('bd-in-mark');
                    $(el).closest('li').addClass('bd-in-mark');
                    $('.bd-mailbox-menu .active').trigger('click');
                    $.unblockUI();
                    //  $(elm).parent('div').removeClass('bd-in-mark');

                }
            }
        });
    });
    $("body").on('click', '.unflagged', function () {

        //$('.flagged').click(function () {
        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/markasFlagged'); ?>",
            data: {id: $(this).data('id'), 'path': 'INBOX'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                BootstrapDialog.alert("<?php echo lang('mail_marked_unflag'); ?>");
                if (d.status == 1)
                {

                    $(el).removeClass('unflagged');
                    $(el).addClass('flagged');
                    $(el).parent('div').removeClass('bd-in-mark');
                    $(el).closest('li').removeClass('bd-in-mark');
                    $('.bd-mailbox-menu .active').trigger('click');
//                    $(elm).parent('div').removeClass('bd-in-mark');
                    $.unblockUI();
//                    alert("done");
//                    $('#' + $(this).data('id')).removeClass('fa-star');
//                    $('#' + $(this).data('id')).addClass('fa-star-0');
                }
            }
        });
    });

    $("body").on('click', '.mail-tr', function () {
        //$('.mail-tr').click(function () {
        $('.mail-tr').removeClass("ActiveTr");
        $(this).addClass("ActiveTr");
    });

</script>
<script>
    $(document).ready(function () {
        $('body').delegate('[data-toggle="ajaxModal"]', 'click',
                function (e) {
                    $('#ajaxModal').remove();
                    e.preventDefault();
                    var $this = $(this)
                            , $remote = $this.data('remote') || $this.attr('data-href')
                            , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                    $('body').append($modal);
                    $modal.modal();
                    $modal.load($remote);
                    //$("body").removeClass("modal-open");
                    //$("body").css("padding-right", "0 !important");

                }
        // $('#ajaxModal').css({height:"350px", overflow:"auto"});
        );

    });


    $('body').on('click', '#refereshLeftbox', function () {
        var id = $('.leftbx.active').attr('id');
        var yp_id = $('.yp_id').val();
        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
        $.ajax({
            url: "<?php echo base_url('Mail/leftBarCount'); ?>"+'/'+yp_id,
            type: "GET",
            success: function (d)
            {
                $('#leftbar').html(d);
                $('.leftbx').removeClass('active');
                $('#' + id).addClass('active');
                $.unblockUI();
               /* var type = $('#refreshBn').data('boxtype');
                BootstrapDialog.alert(type + " Updated!");*/
            }
        });
    });


    $('body').on('click', '#refereshLeft', function () {
        var id = $('.leftbx.active').attr('id');
        var yp_id = $('.yp_id').val();
        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
        $.ajax({
            url: "<?php echo base_url('Mail/leftBarCount'); ?>"+'/'+yp_id,
            type: "GET",
            success: function (d)
            {
                $('#leftbar').html(d);
                $('.leftbx').removeClass('active');
                $('#' + id).addClass('active');
                $.unblockUI();
                var type = $('#refreshBn').data('boxtype');
                BootstrapDialog.alert("Mailbox successfully updated!");

            }
        });

    });


</script>
