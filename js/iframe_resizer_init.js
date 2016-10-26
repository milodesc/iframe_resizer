(function ($, Drupal, iframeResizerSettings) {

  'use strict';

  // Set up the iFrame Resizer library's options.
  var options = {};
  if (iframeResizerSettings.advanced.override_defaults) {
    options = {
      log: iframeResizerSettings.advanced.options.log === 1,
      heightCalculationMethod: iframeResizerSettings.advanced.options.height_calculation_method
    }
  }

  Drupal.behaviors.initIframeResizer = {
    attach: function (context, settings) {
      var selector = 'iframe';
      if (typeof settings.iframeResizer.advanced.targetSelectors !== 'undefined') {
        selector = settings.iframeResizer.advanced.targetSelectors;
      }
      $(selector, context).iFrameResize(options);
    }
  };

})(jQuery, Drupal, drupalSettings.iframeResizer);
