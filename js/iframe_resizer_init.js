(function ($, Drupal) {

  'use strict';

  // Set up the iFrame Resizer library's options.
  var options = {};

  Drupal.behaviors.initIframeResizer = {
    attach: function (context, settings) {
      $('iframe', context).iFrameResize(options);
    }
  };

})(jQuery, Drupal);
