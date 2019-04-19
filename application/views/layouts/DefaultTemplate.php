<!DOCTYPE html>
<html>
        <head>
            <?php
            /*
              Author : Ritesh rana
              Desc   : Call Head area
              Input  : Bunch of Array
              Output : All CSS and JS
              Date   : 05/03/2017
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
              Date   : 05/03/2017
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
              Date   : 05/03/2017
             */
            echo Modules::run('Sidebar/defaultFooter');
            ?>

            <!-- CORE JQUERY SCRIPTS -->
            </div>
           
            <div class="modal fade" id="new-notice" tabindex="-1" role="dialog" aria-labelledby="new-noticeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Add New Notice</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Title</label>
                            <input type="text" class="form-control" id="recipient-name">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">Notice</label>
                            <textarea class="form-control" id="message-text" rows="5"></textarea>
                        </div>
                        <div class="form-group dropzone">
                            <div class="zone">

                                <div id="dropZ">
                                    <i class="fa fa-cloud-upload"></i>
                                    <div>Drag and drop your file here</div>
                                    <span>OR</span>
                                    <div class="selectFile">
                                        <label for="file">Select file</label>
                                        <input type="file" name="files[]" id="file">
                                    </div>
                                    <p>File size limit : 10 MB</p>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
    <link href="<?= base_url() ?>uploads/assets/css/parsley.css" rel="stylesheet">
    <link href="<?= base_url() ?>uploads/assets/css/chosen.css" rel="stylesheet">
	   <?php if (isset($drag) && $drag == true): ?>
        <script src="<?= base_url() ?>uploads/custom/js/jquery-ui.min.js"></script>
    <?php endif; ?>
	<?php 
		if (isset($footerCss) && count($footerCss) > 0) {
            foreach ($footerCss as $css) {?>
				<link href="<?= $css ?>" rel="stylesheet">
	<?php   } 
		}
	?>

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
    <script src="<?php echo base_url('uploads/custom/js/common.js'); ?>"></script>
    

    <script type="text/javascript">
         var baseurl = '<?php echo base_url(); ?>';
        $(document).ready(function () {
            
    
            //init chosen
             $(".chosen-select").chosen({ search_contains: true});
            //init tinymce
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
                autoclose: true
            });
    $(".cpt_adddate_prescribed").datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                endDate: '+0d',
                startDate: new Date("01/01/1970"),
            });

$(".aai_adddate").datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                endDate: '+0d',
                startDate: new Date("01/01/1970"),
            });

            $(".medi_adddate_prescribed").datepicker({
                format: 'dd/mm/yyyy',
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
                autoclose: true,
                endDate: '+0d',
                startDate: new Date("01/01/1970"),
            });
        });
        //dynamic time format intialize
        $(function () {
            $(".addtime").timepicker({
                defaultTime: '',
            });
          
        });
        
        $(function () {
            $(".aaiaddtime").timepicker({
                defaultTime: '',
                minuteStep: 1,
            });
          
        });
         
         $('.addtime_data').click(function(){
            var id=$(this).attr('id');
            $('.'+id+'.input-group-addon').click();
         });
    </script>

    <script>
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
    
     <script type="text/javascript">
function savebeforelogout()
{
    var form_data=$('form');
     var form_id=form_data.attr('id');
     var form_action=form_data.attr('action');
     var formData = new FormData($('form')[0]);
     
	 
      console.log(form_data);
     console.log(form_action); 
    
     $.ajax({
        url:form_action,
        data:formData,
        type:'POST',
        contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
        processData: false, // NEEDED, DON'T OMIT THIS
         success:function(data){
            window.location = '<?= base_url('Dashboard/logout/') ?>';
         }
    }); 
}
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
    window.onkeypress = resetTimer;
    window.onkeyup = resetTimer;
    window.KeyDown = resetTimer;
    //window.onscroll = resetTimer;    // catches scrolling with arrow keys
    
    localStorage.removeItem('logout-event');

    $(window).scroll(function() {
            resetTimer();
      });

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
            $('#repeat_appointment_time').timepicker('hideWidget');     
            tinymce.triggerSave();
            resetTimer();
        });

        editor.on('KeyDown', function (e) {
            $(".datepicker").hide();   
            $('.addtime').timepicker('hideWidget');                         
            $('#appointment_time').timepicker('hideWidget');  
            $('#repeat_appointment_time').timepicker('hideWidget');  
            tinymce.triggerSave();
            resetTimer();
        });

        editor.on('KeyUp', function (e) {
            $(".datepicker").hide();   
            $('.addtime').timepicker('hideWidget');                         
            $('#appointment_time').timepicker('hideWidget');  
            $('#repeat_appointment_time').timepicker('hideWidget');  
            tinymce.triggerSave();
            resetTimer();
        });

        editor.on('click', function (e) {
            $(".datepicker").hide();   
            $('.addtime').timepicker('hideWidget');                         
            $('#appointment_time').timepicker('hideWidget');  
            $('#repeat_appointment_time').timepicker('hideWidget');   
            tinymce.triggerSave();
            resetTimer();
        });
      }
    });


    function logout() {
		localStorage.setItem('logout-event', 'logout');
		var flag=0;
		 $(":text, :file, select, textarea").each(function() {
			 if($(this).val()!='')
			 {
				 flag=1;
			 }
	});
	if(flag==0){
		
		window.location = '<?= base_url('Dashboard/logout/') ?>';
		
	}
	 if(flag==1)
		{
			savebeforelogout();
		}
	}

    function resetTimer() {
        clearTimeout(t);
        if( localStorage.getItem('logout-event') == 'logout' ){
		        localStorage.removeItem('logout-event');
        }else{
		    if(set_logout_time != ''){
                t = setTimeout(logout,set_logout_time);  // time is in milliseconds
            }else{
                t = setTimeout(logout, 240000);  // time is in milliseconds    
            }
        }
    }
}
idleLogout();
</script>


                        
<?php
/*
  @Author : Ritesh Rana
  @Desc   : Used for the custom CSS initilization just pass array of the scripts with links
  @Input  :
  @Output :
  @Date   : 07/03/2017
 */
                if (isset($footerJs) && count($footerJs) > 0) {
                         foreach ($footerJs as $js) {?>
                                <script src="<?php echo $js; ?>" ></script>
                <?php }
				} ?>

	<div id="loading">
		<img id="loading-image" src="<?php echo base_url('uploads/custom/img/loading-sm.gif'); ?>" alt="Loading..." />
	</div>
</body>
</html>
