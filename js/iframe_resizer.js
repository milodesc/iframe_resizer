(function ($) {
  Drupal.behaviors.iframe_resizer = {
    attach: function (context, settings) {
      var options = {};
      if (settings.iframe_resizer_override_defaults) {
        if (settings.iframe_resizer_options.bodyBackground == '') {
          settings.iframe_resizer_options.bodyBackground = null;
        }
        if (settings.iframe_resizer_options.bodyMargin == '') {
          settings.iframe_resizer_options.bodyMargin = null;
        }
        if (settings.iframe_resizer_options.interval == '') {
          settings.iframe_resizer_options.interval = 32;
        }
        if (settings.iframe_resizer_options.maxHeight == '' || settings.iframe_resizer_options.maxHeight.toUpperCase() == 'infinity'.toUpperCase()) {
            settings.iframe_resizer_options.maxHeight = Infinity;
        }
        else {
            settings.iframe_resizer_options.maxHeight = parseInt(settings.iframe_resizer_options.maxHeight);
        }
        options = {
            log: settings.iframe_resizer_options.log == 1,
            heightCalculationMethod: settings.iframe_resizer_options.heightCalculationMethod,
            widthCalculationMethod: settings.iframe_resizer_options.widthCalculationMethod,
            autoResize: settings.iframe_resizer_options.autoResize == 1,
            bodyBackground: settings.iframe_resizer_options.bodyBackground,
            bodyMargin: settings.iframe_resizer_options.bodyMargin,
            inPageLinks: settings.iframe_resizer_options.inPageLinks == 1,
            interval: parseInt(settings.iframe_resizer_options.interval),
            maxHeight: settings.iframe_resizer_options.maxHeight
        };
      }
      $(settings.iframe_resizer_target_specifiers, context).iFrameResize(options);
    }
  };
}(jQuery));
