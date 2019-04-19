<!DOCTYPE html>
<html>
        <head>
            <?php
            /*
              Author : Ritesh rana
              Desc   : Call Head area
              Input  : Bunch of Array
              Output : All CSS and JS
              Date   : 04/02/2016
             */
            if (empty($head)) {
                $head = array();
            }
            echo Modules::run('Sidebar/defaultHeader', $head);
      
            ?>
        </head>
        <body class="cbp-spmenu-push  cbp-spmenu-push-toright">
            <div class="main-content">
            <?php
            /*
              Author : Ritesh rana
              Desc   : Call Head area
              Input  : Bunch of Array
              Output : All CSS and JS
              Date   : 07/03/2017
             */
            if (empty($header)) {
                $header = array();
            }
            echo Modules::run('Sidebar/defaultLogoHeader', $header);
            ?>

            <!-- Example row of columns -->
            <?php
            /*
              Author : Ritesh rana
              Desc   : Call Page Content Area
              Input  : View Page Name and Bunch of array
              Output : View Page
              Date   : 11/01/2016
             */
            $this->load->view($main_content);
            ?>

            <!-- /container -->
            <?php
            /*
              Author : Ritesh rana
              Desc   : Call Footer Area
              Input  :
              Output : Footer Area( Menu, Content)
              Date   : 18/01/2016
             */
            echo Modules::run('Sidebar/defaultFooter');
            ?>

            <!-- CORE JQUERY SCRIPTS -->
            </div>
           
    <script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
    <link href="<?= base_url() ?>uploads/assets/css/parsley.css" rel="stylesheet">
    <link href="<?= base_url() ?>uploads/assets/css/chosen.css" rel="stylesheet">
    <script src="<?= base_url() ?>uploads/dist/js/summernote.min.js"></script>
        <link href="<?= base_url() ?>uploads/dist/css/summernote.css" rel="stylesheet">
    <script src="<?php echo base_url('uploads/custom/js/jsignature/jSignature.min.js'); ?>"></script>
  
  <?php 
    if (isset($footerCss) && count($footerCss) > 0) {
            foreach ($footerCss as $css) {?>
        <link href="<?= $css ?>" rel="stylesheet">
  <?php   } 
    }
  ?>
    <?php if (isset($drag) && $drag == true): ?>
        <script src="<?= base_url() ?>uploads/custom/js/jquery-ui.min.js"></script>
    <?php endif; ?>
    <script src="<?= base_url() ?>uploads/assets/front/js/bootstrap.js"> </script>

    <link href="<?php echo base_url('uploads/assets/js/bootstrap-timepicker/css/bootstrap-timepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
    <script src="<?= base_url() ?>uploads/assets/js/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <link href="<?php echo base_url('uploads/custom/css/bootstrap-dialog.css'); ?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url('uploads/custom/js/bootstrap-dialog-min.js'); ?>"></script>
    <script src="<?= base_url() ?>uploads/assets/front/js/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url() ?>uploads/assets/front/js/dropzone.js"></script>
    <script src="<?= base_url() ?>uploads/assets/front/js/chosen.jquery.js"></script>
    <script src="<?= base_url() ?>uploads/assets/front/js/custom.js"></script>
    <script src="<?= base_url() ?>uploads/assets/js/bootstrap-datepicker.js"></script>
    <script src="<?= base_url() ?>uploads/assets/js/chosen.jquery.js"></script>
    <script src="<?= base_url() ?>uploads/assets/js/moment.js"></script>
    <script src="<?= base_url() ?>uploads/assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src='<?= base_url() ?>uploads/custom/js/jquery.blockUI.js'></script>
    <script src="<?php echo base_url('uploads/custom/js/common.js'); ?>"></script>
    
    <script type="text/javascript">
         var baseurl = '<?php echo base_url(); ?>';

    $(document).ready(function() {
       
        $("form").on('submit', function(e){
            var form = $(this);
             var valid = false;
            form.parsley().validate();
 
            if (form.parsley().isValid()){
                 var valid = true;
                $('input[type="submit"]').prop('disabled', true);
                $('button[type="submit"]').prop('disabled', true);
                
                  $('input[type="submit"]').unbind();
                  $('button[type="submit"]').unbind();
        }

            if (valid) {setTimeout(

  function() 
  {
    $(this).submit();
    //do something special
  }, 2000); 
            }
        });
    });

        $(document).ready(function () {
            //init chosen
             $(".chosen-select").chosen({ search_contains: true});
            //init tinymce
            //tinymce.init({ selector:'.tinyeditor',branding: false });
            $('.tile .slimScroll').slimscroll({
                height: 60,
                size: 3,
                alwaysVisible: true,
                color: '#007c34'
            });
        });
        //dynamic date format
        $(function () {
            $(".adddate").datepicker({
                format: 'dd/mm/yyyy',
                startDate:new Date(),
                autoclose: true
            });

$(".medi_adddate").datepicker({
                format: 'dd/mm/yyyy',
                //startDate:new Date(),
                autoclose: true
            });
            $(".medi_adddate_prescribed").datepicker({
                format: 'dd/mm/yyyy',
                //startDate:new Date(),
                autoclose: true,
                endDate: '+0d',
                startDate: new Date("01/01/1970"),
            });

            $(".dob").datepicker({
                format: 'dd/mm/yyyy',
                endDate: new Date(),
                autoclose: true
            });
            $('#datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            });

            $(".medi_adddate_prescribed").datepicker({
                format: 'dd/mm/yyyy',
                //startDate:new Date(),
                autoclose: true,
                endDate: '+0d',
                startDate: new Date("01/01/1970"),
            });
        });
        //dynamic time format intialize
        $(function () {
            $(".addtime").timepicker({
                defaultTime: '',
                minuteStep: 5
            });
          
        });
         
         $('.addtime_data').click(function(){
            var id=$(this).attr('id');
           $('.'+id+'.input-group-addon').click();
         });
    </script>

     <script type="text/javascript">
        
                            $(document).ready(function () {
                                setTimeout(function () {
                                    $('.alert').fadeOut('1000');
                                }, 2000);
                                
                            });

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
<script>
/*script added by Dhara Bhalala to mail template for sticky header*/
    $(document).ready(function(){
      window.onscroll = function() {myFunction()};

      var header = document.getElementById("sticky-heading");
      var sticky = header.offsetTop - 10;

      function myFunction() {
        if (window.pageYOffset >= sticky) {
        header.classList.add("sticky");
        } else {
        header.classList.remove("sticky");
        }
      }
    
    });
  </script>

<?php
        if (isset($js_content) && !empty($js_content)) {
            $this->load->view($js_content);
        }
        ?>
                        
<?php
/*
  @Author : Ritesh Rana
  @Desc   : Used for the custom CSS initilization just pass array of the scripts with links
  @Input  :
  @Output :
  @Date   : 07/03/2017
 */
                if (isset($footerJs) && count($footerJs) > 0) {
                         foreach ($footerJs as $js) { ?>
                                <script src="<?php echo $js; ?>" ></script>
                <?php }
        } ?>

  <div id="loading">
    <img id="loading-image" src="<?php echo base_url('uploads/custom/img/loading-sm.gif'); ?>" alt="Loading..." />
  </div>
</body>
</html>
