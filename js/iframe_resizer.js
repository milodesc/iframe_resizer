(function ($) {
  Drupal.behaviors.iframe_resizer = {
    attach: function (context, settings) {
      var options = {};
      if (settings.iframe_resizer_override_defaults) {
        options = {
            log: settings.iframe_resizer_options.log == 1,
            heightCalculationMethod: settings.iframe_resizer_options.heightCalculationMethod,
            widthCalculationMethod: settings.iframe_resizer_options.widthCalculationMethod
        };
      }
      $(settings.iframe_resizer_target_specifiers, context).iFrameResize(options);
    }
  };
}(jQuery));
