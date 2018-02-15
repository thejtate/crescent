/**
 * Created by Sergey Grigorenko (svipsa@gmail.com) on 26.08.15.
 */

(function ($) {

    if (typeof Drupal != 'undefined') {
        Drupal.behaviors.projectCrescentCustom = {
            attach: function (context, settings) {
                init();
            },

            completedCallback: function () {
                // Do nothing. But it's here in case other modules/themes want to override it.
            }
        }
    }


    function init() {
      //$(".map").click(function(e){
      //    var offset = $(this).offset();
      //    console.log("left:" + (e.pageX - offset.left) + "  Top:" + (e.pageY - offset.top));
      //});
    }

})(jQuery);