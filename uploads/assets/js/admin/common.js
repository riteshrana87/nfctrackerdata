/**
 * AdminLTE Demo Menu
 * ------------------
 * You should not use this file in production.
 * This file is for demo purposes only.
 */
 $(document).ready(function () {
        $('.tile .slimScroll').slimscroll({
                height: 60,
                size: 3,
                alwaysVisible: true,
                color: '#007c34'
            });
        setTimeout(function () {
            $('.alert').fadeOut('5000');
        }, 8000);
        $("#div_msg").fadeOut(4000); 
        //init chosen
        $(".chosen-select").chosen({ search_contains: true});
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