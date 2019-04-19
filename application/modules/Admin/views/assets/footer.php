<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b></b>
    </div>
    <strong>Copyright &copy; <?php echo date('Y');?> </strong> All rights reserved.
</footer>

<!--
        <script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-3.1.1.min.js"></script>-->
        <!-- jQuery 2.1.4 -->
   
        <script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-ui.min.js"></script><!-- jQuery UI 1.11.4 -->
        <!-- DataTables -->
<script src="<?= base_url() ?>uploads/assets/js/tinymce/js/tinymce/tinymce.min.js"></script>    
        <!-- end-->
        
<!-- Bootstrap 3.3.5 -->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/bootstrap.min.js"></script>

<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/moment.js"></script>
<link href="<?php echo base_url('uploads/custom/css/bootstrap-dialog.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('uploads/custom/js/bootstrap-dialog-min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-confirm.js"></script>

<!-- Block ui-->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery.blockUI.js"></script>

<script type="text/javascript">
    <?php
        $admin_session = $this->session->userdata('nfc_admin_session');
        $login_user_id = $admin_session['admin_id'];
        $set_logout_time = getUserTimeOut($login_user_id);
    ?>
var set_logout_time = <?php echo $set_logout_time;?> 
function idleLogout() {
    var t;
    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onmousedown = resetTimer; // catches touchscreen presses
    window.onclick = resetTimer;     // catches touchpad clicks
    window.onscroll = resetTimer;    // catches scrolling with arrow keys
    window.onkeypress = resetTimer;
    window.onkeyup = resetTimer;
    window.KeyDown = resetTimer;
    
    localStorage.removeItem('logout-event');

    $("input, textarea").on('keypress', function (e) {
     // bind function called when key is pressed
        resetTimer();
    });


    tinymce.init({
      selector: '.tinyeditor',
      init_instance_callback: function (editor) {
        editor.on('keypress', function (e) {
            $(".datepicker").hide();   
            $('.addtime').timepicker('hideWidget');                         
            $('#appointment_time').timepicker('hideWidget');   
            resetTimer();
        });

        editor.on('KeyDown', function (e) {
            $(".datepicker").hide();   
            $('.addtime').timepicker('hideWidget');                         
            $('#appointment_time').timepicker('hideWidget');   
            resetTimer();
        });

        editor.on('KeyUp', function (e) {
            $(".datepicker").hide();   
            $('.addtime').timepicker('hideWidget');                         
            $('#appointment_time').timepicker('hideWidget');   
            resetTimer();
        });

        editor.on('click', function (e) {
            $(".datepicker").hide();   
            $('.addtime').timepicker('hideWidget');                         
            $('#appointment_time').timepicker('hideWidget');    
            resetTimer();
        });
      }
    });


    function logout() {
        localStorage.setItem('logout-event', 'logout');
        window.location = '<?= base_url('Dashboard/logout/') ?>';
    }

    function resetTimer() {

        clearTimeout(t);
        if( localStorage.getItem('logout-event') == 'logout' ){
            localStorage.removeItem('logout-event');
            window.location = '<?= base_url('Dashboard/logout/') ?>';
        }else{
            if(set_logout_time != ''){
                t = setTimeout(logout,set_logout_time);  // time is in milliseconds
            }else{
                t = setTimeout(logout, 240000);  // time is in milliseconds    
            }
            
            //t = setTimeout(logout, 30000);  // time is in milliseconds
        }
    }
}

idleLogout();


//console.log(document.body); 


</script>


<?php
if (isset($footerJs) && count($footerJs) > 0) {
    foreach ($footerJs as $js) {
        ?>
        <script src="<?php echo $js; ?>" ></script>
        <?php
    }
}
?>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>uploads/assets/front/js/jquery.slimscroll.min.js"></script>
<script src="<?= base_url() ?>uploads/assets/front/js/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/app.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/common.js"></script>



</body>
</html>