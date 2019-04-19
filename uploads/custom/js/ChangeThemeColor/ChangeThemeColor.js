
$(document).ready(function() {
$('#init').on('click', function() {
//change background color
$('select[name="background_color"]').simplecolorpicker({
  picker: true,
  theme: 'glyphicons'
}).on('change', function() {
    $('#page-wrapper').css('background-color',$('select[name="background_color"]').val());
});
//change body font color
$('select[name="body_font_color"]').simplecolorpicker({
  picker: true,
  theme: 'glyphicons'
}).on('change', function() {
    $(document.body).css('color', $('select[name="body_font_color"]').val());
    $('a').css('color', $('select[name="body_font_color"]').val());
});
//change panal color
$('select[name="panel_color"]').simplecolorpicker({
  picker: true,
  theme: 'glyphicons'
}).on('change', function() {
    $('.panel').css('background-color',$('select[name="panel_color"]').val());
});

//change title color
$('select[name="title_color"]').simplecolorpicker({
  picker: true,
  theme: 'glyphicons'
}).on('change', function() {
    $('.page-title').css('color',$('select[name="title_color"]').val());
});


//change header color
$('select[name="header_color"]').simplecolorpicker({
  picker: true,
  theme: 'glyphicons'
}).on('change', function() {
    $('.header-section').css('background',$('select[name="header_color"]').val());
});

//change title color
$('select[name="footer_color"]').simplecolorpicker({
  picker: true,
  theme: 'glyphicons'
}).on('change', function() {
    $('.footer').css('background',$('select[name="footer_color"]').val());
});


//
/*
$('select[name="colorpicker-picker-longlist"]').simplecolorpicker({
  picker: true,
  theme: 'glyphicons'
}).on('change', function() {
    $('.panel').css('background-color',$('select[name="colorpicker-picker-longlist"]').val());
});
*/
});
          $('#destroy').on('click', function() {
            $('select').simplecolorpicker('destroy');
          });
          // By default, activate simplecolorpicker plugin on HTML selects
          $('#init').trigger('click');
        });

function reset_theme_color(){
    var delete_meg ="Are you sure you want to Reset theme color?";
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
                    window.location.href = baseurl + '/ChangeThemeColor/reset_theme';
                    dialog.close();
                }
            }]
        });
}