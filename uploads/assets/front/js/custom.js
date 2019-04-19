//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
  var url = window.location;
  var element = $('ul.nav a').filter(function() {
    return this.href == url || url.href.indexOf(this.href) == 0;
  }).addClass('active').parent().parent().addClass('in').parent();
  if (element.is('li')) {
    element.addClass('active');
  }
});

$(window).bind("load resize", function () {
  topOffset = 50;
  height = $(window).height() - topOffset;
  if (height < 1) height = 1;
  if (height > topOffset) {
    $("#page-wrapper").css("min-height", (height + 17) + "px");
  }
});
//mouse over content
screen_width = document.documentElement.clientWidth;
screen_heght = document.documentElement.clientHeight;

if(screen_width > 770)
{
  $('.panel-body').each(function() {

    var $this = $(this);
    if($this.find('.slimScroll-120').html())
    {
      var d = $(this).find('.slimScroll-120').css('height').split('px');
      if(d[0] > 60){
       $this.popover({
        trigger: 'manual',
        placement: 'auto',
        html: true,   
        container: 'body',
        content: $this.find('.slimScroll-120').html()  
      }).on("mouseenter", function () {
        var _this = this;
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
          $(_this).popover('hide');
        });
      }).on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
          if (!$(".popover:hover").length) {
            $(_this).popover("hide");
          }
        }, 0);
      });
    }
  }
});
}
// add by Arkita 

$(document).ready(function(){

 $('.panel-heading a').click(function() {
  $('.panel-heading').removeClass('header_bg_collaps');
  if(!$(this).closest('.panel').find('.panel-collapse').hasClass('in'))
    $(this).parents('.panel-heading').addClass('header_bg_collaps');
});

// Chosen touch support.
$(window).resize(function(){

 if ($(window).width() <= 768) {  

   $(".chosen-select").css("display", "none");
   $("select.chosen-select").css("display", "none");
              // $("select.chosen-select").attr('multiple','multiple');
            }     
          });



});  // ready close