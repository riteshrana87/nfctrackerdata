<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="en">
<head>
    <?php
    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 04/02/2016
     */
    if(empty($head))
    {
        $head = array();
    }
    echo Modules::run('Sidebar/defaultHeader', $head);
    ?>
</head>
<body>
<?php
/*
  Author : Ritesh rana
  Desc   : Call Head area
  Input  : Bunch of Array
  Output : All CSS and JS
  Date   : 07/03/2017
 */
if(empty($header))
{
    $header = array();
}
echo Modules::run('Sidebar/defaultLogoHeader', $header);
?>

<?php
/*
  Author : Rupesh Jorkar(RJ)
  Desc   : Call Head area
  Input  : Bunch of Array
  Output : All CSS and JS
  Date   : 04/02/2016
 */
if(empty($header))
{
    $header = array();
}
echo Modules::run('Sidebar/defaultMenuHeader', $header);
?>

<!-- Example row of columns -->
<?php
/*
  Author : Rupesh Jorkar(RJ)
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
  Author : Rupesh Jorkar(RJ)
  Desc   : Call Footer Area
  Input  :
  Output : Footer Area( Menu, Content)
  Date   : 18/01/2016
 */
echo Modules::run('Sidebar/defaultFooter');
?>
<!-- CORE JQUERY SCRIPTS -->
<script src="<?= base_url() ?>uploads/assets/js/jquery-3.1.1.min.js"></script>
<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<!-- BOOTSTRAP SCRIPTS  -->
<script src="<?= base_url() ?>uploads/assets/js/bootstrap.js"></script>
<script src="<?= base_url() ?>uploads/assets/js/moment.js"></script>
<link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">
<script src="<?= base_url() ?>uploads/custom/js/bootstrap-toggle.js"></script>
<link href="<?= base_url() ?>uploads/custom/css/bootstrap-toggle.css" rel="stylesheet">
<script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script>

<link href="<?= base_url() ?>uploads/custom/css/projectmanagement/parsley.css" rel="stylesheet">
<link href="<?php echo base_url() ?>uploads/custom/css/bootstrap-dialog.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url() ?>uploads/custom/js/bootstrap-dialog-min.js"></script>

<script src="<?= base_url() ?>uploads/assets/js/highcharts.js"></script>
<script src="<?= base_url() ?>uploads/assets/js/chart-custom.js"></script>
<script src="<?php echo base_url('uploads/custom/js/common.js'); ?>"></script>

<?php
/*
  @Author : Ritesh Rana
  @Desc   : Used for the custom CSS initilization just pass array of the scripts with links
  @Input  :
  @Output :
  @Date   : 07/03/2017
 */
if (isset($footerJs) && count($footerJs) > 0) {
    foreach ($footerJs as $js) {
        ?>
        <script src="<?php echo $js; ?>" ></script>
        <?php
    }
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('.content-wrapper').css('min-height', $(window).height() - 152);
    });
    $(window).resize(function () {
        $('.content-wrapper').css('min-height', $(window).height() - 152);
    });

    /*setTimeout(function(){
     $('#flashMsg').html('');
     }, 3000); */
</script>
<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            $('.alert').fadeOut('1000');
        }, 2000);

    });

</script>


<script type="text/javascript">
    $(document).ready(function ()
    {
        var ms_ie = false;
        var ua = window.navigator.userAgent;
        var old_ie = ua.indexOf('MSIE ');
        var new_ie = ua.indexOf('Trident/');

        if ((old_ie > -1) || (new_ie > -1)) {
            ms_ie = true;
        }

        if (ms_ie == false)
        {
            setInterval(function () {
            }, 1000 * 60 * 2);
        }
        /*
         if ( ms_ie ) {
         alert('this is internet exploreer');
         }
         */


    });

</script>
<script type="text/javascript">
    $(document).ready(function () {
        if ($(window).width() > 767) {
            $('.menu-section ul.nav li.dropdown').hover(function () {
                $(this).find('.dropdown-menu').stop(true, true).fadeIn(300);
            }, function () {
                $(this).find('.dropdown-menu').stop(true, true).fadeOut(300);
            });
        }
    });
    $(window).resize(function () {
        if ($(window).width() < 767) {
            $('.menu-section ul.nav li.dropdown').unbind('mouseenter');
            $('.navbar-nav > li > .dropdown-menu').attr('style', function (i, style) {
                return style.replace(/display[^;]+;?/g, '');
            });
        }
        if ($(window).width() > 767) {
            //$('.navbar-nav > li > .dropdown-menu').removeStyle('display');
            $('.menu-section ul.nav li.dropdown').hover(function () {
                $(this).find('.dropdown-menu').stop(true, true).fadeIn(300);
            }, function () {
                $(this).find('.dropdown-menu').stop(true, true).fadeOut(300);
            });
        }
    });
</script>
<?=$this->load->view('/Common/common','',true);?>

<div class="loader" id='loader' style="display: none;"></div>
</body>

</html>
