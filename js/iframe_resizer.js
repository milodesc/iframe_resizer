(function ($) {
  Drupal.behaviors.iframe_resizer = {
    attach: function (context, settings) {
      var options = {
          log: settings.iframe_resizer_options.log == 1,
          heightCalculationMethod: settings.iframe_resizer_options.heightCalculationMethod
      };
      $(settings.iframe_resizer_target_specifiers, context).iFrameResize(options);
    }
  };
}(jQuery));
