/**
 * @file
 * Apply advanced options when this site will be hosted within a resizeable iFrame.
 */

(function ($) {

  "use strict";

  var hosted_options = {};
  if (Drupal.settings.iframe_resizer_targetorigin) {
    hosted_options.targetOrigin = Drupal.settings.iframe_resizer_targetorigin;
  }

  if (!$.isEmptyObject(hosted_options)) {
    window.iFrameResizer = hosted_options
  }

}(jQuery));
