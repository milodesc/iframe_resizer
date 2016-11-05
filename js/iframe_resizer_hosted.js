(function (iframeResizerSettings) {

  'use strict';

  // Set up the iFrame Resizer library's options.
  if (iframeResizerSettings.advancedHosted.heightCalculationMethodOverride === "parent") {
    delete iframeResizerSettings.advancedHosted.heightCalculationMethodOverride;
  }
  if (iframeResizerSettings.advancedHosted.widthCalculationMethodOverride === "parent") {
    delete iframeResizerSettings.advancedHosted.widthCalculationMethodOverride;
  }
  window.iFrameResizer = iframeResizerSettings.advancedHosted;

})(drupalSettings.iframeResizer);
