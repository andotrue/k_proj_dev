/* ===================================================================
box fixed
=================================================================== */

(function($) {

$.fn.boxfixed = function(options){

  var settings = $.extend({
    fixtop: 0
  }, options);

  var obj = $(this),
  offset = obj.offset();

  $(window).scroll(function () {
    if($(window).scrollTop() > offset.top - settings.fixtop) {
      obj.addClass('fixed');
    } else {
      obj.removeClass('fixed');
    }
  });
}

})(jQuery);