(function ($) {
  Drupal.behaviors.iframe_resizer = {
    attach: function (context, settings) {
      var options = {
          log: settings.iframe_resizer.log == 1,
          heightCalculationMethod: settings.iframe_resizer.heightCalculationMethod
      };
      $('iframe', context).iFrameResize(options);
    }
  };
}(jQuery));
