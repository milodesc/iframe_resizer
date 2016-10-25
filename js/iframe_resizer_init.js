(function ($, Drupal) {

  'use strict';

  // Set up the iFrame Resizer library's options.
  var options = {};
  if (drupalSettings.iframeResizer.advanced.override_defaults) {

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

})(jQuery, Drupal);
