(function (iframeResizerSettings) {

  'use strict';

  // Set up the iFrame Resizer library's options.
  if (iframeResizerSettings.advancedHosted.heightCalculationMethodOverride === "parent") {
    delete iframeResizerSettings.advancedHosted.heightCalculationMethodOverride;
  }
  window.iFrameResizer = iframeResizerSettings.advancedHosted;

})(drupalSettings.iframeResizer);
