<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <title>NFC Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?= base_url() ?>uploads/assets/front/css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="<?= base_url() ?>uploads/assets/front/css/style.css" rel='stylesheet' type='text/css' />
    <link href="<?= base_url() ?>uploads/assets/front/css/rits.css" rel='stylesheet' type='text/css' />
    <link href="<?= base_url() ?>uploads/assets/front/css/font-awesome.css" rel="stylesheet">
    <link href="<?= base_url() ?>uploads/assets/front/css/dropzone.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
    <link id="bsdp-css" href="<?= base_url() ?>uploads/assets/css/bootstrap-datepicker.css" rel="stylesheet">
    <link id="bsdp-css" href="<?= base_url() ?>uploads/assets/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="<?= base_url() ?>uploads/assets/front/css/custom.css" rel="stylesheet">
    <script src="<?= base_url() ?>uploads/assets/js/jquery3.2.1.min.js"></script>  

    <script src="<?= base_url() ?>uploads/assets/js/tinymce/js/bootstrap-multiselect.min.js"></script> 

    <script src="<?= base_url() ?>uploads/assets/js/tinymce/js/tinymce/tinymce.min.js"></script> 
    <link rel="stylesheet" href="<?php echo base_url() ?>/uploads/custom/css/jquery-ui.css">
	<link rel="icon" href="<?php echo base_url() . "uploads/assets/front/images/favicon.ico" ?>" type="image/gif" > 
    
    <script type="text/javascript">
var background_color = '<?= !empty($user_theme_color[0]['background_color']) ? $user_theme_color[0]['background_color'] : '' ?>';
var body_font_color = '<?= !empty($user_theme_color[0]['body_font_color']) ? $user_theme_color[0]['body_font_color'] : '' ?>';
var panel_color = '<?= !empty($user_theme_color[0]['panel_color']) ? $user_theme_color[0]['panel_color'] : '' ?>';
var title_color = '<?= !empty($user_theme_color[0]['title_color']) ? $user_theme_color[0]['title_color'] : '' ?>';
var header_color = '<?= !empty($user_theme_color[0]['header_color']) ? $user_theme_color[0]['header_color'] : '' ?>';
var footer_color = '<?= !empty($user_theme_color[0]['footer_color']) ? $user_theme_color[0]['footer_color'] : '' ?>';

$(document).ready(function() {
$('#page-wrapper').css('background-color',background_color);
//change body font color
$(document.body).css('color', body_font_color);
$('a').css('color', body_font_color);
//change panal color
$('.panel').css('background-color',panel_color);
//change title color
$('.page-title').css('color',title_color);
//change header color
$('.header-section').css('background',header_color);
//change title color
$('.footer').css('background',footer_color);
});


var set_logout_time = '<?= !empty($user_set_logout_time[0]['milliseconds']) ? $user_set_logout_time[0]['milliseconds'] : '' ?>';
    </script>
<?php
/*
  @Author : Ritesh rana
  @Desc   : Used for the custom CSS
  @Input 	:
  @Output	:
  @Date   : 06/03/2017
 */
if (isset($headerCss) && count($headerCss) > 0) {
    foreach ($headerCss as $css) {
        ?>
        <link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" />
        <?php
    }
}

?>